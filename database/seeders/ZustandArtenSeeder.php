<?php

namespace Database\Seeders;

use App\Helpers\SeederHelper;
use App\Models\Openimmo\ZustandArt;
use Illuminate\Database\Seeder;

class ZustandArtenSeeder extends Seeder
{
    public function run()
    {
        SeederHelper::firstOrCreate(ZustandArt::class, [
            ["key" => "ERSTBEZUG", "name" => "ERSTBEZUG"],
            ["key" => "TEIL_VOLLRENOVIERUNGSBED", "name" => "TEIL_VOLLRENOVIERUNGSBED"],
            ["key" => "NEUWERTIG", "name" => "NEUWERTIG"],
            ["key" => "TEIL_VOLLSANIERT", "name" => "TEIL_VOLLSANIERT"],
            ["key" => "VOLL_SANIERT", "name" => "VOLL_SANIERT"],
            ["key" => "SANIERUNGSBEDUERFTIG", "name" => "SANIERUNGSBEDUERFTIG"],
            ["key" => "BAUFAELLIG", "name" => "BAUFAELLIG"],
            ["key" => "NACH_VEREINBARUNG", "name" => "NACH_VEREINBARUNG"],
            ["key" => "MODERNISIERT", "name" => "MODERNISIERT"],
            ["key" => "GEPFLEGT", "name" => "GEPFLEGT"],
            ["key" => "ROHBAU", "name" => "ROHBAU"],
            ["key" => "ENTKERNT", "name" => "ENTKERNT"],
            ["key" => "ABRISSOBJEKT", "name" => "ABRISSOBJEKT"],
            ["key" => "PROJEKTIERT", "name" => "PROJEKTIERT"],
        ]);
    }
}
