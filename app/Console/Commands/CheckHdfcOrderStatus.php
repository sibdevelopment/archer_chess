<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckHdfcOrderStatus extends Command
{
    protected $signature = 'hdfc:order-status {order_id}';
    protected $description = 'Check HDFC order status';

    public function handle()
    {

        $order_id = $this->argument('order_id');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode('C6F050B13004DD595A329E8BEF29A3:2A4B272BC704842ABACF30D3F9993D'),
            'version' => '2023-06-30',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'x-merchantid' => 'SG1972',
            'x-customerid' => '123',

        ])->get("https://smartgatewayuat.hdfcbank.com/orders/$order_id");
            // dd($response->json());
        if ($response->successful()) {
            // dd($response->json());
            $this->info("Order Status: " . json_encode($response->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error("Failed to fetch order status.");
        }
    }
}
