<?php

use App\Models\Student;
use App\Models\Timezone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

function ends_with($s, $t)
{
    return \Illuminate\Support\Str::endsWith($s, $t);
}

function str_singular($s)
{
    return \Illuminate\Support\Str::singular($s);
}

function snake_case($s)
{
    return \Illuminate\Support\Str::snake($s);
}

function str_plural($s)
{
    return \Illuminate\Support\Str::plural($s);
}

function toIndianDateTime($datetime)
{
    return \Carbon\Carbon::parse($datetime)->format('d/m/Y h:i A');
}

function toIndianDate($datetime)
{
    return \Carbon\Carbon::parse($datetime)->format('d/m/Y');
}

function getSystemRoles()
{
    $permission_seeder = new \Database\Seeders\PermissionSeeder;
    $roles             = $permission_seeder->roles;
    $systemRoles       = [];
    foreach ($roles as $role => $permissions) {
        $systemRoles[] = $role;
    }
    return $systemRoles;
}

function getCountingNumber($model, $prefix, $field_name, $year = true)
{
    $modelClass = "\App\Models\\" . $model;
    // Assuming you have an 'number_field' column in your database table

    $latestNumber = $modelClass::max($field_name);
    if ($latestNumber === null) {
        // No records in the database yet, start with 1
        $lastNumberPart = 1;
    } else {
        // Extract the last part of the latest number and increment it
        $parts          = explode('-', $latestNumber);
        $lastNumberPart = (int) end($parts);
        $lastNumberPart++; // Increment the last number part
    }
    $currentYear        = date('Y');
    $currentMonth       = date('n');
    $financialYearStart = ($currentMonth >= 4) ? substr($currentYear, -2) : substr(($currentYear - 1), -2);
    $financialYearEnd   = ($currentMonth >= 4) ? substr(($currentYear + 1), -2) : substr($currentYear, -2);
    $number             = $prefix . '-' . $financialYearStart . '-' . $financialYearEnd . '-' . str_pad($lastNumberPart, 4, '0', STR_PAD_LEFT);
    if (! $year) {
        $number = $prefix . '-' . str_pad($lastNumberPart, 4, '0', STR_PAD_LEFT);
    }
    return $number;
}

function desktop()
{
    $detect = new Mobile_Detect;
    if ($detect->isMobile()) {
        return false;
    } else {
        return true;
    }
}

function mobile()
{
    $detect = new Mobile_Detect;
    if ($detect->isMobile()) {
        return true;
    } else {
        return false;
    }
}

function sendSms($numbers, $message)
{

    $fields = [
        "variables_values" => $message,
        "route"            => "otp",
        "numbers"          => $numbers,
    ];
    //dd($fields);
    try {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($fields),
            CURLOPT_HTTPHEADER     => [
                "authorization: EFGpDvhS408wVlIciBTrZ6oayW5jHu1tNsqKYkMXJ9eP2ARUnmiGJeKsOPrfkpUZ0nyugI8wSdlF64BC",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json",
            ],
        ]);
        $response = curl_exec($curl);
        // dd( $response );
        $err = curl_error($curl);
        curl_close($curl);
    } catch (\Exception $e) {
        return false;
    }
    return true;
}

function getDiscountedPercentage($originalPrice, $discountedPrice)
{
    if ($originalPrice == 0) {
        return '0%';
    }
    $percentage          = (($originalPrice - $discountedPrice) / $originalPrice) * 100;
    $formattedPercentage = round($percentage, 0); // Round to 0 decimal places
    return $formattedPercentage . '%';
}

function inRupee($num, $symbol = true, $pdf = false)
{
    $nums = explode('.', $num);
    $num  = $nums[0];

    $minus = false;
    if (substr($num, 0, 1) === '-') {
        $minus = true;
        $num   = substr($num, strpos($num, "-") + 1);
    }

    $explrestunits = "";
    if (strlen($num) > 3) {

        $lastthree = substr($num, strlen($num) - 3, strlen($num));

        $restunits = substr($num, 0, strlen($num) - 3);                             // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit   = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if ($minus) {
        $thecash = "-" . $thecash;
    }

    if (isset($nums[1]) && $nums[1] > 0) {
        $thecash = $thecash . "." . $nums[1];
    } else {
        $thecash = $thecash;
    }

    if ($symbol) {
        $thecash = '₹ ' . $thecash . '/-';

        return $thecash;

    } elseif ($pdf) {

        // return "Rs. ".$thecash.'/-';
        return $thecash . '/-';

    } else {

        return html_entity_decode('₹ ' . $thecash . '/-');
    }

}
function getDomainUrl()
{
    return $url = env("APP_URL", "https://ready.technicul.com");
}

function getCourcePricing()
{
    return [
        'INDIA'      => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    'INR ₹3000 for 10 Regular Classes (1 Module)',
                    'INR ₹8400 for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnersindia',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    'INR ₹3400 for 10 Regular Classes (1 Module)',
                    'INR ₹9600 for 30 Regular Classes (3 Modules)',
                    'INR ₹17000 for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/rzp/intermediateindia',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 10 Kids)',
                'prices'        => [
                    'INR ₹3600 for 10 Regular Classes (1 Module)',
                    'INR ₹10200 for 30 Regular Classes (3 Modules)',
                    'INR ₹18000 for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advancedindia',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 15 Kids)',
                'prices'        => [
                    'INR ₹4000 for 10 Regular Classes (1 Module)',
                    'INR ₹4000 for 10 Regular Classes (2 Modules)',
                    'INR ₹4000 for 10 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/rzp/expertindia',
                ],
            ],
        ],
        'USA'        => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '45 USD for 10 Regular Classes (1 Module)',
                    '120 USD for 30 Regular Classes (3 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/rzp/beginnersusa',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '50 USD for 10 Regular Classes (1 Module)',
                    '130 USD for 30 Regular Classes (3 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/intermediateusa',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '176 USD for 32 Regular Classes (1 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advancedusa',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '224 USD for 32 Regular Classes (1 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/expertusa',
                ],
            ],
        ],
        'UAE'        => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    '125 AED for 10 Regular Classes (1 Module)',
                    '335 AED for 30 Regular Classes (3 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnersuae',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    '138 AED for 10 Regular Classes (1 Module)',
                    '370 AED for 30 Regular Classes (3 Module)',
                    '690 AED for 60 Regular Classes (6 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/intermediateuae',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 10 Kids)',
                'prices'        => [
                    '163 AED for 10 Regular Classes (1 Module)',
                    '440 AED for 30 Regular Classes (3 Module)',
                    '815 AED for 60 Regular Classes (6 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advanceduae',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 10 Kids)',
                'prices'        => [
                    '190 AED for 10 Regular Classes (1 Module)',
                    '190 AED for 10 Regular Classes (2 Modules)',
                    '190 AED for 10 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/rzp/expertuae',
                ],
            ],
        ],
        'SINGAPORE'  => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    '50 SGD for 10 Regular Classes (1 Module)',
                    '135 SGD for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnerssingapore',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 7 Kids)',
                'prices'        => [
                    '55 SGD for 10 Regular Classes (1 Module)',
                    '150 SGD for 30 Regular Classes (3 Modules)',
                    '275 SGD for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/Intermediatesingapore',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 10 Kids)',
                'prices'        => [
                    '63 SGD for 10 Regular Classes (1 Module)',
                    '170 SGD for 30 Regular Classes (3 Modules)',
                    '315 SGD for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advancedsingapore',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 10 Kids)',
                'prices'        => [
                    '75 SGD for 10 Regular Classes (1 Module)',
                    '75 SGD for 10 Regular Classes (2 Modules)',
                    '75 SGD for 10 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/expertsingapore',
                ],
            ],
        ],
        'CANADA'     => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '63 CAD for 10 Regular Classes (1 Module)',
                    '165 CAD for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnerscanada',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '70 CAD for 10 Regular Classes (1 Module)',
                    '180 CAD for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/intermediatecanada',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '246 CAD for 32 Regular Classes (1 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advancedcanada',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '304 CAD for 32 Regular Classes (1 Module)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/expertcanada',
                ],
            ],
        ],
        'UK'         => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '32 GBP for 10 Regular Classes (1 Module)',
                    '85 GBP for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    // 'https://rzp.io/l/IV4CxIrGkg',
                    'https://rzp.io/l/beginnersuk',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 5 Kids)',
                'prices'        => [
                    '38 GBP for 10 Regular Classes (1 Module)',
                    '105 GBP for 30 Regular Classes (3 Module)',
                    '140 GBP for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    // 'https://rzp.io/l/exmryNs',
                    'https://rzp.io/l/intermediateuk',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '140 GBP for 32 Regular Classes (1 Module)',
                    '378 GBP for 96 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    // 'https://rzp.io/l/PndsUxo',
                    'https://rzp.io/l/advanceduk',
                ],
            ],
            // 'Expert'       => [
            //     'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
            //     'prices'        => [
            //         '50 GBP for 8 Regular Classes (1 Module)',
            //         '140 GBP for 24 Regular Classes (3 Modules)',
            //     ],
            //     'payment_links' => [
            //         'https://rzp.io/rzp/expertuk',
            //     ],
            // ],
        ],
        'AUSTRALIA'  => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '56 AUD for 10 Regular Classes (1 Module)',
                    '150 AUD for 30 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnersaustralia',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '63 AUD for 10 Regular Classes (1 Module)',
                    '170 AUD for 30 Regular Classes (3 Modules)',
                    '315 AUD for 60 Regular Classes (6 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/Intermediateaustralia',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '220 AUD for 32 Regular Classes (1 Module)',
                    '594 AUD for 96 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    // 'https://rzp.io/l/dNcIdeXc',
                    'https://rzp.io/l/advancedaustralia',
                ],
            ],
            // 'Expert'       => [
            //     'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
            //     'prices'        => [
            //         '65 AUD for 8 Regular Classes (1 Module)',
            //         '165 AUD for 24 Regular Classes (3 Modules)',
            //     ],
            //     'payment_links' => [
            //         // 'https://rzp.io/l/XJtG7hg',
            //         'https://rzp.io/l/expertaustralia',
            //     ],
            // ],
        ],
        'NEWZEALAND' => [
            'Beginners'    => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '50 NZD for 8 Regular Classes (1 Module)',
                    '140 NZD for 24 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/beginnersnewzealand',
                ],
            ],
            'Intermediate' => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '55 NZD for 8 Regular Classes (1 Module)',
                    '150 NZD for 24 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/rzp/intermediatenewzealand',
                ],
            ],
            'Advanced'     => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '60 NZD for 8 Regular Classes (1 Module)',
                    '165 NZD for 24 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/advancednewzealand',
                ],
            ],
            'Expert'       => [
                'description'   => 'Cost Basis on Group Classes (up to 6 Kids)',
                'prices'        => [
                    '65 NZD for 8 Regular Classes (1 Module)',
                    '175 NZD for 24 Regular Classes (3 Modules)',
                ],
                'payment_links' => [
                    'https://rzp.io/l/expertnewzealand',
                ],
            ],
        ],
    ];
}

function getTimezones()
{
    return [
        'USA'         => [
            'Eastern Daylight Time'         => 'Eastern Daylight Time',
            'Central Daylight Time'         => 'Central Daylight Time',
            'Mountain Daylight Time'        => 'Mountain Daylight Time',
            'Pacific Daylight Time'         => 'Pacific Daylight Time',
            'Alaska Daylight Time'          => 'Alaska Daylight Time',
            'Mountain Standard Time'        => 'Mountain Standard Time',
            'Eastern Standard Time'         => 'Eastern Standard Time',
            'Central Standard Time'         => 'Central Standard Time',
            'Pacific Standard Time'         => 'Pacific Standard Time',
            'Alaska Standard Time'          => 'Alaska Standard Time',
            'Hawaii-Aleutian Standard Time' => 'Hawaii-Aleutian Standard Time',
            'Hawaii-Aleutian Daylight Time' => 'Hawaii-Aleutian Daylight Time',
        ],
        'CANADA'      => [
            'Eastern Daylight Time'         => 'Eastern Daylight Time',
            'Central Daylight Time'         => 'Central Daylight Time',
            'Mountain Daylight Time'        => 'Mountain Daylight Time',
            'Pacific Daylight Time'         => 'Pacific Daylight Time',
            'Alaska Daylight Time'          => 'Alaska Daylight Time',
            'Mountain Standard Time'        => 'Mountain Standard Time',
            'Eastern Standard Time'         => 'Eastern Standard Time',
            'Central Standard Time'         => 'Central Standard Time',
            'Pacific Standard Time'         => 'Pacific Standard Time',
            'Alaska Standard Time'          => 'Alaska Standard Time',
            'Hawaii-Aleutian Standard Time' => 'Hawaii-Aleutian Standard Time',
            'Hawaii-Aleutian Daylight Time' => 'Hawaii-Aleutian Daylight Time',
        ],
        'AUSTRALIA'   => [
            'Australia/Perth'    => 'Australia/Perth',
            'Australia/Darwin'   => 'Australia/Darwin',
            'Australia/Brisbane' => 'Australia/Brisbane',
            'Australia/Adelaide' => 'Australia/Adelaide',
            'Australia/Sydney'   => 'Australia/Sydney',
        ],
        'NEWZEALAND'  => [
            'New Zealand Daylight Time' => 'New Zealand Daylight Time',
            'New Zealand Standard Time' => 'New Zealand Standard Time',
        ],
        'UK'          => [
            'British Summer Time' => 'British Summer Time',
            'Greenwich Mean Time' => 'Greenwich Mean Time',
        ],
        'INDIA'       => [
            'Indian Standard Time' => 'Indian Standard Time',
        ],
        'UAE'         => [
            'Gulf Standard Time' => 'Gulf Standard Time',
        ],
        'SINGAPORE'   => [
            'Singapore Standard Time' => 'Singapore Standard Time',
        ],

        'QATAR'   => [
            'Arabian Standard Time' => 'Arabian Standard Time',
        ],
        'SOUTHAFRICA' => [
            'South Africa Standard Time' => 'South Africa Standard Time',
        ],
        'EUROPEAN UNION' => [
            'Central European Time' => 'Central European Time',
            'Eastern European Time' => 'Eastern European Time',
            'Western European Time' => 'Western European Time',
        ],
        'KUWAIT'   => [
            'Arabian Standard Time' => 'Arabian Standard Time',
        ],
        'BAHRAIN'   => [
            'Arabian Standard Time' => 'Arabian Standard Time',
        ],
        'OMAN'   => [
            'Arabian Standard Time' => 'Arabian Standard Time',
        ],
    ];
}

function convertTimeZoneString($timeZoneString)
{
    $timeZoneMapping = [
        'Mountain Daylight Time'        => 'America/Denver',
        'Mountain Standard Time'        => 'America/Denver', // Added
        'Eastern Standard Time'         => 'America/New_York',
        'Eastern Daylight Time'         => 'America/New_York',
        'Central Daylight Time'         => 'America/Chicago',
        'Central Standard Time'         => 'America/Chicago', // Added
        'Pacific Daylight Time'         => 'America/Los_Angeles',
        'Pacific Standard Time'         => 'America/Los_Angeles',
        'Alaska Daylight Time'          => 'America/Anchorage',
        'Alaska Standard Time'          => 'America/Anchorage',  // Added
        'Hawaii-Aleutian Standard Time' => 'Pacific/Honolulu',   // Added
        'Hawaii-Aleutian Daylight Time' => 'Pacific/Honolulu',   // Added
                                                                 // Australia Time Zones
        'Australia/Perth'               => 'Australia/Perth',    // Added
        'Australia/Sydney'              => 'Australia/Sydney',   // Added
        'Australia/Adelaide'            => 'Australia/Adelaide', // Added
        'Australia/Darwin'              => 'Australia/Darwin',   // Added
        'Australia/Brisbane'            => 'Australia/Brisbane', // Added
        'New Zealand Daylight Time'     => 'Pacific/Auckland',
        'New Zealand Standard Time'     => 'Pacific/Auckland',
        'British Summer Time'           => 'Europe/London',
        'Greenwich Mean Time'           => 'GMT',
        'Indian Standard Time'          => 'Asia/Kolkata',
        'Gulf Standard Time'            => 'Asia/Dubai',
        'Singapore Standard Time'       => 'Asia/Singapore',
        'South Africa Standard Time'    => 'Africa/Johannesburg',
        'Arabian Standard Time'         => 'Asia/Qatar',

        'Central European Time'         => 'Europe/Berlin',
        'Eastern European Time'         => 'Europe/Athens',
        'Western European Time'         => 'Europe/Lisbon',
    ];

    if (array_key_exists($timeZoneString, $timeZoneMapping)) {
        return $timeZoneMapping[$timeZoneString];
    }
    return 'Asia/Kolkata';
}

function sendWhatsAppMessage($toNumber, $otp)
{
    // Set your credentials and endpoint
    $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
    $accessToken   = 'EAANcXDsNH6EBOxANM1zv6oCUoUka21Pli4LuPy0OxumWvSZBGd9ZBSZBF4Br66CMXcK9sKJqFXht2O4LiBaIzWgVNRxA2f2r34HgZAokxyH2EBMuu13RlwEj7RFpViAM6PJAWV780lv59l4U21Ht7YZAWHExezcv0rJaZCBqwRWysbi9nJKJ7OqCaFKKnPu7yiOCDhDBrMR2nroCvLk99g8thxG3IZD';
    // $accessToken = env('WHATSAPP_ACCESS_TOKEN');
    $apiUrl = env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0');

    $endpoint = "{$apiUrl}/{$phoneNumberId}/messages";

    // Prepare your message template with OTP
    $temaplate = 'Dear customer, your OTP is: ' . $otp;

    // Prepare the payload
    $payload = [
        'messaging_product' => 'whatsapp',
        'to'                => $toNumber,
        'type'              => 'template',
        'template'          => [
            'name'       => 'otp_template', // Replace with your approved template name
            'language'   => [
                'code' => 'en_US', // Use the correct language code
            ],
            'components' => [
                [
                    'type'       => 'body',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $temaplate, // The OTP value to be included
                        ],
                    ],
                ],
            ],
        ],
    ];

    // Send the request using Laravel HTTP client
    $response = Http::withToken($accessToken)
        ->post($endpoint, $payload);

    // Check if the request was successful
    if ($response->successful()) {
        return $response->json();
    }

    // Return failed response for debugging
    return $response->json();
}
function getTargetTimeZone($student, $timezoneDataList)
{
    $student_timezone = $student->timezone;
    if ($student_timezone) {
        return convertTimeZoneString($student_timezone);
    } elseif (! $timezoneDataList->isEmpty()) {
        return convertTimeZoneString($timezoneDataList[0]->timezone);
    }
    return 'Asia/Kolkata'; // Default
}

function convertTimeZomeWiseTime($date, $time, $studentId)
{
    $student          = Student::find($studentId);
    $timezoneDataList = Timezone::where('country', $student->country)
        ->where('status', 'ACTIVE')
        ->groupBy('timezone')
        ->get(['timezone']);

    $targetTimeZone = getTargetTimeZone($student, $timezoneDataList);

    try {
        $sourceDateTime = Carbon::createFromFormat('d, M Y h:i A', $date . ' ' . $time, 'Asia/Kolkata');
        $targetDateTime = $sourceDateTime->setTimezone($targetTimeZone);
        return $targetDateTime->format('h:i A');
    } catch (\Exception $e) {
        return 'Invalid Date or Time Format';
    }
}

function convertTimeZoneWiseTime($date, $time, $studentId)
{
    $student = Student::find($studentId);
    $timezoneDataList = Timezone::where('country', $student->country)
        ->where('status', 'ACTIVE')
        ->groupBy('timezone')
        ->get(['timezone']);

    $targetTimeZone = getTargetTimeZone($student, $timezoneDataList);

    try {
        // ✅ Match the actual format of your inputs
        $sourceDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $date . ' ' . $time,
            'Asia/Kolkata'
        );

        $targetDateTime = $sourceDateTime->setTimezone($targetTimeZone);
        return $targetDateTime->format('h:i A');
    } catch (\Exception $e) {
        return 'Invalid Date or Time Format';
    }
}


function convertTimeZomeWiseDate($date, $time, $studentId)
{
    $student          = Student::find($studentId);
    $timezoneDataList = Timezone::where('country', $student->country)
        ->where('status', 'ACTIVE')
        ->groupBy('timezone')
        ->get(['timezone']);

    $targetTimeZone = getTargetTimeZone($student, $timezoneDataList);

    try {
        $sourceDateTime = Carbon::createFromFormat('d, M Y h:i A', $date . ' ' . $time, 'Asia/Kolkata');
        $targetDateTime = $sourceDateTime->setTimezone($targetTimeZone);
        return $targetDateTime->format('d, M Y');
    } catch (\Exception $e) {
        return 'Invalid Date or Time Format';
    }
}
function convertDemoTimeZomeWiseDate($date, $time, $demosession)
{

    $timezoneDataList = Timezone::where('country', $demosession->demolead->country)
        ->where('status', 'ACTIVE')
        ->groupBy('timezone')
        ->get(['timezone']);

    $targetTimeZone = getDemoTargetTimeZone($demosession, $timezoneDataList);

    try {
        $sourceDateTime = Carbon::createFromFormat('d, M Y h:i A', $date . ' ' . $time, 'Asia/Kolkata');
        $targetDateTime = $sourceDateTime->setTimezone($targetTimeZone);
        return $targetDateTime->format('d, M Y');
    } catch (\Exception $e) {
        return 'Invalid Date or Time Format';
    }
}
function convertDemoTimeZomeWiseTime($date, $time, $demosession)
{

    $timezoneDataList = Timezone::where('country', $demosession->demolead->country)
        ->where('status', 'ACTIVE')
        ->groupBy('timezone')
        ->get(['timezone']);

    $targetTimeZone = getDemoTargetTimeZone($demosession, $timezoneDataList);

    try {
        $sourceDateTime = Carbon::createFromFormat('d, M Y h:i A', $date . ' ' . $time, 'Asia/Kolkata');
        $targetDateTime = $sourceDateTime->setTimezone($targetTimeZone);
        return $targetDateTime->format('h:i A');
    } catch (\Exception $e) {
        return 'Invalid Date or Time Format';
    }
}

if (!function_exists('convertToStudentLocalTime')) {
    function convertToStudentLocalTime($date, $time, $country)
    {
        // Country name → phone code mapping
        $countryToPhoneCode = [
            'AUSTRALIA'   => '+61',
            'CANADA'      => '+1',
            'INDIA'       => '+91',
            'NEWZEALAND'  => '+64',
            'SINGAPORE'   => '+65',
            'UAE'         => '+971',
            'UK'          => '+44',
            'USA'         => '+1',
            'QATAR'       => '+974',
            'BAHRAIN'     => '+973',
            'KUWAIT'      => '+965',
        ];

        // Phone code → default timezone mapping
        $phoneCodeToTimezone = [
            '+91'  => 'Asia/Kolkata',
            '+971' => 'Asia/Dubai',
            '+1'   => 'America/New_York',
            '+44'  => 'Europe/London',
            '+61'  => 'Australia/Sydney',
            '+64'  => 'Pacific/Auckland',
            '+65'  => 'Asia/Singapore',
            '+974' => 'Asia/Qatar',
            '+973' => 'Asia/Bahrain',
            '+965' => 'Asia/Kuwait',
        ];

        // Get phone code for country
        $phoneCode = $countryToPhoneCode[$country] ?? null;
        $studentTimeZone = $phoneCode ? ($phoneCodeToTimezone[$phoneCode] ?? 'Asia/Kolkata') : 'Asia/Kolkata';
        // dd($studentTimeZone);

         // If country not found, default to 'Asia/Kolkata'
        try {
            // Parse IST datetime
            $istDateTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $date . ' ' . $time,
                'Asia/Kolkata'
            );

            // Convert to student timezone
            $studentDateTime = $istDateTime->setTimezone($studentTimeZone);
            // dd($studentDateTime, $studentTimeZone);
            return $studentDateTime->format('d M Y | h:i A');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return 'Local Time ';
        }
    }
}

if (!function_exists('convertToKidsTime')) {
    /**
     * Convert IST datetime to student local time based on country
     */
    function convertToKidsTime($date, $time, $country)
    {
        $countryTimezoneMapping = [
            'INDIA'       => 'Asia/Kolkata',
            'USA'         => 'America/New_York',    // default US timezone
            'CANADA'      => 'America/Toronto',
            'UAE'         => 'Asia/Dubai',
            'AUSTRALIA'   => 'Australia/Sydney',
            'UK'          => 'Europe/London',
            'QATAR'       => 'Asia/Qatar',
            'BAHRAIN'     => 'Asia/Bahrain',
            'KUWAIT'      => 'Asia/Kuwait',
            'SINGAPORE'   => 'Asia/Singapore',
            'NEWZEALAND'  => 'Pacific/Auckland',
            'SOUTHAFRICA' => 'Africa/Johannesburg',
            'MALAYSIA'    => 'Asia/Kuala_Lumpur',
            'PHILIPPINES' => 'Asia/Manila',
            'HONGKONG'    => 'Asia/Hong_Kong',
            'JAPAN'       => 'Asia/Tokyo',
            'CHINA'       => 'Asia/Shanghai',
            'GERMANY'     => 'Europe/Berlin',
            'FRANCE'      => 'Europe/Paris',
            'SPAIN'       => 'Europe/Madrid',
            'ITALY'       => 'Europe/Rome',
            'SWITZERLAND' => 'Europe/Zurich',
            'NETHERLANDS' => 'Europe/Amsterdam',
            'BELGIUM'     => 'Europe/Brussels',
            // add more as needed
        ];

        $country = strtoupper($country ?? 'INDIA');
        $kidsTimeZone = $countryTimezoneMapping[$country] ?? 'Asia/Kolkata';

        try {
            // Parse IST datetime (date + time)
            $istDateTime = \Carbon\Carbon::parse("$date $time", 'Asia/Kolkata');

            // Convert to student's local timezone
            $kidsDateTime = $istDateTime->setTimezone($kidsTimeZone);

            return $kidsDateTime->format('d M Y | h:i A');
        } catch (\Exception $e) {
            dd($e->getMessage(), $date, $time, $kidsTimeZone);
            return 'Invalid Date or Time Format';
        }
    }
}


function getDemoTargetTimeZone($demosession, $timezoneDataList)
{
    $student_timezone = $demosession->demolead->kids_time_zone;
    if ($student_timezone) {
        return convertTimeZoneString($student_timezone);
    } elseif (! $timezoneDataList->isEmpty()) {
        return convertTimeZoneString($timezoneDataList[0]->timezone);
    }
    return 'Asia/Kolkata';
}


if (!function_exists('hasPermission')) {
    function hasPermission($permissionName)
    {
        $user = Auth::user();

        // Assuming you have a roles/permissions relationship
        return $user && $user->hasPermission($permissionName);
    }
}




function nextClassDateForBatch($batch, array $batchSchedule, ?string $timezone = 'Asia/Kolkata'): ?Carbon
{
    if (empty($batchSchedule)) {
        return null;
    }

    // Map weekday strings -> Carbon dayOfWeek numbers (0 = Sun ... 6 = Sat)
    $map = [
        'sunday'    => Carbon::SUNDAY,
        'monday'    => Carbon::MONDAY,
        'tuesday'   => Carbon::TUESDAY,
        'wednesday' => Carbon::WEDNESDAY,
        'thursday'  => Carbon::THURSDAY,
        'friday'    => Carbon::FRIDAY,
        'saturday'  => Carbon::SATURDAY,
    ];

    $allowed = array_values(array_filter(array_map(function ($d) use ($map) {
        $key = strtolower(trim($d));
        return $map[$key] ?? null;
    }, $batchSchedule), fn($v) => $v !== null));

    if (empty($allowed)) {
        return null;
    }

    $tz = $timezone ?: 'Asia/Kolkata';

    $start = Carbon::parse($batch->start_date, $tz)->startOfDay();
    $end   = Carbon::parse($batch->end_date,   $tz)->endOfDay();

    // Start from "now" in tz; if before start_date, start at start_date
    $cursor = Carbon::now($tz)->startOfDay();
    if ($cursor->lt($start)) {
        $cursor = $start->copy();
    }

    // Search up to 14 days ahead (enough to cover any weekly pattern)
    for ($i = 0; $i < 14; $i++) {
        if ($cursor->betweenIncluded($start, $end) && in_array($cursor->dayOfWeek, $allowed, true)) {
            return $cursor;
        }
        $cursor->addDay();
    }

    return null; // none found within range
}