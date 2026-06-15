<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index()
    {
        return view('Admin.Blogs.index');
    }

    public function data(Request $request)
    {    
        $query = Blog::where('id', '!=', 0)->orderBy('date', 'desc');
        return DataTables::eloquent($query)
            
            ->editColumn('index', function ($blog) {
                return $blog->index;
            })
            ->editColumn('date', function ($blog) {
                return toIndianDate($blog->date);
            })
            ->editColumn('label', function ($blog) {
                $label = strip_tags($blog->label);
                return strlen($label) > 20 ? substr($label, 0, 20) . '...' : $label;
            })
            
            ->editColumn('title', function ($blog) {
                $title = strip_tags($blog->title);
                return strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
            })
            ->editColumn('short_description', function ($blog) {
                $description = strip_tags($blog->short_description);
                return strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description;
            })
            
            ->editColumn('description', function ($blog) {
                return $blog->description;
            })
            ->editColumn('cover_img', function ($blog) {
                $url = asset('storage/' . $blog->cover_img);
                return '<img src="' . $url . '" alt="Cover Image" height="60">';
            })
            ->editColumn('main_img', function ($blog) {
                $url = asset('storage/' . $blog->main_img);
                return '<img src="' . $url . '" alt="Main Image" height="60">';
            })
            ->editColumn('slug', function ($blog) {
                return $blog->slug;
            })
            ->editColumn('meta_title', function ($blog) {
                return $blog->meta_title;
            })
            ->editColumn('meta_description', function ($blog) {
                return $blog->meta_description;
            })
            ->editColumn('home_featured', function ($blog) {
                if ($blog->home_featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input blogs-home_featured-switch" type="checkbox" data-column="home_featured" checked data-routekey="' . $blog->route_key . '" /></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input blogs-home_featured-switch" type="checkbox" data-column="home_featured" data-routekey="' . $blog->route_key . '" /></div>';
                }
            })
            ->editColumn('status', function ($blog) {
                if ($blog->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input blogs-status-switch" type="checkbox" checked data-routekey="' . $blog->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input blogs-status-switch" type="checkbox" data-routekey="' . $blog->route_key . '"/></div>';
                }
            })
            
            ->addColumn('action', function ($blog) {
                $edit  = '<a href="' . route('admin.blogs.edit', ['blog' => $blog->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $show = '<a href="'.route('admin.blogs.show',['blog' => $blog->id]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="blogs" data-title="Blog" data-id="'.$blog->id.'" data-route-key="'.$blog->id.'"><i class="fa fa-eye"></i></a>';

                return $edit . ' ' . $show;

            })
            ->addIndexColumn()
            ->rawColumns(['index', 'date','label','title','short_description', 'description','cover_img','main_img','slug','meta_title','meta_description','action', 'status','home_featured'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $blogs = Blog::all();
        return response()->json([
            'status' => 'success',
            'list' => $blogs
        ], 200);
    }

    public function create()
    {
        return view('Admin.Blogs.form');
    }

    public function store(Request $request)
    {
        // $this->rules['index'] = 'required|unique:blogs,index,NULL,id';
        $request->validate($this->rules, $this->customMessages);

        $blog = new Blog();
        $blog->fill($request->except(['cover_img', 'main_img']));

        if ($request->hasFile('cover_img')) {
            $blog->cover_img = Storage::disk('public')->put('blog/cover_img', $request->file('cover_img'));
        }

        if ($request->hasFile('main_img')) {
            $blog->main_img = Storage::disk('public')->put('blog/main_img', $request->file('main_img'));
        }

        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog Created Successfully',
            'blog' => $blog
        ], 201);
    }

    public function show(Blog $blog){
        // dd($blog);
        return view('Admin.Blogs.show',compact('blog'));
    }
    

    public function edit(Blog $blog)
    {
        return view('Admin.Blogs.form', compact('blog'));
    }
    

    public function update(Request $request, Blog $blog)
    {
        $this->rules['cover_img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=416,height=227';
        $this->rules['main_img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=996,height=600';

        $request->validate($this->rules, $this->customMessages);

        $blog->fill($request->except(['cover_img', 'main_img']));

        if ($request->hasFile('cover_img')) {
            if ($blog->cover_img && Storage::disk('public')->exists($blog->cover_img)) {
                Storage::disk('public')->delete($blog->cover_img);
            }
            $blog->cover_img = Storage::disk('public')->put('blog/cover_img', $request->file('cover_img'));
        }

        if ($request->hasFile('main_img')) {
            if ($blog->main_img && Storage::disk('public')->exists($blog->main_img)) {
                Storage::disk('public')->delete($blog->main_img);
            }

            $blog->main_img = Storage::disk('public')->put('blog/main_img', $request->file('main_img'));
        }

        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully.',
            'blog' => $blog
        ]);
    }
        
public function changeHomeFeaturedStatus(Request $request)
{
    $blog = Blog::findByKey($request->route_key);
    $blog->home_featured = $request->status;
    $blog->save();

    return response()->json([
        'status' => 'success',
        'message' => $blog->name . ' has been marked ' . $blog->home_featured . ' successfully',
        'blog' => $blog
    ], 201);
}

public function changeStatus(Request $request)
{
    $blog = Blog::findByKey($request->route_key);
    $blog->status = $request->status;
    $blog->save();

    return response()->json([
        'status' => 'success',
        'message' => $blog->name . ' has been marked ' . $blog->status . ' successfully',
        'blog' => $blog
    ], 201);
}

    public function destroy($id)
    {
       //
    }

    private $rules = [
        // 'index' => 'required|numeric',
        'date' => 'required|date',
        'label' => 'required|string',
        'title' => 'required|string',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'cover_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=416,height=227',
        'main_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=996,height=600', 
        'slug' => 'required|string',
    ];
    
    // Custom messages
    private $customMessages = [
        // 'index.required' => 'Index is required',
        'description.required' => 'Description is required',
        'date.required' => 'Date is required',
        'label.required' => 'Label is required',
        'title.required' => 'Title is required',
        'cover_img.required' => 'Cover image is required', 
        'cover_img.image' => 'Cover image must be an image',
        'cover_img.mimes' => 'Cover image must be a file of type: jpeg, png, jpg, gif, svg',
        'cover_img.max' => 'Cover image size should not exceed 2048 KB',
        'main_img.required' => 'Main image is required',
        'main_img.image' => 'Main image must be an image',
        'main_img.mimes' => 'Main image must be a file of type: jpeg, png, jpg, gif, svg',
        'main_img.max' => 'Main image size should not exceed 2048 KB',
        'slug.required' => 'Slug is required',
        'short_description.required' => 'Short Description is required',
        'cover_img.dimensions' => 'Cover image dimensions must be 416x227 pixels',
        'main_img.dimensions' => 'Main image dimensions must be 996x600 pixels',
    ];
    
}
