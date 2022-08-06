<?php

namespace Database\Seeders;

use App\Models\Openimmo\Maincategory;
use App\Models\Openimmo\Subcategory;
use Illuminate\Database\Seeder;
use Ujamii\OpenImmo\API\Zimmer;

class MainAndSubcategorySeeder extends Seeder
{
    public function run()
    {
        $array = [
            'zimmer' => [
                Zimmer::ZIMMERTYP_ZIMMER
            ],
            'wohnung' => [
                0 => 'DACHGESCHOSS',
                1 => 'MAISONETTE',
                2 => 'LOFT-STUDIO-ATELIER',
                3 => 'PENTHOUSE',
                4 => 'TERRASSEN',
                5 => 'ETAGE',
                6 => 'ERDGESCHOSS',
                7 => 'SOUTERRAIN',
                8 => 'APARTMENT',
                9 => 'FERIENWOHNUNG',
                10 => 'GALERIE',
                11 => 'ROHDACHBODEN',
                12 => 'ATTIKAWOHNUNG',
                13 => 'KEINE_ANGABE',
            ],
            'haus' => [
                0 => 'REIHENHAUS',
                1 => 'REIHENEND',
                2 => 'REIHENMITTEL',
                3 => 'REIHENECK',
                4 => 'DOPPELHAUSHAELFTE',
                5 => 'EINFAMILIENHAUS',
                6 => 'STADTHAUS',
                7 => 'BUNGALOW',
                8 => 'VILLA',
                9 => 'RESTHOF',
                10 => 'BAUERNHAUS',
                11 => 'LANDHAUS',
                12 => 'SCHLOSS',
                13 => 'ZWEIFAMILIENHAUS',
                14 => 'MEHRFAMILIENHAUS',
                15 => 'FERIENHAUS',
                16 => 'BERGHUETTE',
                17 => 'CHALET',
                18 => 'STRANDHAUS',
                19 => 'LAUBE-DATSCHE-GARTENHAUS',
                20 => 'APARTMENTHAUS',
                21 => 'BURG',
                22 => 'HERRENHAUS',
                23 => 'FINCA',
                24 => 'RUSTICO',
                25 => 'FERTIGHAUS',
                26 => 'KEINE_ANGABE',
            ],
            'grundstueck' =>
                [
                    0 => 'WOHNEN',
                    1 => 'GEWERBE',
                    2 => 'INDUSTRIE',
                    3 => 'LAND_FORSTWIRSCHAFT',
                    4 => 'FREIZEIT',
                    5 => 'GEMISCHT',
                    6 => 'GEWERBEPARK',
                    7 => 'SONDERNUTZUNG',
                    8 => 'SEELIEGENSCHAFT',
                ],
            'buero_praxen' =>
                [
                    0 => 'BUEROFLAECHE',
                    1 => 'BUEROHAUS',
                    2 => 'BUEROZENTRUM',
                    3 => 'LOFT_ATELIER',
                    4 => 'PRAXIS',
                    5 => 'PRAXISFLAECHE',
                    6 => 'PRAXISHAUS',
                    7 => 'AUSSTELLUNGSFLAECHE',
                    8 => 'COWORKING',
                    9 => 'SHARED_OFFICE',
                ],
            'einzelhandel' =>
                [
                    0 => 'LADENLOKAL',
                    1 => 'EINZELHANDELSLADEN',
                    2 => 'VERBRAUCHERMARKT',
                    3 => 'EINKAUFSZENTRUM',
                    4 => 'KAUFHAUS',
                    5 => 'FACTORY_OUTLET',
                    6 => 'KIOSK',
                    7 => 'VERKAUFSFLAECHE',
                    8 => 'AUSSTELLUNGSFLAECHE',
                ],
            'gastgewerbe' =>
                [
                    0 => 'GASTRONOMIE',
                    1 => 'GASTRONOMIE_UND_WOHNUNG',
                    2 => 'PENSIONEN',
                    3 => 'HOTELS',
                    4 => 'WEITERE_BEHERBERGUNGSBETRIEBE',
                    5 => 'BAR',
                    6 => 'CAFE',
                    7 => 'DISCOTHEK',
                    8 => 'RESTAURANT',
                    9 => 'RAUCHERLOKAL',
                    10 => 'EINRAUMLOKAL',
                ],
            'hallen_lager_prod' =>
                [
                    0 => 'HALLE',
                    1 => 'INDUSTRIEHALLE',
                    2 => 'LAGER',
                    3 => 'LAGERFLAECHEN',
                    4 => 'LAGER_MIT_FREIFLAECHE',
                    5 => 'HOCHREGALLAGER',
                    6 => 'SPEDITIONSLAGER',
                    7 => 'PRODUKTION',
                    8 => 'WERKSTATT',
                    9 => 'SERVICE',
                    10 => 'FREIFLAECHEN',
                    11 => 'KUEHLHAUS',
                ],
            'land_und_forstwirtschaft' =>
                [
                    0 => 'LANDWIRTSCHAFTLICHE_BETRIEBE',
                    1 => 'BAUERNHOF',
                    2 => 'AUSSIEDLERHOF',
                    3 => 'GARTENBAU',
                    4 => 'ACKERBAU',
                    5 => 'WEINBAU',
                    6 => 'VIEHWIRTSCHAFT',
                    7 => 'JAGD_UND_FORSTWIRTSCHAFT',
                    8 => 'TEICH_UND_FISCHWIRTSCHAFT',
                    9 => 'SCHEUNEN',
                    10 => 'REITERHOEFE',
                    11 => 'SONSTIGE_LANDWIRTSCHAFTSIMMOBILIEN',
                    12 => 'ANWESEN',
                    13 => 'JAGDREVIER',
                ],
            'parken' =>
                [
                    0 => 'STELLPLATZ',
                    1 => 'CARPORT',
                    2 => 'DOPPELGARAGE',
                    3 => 'DUPLEX',
                    4 => 'TIEFGARAGE',
                    5 => 'BOOTSLIEGEPLATZ',
                    6 => 'EINZELGARAGE',
                    7 => 'PARKHAUS',
                    8 => 'TIEFGARAGENSTELLPLATZ',
                    9 => 'PARKPLATZ_STROM',
                ],
            'sonstige' =>
                [
                    0 => 'PARKHAUS',
                    1 => 'TANKSTELLE',
                    2 => 'KRANKENHAUS',
                    3 => 'SONSTIGE',
                ],
            'freizeitimmobilie_gewerblich' =>
                [
                    0 => 'SPORTANLAGEN',
                    1 => 'VERGNUEGUNGSPARKS_UND_CENTER',
                    2 => 'FREIZEITANLAGE',
                ],
            'zinshaus_renditeobjekt' =>
                [
                    0 => 'MEHRFAMILIENHAUS',
                    1 => 'WOHN_UND_GESCHAEFTSHAUS',
                    2 => 'GESCHAEFTSHAUS',
                    3 => 'BUEROGEBAEUDE',
                    4 => 'SB_MAERKTE',
                    5 => 'EINKAUFSCENTREN',
                    6 => 'WOHNANLAGEN',
                    7 => 'VERBRAUCHERMAERKTE',
                    8 => 'INDUSTRIEANLAGEN',
                    9 => 'PFLEGEHEIM',
                    10 => 'SANATORIUM',
                    11 => 'SENIORENHEIM',
                    12 => 'BETREUTES-WOHNEN',
                ],
        ];

        // Truncate
        Maincategory::query()->truncate();
        Subcategory::query()->truncate();

        // Fill
        foreach ($array as $main => $sub) {
            $main = Maincategory::create([
                "key" => $main,
                "name" => str_replace("/Und/", " und ", str_replace("_", "/", \Str::title($main))),
            ]);

            foreach ($sub as $row) {
                Subcategory::create([
                    "maincategory_id" => $main->id,
                    "key" => $row,
                    "name" => str_replace("/Und/", " und ", str_replace("_", "/", \Str::title($row))),
                ]);
            }
        }
    }
}
