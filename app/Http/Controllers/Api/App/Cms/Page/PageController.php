<?php

namespace App\Http\Controllers\Api\App\Cms\Page;

use App\Http\Controllers\Controller;
use App\Lib\SojebVar\SojebVar;
use App\Models\Cms\Page\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($slug)
    {
        try {
            $page = Page::where('slug', $slug)
                ->where('status', 1)
                ->first();

            if ($page) {
                // variable parsing
                $page->body = SojebVar::parse($page->body);
                $page->meta_title = SojebVar::parse($page->meta_title);
                $page->meta_description = SojebVar::parse($page->meta_description);
                // end variable parsing

                return response()->json([
                    'success' => true,
                    'data' => $page,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Page not found",
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Page not found",
            ]);
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
