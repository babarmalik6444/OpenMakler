<?php

namespace Database\Seeders;

use App\ExternalApis\OpenImmoDriver;
use App\Models\ExternalApi;
use Illuminate\Database\Seeder;

class ExternalApiSeeder extends Seeder
{
    public function run()
    {
        ExternalApi::firstOrCreate([
            "name" => "Immonet",
            "driver" => OpenImmoDriver::class,
            "server" => "immonet.de"
        ]);
    }
}
