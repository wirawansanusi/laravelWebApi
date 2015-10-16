<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

// Using Dropbox Service
use Illuminate\Support\Facades\File;
use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\Filesystem;
use Dropbox\Client;

// Using Entity Model
use App\Category;
use App\Post;
use App\PostThumbnail;

// Using Utility Class
use Image;
use Intervention\Image\Size;

class PostThumbnailController extends Controller
{
    protected function initFilesystem()
    {
        $prefix = 'product_images';
        $client = new Client('3DmH_8oLTKAAAAAAAAAAB6OxHkkn9-0UUPBa0A-_TNV7y3n5raKdaezv5262EAc7', 'by7j8gc0f3htexs');
        $adapter = new DropboxAdapter($client, $prefix);
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }
    protected function initFilesystemForSmallThumbnail()
    {
        $prefix = 'product_images_small';
        $client = new Client('3DmH_8oLTKAAAAAAAAAAB6OxHkkn9-0UUPBa0A-_TNV7y3n5raKdaezv5262EAc7', 'by7j8gc0f3htexs');
        $adapter = new DropboxAdapter($client, $prefix);
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId, $postId)
    {
        $filesystem = $this->initFilesystemForSmallThumbnail();

        // fetching a record based on post_id
        $post_thumbnails = PostThumbnail::where('post_id', $postId)->get();
        $images = array();
        if ($post_thumbnails) {
            foreach ($post_thumbnails as $post_thumbnail) {
                $file = $filesystem->read($post_thumbnail->path);
                $data = Image::make($file);
                $id = $post_thumbnail->id;

                $image = array();
                $image['data'] = $data->encode('data-url');
                $image['id'] = $id;
                $images[] = $image;
            }
            return $images;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId, $postId, $thumbnailId)
    {
        $filesystem = $this->initFilesystemForSmallThumbnail();

        // fetching a record based on post_id
        $post_thumbnail = PostThumbnail::findOrFail($thumbnailId);

        $file = $filesystem->read($post_thumbnail->path);
        $data = Image::make($file);
        $id = $post_thumbnail->id;

        $image = array();
        $image['data'] = $data->encode('data-url');
        $image['id'] = $id;

        return $image;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLarge($categoryId, $postId, $thumbnailId)
    {
        $filesystem = $this->initFilesystem();

        // fetching a record based on post_id
        $post_thumbnail = PostThumbnail::findOrFail($thumbnailId);

        $file = $filesystem->read($post_thumbnail->path);
        $data = Image::make($file);
        $id = $post_thumbnail->id;

        $image = array();
        $image['data'] = $data->encode('data-url');
        $image['id'] = $id;

        return $image;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $categoryId, $postId)
    {
        $filesystem = $this->initFilesystem();
        $filesystem2 = $this->initFilesystemForSmallThumbnail();

        if(Request::hasFile('file')) {

            $files = Request::file('file');
            foreach ($files as $file) {  

                $fullname = $file->getClientOriginalName(); 
                $ext = $file->guessClientExtension(); 
                $filename = time() . '-' .md5($fullname). '.' . $ext;
                $filesystem->put($filename, file_get_contents($file));

                $file_small =Image::make($file)->resize('150', null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                $file_small->encode('jpeg');
                $filesystem2->put($filename, (string) $file_small);

                // create a new record
                $post_thumbnail = new PostThumbnail;
                $post_thumbnail->path = $filename;
                $post_thumbnail->path_small = $filename;
                $post_thumbnail->mime = $file->getClientMimeType();
                $post_thumbnail->post_id = $postId;
                $post_thumbnail->save();
            }

        }else{
            return response()->json(['file' => 'File not found, please try again.']);
        }
    }

    /**
     * Remove the specified thumbnail from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId, $postId, $thumbnailId)
    {
        $filesystem = $this->initFilesystem();

        $post_thumbnail = PostThumbnail::findOrFail($thumbnailId);
        $path = $post_thumbnail->path;
        $filesystem->delete($path);
        $post_thumbnail->delete();

        // Update the category version in order to request a new json data
        // from the ios application
        $category = Category::findOrFail($categoryId);
        $category_version_string = $category->version;
        $category_version = ((int) $category_version_string) + 1;
        $category->version = $category_version;
        $category->save();
        return "success";
    }

    /**
     * Remove the specified post and thumbnail from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPost($categoryId, $postId)
    { 
        $filesystem = $this->initFilesystem();

        $post = Post::findOrFail($postId);
        $post_thumbnails = PostThumbnail::where('post_id', $postId)->get();
        if($post_thumbnails){
            foreach ($post_thumbnails as $post_thumbnail) {
                $path = $post_thumbnail->path;
                $filesystem->delete($path);
                $post_thumbnail->delete();
            }
        }

        $post->delete();

        // Update the category version in order to request a new json data
        // from the ios application
        $category = Category::findOrFail($categoryId);
        $category_version_string = $category->version;
        $category_version = ((int) $category_version_string) + 1;
        $category->version = $category_version;
        $category->save();
        return "success";
    }

    public function resizeThumbnail() 
    {
        $filesystem = $this->initFilesystem();
        $filesystem2 = $this->initFilesystemForSmallThumbnail();

        $post_thumbnails = PostThumbnail::where('id', '>', 200)->get()->take(17);
        foreach ($post_thumbnails as $post_thumbnail) {
            
            $file = $filesystem->read($post_thumbnail->path);
            $data = Image::make($file)->resize('150', null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
            $data->encode('jpeg');

            $filename = $post_thumbnail->path;
            $filesystem2->put($filename, (string) $data);

            // update record
            $post_thumbnail->path_small = $filename;
            $post_thumbnail->save();
        }
    }
}
