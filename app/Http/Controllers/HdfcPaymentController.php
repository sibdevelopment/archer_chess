<?php
namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Order;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Paymentlevel;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class HdfcPaymentController extends Controller
{

    public function createOrder(Request $request)
    {
        $order_id     = rand(100000000000, 999999999999);
        $order_id_str = strval($order_id);

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found']);
        }

        $student_last_batch = StudentBatch::where('student_id', $student->id)->orderBy('id', 'desc')->first();
        $lastpayment_level  = Paymentlevel::where('level_id', $student_last_batch->level_id)->first();

        if ($student_last_batch->batch->status != 'ACTIVE' && $lastpayment_level) {
            $lastpayment_level = Paymentlevel::where('sequence', $lastpayment_level->sequence + 1)->first();
        }

        $nextPaymentLevel = $lastpayment_level 
            ? Paymentlevel::where('sequence', $lastpayment_level->sequence)->first() 
            : Paymentlevel::first();

        $requestedAmount = floatval($request->amount); 
        $country = strtoupper(trim($student->country));
        $currency = '';
        $correctAmount = null;

        switch ($country) {
            case 'USA':
            case 'CANADA':
                $correctAmount = floatval($nextPaymentLevel->usa_fees);
                $currency = 'USD';
                break;
            case 'AUSTRALIA':
                $correctAmount = floatval($nextPaymentLevel->australia_fees);
                $currency = 'AUD';
                break;
            case 'NEW ZEALAND':
                $correctAmount = floatval($nextPaymentLevel->newzealand_fees);
                $currency = 'NZD';
                break;
            case 'INDIA':
                $correctAmount = floatval($nextPaymentLevel->india_fees);
                $currency = 'INR';
                break;
            case 'UAE':
                $correctAmount = floatval($nextPaymentLevel->uae_fees);
                $currency = 'AED';
                break;
            case 'UK':
                $correctAmount = floatval($nextPaymentLevel->uk_fees);
                $currency = 'GBP';
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Unsupported country']);
        }

        // dd($requestedAmount, $correctAmount, $country, $currency);

        $nextThreePaymentLevels = Paymentlevel::where('sequence', '>=', $nextPaymentLevel->sequence)
                        ->orderBy('sequence', 'asc')
                        // ->where('status', 'ACTIVE')
                        ->limit(3)
                        ->get();
        
        $nextThreePaymentLevelsAmount = 0;
         if ($country == 'USA') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('usa_fees');
        } elseif ($country == 'CANADA') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('canada_fees');
        } elseif ($country == 'AUSTRALIA') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('australia_fees');
        } elseif ($country == 'NEW ZEALAND') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('newzealand_fees');
        } elseif ($country == 'INDIA') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('india_fees');
        } elseif ($country == 'UAE') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('uae_fees');
        } elseif ($country == 'UK') {
            $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('uk_fees');
        }


        if (
            abs($requestedAmount - $correctAmount) > 0.01
            && abs($requestedAmount - $nextThreePaymentLevelsAmount) > 0.01
        ) {
            return response()->json([
                'status' => 'error',
                'message' => "Amount mismatch. You entered ₹$requestedAmount but required is ₹$correctAmount or ₹$nextThreePaymentLevelsAmount for country $country."
            ]);
        }

        // Create order
        $order = new Order();
        $order->student_id    = $student->id;
        $order->hdfc_order_id = $order_id_str;
        $order->amount        = $requestedAmount;
        $order->currency      = $currency;
        $order->status        = 'PENDING';
        $order->save();

        return response()->json([
            'status'     => 'success',
            'order_id'   => $order_id_str,
            'amount'     => $requestedAmount,
            'currency'   => $currency,
            'student_id' => $student->id
        ]);
    }

    public function createSession(Request $request)
    {
        $student = Student::find($request->student_id);
        $order = Order::where('hdfc_order_id', $request->order_id)->first();

        if ($request->amount != $order->amount) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Amount mismatch',
            ]);
        }

        // $response = Http::withHeaders([
        //     'Authorization' => 'Basic ' . base64_encode('C6F050B13004DD595A329E8BEF29A3:2A4B272BC704842ABACF30D3F9993D'),
        //     'Content-Type'  => 'application/json',
        //     'x-merchantid'  => 'SG1972',
        //     'x-customerid'  => $student->id,
        // ])->post('https://smartgatewayuat.hdfcbank.com/session', [
        //     "order_id"               => $order->hdfc_order_id,
        //     "amount"                 => $order->amount,
        //     "customer_id"            => strval($student->id),
        //     "customer_email"         => $student->email,
        //     "customer_phone"         => $student->mobile,
        //     "payment_page_client_id" => "hdfcmaster",
        //     "action"                 => "paymentPage",
        //     "currency"               => $request->currency,
        //     // "return_url"             => "https://archerkids.technicul.com/student/order/thankyou",
        //     // "return_url"             => "https://archerkids.technicul.com/student/order/thankyou",
        //     "return_url"             => "http://127.0.0.1:8000/student/order/thankyou",
        //     "description"            => "Payment for student fees",
        //     "first_name"             => $student->first_name,
        //     "last_name"              => $student->last_name,
        // ]);


        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode('229F253F7C84A2A966062496E7386C:0BC5D1F84F94B94BD81D4A114C6353'),
            'Content-Type'  => 'application/json',
            'x-merchantid'  => '48026',
            'x-customerid'  => $student->id,
        ])->post('https://smartgateway.hdfcbank.com/session', [
            "order_id"               => $order->hdfc_order_id,
            "amount"                 => $order->amount,
            "customer_id"            => strval($student->id),
            "customer_email"         => $student->email,
            "customer_phone"         => $student->mobile,
            "payment_page_client_id" => "48026", 
            "action"                 => "paymentPage",
            "currency"               => $request->currency,
            "return_url"             => "https://archerchessacademy.com/student/order/thankyou",
            // "return_url"             => "https://archerkids.technicul.com/student/order/thankyou",
            "description"            => "Payment for student fees",
            "first_name"             => $student->first_name,
            "last_name"              => $student->last_name,
        ]);

        $data = $response->json();

        // dd($data);

        if ($data['status'] == 'NEW') {
            return response()->json([
                'status'       => 'success',
                'redirect_url' => $data['payment_links']['web'],
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
            ]);
        }
    }


    // public function studentOrderThankyou(Request $request)
    // {
    //     $response = $request->all();

    //     if (isset($response['order_id'])) {
    //         $order_id = $response['order_id'];
    //         $order    = Order::where('hdfc_order_id', $order_id)->first();
    //         if (!$order) {
    //             return redirect()->back()->with('error', 'Order not found.');
    //         }

    //         $student = Student::find($order->student_id);
    //         // Make the HTTP request to fetch order details
    //         $orderResponse = Http::withHeaders([
    //             // 'Authorization' => 'Basic ' . base64_encode('C6F050B13004DD595A329E8BEF29A3:2A4B272BC704842ABACF30D3F9993D'),
    //             'Authorization' => 'Basic ' . base64_encode('229F253F7C84A2A966062496E7386C:0BC5D1F84F94B94BD81D4A114C6353'),
    //             'version'       => '2023-06-30',
    //             'Content-Type'  => 'application/x-www-form-urlencoded',
    //             // 'x-merchantid'  => 'SG1972',
    //             'x-merchantid'  => '48026',
    //             'x-customerid'  => '123',
    //         ])->get("https://smartgateway.hdfcbank.com/orders/$order_id");

    //         if ($orderResponse->successful()) {
    //             $orderDetails = $orderResponse->json();
    //             if ($order->amount != $orderResponse['amount']) {
    //                 return view('Frontend.hdfcerror', compact('response', 'orderDetails'));
    //             }

    //             if ($order->status == 'COMPLETED' || $order->status == 'FAILED') {
    //                 return view('Frontend.hdfcthankyou', compact('response', 'orderDetails'));
    //             }

    //             if ($orderDetails['status'] == 'CHARGED') {
    //                 $order->status = 'COMPLETED';
    //                 $order->save();


    //                 $todays_date = Carbon::today()->format('Y-m-d');
    //                 $next_30_days = Carbon::today()->addDays(30)->format('Y-m-d');

    //                 $studentfee                    = new StudentFee();
    //                 $studentfee->student_id        = $order->student_id;
    //                 $studentfee->start_date        = date('Y-m-d');
    //                 $studentfee->end_date          = $next_30_days;
    //                 $studentfee->receive_date     = date('Y-m-d');
    //                 $studentfee->monthly_fees      = $order->amount;
    //                 $studentfee->total_amount_paid = $order->amount;
    //                 $studentfee->status            = 'ACTIVE';
    //                 $studentfee->currency          = $request->currency;
    //                 $studentfee->save();

    //                 $order->student_fee_id = $studentfee->id;
    //                 $order->save();

    //                 if ($student->status == 'FEESDUE') {
    //                     $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
    //                     if ($student_latest_batch) {
    //                         $batch = Batch::where('id', $student_latest_batch->batch_id)->first();
    //                         if ($batch) {
    //                             $latest_reassign_batch = Batch::where('parent_id', $batch->parent_id)->where('status', '!=', 'INACTIVE')->latest('created_at')->first();
    //                             if ($latest_reassign_batch) {
    //                                 $last_student  = StudentBatch::where('batch_id', $latest_reassign_batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
    //                                 $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $latest_reassign_batch->id)->first();
    //                                 if (isset($student_batch)) {
    //                                     $sudentBatch                     = new StudentBatch();
    //                                     $sudentBatch->student_id         = $student->id;
    //                                     $sudentBatch->batch_id           = $student_batch->batch_id;
    //                                     $sudentBatch->coach_id           = $student_batch->coach_id;
    //                                     $sudentBatch->level_id           = $student_batch->level_id;
    //                                     $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
    //                                     $sudentBatch->confirm_reassign   = $student_batch->confirm_reassign;
    //                                     $sudentBatch->status             = $student_batch->status;
    //                                     $sudentBatch->is_fees_due        = 0;
    //                                     $sudentBatch->start_date         = Carbon::today();
    //                                     $sudentBatch->end_date           = $student_batch->batch->end_date;
    //                                     $sudentBatch->status             = 'ACTIVE';
    //                                     $sudentBatch->save();
    //                                 } else {
    //                                     $sudentBatch                     = new StudentBatch();
    //                                     $sudentBatch->student_id         = $student->id;
    //                                     $sudentBatch->batch_id           = $last_student->batch_id;
    //                                     $sudentBatch->coach_id           = $last_student->coach_id;
    //                                     $sudentBatch->level_id           = $last_student->level_id;
    //                                     $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
    //                                     $sudentBatch->confirm_reassign   = $last_student->confirm_reassign;
    //                                     $sudentBatch->status             = $last_student->status;
    //                                     $sudentBatch->is_fees_due        = $last_student->is_fees_due;
    //                                     $sudentBatch->start_date         = $last_student->start_date;
    //                                     $sudentBatch->end_date           = $last_student->end_date;
    //                                     $sudentBatch->created_by         = $last_student->created_by;
    //                                     $sudentBatch->updated_by         = $last_student->updated_by;
    //                                     $sudentBatch->save();
    //                                 }
    //                             } else {
    //                                 $last_student  = StudentBatch::where('batch_id', $batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
    //                                 $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $batch->id)->first();
    //                                 if (isset($student_batch)) {
    //                                     $sudentBatch                     = new StudentBatch();
    //                                     $sudentBatch->student_id         = $student->id;
    //                                     $sudentBatch->batch_id           = $student_batch->batch_id;
    //                                     $sudentBatch->coach_id           = $student_batch->coach_id;
    //                                     $sudentBatch->level_id           = $student_batch->level_id;
    //                                     $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
    //                                     $sudentBatch->confirm_reassign   = $student_batch->confirm_reassign;
    //                                     $sudentBatch->status             = $student_batch->status;
    //                                     $sudentBatch->is_fees_due        = 0;
    //                                     $sudentBatch->start_date         = Carbon::today();
    //                                     $sudentBatch->end_date           = $student_batch->batch->end_date;
    //                                     $sudentBatch->status             = 'ACTIVE';
    //                                     $sudentBatch->save();
    //                                 } else {
    //                                     $sudentBatch                     = new StudentBatch();
    //                                     $sudentBatch->student_id         = $student->id;
    //                                     $sudentBatch->batch_id           = $last_student->batch_id;
    //                                     $sudentBatch->coach_id           = $last_student->coach_id;
    //                                     $sudentBatch->level_id           = $last_student->level_id;
    //                                     $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
    //                                     $sudentBatch->confirm_reassign   = $last_student->confirm_reassign;
    //                                     $sudentBatch->status             = $last_student->status;
    //                                     $sudentBatch->is_fees_due        = $last_student->is_fees_due;
    //                                     $sudentBatch->start_date         = $last_student->start_date;
    //                                     $sudentBatch->end_date           = $last_student->end_date;
    //                                     $sudentBatch->created_by         = $last_student->created_by;
    //                                     $sudentBatch->updated_by         = $last_student->updated_by;
    //                                     $sudentBatch->save();
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }

    //                 $student->status = 'ACTIVE';
    //                 $student->save();

    //             } else {
    //                 $order->status = 'FAILED';
    //                 $order->save();
    //             }
    //             $order->hdfc_data = json_encode($orderDetails);
    //             $order->save();
    //             return view('Frontend.hdfcthankyou', compact('response', 'orderDetails'));
    //         } else {
    //             // Handle the case where the order details could not be fetched
    //             return redirect()->back()->with('error', 'Failed to fetch order details.');
    //         }
    //     } else {
    //         // Handle the case where 'order_id' is missing in the response
    //         return redirect()->back()->with('error', 'Order ID is missing in the response.');
    //     }
    // }


    public function studentOrderThankyou(Request $request)
    {
        $response = $request->all();

        if (isset($response['order_id'])) {
            $order_id = $response['order_id'];
            $order = Order::where('hdfc_order_id', $order_id)->first();
            if (!$order) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            $student = Student::find($order->student_id);

            // $orderResponse = Http::withHeaders([
            //     'Authorization' => 'Basic ' . base64_encode('C6F050B13004DD595A329E8BEF29A3:2A4B272BC704842ABACF30D3F9993D'),
            //     'version'       => '2023-06-30',
            //     'Content-Type'  => 'application/x-www-form-urlencoded',
            //     'x-merchantid'  => '48026',
            //     'x-customerid'  => '123',
            // ])->get("https://smartgatewayuat.hdfcbank.com/orders/$order_id");

            $orderResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode('229F253F7C84A2A966062496E7386C:0BC5D1F84F94B94BD81D4A114C6353'),
                'version'       => '2023-06-30',
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'x-merchantid'  => '48026',
                'x-customerid'  => '123',
            ])->get("https://smartgateway.hdfcbank.com/orders/$order_id");

            if ($orderResponse->successful()) {
                $orderDetails = $orderResponse->json();
                if ($order->amount != $orderResponse['amount']) {
                    return view('Frontend.hdfcerror', compact('response', 'orderDetails'));
                }

                if ($order->status == 'COMPLETED' || $order->status == 'FAILED') {
                    return view('Frontend.hdfcthankyou', compact('response', 'orderDetails'));
                }

                if ($orderDetails['status'] == 'CHARGED') {
                    $order->status = 'COMPLETED';
                    $order->save();

                    // Calculate dynamic end_date based on 10 upcoming sessions
                    $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                    $batch = $student_latest_batch ? Batch::find($student_latest_batch->batch_id) : null;
                    $batchSchedules = $batch ? $batch->batchSchedules()->pluck('weekday')->toArray() : [];

                    $weekdayMap = [
                        'Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
                        'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6,
                    ];

                    $scheduledWeekdays = collect($batchSchedules)
                        ->map(fn($day) => $weekdayMap[$day])
                        ->sort()
                        ->values()
                        ->toArray();

                    $sessionDates = [];
                    $checkDate = Carbon::today();
                    while (count($sessionDates) < 10) {
                        if (in_array($checkDate->dayOfWeek, $scheduledWeekdays)) {
                            $sessionDates[] = $checkDate->copy();
                        }
                        $checkDate->addDay();
                    }

                    // $end_date = end($sessionDates)->format('Y-m-d');

                    $studentfee = new StudentFee();
                    $studentfee->student_id        = $order->student_id;
                    $studentfee->start_date        = Carbon::today()->format('Y-m-d');
                    $studentfee->end_date =          Carbon::today()->addDays(29);
                    $studentfee->receive_date      = Carbon::today()->format('Y-m-d');
                    $studentfee->monthly_fees      = $order->amount;
                    $studentfee->total_amount_paid = $order->amount;
                    $studentfee->status            = 'ACTIVE';
                    $studentfee->currency          = $request->currency;
                    $studentfee->save();

                    $order->student_fee_id = $studentfee->id;
                    $order->save();

                    // Rest of logic remains untouched...
                    // FEESDUE batch reassignment, StudentBatch logic, etc.

                    if ($student->status == 'FEESDUE') {
                         $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                        if ($student_latest_batch) {
                            $batch = Batch::where('id', $student_latest_batch->batch_id)->first();
                            if ($batch) {
                                $latest_reassign_batch = Batch::where('parent_id', $batch->parent_id)->where('status', '!=', 'INACTIVE')->latest('created_at')->first();
                                if ($latest_reassign_batch) {
                                    $last_student = StudentBatch::where('batch_id', $latest_reassign_batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                                    $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $latest_reassign_batch->id)->first();
                                    if (isset($student_batch)) {
                                        $sudentBatch = new StudentBatch();
                                        $sudentBatch->student_id = $student->id;
                                        $sudentBatch->batch_id = $student_batch->batch_id;
                                        $sudentBatch->coach_id = $student_batch->coach_id;
                                        $sudentBatch->level_id = $student_batch->level_id;
                                        $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                                        $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                                        $sudentBatch->status = $student_batch->status;
                                        $sudentBatch->is_fees_due = 0;
                                        $sudentBatch->start_date = Carbon::today();
                                        $sudentBatch->end_date = $student_batch->batch->end_date;
                                        $sudentBatch->status = 'ACTIVE';
                                        $sudentBatch->save();
                                    } else {
                                        $sudentBatch = new StudentBatch();
                                        $sudentBatch->student_id = $student->id;
                                        $sudentBatch->batch_id = $last_student->batch_id;
                                        $sudentBatch->coach_id = $last_student->coach_id;
                                        $sudentBatch->level_id = $last_student->level_id;
                                        $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                                        $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                                        // $sudentBatch->status = $last_student->status;
                                        $sudentBatch->status = 'ACTIVE';
                                        $sudentBatch->is_fees_due = $last_student->is_fees_due;
                                        $sudentBatch->start_date = $last_student->start_date;
                                        $sudentBatch->end_date = $last_student->end_date;
                                        $sudentBatch->created_by = $last_student->created_by;
                                        $sudentBatch->updated_by = $last_student->updated_by;
                                        $sudentBatch->save();
                                    }
                                } else {
                                    $last_student = StudentBatch::where('batch_id', $batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                                    $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $batch->id)->first();
                                    if (isset($student_batch)) {
                                        $sudentBatch = new StudentBatch();
                                        $sudentBatch->student_id = $student->id;
                                        $sudentBatch->batch_id = $student_batch->batch_id;
                                        $sudentBatch->coach_id = $student_batch->coach_id;
                                        $sudentBatch->level_id = $student_batch->level_id;
                                        $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                                        $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                                        // $sudentBatch->status = $student_batch->status;
                                        $sudentBatch->is_fees_due = 0;
                                        $sudentBatch->start_date = Carbon::today();
                                        $sudentBatch->end_date = $student_batch->batch->end_date;
                                        $sudentBatch->status = 'ACTIVE';
                                        $sudentBatch->save();
                                    } else {
                                        $sudentBatch = new StudentBatch();
                                        $sudentBatch->student_id = $student->id;
                                        $sudentBatch->batch_id = $last_student->batch_id;
                                        $sudentBatch->coach_id = $last_student->coach_id;
                                        $sudentBatch->level_id = $last_student->level_id;
                                        $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                                        $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                                        $sudentBatch->status = 'ACTIVE';
                                        $sudentBatch->is_fees_due = $last_student->is_fees_due;
                                        $sudentBatch->start_date = $last_student->start_date;
                                        $sudentBatch->end_date = $last_student->end_date;
                                        $sudentBatch->created_by = $last_student->created_by;
                                        $sudentBatch->updated_by = $last_student->updated_by;
                                        $sudentBatch->save();
                                    }
                                }
                            }
                        }
                    }

                    $student->status = 'ACTIVE';
                    $student->save();
                } else {
                    $order->status = 'FAILED';
                    $order->save();
                }
                $order->hdfc_data = json_encode($orderDetails);
                $order->save();
                return view('Frontend.hdfcthankyou', compact('response', 'orderDetails'));
            } else {
                return redirect()->back()->with('error', 'Failed to fetch order details.');
            }
        } else {
            return redirect()->back()->with('error', 'Order ID is missing in the response.');
        }
    }


}
