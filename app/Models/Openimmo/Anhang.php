<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToRealestateTrait;
use Illuminate\Database\Eloquent\Model;

class Anhang extends Model
{
    use BelongsToRealestateTrait;

    const GRUPPE_TITELBILD = "TITELBILD";
    const GRUPPE_INNENANSICHTEN = "INNENANSICHTEN";
    const GRUPPE_AUSSENANSICHTEN = "AUSSENANSICHTEN";
    const GRUPPE_GRUNDRISS = "GRUNDRISS";
    const GRUPPE_KARTEN_LAGEPLAN = "KARTEN_LAGEPLAN";
    const GRUPPE_ANBIETERLOGO = "ANBIETERLOGO";
    const GRUPPE_BILD = "BILD";
    const GRUPPE_DOKUMENTE = "DOKUMENTE";
    const GRUPPE_LINKS = "LINKS";
    const GRUPPE_PANORAMA = "PANORAMA";
    const GRUPPE_QRCODE = "QRCODE";
    const GRUPPE_FILM = "FILM"; // ein beiliegendes Movie (siehe formate)
    const GRUPPE_FILMLINK = "FILMLINK"; // ein Link zu externen Movie in z.b- youtube, sevenload ...
    const GRUPPE_EPASS_SKALA = "EPASS-SKALA"; // Skala eines Epasses
    const GRUPPE_ANBOBJURL = "ANBOBJURL"; // Eine Url die auf das Objekt in den Onlineseiten das Anbieters verweist

    protected $table = "openimmo_anhaenge";


    public static function boot(): void
    {
        parent::boot();

        self::creating(function (self $model) {
            $model->format = $model->format ?: substr($model->filename, strpos($model->filename, "."));

            if(!$model->sort_order) {
                $model->sort_order = 1;
            }
        });

        self::deleting(function(self $model){
            unlink($model->getStoragePath());
        });
    }


    public function getUrl(): string
    {
        return $this->realestate->getPublicPath($this->filename);
    }


    public function getStoragePath(): string
    {
        return $this->realestate->getStoragePath($this->filename);
    }


    public static function getImageGruppen(): array
    {
        return array_keys(static::getImageGruppenSelect());
    }


    public static function getImageGruppenSelect(): array
    {
        return [
            static::GRUPPE_TITELBILD => "Titelbild",
            static::GRUPPE_INNENANSICHTEN => "Innenansicht",
            static::GRUPPE_AUSSENANSICHTEN => "Außenansicht",
            static::GRUPPE_GRUNDRISS => "Grundriss",
            static::GRUPPE_KARTEN_LAGEPLAN => "Karten / Lageplan",
            static::GRUPPE_ANBIETERLOGO => "Anbieterlogo",
            static::GRUPPE_BILD => "Bild",
            static::GRUPPE_PANORAMA => "Panorama"
        ];
    }
}
