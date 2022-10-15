<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Submenu;
use Illuminate\Support\Facades\Session;

class SubmenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Submenu List';
        $submenus = Submenu::orderBy('title', 'asc')->get();
        return view('admin.submenu.index', compact('title', 'submenus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Submenu';
        $menus = Menu::orderBy('menu', 'asc')->get();
        return view('admin.submenu.create', compact('title', 'menus'));
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
            'menu_id' => 'required',
            'title' => 'required|min:3',
            'route' => 'required|min:3',
            'icon' => 'required',
            'active' => 'required'
        ]);

        $data = $request->all();
        $insert = Submenu::create($data);

        if ($insert) {
            Session::flash('success', "Submenu $request->title added successfully");
        } else {
            Session::flash('error', "Submenu $request->title fail to add");
        }

        return redirect()->route('submenu.create');
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
        $title = 'Edit Submenu';
        $submenu = Submenu::where('id', $id)->first();
        return view('admin.submenu.edit', compact('submenu', 'title'));
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
            'menu_id' => 'required',
            'title' => 'required|min:3',
            'route' => 'required|min:3',
            'icon' => 'required',
            'active' => 'required'
        ]);

        $submenu = Submenu::find($id);
        $data = $request->all();
        $update = $submenu->update($data);

        if ($update) {
            Session::flash('success', "Submenu $request->title updated successfully");
        } else {
            Session::flash('error', "Submneu $request->title fail to update");
        }

        return redirect()->route('submenu.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $submenuName = Submenu::where('id', $id)->first()->title;
        $delete = Submenu::find($id)->delete();
        if ($delete) {
            Session::flash('success', "Submenu $submenuName delete successfully");
        } else {
            Session::flash('error', "Submneu $submenuName fail to delete");
        }

        return redirect()->route('submenu.index');
    }

    public function trash() {
        $title = 'Trash Submenu';
        $submenus = Submenu::onlyTrashed()->orderBy('deleted_at','asc')->get();
        return view('admin.submenu.trash', compact('submenus', 'title'));
    }

    public function restore($id) {
        $submenuName = Submenu::onlyTrashed()->where('id', $id)->first()->title;
        $restore = Submenu::onlyTrashed('id', $id)->restore();

        if ($restore) {
            Session::flash('success', "Submenu $submenuName restore successfully");
        } else {
            Session::flash('error', "Submneu $submenuName fail to restore");
        }

        return redirect()->route('submenu.trash');
    }

    public function delete($id) {
        $submenuName = Submenu::onlyTrashed()->where('id', $id)->first()->title;
        $delete = Submenu::onlyTrashed()->where('id', $id)->forceDelete();

        if ($delete) {
            Session::flash('success', "Submenu $submenuName has deleted permanently");
        } else {
            Session::flash('error', "Submneu $submenuName fail to deleted permanently");
        }

        return redirect()->route('submenu.trash');
    }
}
