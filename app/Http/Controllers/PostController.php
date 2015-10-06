<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

// Using Entity Model
use App\Post;
use App\PostThumbnail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId)
    {
        $posts = Post::where('category_id', $categoryId)->get();
        $posts_DTO = array();

        if($posts){

            foreach ($posts as $post) {
                $post_thumbnails = PostThumbnail::where('post_id', $post->id)->get();
                
                $post_DTO['id'] = $post->id;
                $post_DTO["title"] = $post->title;
                $post_DTO["body"] = $post->body;
                $post_DTO["category_id"] = $post->category_id;

                $post_DTO["thumbnails"] = array();
                if ($post_thumbnails) {
                    foreach ($post_thumbnails as $post_thumbnail) {
                        $id = $post_thumbnail->id;
                        $post_DTO["thumbnails"][] = (int) $id;
                    }
                }
                $posts_DTO[] = $post_DTO;
            }
        }

        return $posts_DTO;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $categoryId)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->category_id = $categoryId;
        $post->save();

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId, $postId)
    {
        $post = Post::findOrFail($postId);
        $post_thumbnails = PostThumbnail::where('post_id', $postId)->get();

        $post_DTO = array();
        $post_DTO["id"] = $post->id;
        $post_DTO["title"] = $post->title;
        $post_DTO["body"] = $post->body;
        $post_DTO["category_id"] = $post->category_id;

        $post_DTO["thumbnails"] = array();
        if ($post_thumbnails) {
            foreach ($post_thumbnails as $post_thumbnail) {
                $id = $post_thumbnail->id;
                $post_DTO["thumbnails"][] = (int) $id;
            }
        }

        return $post_DTO;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $categoryId, $postId)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $post = Post::findOrFail($postId);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId, $postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();
    }
}
