<?php

namespace App\Console\Commands;


use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Student;
use App\Models\StudentBatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all the Pending Payments and update the status to Paid if the payment is done else fail the payment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */ 
    public function handle()
    {
        $FromDate = '21-11-2025';
        $from = Carbon::createFromFormat('d-m-Y', $FromDate)->startOfDay(); // 2025-11-18 00:00:00

        $orders = Order::where('status', 'authorized')
            ->where('created_at', '>=', $from)
            ->whereNotNull('razorpay_payment_id')
            ->get();

        $processed = 0;
        $errors = [];

        foreach ($orders as $order) {
            $paymentId = $order->razorpay_payment_id;

            // Skip if not a payment id
            if (empty($paymentId) || !str_starts_with($paymentId, 'pay_')) {
                $errors[] = "Order {$order->id} has invalid razorpay_payment_id: {$paymentId}";
                continue;
            }

            // Call Razorpay payments endpoint: /v1/payments/{payment_id}
            // dd($paymentId);
            $payment = $this->checkPaymentApi($paymentId);
            // dd($payment);
            if ($payment === false) {
                $errors[] = "Order {$order->id} (payment {$paymentId}) - HTTP error or empty response";
                continue;
            }
 
            $status = $payment['status'] ?? null;

            if ($status === 'captured') { 
                $order->razorpay_payment_id = $payment['id'];
                $order->status = 'PAID';
                $order->save();

                $student = Student::where('id', $order->student_id)->first();

                $studentfee = new StudentFee();
                $studentfee->student_id = $student->id;
                $studentfee->start_date = date('Y-m-d');
                $studentfee->end_date = date('Y-m-d', strtotime('+15 days'));
                $studentfee->monthly_fees = $order->amount;
                $studentfee->total_amount_paid = $order->amount;
                $studentfee->receive_date = date('Y-m-d');
                $studentfee->status = 'ACTIVE';
                $studentfee->save();
                
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
                                    $sudentBatch->status = $last_student->status;
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
                                    $sudentBatch->status = $last_student->status;
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
            } elseif ($status === 'failed') {
                $order->razorpay_payment_id = $payment['id'];
                $order->status = 'FAILED';
                $order->save();
                $this->info("Order {$order->id} marked FAILED (payment {$paymentId})");
            } elseif ($status === 'authorized') {
                // still authorized — you may choose to auto-capture here, or skip and wait for webhook
                $this->info("Order {$order->id} payment is still authorized (payment {$paymentId})");
            } else {
                $errors[] = "Order {$order->id} (payment {$paymentId}) unexpected status: " . json_encode($payment);
            }

            $processed++;
            // optional: sleep(1) to avoid rate limits if you have many requests
        }

        $this->info("Processed {$processed} orders. Errors: " . count($errors));
        foreach ($errors as $e) {
            $this->error($e);
        }

        return 0;
    }

    /**
     * Fetch a single payment from Razorpay using the payment id.
     * Returns decoded array on success, or false on failure.
     */
    protected function checkPaymentApi(string $paymentId)
    {
        $key = 'rzp_live_eckVmG8LHU5uhu';
        $secret = 'yN3zXf5cmDKzcgcYn8fWoEoC'; 
        
        // $key = 'rzp_test_RLrov8eGceCpPt';
        // $secret = 'tWqTNh7WveDI7oSqKFeoj446'; 

        if (empty($key) || empty($secret)) {
            Log::error('Razorpay keys not configured');
            return false;
        }

        $url = 'https://api.razorpay.com/v1/payments/' . $paymentId;

        try {
            $response = Http::withBasicAuth($key, $secret)
                ->acceptJson()
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            } else {
                // log details for debugging
                Log::warning('Razorpay payment fetch failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception calling Razorpay', ['exception' => $e->getMessage()]);
            return false;
        }
    }

}
