<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('Admin.Testimonials.index');
    }

    public function data(Request $request)
    {
        $query = Testimonial::orderBy('display_order')->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('image', fn($testimonial) => $testimonial->image)
            ->editColumn('name', fn($testimonial) => $testimonial->name)
            ->editColumn('designation', fn($testimonial) => $testimonial->designation)
            ->editColumn('rating', fn($testimonial) => $testimonial->rating)
            ->editColumn('display_order', fn($testimonial) => $testimonial->display_order)
            ->addColumn('action', function ($testimonial) {
                return '<a href="' . route('admin.testimonials.edit', ['testimonial' => $testimonial->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
            })
            ->addColumn('status', fn($testimonial) =>
                '<div class="form-check form-switch"><input class="form-check-input testimonials-status-switch" type="checkbox" data-routekey="' . $testimonial->route_key . '"' . ($testimonial->status == 'ACTIVE' ? ' checked' : '') . '/></div>'
            )
            ->addIndexColumn()
            ->rawColumns(['image', 'action', 'status'])
            ->setRowId('route_key')
            ->make(true);
    }

    public function create()
    {
        return view('Admin.Testimonials.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $testimonial = new Testimonial();
        $testimonial->fill($request->all());

        if ($request->hasFile('image')) {
            $testimonial->image = Storage::disk('public')->put('images', $request->file('image'));
        }

        $testimonial->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial Created Successfully',
        ], 201);
    }

    public function edit(Testimonial $testimonial)
    {
        return view('Admin.Testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $this->rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480|dimensions:ratio=1/1';

        $request->validate($this->rules, $this->customMessages);

        $testimonial->fill($request->all());

        if ($request->hasFile('image')) {
            if ($testimonial->image && !str_starts_with($testimonial->image, '/')) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $testimonial->image = Storage::disk('public')->put('images', $request->file('image'));
        }

        $testimonial->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial Updated Successfully',
        ], 200);
    }

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $testimonial = Testimonial::findByKey($request->route_key);
        $testimonial->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status has been marked ' . $testimonial->status . ' successfully',
        ], 200);
    }

    private $rules = [
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'review' => 'required|string',
        'rating' => 'required|numeric|min:1|max:5',
        'display_order' => 'nullable|integer|min:0',
        'card_class' => 'nullable|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480|dimensions:ratio=1/1',
    ];

    private $customMessages = [
        'name.required' => 'Name is required',
        'designation.required' => 'Designation is required',
        'review.required' => 'Review is required',
        'rating.required' => 'Rating is required',
        'rating.numeric' => 'Rating must be a number',
        'rating.min' => 'Rating must be at least 1',
        'rating.max' => 'Rating must not be greater than 5',
        'image.required' => 'Image is required',
        'image.image' => 'Image must be a valid image file.',
        'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, webp.',
        'image.dimensions' => 'Image ratio must be 1:1 square, for example 304x304 pixels.',
        'image.max' => 'Image size must not exceed 20MB.',
    ];
}
