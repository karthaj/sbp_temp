<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Models\Front\Theme;
use Shopbox\Http\Controllers\Controller;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
	protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
    	$this->imageManager = $imageManager;
    }

    public function store(Request $request, $theme_id)
    {
    	$this->validate($request, [
    		'image' => 'image|max:1024'
    	]);

        if(!Theme::where('alias', $theme_id)->count()) {
            abort('404');
        }

        $theme = Theme::where('alias', $theme_id)->first()->slug;

    	$uploadedFile = $request->file('image');
    	$directory = public_path('stores').'/'.$request->tenant()->domain.'/img/';
        $name = str_slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $extension = $uploadedFile->getClientOriginalExtension();

    	$processedImage = $this->imageManager->make($uploadedFile->getPathName())
    					->save($directory.$name.'.'.$extension);

        return response()->json([
            'image' => $name.'.'.$extension
        ]);


    }



}
