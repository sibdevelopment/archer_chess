<?php

namespace App\Console\Commands;


use App\Models\Batch;
use App\Models\Student;
use App\Mail\FeesDueMail;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class test extends Command
{
    protected $signature = 'test:command';

    protected $description = 'This is a test command to demonstrate the structure of a Laravel console command.';

    public function handle()
    {

        $response = file_get_contents('https://ifsc.razorpay.com/SBIN0005318');
        $data = json_decode($response, true);
            // dd($data); // Debugging line to check the response structure
        if ($data && isset($data['BANK'])) {
            print_r($data); // Show IFSC details
        } else {
            echo "Invalid IFSC or not found.";
        }
        
         
    }
}
