<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    public function index()
    {
        return view('Admin.Galleries.index');
    }

    public function data(Request $request)
    {
        $query = Gallery::where('id', '!=', 0);

        return DataTables::eloquent($query)
            // ->editColumn('index', function ($gallery) {
            //     return $gallery->index;
            // })
            ->editColumn('status', function ($gallery) {
                if ($gallery->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input gallery-status-switch" type="checkbox" checked data-routekey="' . $gallery->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input gallery-status-switch" type="checkbox" data-routekey="' . $gallery->route_key . '"/></div>';
                }
            })
            // ->addColumn('images', function ($gallery) {
            //     // $count = $gallery->images->count();
            //     $galleryImages  = '<a href="' . route('admin.galleries.gallery_images.index', ['gallery' => $gallery->route_key]) . '" class="badge bg-warning fs-1">Images</a>';
            //     return $galleryImages;
            // })
            ->addColumn('action', function ($gallery) {
                $edit  = '<a href="' . route('admin.galleries.edit', ['gallery' => $gallery->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addColumn('images', function ($gallery) {
                // Try to find the first available image from images_1 to images_5
                for ($i = 1; $i <= 5; $i++) {
                    $field = 'images_' . $i;
                    $images = $gallery->$field;

                    if (is_array($images) && count($images)) {
                        $firstImage = $images[0];
                        $url = Storage::url($firstImage);

                        return '<img src="' . $url . '" alt="Image" style="width: 80px; height: 60px; object-fit: cover;">';
                    }
                }

                return '<span class="badge bg-secondary">No Image</span>';
            })

            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'images'])->setRowId('id')->make(true);
    }

    public function create()
    {
        return view('Admin.Galleries.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);
        $lastIndex = Gallery::max('index');
        $newIndex = $lastIndex ? $lastIndex + 1 : 1;
        $gallery = new Gallery();
        $gallery->fill($request->except(['images_1','images_2', 'images_3', 'images_4', 'images_5']));

        $imageFields = ['images_1', 'images_2', 'images_3', 'images_4', 'images_5'];

        foreach ($imageFields as $field) {
            $paths = [];

            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    if ($file->isValid()) {
                        // Generate a unique name and store the file using Storage
                        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $path = Storage::disk('public')->putFileAs('gallery', $file, $filename);
                        $paths[] = $path;
                    }
                }
            }

            $gallery->{$field} = $paths;
        }


        $gallery->index = $newIndex;
        $gallery->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery Details Stored Successfully',
        ], 201);
    }

    public function show($id)
    {
        //
    }

    public function edit(Gallery $gallery)
    {
        return view('Admin.Galleries.form', compact('gallery'));
    }


    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|unique:galleries,title,' . $gallery->id,
            'images_1.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif',
            'images_2.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif',
            'images_3.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif',
            'images_4.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif',
            'images_5.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif',
        ]);

        // Delete selected images
        $deleteImages = $request->input('delete_images', []);

        foreach (['images_1', 'images_2', 'images_3', 'images_4', 'images_5'] as $field) {
            $currentImages = $gallery->$field ?? [];

            // Remove deleted images from DB field
            $updatedImages = array_filter($currentImages, function ($imgPath) use ($deleteImages) {
                return !in_array($imgPath, $deleteImages);
            });

            // Physically delete selected files
            foreach ($currentImages as $imgPath) {
                if (in_array($imgPath, $deleteImages) && Storage::exists($imgPath)) {
                    Storage::delete($imgPath);
                }
            }

            // Reassign cleaned-up array (reset keys)
            $gallery->$field = array_values($updatedImages);
        }

        // Upload new images and append them
        foreach (['images_1', 'images_2', 'images_3', 'images_4', 'images_5'] as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('gallery', 'public'); // ✅ better: save in "public" disk
                        $gallery->$field = array_merge($gallery->$field ?? [], [$path]);
                    }
                }
            }
        }

        $gallery->title = $request->title;
        $gallery->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery updated successfully.',
        ], 200);
    }



    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $gallery = Gallery::findByKey($request->route_key);
        $gallery->status = $request->status;
        $gallery->save();

        return response()->json([
            'status' => 'success',
            'message' => $gallery->title . ' has marked ' . $gallery->status . ' successfully',
            'gallery' => $gallery
        ], 201);
    }

    private $rules = [
        'title' => 'required|unique:galleries,title',
        'images_1' => 'nullable|array',
        'images_1.*' => 'file|mimes:jpeg,png,jpg,webp,svg,gif',

        'images_2' => 'nullable|array',
        'images_2.*' => 'file|mimes:jpeg,png,jpg,webp,svg,gif',

        'images_3' => 'nullable|array',
        'images_3.*' => 'file|mimes:jpeg,png,jpg,webp,svg,gif',

        'images_4' => 'nullable|array',
        'images_4.*' => 'file|mimes:jpeg,png,jpg,webp,svg,gif',

        'images_5' => 'nullable|array',
        'images_5.*' => 'file|mimes:jpeg,png,jpg,webp,svg,gif',
    ];

    private $customMessages = [
        'title.required' => ' Please enter title',
        'title.unique' => ' This title already exists',
        'images_1.array' => 'Images 1 must be an array',
        'images_2.array' => 'Images 2 must be an array',
        'images_3.array' => 'Images 3 must be an array',
        'images_4.array' => 'Images 4 must be an array',
        'images_5.array' => 'Images 5 must be an array',        
        'images_1.mime_types' => 'Images 1 must be of type: jpeg, png, jpg, gif',
        'images_2.mime_types' => 'Images 2 must be of type: jpeg, png, jpg, gif',
        'images_3.mime_types' => 'Images 3 must be of type: jpeg, png, jpg, gif',
        'images_4.mime_types' => 'Images 4 must be of type: jpeg, png, jpg, gif',
        'images_5.mime_types' => 'Images 5 must be of type: jpeg, png, jpg, gif',   
    ];
}
