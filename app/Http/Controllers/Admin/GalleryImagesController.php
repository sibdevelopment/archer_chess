<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Models\GalleryImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryImagesController extends Controller
{
    public function index(Gallery $gallery)
    {
        return view('Admin.Galleries.GalleryImages.index', compact('gallery'));
    }

    public function data(Request $request, Gallery $gallery)
    {
        $query = GalleryImage::where('gallery_id', $gallery->id)->where('id', '!=', 0);

        return DataTables::eloquent($query)
            // ->editColumn('index', function ($galleryImages) {
            //     return $galleryImages->index;
            // })
            ->editColumn('status', function ($galleryImages) {
                if ($galleryImages->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input galleryImages-status-switch" type="checkbox" checked data-routekey="' . $galleryImages->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input galleryImages-status-switch" type="checkbox" data-routekey="' . $galleryImages->route_key . '"/></div>';
                }
            })
            ->addColumn('image', function ($galleryImages) {
                return $galleryImages->image;
            })
            ->addColumn('action', function ($galleryImages) {
                $edit  = '<a href="' . route('admin.galleries.gallery_images.edit', ['gallery' => $galleryImages->gallery->route_key, 'gallery_image' => $galleryImages->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'image'])->setRowId('id')->make(true);
    }

    public function create(Gallery $gallery)
    {
        return view('Admin.Galleries.GalleryImages.form', compact('gallery'));
    }

    public function store(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB = 5120 KB
        ], [
            'image.required' => 'Please upload an image.',
            'image.image' => 'Only image files are allowed (jpg, jpeg, png, svg, webp).',
            'image.max' => 'Image size should not exceed 5MB.',
        ]);

        // $lastIndex = GalleryImage::max('index');
        $lastIndex = GalleryImage::where('gallery_id', $gallery->id)->max('index');
        $newIndex = $lastIndex ? $lastIndex + 1 : 1;
        $galleryImages = new GalleryImage();
        $galleryImages->gallery_id = $gallery->id;
        $galleryImages->fill($request->all());
        if ($request->hasFile('image')) {
            $galleryImages->image = Storage::disk('public')->put('images', $request->file('image'));
        }
        $galleryImages->index = $newIndex;
        $galleryImages->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery Images Stored Successfully',
        ], 201);
    }

    public function show($id)
    {
        //
    }

    public function edit(Gallery $gallery, GalleryImage $galleryImages)
    {
        return view('Admin.Galleries.GalleryImages.form', compact('gallery', 'galleryImages'));
    }

    public function update(Request $request, Gallery $gallery, GalleryImage $gallery_image)
    {
        $request->validate([
            // Image is optional on update, but must be valid if provided
            'image' => 'nullable|image|max:5120', // 5MB = 5120 KB
        ], [
            'image.mimes' => 'Only image files are allowed (jpg, jpeg, png, svg, webp).',
            'image.max' => 'Image size should not exceed 5MB.',
        ]);

        $gallery_image->fill($request->all());

        if ($request->hasFile('image')) {
            $gallery_image->image = Storage::disk('public')->put('images', $request->file('image'));
        }

        $gallery_image->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery Images Updated Successfully',
        ], 201);
    }

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $galleryImages = GalleryImage::findByKey($request->route_key);
        $galleryImages->status = $request->status;
        $galleryImages->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Image has marked ' . $galleryImages->status . ' successfully',
            'galleryImages' => $galleryImages
        ], 201);
    }

    private $rules = [
        'name' => 'required',
    ];

    private $customMessages = [
        'name.required' => ' Please enter name',
    ];
}
