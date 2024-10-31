<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Page\Http\Requests\PageFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('page::pages.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('page::pages.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(PageFormRequest $request)
    {
        Page::create([
            'type' => $request->type,
            'title' => $request->title,
            'slug' => str_slug($request->url_handle, '-'),
            'body' => htmlentities($request->content),
            'meta_title' => $request->page_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'map' => $request->map,
            'enable_form' => $request->enable_form ? 1 : 0,
            'active' => $request->active,
        ]);

        return redirect()->route('pages.index')->withSuccess('Page created successfully.');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('page::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Page $page)
    {
        return view('page::pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(PageFormRequest $request, Page $page)
    {
        $page->update([
            'type' => $request->type,
            'title' => $request->title,
            'slug' => str_slug($request->url_handle, '-'),
            'body' => htmlentities($request->content),
            'meta_title' => $request->page_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'map' => $request->map,
            'enable_form' => $request->enable_form ? 1 : 0,
            'active' => $request->active
        ]);

        return redirect()->route('pages.index')->withSuccess('Page updated successfully.');
    }


}
