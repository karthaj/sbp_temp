<?php

namespace Shopbox\Http\Controllers\Zpanel\Theme;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Leafo\ScssPhp\Compiler;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Menu\Entities\Menu;
use Shopbox\Models\Zpanel\GFont;
use Shopbox\Models\Front\Theme;
use Shopbox\Models\Front\StoreTheme;
use Shopbox\Transformers\ThemeCollectionTransformer;
use Illuminate\Support\Facades\View;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $active_theme = fractal()
                        ->item($request->tenant()->template)
                        ->transformWith(new ThemeCollectionTransformer)
                        ->toArray()['data'];
        
        $themes = fractal()
                ->collection($request->tenant()->themes()->with('theme')->where('active', 0)->get())
                ->transformWith(new ThemeCollectionTransformer)
                ->toArray()['data'];

        return view('zpanel.themes.index', compact('active_theme', 'themes'));
    }

    public function update(StoreTheme $store_theme, Request $request)
    {
        $template = $store_theme->theme->slug;

        $theme_config = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$template.'/config/setting.json')), true);

        if($theme_config['version'] === $store_theme->version) {
            return;
        }

        $template_path = resource_path('views/stores').'/'.$request->tenant()->domain.'/'.$template;

        copy(resource_path('views/stores/'.$request->tenant()->domain.'/'.$template.'/config/setting.json'), resource_path('views/stores/'.$request->tenant()->domain.'/'.$template.'/config/settings_backup.json'));

        $config = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$template.'/config/setting.json')), true);
        $config['version'] = $theme_config['version'];
        $config['meta'] = $theme_config['meta'];

        $zipper = new Zipper;

        $zipper->zip($template_path.'/'.$template.'.zip')->add(storage_path('app/appconfig/themes/'.$template.'/'));

        $zipper->make($template_path.'/'.$template.'.zip')->extractTo($template_path);

        $zipper->close();

        copyDirectory($template_path.'/assets', public_path('stores').'/'.$request->tenant()->domain.'/themes/'.$template.'/assets');
        copyDirectory(storage_path('app/appconfig/themes/'.$template.'/meta'), public_path('stores').'/'.$request->tenant()->domain.'/themes/'.$template.'/meta');

        $settings = array_replace_recursive($theme_config, $config);
        $sections = array_except($settings['current']['sections'], array_keys(array_diff_key($settings['current']['sections'], $config['current']['sections'])));
        $content_for_index = array_except($settings['current']['content_for_index'], array_keys(array_diff_key($settings['current']['content_for_index'], $config['current']['content_for_index'])));
        $settings['current']['sections'] = $sections;
        $settings['current']['content_for_index'] = $content_for_index;

        file_put_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$template.'/config/setting.json'), json_encode($settings, JSON_PRETTY_PRINT));

        unlink($template_path.'/'.$template.'.zip');

        $this->compileAssets($settings['current'], 'current', $template);

        $store_theme->update([
            'version' => $theme_config['version']
        ]);
       
        return redirect()->route('theme.index')->withSuccess('Theme updated successfully.');
    }

    public function editor(Request $request, $theme_id)
    {   
        if(!DB::table('themes')->where('alias', $theme_id)->count()) {
            abort('404');
        }

        $theme = DB::table('themes')->where('alias', $theme_id)->first();

        if(!file_exists(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/schema.json'))) {

            exit('<pre style="word-wrap: break-word; white-space: pre-wrap;">config/schema.json is missing in '.$theme->theme_name.' theme.</pre>');

        }

        $config = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/setting.json')), true);

        $config = json_encode($config['current'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
       
        $generalSettings = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/schema.json')), true);
        $generalSettings = json_encode($generalSettings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

        $sections = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/sections.json')), true);
        $sections = json_encode($sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
       
        $image_path = asset('stores').'/'.session('store')->domain.'/img/';

        $store_url = $this->pageUrl($request->tenant()->domain, $request->page);

        $pages = DB::table('pages')->where('store_id', session('tenant'))->get();

        $product = DB::table('products')->where('store_id', session('tenant'))->inRandomOrder()->first();
  
        $request->session()->put('settings', json_decode($config, true));
        
        return view('layouts.editor', compact('config', 'store_url', 'generalSettings', 'image_path', 'theme', 'sections', 'pages', 'product'));
    }

    protected function pageUrl($domain, $path = null) 
    {
        $url = config('domain.protocol').$domain.'.'.config('domain.app_domain');

        if(!$path) {
            return $url;
        }

        return $url.$path;
    }

    public function store(Request $request, $theme_id) 
    {
        if(!Theme::where('alias', $theme_id)->count()) {
            abort('404');
        }

        $theme = Theme::where('alias', $theme_id)->first()->slug;

        $config = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme.'/config/setting.json')), true);

        $config['current'] = $request->settings;
      
        file_put_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme.'/config/setting.json'),json_encode(array_replace_null_values($config), JSON_PRETTY_PRINT));

        $config = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme.'/config/setting.json')), true);

        $this->compileAssets($config['current'], 'current', $theme);
        
    }


    public function customize(Request $request, $theme_id)
    {
        if(!$request->ajax()) {
            return;
        }

        $theme = Theme::where('alias', $theme_id)->first();
        if(!$theme) {
            abort('404');
        }

        $settings = $request->session()->get('settings');

        if($request->section_id) {
            $section = $request->settings;
            $theme_path = 'stores.'.$request->tenant()->domain.'.'.$theme->slug;
            $powered_by_link = 'Powered by ShopBox';
            $menus = $request->tenant()->menus()->with(['items' => function ($query) {
                $query->whereNull('parent_id');
            }, 'items.children'])->where('active', 1)->get();
            $settings['sections'][$request->section_id] = $request->settings;
            $sections = $settings['sections'];
            $request->session()->put('settings', $settings);

            $html = '';
            $json = '';

            $html = view('stores.'.$request->tenant()->domain.'.'.$theme->slug.'.components.'.$section['type'], [
                            'section_id' => $request->section_id, 
                            'section' => $section, 
                            'type' => $section['type'],
                            'theme_path' => $theme_path, 
                            'store' => $request->tenant(),
                            'powered_by_link' => $powered_by_link,
                            'payments'=> getActiveStorePayments($request->tenant()),
                            'menus' => $menus,
                            'settings' => $settings,
                            'sections' => $sections,
                            ])->render();

            foreach(session('schema') as $data) {
                if($data['section'] === $request->section_id) {
                    $json = $data;
                    break;
                }
            }

            $json['disabled'] = array_key_exists('disabled', $section) ? $section['disabled'] : false;
            $json['content_for_index'] = false;

            if(array_search($request->section_id, $settings['content_for_index'])) {
                $json['content_for_index'] = true;
            }

            if($json['disabled']) {
                $html = '';
            }

            return response()->json([
                'html' =>  $html,
                'json' => $json
            ]);
        }

        $request->session()->put('settings', $request->settings);

        if($request->settings) {
            return $this->compileAssets($request->settings, 'preview', $theme->slug);
        }
        
    }

    public function override(Request $request, $theme_id)
    {
        if(!$request->ajax()) {
            return;
        }

        $theme = Theme::where('alias', $theme_id)->first();
        if(!$theme) {
            abort('404');
        }

        $request->session()->put('settings', $request->settings);
    }

    protected function compileAssets($config, $mode, $theme) 
    {
        $scss = new Compiler();
        $scss->setImportPaths(resource_path('views/stores/'.session('store')->domain.'/'.$theme.'/assets/'));
        $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
        $scss->setVariables(array_except($config, ['sections', 'content_for_index']));

        $scss->registerFunction("typography", function($data) {
          
            $data = array_flatten($data);

            $data = array_where($data, function ($value, $key) {
                        
                        if($value != 'string' && $value != 'keyword' && $value != '') {
                            return $value;
                        }

                    });
            
            $data = implode('+', $data);
            
            if(str_contains($data, 'Google')) {
               
                $type_parts = explode('_', $data);
                $type_name = $type_parts[1];
                $font_family = str_replace("+"," ",$type_name);

                if(last($type_parts) === 'serif') {
                    $font_family .= ', serif';
                } else {
                    $font_family .= ', "HelveticaNeue", "Helvetica Neue", sans-serif';
                }
       
                $font_weight = $type_parts[2];

                return $font_family.'|'.$font_weight;
        
            }

        });

        $css = $scss->compile('@import "customized.scss";');

        if($mode === 'preview') {
             return $css;
        } else if($mode === 'current') {
            file_put_contents(public_path('stores/'.session('store')->domain.'/themes/'.$theme.'/assets/customized.css'),$css);
        }
    }


    public function reset(Request $request, $theme_id) 
    {
        if(!Theme::where('alias', $theme_id)->count()) {
            abort('404');
        }

        $theme = Theme::where('alias', $theme_id)->first();

        if(!file_exists(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/setting.json'))) {

            return response()->json([
                'error' => 'Theme config file not found.'
            ], 422);
        }

        $config = json_decode(file_get_contents(resource_path('views/stores/'.$request->tenant()->domain.'/'.$theme->slug.'/config/setting.json')), true);

        if(!array_key_exists('default', $config)) {

            return response()->json([
                'error' => 'Theme reset failed.'
            ]);

        }

        $setting['current'] = $config['default'];

        /*file_put_contents(public_path('stores/'.$request->tenant()->domain.'/themes/'.$theme->slug.'/assets/editor.json'),json_encode(array_replace_null_values($setting), JSON_PRETTY_PRINT));

        $config = json_decode(file_get_contents(public_path('stores/'.$request->tenant()->domain.'/themes/'.$theme->slug.'/assets/editor.json')), true);

        $this->compileAssets($config, 'preview', $theme->slug);*/

        return response()->json([
            'redirect' => route('theme.editor', $theme->alias)
        ]);

    }

    public function activeTheme(Request $request)
    {
        $theme = Theme::where('alias', $request->theme_id)->first();

        if(!$theme) 
            abort(404);

        if(!$request->tenant()->themes->contains('theme_id', $theme->id))
            abort(404);

        
        $request->tenant()->themes()->whereIn('id', $request->tenant()->themes->pluck('id')->toArray())->update([
            'active' => 0
        ]);

        $request->tenant()->themes()->where('theme_id', $theme->id)->update([
            'active' => 1
        ]);

        $request->session()->flash('success', $theme->theme_name.' activated successfully.');

    }

}
