<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Blog\Http\Requests\BlogFormRequest;
use Modules\Blog\Entities\Blog;
use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;

class BlogController extends Controller
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
        return view('blog::blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('blog::blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(BlogFormRequest $request)
    {
        $blog = new Blog;
        $blog->title = $request->title;
        $blog->slug = $request->url_handle;
        $blog->content = htmlentities($request->content);
        $blog->author = $request->author;
        $blog->meta_title = $request->page_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->active = $request->visibility;

        if($request->featured_image != null) { 

            $blog->featured_image = $this->uploadFeaturedImage($request->file('featured_image'));
                
        }

        $blog->save();

        return redirect()->route('blogs.index')->withSuccess('Blog created successfully!');
    }

    protected function uploadFeaturedImage(UploadedFile $uploadedFile)
    {
        $path = public_path('stores').'/'.session('store')->domain.'/blog';
        $name = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->getClientOriginalExtension();

        $this->imageManager->make($uploadedFile->getPathName())
         ->save($path.'/'.$image_path = $name.'_'.str_random(10).'.'.$extension, 60);

        return $image_path;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('blog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Blog $blog)
    {
        return view('blog::blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(BlogFormRequest $request, Blog $blog)
    {
        $blog->title = $request->title;
        $blog->slug = $request->url_handle;
        $blog->content = htmlentities($request->content);
        $blog->author = $request->author;
        $blog->meta_title = $request->page_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->active = $request->visibility;

        if($request->featured_image != null) { 

            $blog->featured_image = $this->uploadFeaturedImage($request->file('featured_image'));
                
        }

        $blog->save();

        return redirect()->route('blogs.index')->withSuccess('Blog updated successfully!');
    }
    

    public function destroy(Blog $blog)
    {
        if(file_exists(public_path('stores').'/'.session('store')->domain.'/blog/'.$blog->featured_image)) {
            unlinkFile(public_path('stores').'/'.session('store')->domain.'/blog/'.$blog->featured_image);
        }
    

        $blog->update([
            'featured_image' => null,
        ]);

        return response()->json([
            'message' => 'Featured image deleted successfully.',
            'type' => 'success'
        ]);
    }

}
