<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\Attribute\AttributeFormRequest;
use Modules\Product\Http\Requests\Attribute\AttributeUpdateFormRequest;
use Intervention\Image\ImageManager;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Option;
use Modules\Product\Entities\Helper;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\ImageDimension;
use Modules\Product\Transformers\AttributeTransformer;


class AttributeController extends Controller
{ 
    protected $imageManager;

    public function __construct(ImageManager $imageManager)     
    {
        $this->imageManager = $imageManager;
        
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(AttributeFormRequest $request)
    {  
        $attribute = new Attribute;
        $attribute->name = $request->option_name;
        $attribute->public_name = $request->display_name;
        $attribute->group_type = $request->display_type;

        if(!empty($request->display_style)) {
            $attribute->display_style = $request->display_style;
        } else {
            $attribute->display_style = '[CS]';
        }

        $attribute->save();

        if($request->display_type == 'multiple choice') {
            $this->addAttributeValue($attribute, $request->values, $request->tenant());
        }

        if($request->display_type == 'swatch') {
            $this->addSwatch($attribute, $request->swatches, $request->tenant());
        }
       
        return redirect()->route('attributes.index')->withSuccess('Attribute created successfully!');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Attribute $attribute)
    { 
        $attribute = fractal()->item($attribute)->transformWith(new AttributeTransformer)->toArray();
        return view('product::attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(AttributeUpdateFormRequest $request, Attribute $attribute)
    {

        if($attribute->group_type !== $request->display_type) {

            $this->refreshOptions($attribute->options);

        }
       
        if(count($request->options)) {
            $this->destroy($attribute, $request->options);
        }

        $attribute->name = $request->option_name;
        $attribute->public_name = $request->display_name;
        $attribute->group_type = $request->display_type;

        if(!empty($request->display_style)) {
            $attribute->display_style = $request->display_style;
        } else {
            $attribute->display_style = '[CS]';
        }

        $attribute->save();


        if($request->display_type == 'multiple choice') {

            $this->updateAttributeValue($attribute, $request->values, $request->tenant());
        }

        if($request->display_type == 'swatch') {
    
            $this->updateSwatch($attribute, $request->swatches, $request->tenant());
        }

    }

    protected function refreshOptions($options)
    {
        if($options->count()) {

            Option::whereIn('id', $options->pluck('id'))->delete();

        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Attribute $attribute, $options)
    {       
        foreach($options as $value) {

            $option = $attribute->options()->where('id', $value)->first();

            if($option) {
                $option->delete();

                if($option->type === 'pattern') {

                    $this->unlinkFile($option->pattern);

                }
            }
        }
    }   

    protected function addAttributeValue(Attribute $attribute, array $values, Store $store) 
    {
         for ($i=0; $i < count($values); $i++) { 
            $attribute->options()->create([
                'name' => $values[$i]['label'],
                'sort_order' => $i + 1,
                'created_at_tz' => $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone),
                'updated_at_tz' => $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone),
            ]);
         }
    }

    protected function updateAttributeValue(Attribute $attribute, array $values, Store $store) 
    { 
        for ($i=0; $i < count($values); $i++) { 

            if(!empty($values[$i]['id'])) {

                $option = Option::find($values[$i]['id']);

                if(!$option) {
                    $option = new Option;
                } 

            } else {

                $option = new Option;
            }
            
            $option->name = $values[$i]['label'];
            $option->attribute()->associate($attribute);
            $option->sort_order = $i + 1;
            $option->updated_at_tz = $option->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $option->save();
        }
    } 

    protected function updateSwatch(Attribute $attribute, array $swatches, Store $store) 
    {  
        for ($i=0; $i < count($swatches); $i++) { 
    
            if(!empty($swatches[$i]['id'])) {

                $option = Option::find($swatches[$i]['id']);

                if(!$option) {
                    $option = new Option;
                } 

            } else {

                $option = new Option;
            }
            
            $option->attribute()->associate($attribute);

            if($swatches[$i]['type'] == 'color') {

                $option->name = $swatches[$i]['label'];
                $option->color = $swatches[$i]['color'];
                $option->sort_order = $i+1;
                $option->updated_at_tz = $option->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->save();
            
            } else if($swatches[$i]['type'] == 'pattern') {

                $option->name = $swatches[$i]['label'];
                $option->sort_order = $i+1;
                $option->updated_at_tz = $option->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->save();

                if($swatches[$i]['image']) {
                    $option->pattern = $this->uploadPattern($swatches[$i]['image']);
                }
                
                $option->timestamps = false;
                $option->save();
            }
        }
    }  

    protected function addSwatch(Attribute $attribute, array $swatches, Store $store) 
    {  
        for ($i=0; $i < count($swatches); $i++) { 
            if($swatches[$i]['type'] == 'color') {
                $option = new Option;
                $option->attribute()->associate($attribute);
                $option->name = $swatches[$i]['label'];
                $option->type = 'color';
                $option->color = $swatches[$i]['color'];
                $option->sort_order = $i+1;
                $option->created_at_tz = $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->updated_at_tz = $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->save();
            } else if($swatches[$i]['type'] == 'pattern') {
                $option = new Option;
                $option->attribute()->associate($attribute);
                $option->name = $swatches[$i]['label'];
                $option->type = 'pattern';
                $option->sort_order = $i+1;
                $option->created_at_tz = $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->updated_at_tz = $attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $option->save();
                $option->timestamps = false;
                $option->pattern = $this->uploadPattern($swatches[$i]['image']);
                $option->save();
            }
        }
    }  

    protected function uploadPattern($pattern)
    {
        $dimension = ImageDimension::where('name', 'pattern_default')->first();
        $path = public_path('stores').'/'.session('store')->domain.'/pattern/';
        $name = str_slug(pathinfo($pattern->getClientOriginalName(), PATHINFO_FILENAME), '_');
        $extension = $pattern->getClientOriginalExtension();
        
        $this->imageManager->make($pattern->getPathName())
         ->fit($dimension->width, $dimension->height, function ($constraint) {
            $constraint->aspectRatio();
         })
         ->save($path.$image_path = $name.'_'.$dimension->width.'x'.$dimension->height.'_'.str_random(10).'.'.$extension);
         return $image_path;
    } 

    protected function unlinkFile($file) 
    { 
        if(file_exists(public_path('stores').'/'.session('store')->domain.'/pattern/'.$file)) {

            unlinkFile(public_path('stores').'/'.session('store')->domain.'/pattern/'.$file);

        }
        
    }

}
