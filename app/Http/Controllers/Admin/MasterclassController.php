<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Level;
use App\Models\Student;
use App\Models\Masterclass;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Mail\MasterclassMail;
use Illuminate\Support\Carbon;
use App\Models\MasterclassData;
use App\Http\Controllers\Controller;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Mail;

class MasterclassController extends Controller
{ 
    public function index()
    {
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.Masterclasses.index', compact('coaches'));
    }

    public function data(Request $request)
    {
        $query = Masterclass::where('id', '!=', 0)->orderBy('date', 'desc');

        if ($request->has('coach_id') && !empty($request->coach_id)) {
            $query->where('coach_id', $request->coach_id);
        }

        if ($request->has('country') && !empty($request->country)) {
            $country = $request->country;
            $query->where(function ($q) use ($country) {
                $q->whereRaw('JSON_CONTAINS(country, ?)', [json_encode($country)]);
            });
        }

        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay()->format('Y-m-d H:i:s');
            $endDate   = Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay()->format('Y-m-d H:i:s');

            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return DataTables::eloquent($query)

            ->editColumn('name', function ($masterclass) {
                return $masterclass->name;
            })
            ->editColumn('batches', function ($masterclass) {
                $batches = [];
                if (empty($masterclass->batch_ids)) {
                    return 'N/A';
                } else {
                    if (!empty($masterclass->batch_ids)) {
                        foreach ($masterclass->batch_ids as $batch_id) {
                            $batch = Batch::find($batch_id);
                            if ($batch) { // Ensure the batch exists
                                $batches[] = $batch->name;
                            }
                        }
                        return implode('<br>', $batches);
                    }
                }

            })
            ->editColumn('levels', function ($masterclass) {
                $levels = [];
                if (!empty($masterclass->level_ids)) {
                    foreach ($masterclass->level_ids as $level_id) {
                        $level = Level::find($level_id);
                        if ($level) {
                            $levels[] = $level->name;
                        }
                    }
                    return implode('<br>', $levels);
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('students', function ($masterclass) {
                $students = [];
                if (!empty($masterclass->student_ids)) {
                    foreach ($masterclass->student_ids as $student_id) {
                        $student = Student::find($student_id);
                        if ($student) {
                            $students[] = $student->getFullNameAttribute();
                        }
                    }
                    return implode('<br>', $students);
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('coach', function ($masterclass) {

                if ($masterclass->coach->user) {
                    return $masterclass->coach->user->full_name;
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('date', function ($masterclass) {
                return toIndianDate($masterclass->date);
            })
            ->editColumn('status', function ($masterclass) {
                if ($masterclass->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input masterclass-status-switch" type="checkbox" checked data-routekey="' . $masterclass->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input masterclass-status-switch" type="checkbox" data-routekey="' . $masterclass->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($masterclass) {
                $edit = '<a href="' . route('admin.masterclasses.edit', ['masterclass' => $masterclass->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $show = '<a href="' . route('admin.masterclasses.show', ['masterclass' => $masterclass->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="masterclasses" data-title="Masterclass Details" data-route-key="' . $masterclass->route_key . '"><i class="fa fa-eye"></i></a>';

                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-masterclass-id="' . $masterclass->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                return $edit;
            })
            ->editColumn('start_url', function ($masterclass) {
                if(!empty($masterclass->start_url)) {
                    return '<i class="ti ti-link cursor-pointer text-primary fs-5 copy-link" data-url="' . e($masterclass->start_url) . '" title="Copy Start URL"></i>';
                } 

                return '<span class="badge bg-secondary fs-1">N/A</span>';
            })
            ->editColumn('join_url', function ($masterclass) {
                if(!empty($masterclass->join_url)){
                    return '<i class="ti ti-link cursor-pointer text-success fs-5 copy-link" data-url="' . e($masterclass->join_url) . '" title="Copy Join URL"></i>';
                }

                return '<span class="badge bg-success fs-1">N/A</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'name', 'date', 'coach', 'batches', 'levels', 'students', 'action', 'start_url', 'join_url'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $batches = Batch::where('status', 'ACTIVE')->get();
        $students = Student::where('status', 'ACTIVE')->get();
        $levels = Level::where('status', 'ACTIVE')->get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.Masterclasses.form', compact('batches', 'students', 'levels', 'coaches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->rules, $this->customMessages);
        $countries = $request->country;
        $coach = Coach::find($request->coach_id);

        

        $masterclass = new Masterclass;
        $masterclass->fill($request->all());
        $masterclass->save();


        if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {

            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id 
            );
            
            $meetingData = [
                'title'              => 'Masterclass - '. $coach->user->first_name.' - '.$masterclass->name,
                'duration_in_minute' => 40,
                'start_date_time' => now()->addMinutes(5)->toIso8601String(),
            ];
            
            // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);
            $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

            $masterclass->start_url = $zoomResponse['start_url'] ?? '';
            $masterclass->join_url  = $zoomResponse['join_url'] ?? '';
            $masterclass->zoom_meeting_id  = $zoomResponse['id'] ?? null;
            $masterclass->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
            $masterclass->save();
        }

            $students = collect();

        // (a) Direct Students
        if (!empty($request->student_ids)) {
            $students = $students->merge(
                Student::whereIn('id', $request->student_ids)
                    ->where('status', '!=', 'INACTIVE')
                    ->get()
            );
        }

        // (b) Batch Students
        if (!empty($request->batch_ids)) {
            $batchStudents = Student::whereHas('studentBatches', function ($q) use ($request) {
                    $q->whereIn('batch_id', $request->batch_ids)
                    ->where('status', '!=', 'INACTIVE');
                })->get();

            $students = $students->merge($batchStudents);
        }

        // (c) Level Students
        if (!empty($request->level_ids)) {
            $levelStudents = Student::whereHas('studentBatches.batch', function ($q) use ($request) {
                    $q->whereIn('level_id', $request->level_ids)
                    ->where('status', '!=', 'INACTIVE');
                })->get();

            $students = $students->merge($levelStudents);
        }

        // (d) Country Students (sometimes only country, sometimes combined)
        if (!empty($countries)) {
            $countryStudents = Student::whereIn('country', $countries)
                ->where('status', '!=', 'INACTIVE')
                ->get();

            $students = $students->merge($countryStudents);
        }

        // Remove duplicate students
        $students = $students->unique('id');

        // 4. Save MasterclassData + Send Mail
        foreach ($students as $student) {
            $masterclassData = MasterclassData::firstOrNew([
                'masterclass_id' => $masterclass->id,
                'student_id'     => $student->id,
            ]);
            $masterclassData->save();

            // Send mail
            // Mail::to($student->email)->send(new MasterclassMail($student, $masterclass));
        }



        // if (!empty($request->student_ids)) {
        //     foreach ($request->student_ids as $student_id) {
        //         $masterclassData = MasterclassData::where('student_id', $student_id)->first();
        //         if (empty($masterclassData)) {
        //             $masterclassData = new MasterclassData;
        //         }
        //         $masterclassData->masterclass_id = $masterclass->id;
        //         $masterclassData->student_id = $student_id;
        //         $masterclassData->save();
        //     }
        // }

        // if (!empty($request->batch_ids)) {
        //     foreach ($request->batch_ids as $batch_id) {
        //         $batch = Batch::find($batch_id);
        //         if ($batch) {
        //             $student_batches = StudentBatch::where('batch_id', $batch_id)
        //                 ->wherehas('student', function ($q) use ($countries) {
        //                     $q->where('status', '!=', 'INACTIVE');
        //                 });

        //             if ($student_batches) {
        //                 foreach ($student_batches as $student_batch) {
        //                     $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                     if (empty($masterclassData)) {
        //                         $masterclassData = new MasterclassData;
        //                     }
        //                     $masterclassData->masterclass_id = $masterclass->id;
        //                     $masterclassData->student_id = $student_batch->student_id;
        //                     $masterclassData->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        // if ($request->has('country') && !empty($request->country) && $request->level_ids == null) {
        //     // dd(11);
        //     $batches = Batch::where(function ($query) use ($countries) {
        //         foreach ($countries as $country) {
        //             $query->orWhereRaw('JSON_CONTAINS(country, ?)', [json_encode($country)]);
        //         }
        //     })->get();

        //     if (isset($batches)) {
        //         foreach ($batches as $batch) {
        //             $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                 ->wherehas('student', function ($q) use ($countries) {
        //                     $q->where('status', '!=', 'INACTIVE');
        //                 });
        //             if (!empty($student_batches)) {
        //                 foreach ($student_batches as $student_batch) {
        //                     $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                     if (empty($masterclassData)) {
        //                         $masterclassData = new MasterclassData;
        //                     }
        //                     $masterclassData->masterclass_id = $masterclass->id;
        //                     $masterclassData->student_id = $student_batch->student_id;
        //                     $masterclassData->save();
        //                 }
        //             }
        //         }
        //     }
        // } else {
        //     if (!empty($request->level_ids)) {
        //         foreach ($request->level_ids as $level_id) {
        //             $level = Level::find($level_id);
        //             if ($level) {
        //                 $batches = Batch::where('level_id', $level_id)
        //                     ->where('status', '!=', 'INACTIVE')
        //                     ->whereNotNull('country')
        //                     ->where(function ($query) use ($countries) {
        //                         foreach ($countries as $country) {
        //                             $query->orWhere(function ($query) use ($country) {
        //                                 $query->whereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $country . '"]'])
        //                                     ->orWhere(function ($query) use ($country) {
        //                                         $query->whereRaw('NOT json_valid(country)')
        //                                             ->where('country', $country);
        //                                     });
        //                             });
        //                         }
        //                     })->get();
        //                 // dd($batches);
        //                 if ($batches) {
        //                     foreach ($batches as $batch) {
        //                         $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                             ->wherehas('student', function ($q) use ($countries) {
        //                                 $q->where('status', '!=', 'INACTIVE');
        //                             })->get();
        //                         // dd($student_batches);
        //                         if ($student_batches) {
        //                             foreach ($student_batches as $student_batch) {
        //                                 $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                                 if (empty($masterclassData)) {
        //                                     $masterclassData = new MasterclassData;
        //                                 }
        //                                 $masterclassData->masterclass_id = $masterclass->id;
        //                                 $masterclassData->student_id = $student_batch->student_id;
        //                                 $masterclassData->save();
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'Masterclass Created Successfully',
        ], 201);
    }

    public function show(Masterclass $masterclass)
    {
        $masterclass_logs = MasterclassData::where('masterclass_id', $masterclass->id)->get();
        return view('Admin.Masterclasses.show', compact('masterclass', 'masterclass_logs'));
    }

    public function edit(Masterclass $masterclass)
    {

        $batches = Batch::where('status', 'ACTIVE')->get();
        $students = Student::where('status', 'ACTIVE')->get();
        $levels = Level::where('status', 'ACTIVE')->get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        $selectedBatches = old('batch_ids', $masterclass->batch_ids ?? []);
        $selectedStudents = old('student_ids', $masterclass->student_ids ?? []);
        $selectedLevels = old('level_ids', $masterclass->level_ids ?? []);

        return view('Admin.Masterclasses.form', compact('masterclass', 'batches', 'students', 'levels', 'coaches', 'selectedBatches', 'selectedStudents', 'selectedLevels'));
    }

    public function update(Request $request, Masterclass $masterclass)
    {
        $request->validate($this->rules, $this->customMessages);
        $countries = $request->country;
        $masterclass->fill($request->all());
        $masterclass->save();

        $coach = Coach::find($masterclass->coach_id);

        if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {
            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id 
            );

                $meetingData = [
                'title'              => 'Masterclass - '. $coach->user->first_name.' - '.$masterclass->name,
                'duration_in_minute' => 40,
                'start_date_time' => now()->addMinutes(5)->toIso8601String(),
            ];

            $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

            $masterclass->start_url = $zoomResponse['start_url'] ?? '';
            $masterclass->join_url  = $zoomResponse['join_url'] ?? '';
            $masterclass->zoom_meeting_id  = $zoomResponse['id'] ?? null;
            $masterclass->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
            $masterclass->save();
        }

        // MasterclassData::where('masterclass_id', $masterclass->id)->delete();

        // if (!empty($request->student_ids)) {
        //     foreach ($request->student_ids as $student_id) {
        //         $masterclassData = MasterclassData::where('student_id', $student_id)->first();
        //         if (empty($masterclassData)) {
        //             $masterclassData = new MasterclassData;
        //         }
        //         $masterclassData->masterclass_id = $masterclass->id;
        //         $masterclassData->student_id = $student_id;
        //         $masterclassData->save();
        //     }
        // }

        // if (!empty($request->batch_ids)) {
        //     if (!empty($request->batch_ids)) {
        //         foreach ($request->batch_ids as $batch_id) {
        //             $batch = Batch::find($batch_id);
        //             if ($batch) {
        //                 $student_batches = StudentBatch::where('batch_id', $batch_id)
        //                     ->wherehas('student', function ($q) use ($countries) {
        //                         $q->where('status', '!=', 'INACTIVE');
        //                     });

        //                 if ($student_batches) {
        //                     foreach ($student_batches as $student_batch) {
        //                         $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                         if (empty($masterclassData)) {
        //                             $masterclassData = new MasterclassData;
        //                         }
        //                         $masterclassData->masterclass_id = $masterclass->id;
        //                         $masterclassData->student_id = $student_batch->student_id;
        //                         $masterclassData->save();
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // if ($request->has('country') && !empty($request->country) && $request->level_ids == null) {
        //     // dd(11);
        //     $batches = Batch::where(function ($query) use ($countries) {
        //         foreach ($countries as $country) {
        //             $query->orWhereRaw('JSON_CONTAINS(country, ?)', [json_encode($country)]);
        //         }
        //     })->get();

        //     if (isset($batches)) {
        //         foreach ($batches as $batch) {
        //             $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                 ->wherehas('student', function ($q) use ($countries) {
        //                     $q->where('status', '!=', 'INACTIVE');
        //                 });
        //             if ($student_batches) {
        //                 foreach ($student_batches as $student_batch) {
        //                     $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                     if (empty($masterclassData)) {
        //                         $masterclassData = new MasterclassData;
        //                     }
        //                     $masterclassData->masterclass_id = $masterclass->id;
        //                     $masterclassData->student_id = $student_batch->student_id;
        //                     $masterclassData->save();
        //                 }
        //             }
        //         }
        //     }
        // } else {

        //     if (!empty($request->level_ids)) {
        //         if (!empty($request->level_ids)) {
        //             foreach ($request->level_ids as $level_id) {
        //                 $level = Level::find($level_id);
        //                 if ($level) {
        //                     $batches = Batch::where('level_id', $level_id)
        //                         ->where('status', '!=', 'INACTIVE')
        //                         ->whereNotNull('country')
        //                         ->where(function ($query) use ($countries) {
        //                             foreach ($countries as $country) {
        //                                 $query->orWhere(function ($query) use ($country) {
        //                                     $query->whereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $country . '"]'])
        //                                         ->orWhere(function ($query) use ($country) {
        //                                             $query->whereRaw('NOT json_valid(country)')
        //                                                 ->where('country', $country);
        //                                         });
        //                                 });
        //                             }
        //                         })->get();
        //                     // dd($batches);
        //                     if ($batches) {
        //                         foreach ($batches as $batch) {
        //                             $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                                 ->wherehas('student', function ($q) use ($countries) {
        //                                     $q->where('status', '!=', 'INACTIVE');
        //                                 })->get();
        //                             // dd($student_batches);

        //                             if ($student_batches) {
        //                                 foreach ($student_batches as $student_batch) {
        //                                     $masterclassData = MasterclassData::where('student_id', $student_batch->student_id)->first();
        //                                     if (empty($masterclassData)) {
        //                                         $masterclassData = new MasterclassData;
        //                                     }
        //                                     $masterclassData->masterclass_id = $masterclass->id;
        //                                     $masterclassData->student_id = $student_batch->student_id;
        //                                     $masterclassData->save();
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'Masterclass Created Successfully',
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $masterclass = Masterclass::findByKey($request->route_key);
        $masterclass->status = $request->status;
        $masterclass->save();

        return response()->json([
            'status' => 'success',
            'message' => $masterclass->name . ' has been marked ' . $masterclass->status . ' successfully',
            'level' => $masterclass,
        ], 201);
    }

    public function destroy(Request $request, Masterclass $masterclass)
    {
        //dd($request->all());
        $masterclass = Masterclass::where('id', $request->masterclass_id)->first();
        if ($masterclass) {
            $masterclass->delete();
            return response()->json([
                'success' => 'Masterclass Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'Masterclass not found',
            ], 404);
        }
    }

    private $rules = [
        'name' => 'required',
        'date' => 'required',
        'time' => 'required',
        'coach_id' => 'required',
        'country' => 'required',
    ];

    private $customMessages = [
        'name.required' => 'Name is required',
        'date.required' => 'Date is required',
        'time.required' => 'Time is required',
        'coach_id.required' => 'Coach is required',
        'country.required' => 'Country is required',
    ];
}
