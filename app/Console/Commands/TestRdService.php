<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RdServiceClient;

class TestRdService extends Command
{
    protected $signature = 'rd:test {token}';
    protected $description = 'Test Mantra RD Service connection';

    public function handle()
    {
        $token = $this->argument('token');

        if ($token !== env('BIOMETRIC_DEVICE_TOKEN')) {
            $this->error("Invalid token!");
            return;
        }

        $client = new RdServiceClient("http://127.0.0.1:11100"); // RD service port
        $response = $client->info();

        $this->info("RD Service Response:");
        print_r($response);
    }
}
