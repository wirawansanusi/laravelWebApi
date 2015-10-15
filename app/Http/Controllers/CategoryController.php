<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

// Using Entity Models
use App\Category;
use App\ParentCategory;

class CategoryController extends Controller
{
    /**
     * Check user credentials.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $i = 0;
        $categories = Category::all();
        $_categories = array();

        foreach ($categories as $category) {
            $_hasChild = ParentCategory::where('child_id', $category->id)->first();
            if(!$_hasChild){
                $_category = array();
                $_category['id'] = $category->id;
                $_category['title'] = $category->title;
                $_category['temp_id'] = $i++;
                $_category['hasUpdated'] = $category->hasUpdated;
                $_category['child'] = array();
                $parentCategory = ParentCategory::where('child_id', $category->id)->first();
                if(!empty($parentCategory)){
                    $parent = Category::find($parentCategory->parent_id);
                    $_category['parent_id'] = $parent->id;
                    $_category['parent_title'] = $parent->title;
                }

                $subCategories = ParentCategory::where('parent_id', $category->id)->get();

                foreach ($subCategories as $subCategory) {
                    $_parentCategory = Category::find($subCategory->parent_id);
                    $_childCategory = Category::find($subCategory->child_id);
                    $_subCategory = array();
                    $_subCategory['id'] = $subCategory->id;
                    $_subCategory['child_id'] = $subCategory->child_id;
                    $_subCategory['parent_id'] = $subCategory->parent_id;
                    $_subCategory['title'] = $_childCategory->title;
                    $_subCategory['parent_title'] = $_parentCategory->title;
                    $_subCategory['parentTemp_id'] = $_category['temp_id'];
                    $_subCategory['hasUpdated'] = $_childCategory->hasUpdated;
                    $_category['child'][] = $_subCategory;
                }

                $_categories[] = $_category;
            }
        }

        return $_categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'parent_id' => 'required',
            'title' => 'required'
        ]);

        $category = new Category;
        $category->title = $request->title;
        $category->save();

        $newCategory = array();
        $newCategory['id'] = $category->id;
        $newCategory['title'] = $category->title;

        $result = array();
        $result['isChild'] = false; 
        $newCategory['child'] = array();

        $parent_id = $request->parent_id;
        if($parent_id != 0){
            $parentCategory = new ParentCategory;
            $parentCategory->child_id = $category->id;
            $parentCategory->parent_id = $parent_id;
            $parentCategory->save();

            $subCategory = Category::find($parentCategory->parent_id);
            $newCategory['id'] = $parentCategory->id;
            $newCategory['parent_id'] = $subCategory->id;
            $newCategory['parent_title'] = $subCategory->title;
            $newCategory['child_id'] = $category->id;
            $newCategory['temp_id'] = $request->temp_id;

            $result['isChild'] = true;
            $result['category'] = $newCategory;

        }else{

            $result['category'] = $newCategory;
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $subCategories = ParentCategory::where('parent_id', $id)->get();

        $_category = array();
        $_category['id'] = $category->id;
        $_category['title'] = $category->title;
        $_category['hasUpdated'] = $category->hasUpdated;
        $parentCategory = ParentCategory::where('child_id', $id)->first();
        if($parentCategory){
            $parent = Category::find($parentCategory->parent_id);
            $_category['parent_id'] = $parent->id;
            $_category['parent_title'] = $parent->title;
            $_category['temp_id'] = $parentCategory->id;
        }

        foreach ($subCategories as $subCategory) {
            $_parentCategory = Category::find($subCategory->parent_id);
            $_childCategory = Category::find($subCategory->child_id);
            $_subCategory = array();
            $_subCategory['id'] = $subCategory->id;
            $_subCategory['title'] = $_childCategory->title;
            $_subCategory['parent_id'] = $subCategory->parent_id;
            $_subCategory['parent_title'] = $_parentCategory->title;
            $_subCategory['child_id'] = $subCategory->child_id;
            $_subCategory['hasUpdated'] = $_childCategory->hasUpdated;
            $_category['child'] = $_subCategory;
        }

        return $_category;
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
        $this->validate($request, [
            'id' => 'required',
            'parent_id' => 'required',
            'title' => 'required'
        ]);

        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->save();

        $newCategory = array();
        $newCategory['id'] = $category->id;
        $newCategory['title'] = $category->title;

        $result = array();
        $result['isChild'] = false; 

        $parent_id = $request->parent_id;
        if($parent_id != 0){

            if($request->temp_id != 0){
                $parentCategory = ParentCategory::find($request->temp_id);
                $result['isChildBefore'] = true;
            }else{
                $parentCategory = new ParentCategory;
                $result['isChildBefore'] = false;
            }
            $parentCategory->child_id = $category->id;
            $parentCategory->parent_id = $parent_id;
            $parentCategory->save();

            $subCategory = Category::find($parentCategory->parent_id);
            $newCategory['id'] = $parentCategory->id;
            $newCategory['parent_id'] = $subCategory->id;
            $newCategory['parent_title'] = $subCategory->title;
            $newCategory['child_id'] = $category->id;
            $newCategory['temp_id'] = $request->temp_id;

            $result['isChild'] = true;
            $result['category'] = $newCategory;

        }else{
            if($request->temp_id){
                $parentCategory = ParentCategory::find($request->temp_id);
                $parentCategory->delete();
            }
            $newCategory['child'] = array();

            $result['category'] = $newCategory;
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $parentCategory = ParentCategory::where('child_id', $id);
        if($parentCategory){
            $parentCategory->delete();
        }
    }
}
