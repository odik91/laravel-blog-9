<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\SubCategory;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use Session;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Post List';
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('admin.post.index', compact('title', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Post';
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.post.create', compact('title', 'categories'));
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
            'title' => 'required|min:3',
            'category' => 'required',
            'subcategory' => 'required',
            'picture' => 'mimes:jpg,jpeg,png',
            'article' => 'required|min:10'
        ]);

        $content = $request['article'];
        $dom = new \DOMDocument();
        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | libxml_use_internal_errors(true));
        $imageFiles = $dom->getElementsByTagName('img');
        $arrImg = [];
        foreach ($imageFiles as $key => $imageFile) {
            $data = $imageFile->getAttribute('src');
            if (strpos($data, ';') === false) {
                continue;
            }
            list($type, $data) = explode(';', $data);
            list($e, $data) = explode(',', $data);
            $imageData[$key] = base64_decode($data);
            $uniqueName = date_timestamp_get(date_create());
            $imageName[$key] = '/post-image/' . date('timestamp') . time() . $uniqueName . $imageFiles[$key]->getAttribute('data-filename');
            $path = public_path() . $imageName[$key];
            file_put_contents($path, $imageData[$key]);
            $imageFile->removeAttribute('src');
            $imageFile->setAttribute('src', $imageName[$key]);
            array_push($arrImg, substr($imageName[$key], 12));
        }

        $content = $dom->saveHTML();

        $image = '';

        if ($request->file('picture')) {
            /**
             * mage require to install composer require intervention/image
             * then configure config/app.php
             * in provier add code in the bottom 
             * Intervention\Image\ImageServiceProvider::class
             * 
             * add code in the bottom of aliases
             * 'Image' => Intervention\Image\Facades\Image::class
             */
            $image = time() . $request['picture']->hashName();
            $pathImage = public_path('/post-image');
            $resizeImage = Image::make($request['picture']->path());
            $resizeImage->resize(1024, 1024, function ($const) {
                $const->aspectRatio();
            })->save($pathImage . '/' . $image);
        }

        $inputData['title'] = ($request['title']);
        $inputData['slug'] = Str::slug(strtolower($request['title']));
        $inputData['category_id'] = $request['category'];
        $inputData['sub_category_id'] = $request['subcategory'];
        $inputData['content'] = $content;
        $inputData['image'] = $image;
        $inputData['author'] = auth()->user()->id;
        $inputData['year'] = date("Y");
        $inputData['month'] = date("m");
        $inputData['post_image_list_id'] = date('timestamp') . time();

        $create = Post::create($inputData);

        if ($create) {
            $post = Post::where('title', $request['title'])->first();
            for ($i = 0; $i < sizeof($arrImg); $i++) {
                $list['image_name'] = $arrImg[$i];
                $list['unique_post_id'] = $post['id'];
                PostImage::create($list);
            }
            Session::flash('success', "Article $request->title added successfully");
        } else {
            for ($i = 0; $i < sizeof($arrImg); $i++) {
                $list['image_name'] = $arrImg[$i];
                unlink(public_path("post-image/{$list['image_name']}"));
            }
            Session::flash('error', "Article $request->title fail to add");
        }

        return redirect()->route('post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "View Post";
        $post = Post::find($id);
        $postImages = DB::table('post_images')->where('unique_post_id', $id)->pluck('image_name', 'id');

        return view('admin.post.view', compact('title', 'post', 'postImages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit Post';
        $post = Post::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        $subcategory = SubCategory::withTrashed()->where('id', $post['sub_category_id'])->first();
        return view('admin.post.edit', compact('title', 'post', 'categories', 'subcategory'));
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
            'title' => 'required|min:3',
            'category' => 'required',
            'subcategory' => 'required',
            'picture' => 'mimes:jpg,jpeg,png',
            'article' => 'required|min:10'
        ]);

        $post = Post::find($id);
        $oldContent = $post['content'];

        $domOldArticle = new \DOMDocument();
        $domOldArticle->loadHTML($oldContent, LIBXML_HTML_NOIMPLIED | libxml_use_internal_errors(true));
        $findImages = $domOldArticle->getElementsByTagName('img');
        $oldImages = [];

        foreach ($findImages as $key => $findImages) {
            $data = $findImages->getAttribute('src');
            $data = explode('/', $data);
            array_push($oldImages, $data[2]);
        }

        $content = $request['article'];
        $dom = new \DOMDocument();
        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | libxml_use_internal_errors(true));
        $imageFiles = $dom->getElementsByTagName('img');
        $arrayImage = [];

        foreach ($imageFiles as $key => $imageFile) {
            $data = $imageFile->getAttribute('src');
            if (strpos($data, ';') === false) {
                continue;
            }
            list($type, $data) = explode(';', $data);
            list($e, $data) = explode(',', $data);
            $imageData[$key] = base64_decode($data);
            $uniqueName = date_timestamp_get(date_create());
            $imageName[$key] = "/post-image/" . date('timestamp') . time() . $key . $uniqueName . $imageFiles[$key]->getAttribute('data-filename');
            $path = public_path() . $imageName[$key];
            file_put_contents($path, $imageData[$key]);
            $imageFile->removeAttribute('src');
            $imageFile->setAttribute('src', $imageName[$key]);
            array_push($arrayImage, substr($imageName[$key], 12));
        }

        $content = $dom->saveHTML();

        $arrayRemoveimage = array_diff($arrayImage, $oldImages);
        if (sizeof($arrayRemoveimage) > 0) {
            for ($i = 0; $i > sizeof($arrayRemoveimage); $i++) {
                PostImage::where('image_name', $arrayRemoveimage[$i])->delete();
                unlink(public_path("post-image/{$arrayRemoveimage[$i]}"));
            }
        }

        $imageName = $post['image'];
        if ($request->hasFile('image')) {
            unlink(public_path("post-image/{$post['image']}"));

            $imageName = time() . $request['picture']->hashName();
            $pathImage = public_path('/post-image');
            $smallImage = Image::make($request['picture']->path());
            $smallImage->resize(1024, 1024, function($const) {
                $const->aspectRatio();
            })->save($pathImage . '/' . $imageName);
        }

        $inputData['title'] = ($request['title']);
        $inputData['slug'] = Str::slug(strtolower($request['title']));
        $inputData['category_id'] = $request['category'];
        $inputData['sub_category_id'] = $request['subcategory'];
        $inputData['content'] = $content;
        $inputData['image'] = $imageName;
        $inputData['author'] = auth()->user()->id;
        $inputData['year'] = date("Y");
        $inputData['month'] = date("m");
        $inputData['post_image_list_id'] = $post['post_image_list_id'];

        $update = $post->update($inputData);
        if ($update) {
            for ($i = 0; $i < sizeof($arrayImage); $i++) {
                $list['image_name'] = $arrayImage[$i];
                $list['unique_post_id'] = $post['id'];
                PostImage::create($list);
            }
            Session::flash('success', "Article $request->title edited successfully");
        } else {
            for ($i = 0; $i < sizeof($arrayImage); $i++) {
                Post::where('image_name', $arrayImage[$i])->delete();
                unlink(public_path("post-image/{$arrayImage[$i]}"));                
            }
            Session::flash('error', "Article $request->title fail to edit");
        }

        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $postTitle = $post['title'];
        $delete = $post->delete();

        if ($delete) {
            Session::flash('success', "Submenu $postTitle deleted succesfully");
        } else {
            Session::flash('error', "Submneu $postTitle fail to deleted");
        }

        return redirect()->route('post.index');
    }

    public function trash()
    {
        $title = 'Trash Post';
        $posts = Post::onlyTrashed()->orderBy('deleted_at', 'asc')->get();
        return view('admin.post.trash', compact('posts', 'title'));
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->where('id', $id);
        $postTitle = $post->first()->title;
        $restore = $post->restore();

        if ($restore) {
            Session::flash('success', "Submenu $postTitle restored succesfully");
        } else {
            Session::flash('error', "Submneu $postTitle fail to restore");
        }

        return redirect()->route('post.trash');
    }

    public function delete($id)
    {
        $post = Post::onlyTrashed()->where('id', $id);
        $postTitle = $post->first()->title;
        $singleImage = $post->first()->image;
        $images = PostImage::where('unique_post_id', $id)->get();
        $delete = $post->forceDelete();

        if ($delete) {
            foreach ($images as $image) {
                unlink(public_path("post-image/{$image['image_name']}"));
            }
            PostImage::where('unique_post_id', $id)->delete();
            unlink(public_path("post-image/{$singleImage}"));
            Session::flash('success', "Submenu $postTitle has deleted permanently");
        } else {
            Session::flash('error', "Submneu $postTitle fail to delete permanently");
        }

        return redirect()->route('post.trash');
    }

    public function subcategory($id)
    {
        $subcategories = SubCategory::where('category_id', $id)->pluck('subname', 'id');
        return response()->json($subcategories);
    }
}
