<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Select;

class CountrySelect extends Select
{
    public static function make(string $name): static
    {
        $obj = parent::make($name);
        $obj->options([
            "DEU" => "Deutschland",
            "AUT" => "Österreich",
            "CHE" => "Schweiz",
            "FRA" => "Frankreich",
            "LIE" => "Liechtenstein",
            "LUX" => "Luxembourg",
            "ESP" => "Spanien",
            "ITA" => "Italien",
            "POL" => "Polen",
            "CZE" => "Tschechien",
            "NLD" => "Niederlande",
            "BEL" => "Belgien",
            "DNK" => "Dänemark",
        ])->default(fn() => "DEU");

        return $obj;
    }
}
