<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Subcategory List';
        $subcategories = SubCategory::orderBy('subname', 'asc')->get();
        return view('admin.subcategory.index', compact('title','subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Subcategory';
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.subcategory.create', compact('title', 'categories'));
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
            'category' => 'required',
            'subname' => 'required|unique:sub_categories'
        ]);

        $data['category_id'] = $request['category'];
        $data['subname'] = $request['subname'];
        $data['slug'] = Str::slug(strtolower($request['subname']));

        $create = SubCategory::create($data);

        if ($create) {
            Session::flash('success', "Subcategory $request->subname added successfully");
        } else {
            Session::flash('error', "Subcategory $request->subname fail to add");
        }

        return redirect()->route('subcategory.index');
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
        $title = 'Edit Subcategory';
        $categories = Category::orderBy('name', 'asc')->get();
        $subcategory = SubCategory::findOrFail($id);
        return view('admin.subcategory.edit', compact('subcategory', 'categories', 'title'));
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
            'category' => 'required',
            'subname' => 'required'
        ]);

        $data['category_id'] = $request['category'];
        $data['subname'] = $request['subname'];
        $data['slug'] = Str::slug(strtolower($request['subname']));

        $subcategory = SubCategory::find($id);

        $update = $subcategory->update($data);
        if ($update) {
            Session::flash('success', "Subcategory $request->subname updated successfully");
        } else {
            Session::flash('error', "Subcategory $request->subname fail to update");
        }

        return redirect()->route('subcategory.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);
        $subcategoryName = $subcategory['subname'];
        $delete = $subcategory->delete();

        if ($delete) {
            Session::flash('success', "Subcategory $subcategoryName deleted successfully");
        } else {
            Session::flash('error', "Subcategory $subcategoryName fail to delete");
        }

        return redirect()->route('subcategory.index');
    }

    public function trash() {
        $title = 'Trash Subcategory';
        $subcategories = SubCategory::onlyTrashed()->orderBy('deleted_at', 'asc')->get();
        return view('admin.subcategory.trash', compact('title','subcategories'));
    }

    public function restore($id) {
        $subcategory = Subcategory::onlyTrashed()->where('id', $id);
        $subcategoryName = $subcategory->first()->subname;
        $restore = $subcategory->restore();

        if ($restore) {
            Session::flash('success', "Subcategory $subcategoryName restored successfully");
        } else {
            Session::flash('error', "Subcategory $subcategoryName fail to restore");
        }

        return redirect()->route('subcategory.trash');
    }

    public function delete($id) {
        $subcategory = Subcategory::onlyTrashed()->where('id', $id);
        $subcategoryName = $subcategory->first()->subname;
        $delete = $subcategory->forceDelete();

        if ($delete) {
            Session::flash('success', "Subcategory $subcategoryName has deleted permanently");
        } else {
            Session::flash('error', "Subcategory $subcategoryName fail to deleted permanently");
        }

        return redirect()->route('subcategory.trash');
    }
}
