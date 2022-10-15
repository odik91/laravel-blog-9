<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Menu Lists';
        $menus = Menu::orderBy('menu', 'asc')->get();
        return view('admin.menu.index', compact('title', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Menu';
        return view('admin.menu.create', compact('title'));
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
            'menu' => 'required|min:3|unique:menus',
            'icon' => 'required|min:5'
        ]);

        $route = $request['route'];
        $route == null ? $route = 'none' : $route = $request['route'];

        $data = $request->all();
        $data['route'] = $route;
        $insert = Menu::create($data);

        if ($insert) {
            Session::flash('success', "Menu $request->menu added successfully");
        } else {
            Session::flash('error', "Menu $request->menu to add new menu");
        }

        return redirect()->route('menu.create');
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
        $menu = Menu::where('id', $id)->first();
        $title = 'Edit Menu';
        return view('admin.menu.edit', compact('menu', 'title'));
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
            'menu' => 'required|min:3|unique:menus',
            'icon' => 'required|min:5'
        ]);

        $route = $request['route'];
        $route == null ? $route = 'none' : $route = $request['route'];

        $menu = Menu::find($id);
        $data = $request->all();
        $data['route'] = $route;
        $update = $menu->update($data);

        if ($update) {
            Session::flash('success', "Menu $request->menu edited successfully");
        } else {
            Session::flash('error', "Menu $request->menu to has fail to update");
        }
        return redirect()->route('menu.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        $menuName = $menu['menu'];
        $delete = $menu->delete();
        if ($delete) {
            Submenu::where('menu_id', $id)->update(['active' => 'inactive']);
            Session::flash('success', "Menu $menuName deleted successfully");
        } else {
            Session::flash('error', "Menu $menuName fail to delete");
        }
        return redirect()->route('menu.index');
    }

    public function trash()
    {
        $title = 'Trash Menu';
        $menus = Menu::onlyTrashed()->orderBy('deleted_at', 'asc')->get();
        return view('admin.menu.trash', compact('menus', 'title'));
    }

    public function restoreMenu($id)
    {
        $menuName = Menu::onlyTrashed()->where('id', $id)->first()->menu;
        $menu = Menu::onlyTrashed()->where('id', $id)->restore();
        if ($menu) {
            Session::flash('success', "Menu $menuName restored successfully");
        } else {
            Session::flash('error', "Menu $menuName fail to restore");
        }
        return redirect()->route('menu.trash');
    }

    public function delete($id)
    {
        $menuName = Menu::onlyTrashed()->where('id', $id)->first()->menu;
        $delete = Menu::onlyTrashed()->where('id', $id)->forceDelete();
        if ($delete) {
            Session::flash('success', "Menu $menuName has deleted permanently");
        } else {
            Session::flash('error', "Menu $menuName fail to delete permanently");
        }
        return redirect()->route('menu.trash');
    }
}
