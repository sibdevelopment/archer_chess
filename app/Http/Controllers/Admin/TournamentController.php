<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TournamentMail;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\Tournament;
use App\Models\TournamentData;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    public function index()
    {
        return view('Admin.Tournaments.index');
    }

    public function data(Request $request)
    {
        $query = Tournament::where('id', '!=', 0)->orderBy('date', 'desc');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($tournament) {
                return $tournament->name;
            })
            ->editColumn('batches', function ($tournament) {
                $batches = [];
                if (!empty($tournament->batch_ids)) {
                    foreach ($tournament->batch_ids as $batch_id) {
                        $batch = Batch::find($batch_id);
                        if ($batch) { // Ensure the batch exists
                            $batches[] = $batch->name;
                        }
                    }
                    return implode('<br>', $batches);
                } else {
                    return '';
                }
            })
            ->editColumn('levels', function ($tournament) {
                $levels = [];
                if (!empty($tournament->level_ids)) {
                    foreach ($tournament->level_ids as $level_id) {
                        $level = Level::find($level_id);
                        if ($level) {
                            $levels[] = $level->name;
                        }
                    }
                    return implode('<br>', $levels);
                } else {
                    return '';
                }
            })
            ->editColumn('students', function ($tournament) {
                $students = [];
                if (!empty($tournament->student_ids)) {
                    foreach ($tournament->student_ids as $student_id) {
                        $student = Student::find($student_id);
                        // dd($student);
                        if ($student) {
                            $students[] = $student->getFullNameAttribute();
                        }
                    }
                    return implode('<br>', $students);
                } else {
                    return '';
                }
            })
            ->editColumn('certificate', function ($tournament) {
                if (!empty($tournament->certificate)) {
                    $certificateUrl = asset(\Storage::url($tournament->certificate['path']));
                    $viewCertificateUrl = url('admin/tournaments/view/certificate/' . $tournament->route_key);
                    return '<div>
                                <img src="' . $certificateUrl . '" alt="Certificate" style="max-width: 100px; height: auto; display: block; margin-bottom: 5px;">
                                <a href="' . $viewCertificateUrl . '"  class="btn btn-primary downloadCertificate" style="display: inline-block; font-size: 10px; padding:0px 25px; text-align: center; text-decoration: none; color: #fff; background-color: #007bff; border-radius: 3px;" target="_blank">Download</a>
                            </div>';
                } else {
                    return '';
                }
            })
            ->editColumn('link', function ($tournament) {
                return $tournament->link;
            })
            ->editColumn('date', function ($tournament) {
                return toIndianDate($tournament->date);
            })
            ->editColumn('status', function ($tournament) {
                if ($tournament->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input tournament-status-switch" type="checkbox" checked data-routekey="' . $tournament->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input tournament-status-switch" type="checkbox" data-routekey="' . $tournament->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($tournament) {
                $edit = '<a href="' . route('admin.tournaments.edit', ['tournament' => $tournament->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-tournament-id="' . $tournament->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                return $edit . '  ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'name', 'date', 'link', 'action', 'batches', 'levels', 'students', 'certificate'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        $batches = Batch::where('status', 'ACTIVE')->get();
        $students = Student::where('status', 'ACTIVE')->get();
        $levels = Level::where('status', 'ACTIVE')->get();
        return view('Admin.Tournaments.form', compact('batches', 'students', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);
        $countries = $request->country;
        $tournament = new Tournament;
        $tournament->fill($request->all());
        if (!empty($request->certificate) && $request->file('certificate')) {
            $certificate = $request->file('certificate');
            $certificatefile['name'] = $certificate->getClientOriginalName();
            $certificatefile['name'] = pathinfo($certificate->getClientOriginalName(), PATHINFO_FILENAME);
            $certificatefile['path'] = Storage::disk('public')->put('certificate', $certificate);
            $tournament->certificate = $certificatefile;
        }
        $tournament->save();

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

        // (d) Country Students
        if (!empty($countries)) {
            $countryStudents = Student::whereIn('country', $countries)
                ->where('status', '!=', 'INACTIVE')
                ->get();

            $students = $students->merge($countryStudents);
        }

        // ✅ Remove duplicates
        $students = $students->unique('id');

        foreach ($students as $student) {
            // TournamentData::firstOrCreate([
            //     'tournament_id' => $tournament->id,
            //     'student_id'    => $student->id,
            // ]);

            // 🔑 Send Mail
            if (!empty($student->email)) {
                // Mail::to($student->email)->send(new TournamentMail($student, $tournament));
                break;
            }
        }

        // if (!empty($request->student_ids)) {
        //     foreach ($request->student_ids as $student_id) {
        //         $tournamentData = new TournamentData;
        //         $tournamentData->tournament_id = $tournament->id;
        //         $tournamentData->student_id = $student_id;
        //         $tournamentData->save();
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
        //                         $tournamentData = new TournamentData;
        //                         $tournamentData->tournament_id = $tournament->id;
        //                         $tournamentData->student_id = $student_batch->student_id;
        //                         $tournamentData->save();
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // if ($request->has('country') && !empty($request->country) && $request->level_ids == null) {
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
        //                     $tournamentData = TournamentData::where('student_id', $student_batch->student_id)->first();
        //                     if (empty($tournamentData)) {
        //                         $tournamentData = new TournamentData;
        //                     }
        //                     $tournamentData->tournament_id = $tournament->id;
        //                     $tournamentData->student_id = $student_batch->student_id;
        //                     $tournamentData->save();
        //                 }
        //             }
        //         }
        //     }
        // } else {
        //     if (!empty($request->level_ids)) {
        //         foreach ($request->level_ids as $level_id) {
        //             $level = Level::find($level_id);
        //             if (!empty($level)) {
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
        //                 if ($batches) {
        //                     foreach ($batches as $batch) {
        //                         $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                             ->wherehas('student', function ($q) use ($countries) {
        //                                 $q->where('status', '!=', 'INACTIVE');
        //                             })->get();
        //                         if (!empty($student_batches)) {
        //                             foreach ($student_batches as $student_batch) {
        //                                 $tournamentData = TournamentData::where('student_id', $student_batch->student_id)->first();
        //                                 if (empty($tournamentData)) {
        //                                     $tournamentData = new TournamentData;
        //                                 }
        //                                 $tournamentData->tournament_id = $tournament->id;
        //                                 $tournamentData->student_id = $student_batch->student_id;
        //                                 $tournamentData->save();
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
            'message' => 'Tournament Created Successfully',
        ], 201);
    }

    public function edit(Tournament $tournament)
    {

        $batches = Batch::where('status', 'ACTIVE')->get();
        $students = Student::get();
        $levels = Level::where('status', 'ACTIVE')->get();
        $selectedBatches = old('batch_ids', $tournament->batch_ids ?? []);
        $selectedStudents = old('student_ids', $tournament->student_ids ?? []);
        $selectedLevels = old('level_ids', $tournament->level_ids ?? []);

        return view('Admin.Tournaments.form', compact('tournament', 'batches', 'students', 'levels', 'selectedBatches', 'selectedStudents', 'selectedLevels'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $request->validate($this->rules, $this->customMessages);
        $tournament->fill($request->all());
        $countries = $request->country;

        if (!empty($request->certificate) && $request->file('certificate')) {
            $certificate = $request->file('certificate');
            $certificatefile['name'] = pathinfo($certificate->getClientOriginalName(), PATHINFO_FILENAME);
            $certificatefile['path'] = Storage::disk('public')->put('certificate', $certificate);
            $tournament->certificate = $certificatefile;
        }
        $tournament->save();

        // $tournamentdata = TournamentData::where('tournament_id', $tournament->id)->delete();

        // if (!empty($request->student_ids)) {
        //     foreach ($request->student_ids as $student_id) {
        //         $tournamentData = new TournamentData;
        //         $tournamentData->tournament_id = $tournament->id;
        //         $tournamentData->student_id = $student_id;
        //         $tournamentData->save();
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
        //                         $tournamentData = new TournamentData;
        //                         $tournamentData->tournament_id = $tournament->id;
        //                         $tournamentData->student_id = $student_batch->student_id;
        //                         $tournamentData->save();
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // if ($request->has('country') && !empty($request->country) && $request->level_ids == null) {
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
        //                     $tournamentData = TournamentData::where('student_id', $student_batch->student_id)->first();
        //                     if (empty($tournamentData)) {
        //                         $tournamentData = new TournamentData;
        //                     }
        //                     $tournamentData->tournament_id = $tournament->id;
        //                     $tournamentData->student_id = $student_batch->student_id;
        //                     $tournamentData->save();
        //                 }
        //             }
        //         }
        //     }
        // } else {
        //     if (!empty($request->level_ids)) {
        //         foreach ($request->level_ids as $level_id) {
        //             $level = Level::find($level_id);
        //             if (!empty($level)) {
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
        //                 if ($batches) {
        //                     foreach ($batches as $batch) {
        //                         $student_batches = StudentBatch::where('batch_id', $batch->id)
        //                             ->wherehas('student', function ($q) use ($countries) {
        //                                 $q->where('status', '!=', 'INACTIVE');
        //                             })->get();
        //                         if (!empty($student_batches)) {
        //                             foreach ($student_batches as $student_batch) {
        //                                 $tournamentData = TournamentData::where('student_id', $student_batch->student_id)->first();
        //                                 if (empty($tournamentData)) {
        //                                     $tournamentData = new TournamentData;
        //                                 }
        //                                 $tournamentData->tournament_id = $tournament->id;
        //                                 $tournamentData->student_id = $student_batch->student_id;
        //                                 $tournamentData->save();
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
            'message' => 'Tournament Created Successfully',
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $tournament = Tournament::findByKey($request->route_key);
        $tournament->status = $request->status;
        $tournament->save();

        return response()->json([
            'status' => 'success',
            'message' => $tournament->name . ' has been marked ' . $tournament->status . ' successfully',
            'level' => $tournament,
        ], 201);
    }

    public function destroy(Request $request, Tournament $tournament)
    {
        $tournament = Tournament::where('id', $request->tournament_id)->first();
        if ($tournament) {
            $tournament->delete();
            return response()->json([
                'success' => 'Touranment deleted successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'Touranment not found',
            ], 404);
        }
    }

    public function viewCertificate(Tournament $tournament)
    {
        if (!empty($tournament->certificate)) {
            // Path to certificate
            $certificatePath = storage_path('app/public/' . $tournament->certificate['path']);

            // Ensure the file exists
            if (!file_exists($certificatePath)) {
                return response()->json(['message' => 'Certificate file not found'], 404);
            }

            $studentName = '';
            if (!empty($tournament->student_ids)) {
                foreach ($tournament->student_ids as $student_id) {
                    $student = Student::find($student_id);
                    if ($student) {
                        $studentName = $student->getFullNameAttribute();
                        break; // Get only the first student's name
                    }
                }
            }

            $data = [
                'certificate_name' => $tournament->name ?? 'Certificate',
                'certificateUrl' => $certificatePath,
                'studentName' => $studentName,
            ];

            // Generate the PDF
            $pdf = PDF::loadView('Admin.Tournaments.certificate', $data);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream($data['certificate_name'] . '.pdf');
        }

        return response()->json(['message' => 'Certificate not found'], 404);
    }

    private $rules = [
        'country' => 'required',
        'name' => 'required',
        'date' => 'required',
        'time' => 'required',
        'link' => 'required',
    ];

    private $customMessages = [
        'name.required' => 'Name is required',

        'date.required' => 'Date is required',
        'time.required' => 'Time is required',
        'link.required' => 'Link is required',
    ];

}
