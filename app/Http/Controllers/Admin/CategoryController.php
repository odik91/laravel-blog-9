<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Image;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Category List';
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.category.index', compact('title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Category';
        return view('admin.category.create', compact('title'));
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
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'image' => 'mimes:jpg,jpeg,png'
        ]);

        $data = $request->all();
        $image = 'default.png';

        if($request->hasFile('image')) {
            /**
             * mage require to install composer require intervention/image
             * then configure config/app.php
             * in provier add code in the bottom 
             * Intervention\Image\ImageServiceProvider::class
             * 
             * add code in the bottom of aliases
             * 'Image' => Intervention\Image\Facades\Image::class
             */
            $image = time() . $request['image']->hashName();
            $pathImage = public_path('/image');
            $smallImage = Image::make($request['image']->path());
            $smallImage->resize(512, 512, function($const) {
                $const->aspectRatio();
            })->save($pathImage . '/' . $image);
        }

        $data['image'] = $image;
        $data['slug'] = Str::slug(strtolower($request['name']));
        $added = Category::create($data);

        if ($added) {
            Session::flash('success', "Category $request->name added successfully");
        } else {
            Session::flash('error', "Category $request->name fail to add");
        }

        return redirect()->route('category.index');
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
        $category = Category::find($id);
        $title = 'Edit Category';
        return view('admin.category.edit', compact('category', 'title'));
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
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'image' => 'mimes:jpg,jpeg,png'
        ]);

        $data = $request->all();
        $category = Category::find($id);
        $categoryName = $category['name'];
        $image = $category['image'];

        if($request->hasFile('image')) {
            unlink(public_path('image/' . $category['image']));

            /**
             * mage require to install composer require intervention/image
             * then configure config/app.php
             * in provier add code in the bottom 
             * Intervention\Image\ImageServiceProvider::class
             * 
             * add code in the bottom of aliases
             * 'Image' => Intervention\Image\Facades\Image::class
             */
            $image = time() . $request['image']->hashName();
            $pathImage = public_path('/image');
            $smallImage = Image::make($request['image']->path());
            $smallImage->resize(512, 512, function($const) {
                $const->aspectRatio();
            })->save($pathImage . '/' . $image);
        }

        $data['image'] = $image;
        $data['slug'] = Str::slug(strtolower($request['name']));
        $update = $category->update($data);

        if ($update) {
            Session::flash('success', "Category $categoryName updated successfully");
        } else {
            Session::flash('error', "Category $categoryName fail to update");
        }

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $categoryName = $category['name'];
        $delete = $category->delete();

        if ($delete) {
            Session::flash('success', "Category $categoryName deleted successfully");
        } else {
            Session::flash('error', "Category $categoryName fail to delete");
        }

        return redirect()->route('category.index');
    }

    public function trash() {
        $title = 'Trash Category';
        $categories = Category::onlyTrashed()->orderBy('deleted_at', 'asc')->get();
        return view('admin.category.trash', compact('categories', 'title'));
    }

    public function restore($id) {
        $category = Category::onlyTrashed()->where('id', $id);
        $categoryName = $category->first()->name;
        $restore = $category->restore();

        if ($restore) {
            Session::flash('success', "Category $categoryName restored successfully");
        } else {
            Session::flash('error', "Category $categoryName fail to restore");
        }

        return redirect()->route('category.trash');
    }

    public function delete($id) {
        $category = Category::onlyTrashed()->where('id', $id);
        $categoryName = $category->first()->name; 
        $delete = $category->forceDelete();

        if ($delete) {
            Session::flash('success', "Category $categoryName has deleted permanently");
        } else {
            Session::flash('error', "Category $categoryName fail to deleted permanently");
        }

        return redirect()->route('category.trash');
    }
}
