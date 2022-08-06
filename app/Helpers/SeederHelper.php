<?php

namespace App\Helpers;

class SeederHelper
{
    /**
     * @param string $class
     * @param array $array
     * @return void
     */
    public static function firstOrCreate(string $class, array $array): void
    {
        collect($array)->each(function ($item) use($class) {
            $class::firstOrCreate($item);
        });
    }


    public static function firstOrCreateEnum(string $class, array $array): void
    {
        foreach($array AS &$value) {
            $value = [
                "key" => $value,
                "name" => $value
            ];
        }

        static::firstOrCreate($class, $array);
    }
}
