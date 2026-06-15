<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.Events.index');
    }

    public function data(Request $request)
    {
        $query = Event::where('id', '!=', 0);

        return DataTables::eloquent($query)
            ->editColumn('status', function ($event) {
                if ($event->status == 'ACTIVE') {
                    return '<div class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input event-status-switch" type="checkbox" checked data-routekey="' . $event->route_key . '"/>
                                </div>
                            </div>';
                } else {
                    return '<div class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input event-status-switch" type="checkbox" data-routekey="' . $event->route_key . '"/>
                                </div>
                            </div>';
                }
            })

            ->addColumn('image', function ($event) {
                return $event->image;
            })
            ->addColumn('date', function ($event) {
                if(!empty($event->date)){
                    return toIndianDate($event->date);
                }
                return '';
            })
            ->addColumn('action', function ($event) {
                $edit  = '<a href="' . route('admin.events.edit', ['event' => $event->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $show = '<a href="'.route('admin.events.show',['event' => $event->route_key]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="events" data-title="Event" data-route-key="'.$event->route_key.'"><i class="fa fa-eye"></i></a>';
                return $edit . ' ' . $show;
            })
            ->addColumn('link', function ($event) {
                if ($event->link) {
                    $url = $event->link;

                    // Automatically prepend http:// if missing
                    if (!preg_match('#^https?://#', $url)) {
                        $url = 'https://' . $url;
                    }

                    return '<a href="' . e($url) . '" target="_blank" class="btn btn-sm btn-info">Link</a>';
                }
                return '-';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'image', 'link'])->setRowId('id')->make(true);
    }

    public function create()
    {
        return view('Admin.Events.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $lastIndex = Event::max('index');
        $newIndex = $lastIndex ? $lastIndex + 1 : 1;
        $event = new Event();
        $event->fill($request->except('image'));
        if ($request->hasFile('image')) {
            $event->image = Storage::disk('public')->put('images', $request->file('image'));
        } else {
            $event->image = null; // <-- add this line
        }
        if ($request->hasFile('brochure')) {
            $event->brochure = Storage::disk('public')->put('brochures', $request->file('brochure'));
        } else {
            $event->brochure = null; // <-- add this line
        }
        $event->index = $newIndex;
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Event Details Stored Successfully',
        ], 201);
    }

    public function show(Event $event)
    {
        return view('Admin.Events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('Admin.Events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->rules['title'] = 'required|unique:events,title,' . $event->id;
        $this->rules['image'] = 'nullable|image|max:5120'; // Make image optional for update
        $request->validate($this->rules, $this->customMessages);

        $event->fill($request->all());
        if ($request->hasFile('image')) {
            $event->image = Storage::disk('public')->put('images', $request->file('image'));
        }

        if ($request->hasFile('brochure')) {
            $event->brochure = Storage::disk('public')->put('brochures', $request->file('brochure'));
        }
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Event Details Updated Successfully',
        ], 201);
    }

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $event = Event::findByKey($request->route_key);
        $event->status = $request->status;
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Event has marked ' . $event->status . ' successfully',
            'event' => $event
        ], 201);
    }

    private $rules = [
        'title' => 'required|unique:galleries,title',
        'link' => 'required|url',
        'image' => 'required|image|max:5120',
        'date' => 'required|date',
        'mode' => 'required',
        'short_description' => 'required',
        'location' => 'required',
        'brochure' => 'required',
    ];

    private $customMessages = [
        'title.required' => 'Please enter a title.',
        'title.unique' => 'This event title already exists.',
        'link.required' => 'Please enter a link.',
        'image.required' => 'Please upload an image.',
        'image.image' => 'Only image files are allowed (jpg, jpeg, png, svg, webp).',
        'image.max' => 'Image size should not exceed 5MB.',
        'date.required' => 'Please enter a date.',
        'mode.required' => 'Please select a mode.',
        'location.required' => 'Please enter a location.',
        'short_description.required' => 'Please enter a short description.',
    ];
}
