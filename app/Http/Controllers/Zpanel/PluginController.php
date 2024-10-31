<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Zipper;
use Shopbox\Models\Zpanel\Plugin;
use Artisan;
use Module;

class PluginController extends Controller
{  
    
    protected function getExtractedModule (array $modules) {
        foreach($modules as $module) { 
            if(!(bool) Plugin::where('plugin_name', $module->name)->count()) { 
                return Module::find($module->name);
            } 
        } 
        return null;
    }
 
    protected function runModuleCommands($command, array $options = null) { 
        if($options != null) {
            Artisan::call($command, $options);
        } else {
            Artisan::call($command);
        }
        
    } 
    
    protected function insertModule(array $plugin_info) {
        Plugin::create([
            'plugin_name' => strtolower($plugin_info['name']),
            'slug' => str_slug($plugin_info['name'], '-'),
            'author' => strtolower($plugin_info['author']),
            'email' => strtolower($plugin_info['email']),
            'description' => strtolower($plugin_info['description']),
            'price' => strtolower($plugin_info['price']),
            'free-trial' => strtolower($plugin_info['free_trail']),
            'is_core' => strtolower($plugin_info['is_core'])
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $plugins = Plugin::all();
        return view('zpanel.modules.index')->withPlugins($plugins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('zpanel.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id)
    {
        $plugin = Plugin::find($id);
        $module = Module::find($plugin->plugin_name);
        $module->enable();
        $plugin->status = 1;
        $plugin->save();
        return redirect()->back()->withSuccess('Plugin '.strtolower($module->getName()).' activated successfully!');
    } 
    
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        $plugin = Plugin::find($id);
        $module = Module::find($plugin->plugin_name);
        $module->disable();
        $plugin->status = 0;
        $plugin->save();
        return redirect()->back()->withSuccess('Plugin '.strtolower($module->getName()).' deactivated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $plugin = Plugin::find($id);
        // delete db record.
        $plugin->delete();
        // roll back migrations.
        $module = Module::find($plugin->plugin_name);
        $this->runModuleCommands('module:migrate-rollback', [ $module->getName() ]);
        // delete module.
        $module->delete();
        return response()->json([
            'message' => title_case($plugin->plugin_name).' has been deleted',
            'type' => 'success'
        ],200);
    } 
    
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {  
        foreach($request->file('file') as $file) {
            Zipper::make($file->getPathName())->extractTo(config('modules.paths.modules'));
        }   
        // get the extracted module.
        $modules = Module::getByStatus(1);
        $module = $this->getExtractedModule($modules);
        $this->runModuleCommands('module:migrate', [ $module->getName() ]);
        $this->runModuleCommands('module:seed', [ $module->getName() ]);
        // insert module details.
        $module_json = file_get_contents(config('modules.paths.modules').'/'.$module->getName().'/module.json');
        $module_info = json_decode($module_json, true);
        $this->insertModule($module_info);
        // disable module by default.
        $module->disable();
        return response()->json([
            'message' => 'Module uploaded successfully!',
            'type' => 'success',
        ],200);
    }

}
