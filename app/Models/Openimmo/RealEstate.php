<?php

namespace App\Models\Openimmo;

use App\Models\Traits\BelongsToCompanyOfficeTrait;
use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\User;
use App\OpenImmoV1\RealEstateExport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ujamii\OpenImmo\API\Anbieter;
use Ujamii\OpenImmo\API\Openimmo;

/**
 * @property boolean $published
 *
 * @property User $creator
 * @property User $agent
 * @property RealestateGeo $geo
 * @property RealestatePreis $preis
 * @property RealestateAusstattung $ausstattung
 * @property RealestateFlaeche $flaeche
 * @property RealestateZustandAngaben $zustand_angaben
 * @property RealestateInfrastruktur $infrastruktur
 * @property RealestateVerwaltungObjekt $verwaltung_objekt
 * @property RealestateObjektkategorie $objektkategorie
 * @property RealestateFreitexte $freitexte
 * @property \App\Models\Openimmo\Maincategory $mainCategory
 * @property \App\Models\Openimmo\Subcategory $subCategory
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Openimmo\Anhang> $anhaenge
 */
class RealEstate extends Model
{
    use BelongsToCompanyOfficeTrait, BelongsToCompanyTrait, SoftDeletes;

    protected $table = "openimmo_realestates";


    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $item) {
            $item->geo()->cascadeDelete();
            $item->preis()->cascadeDelete();
            $item->ausstattung()->cascadeDelete();
            $item->flaechen()->cascadeDelete();
            $item->zustand_angaben()->cascadeDelete();
            $item->infrastruktur()->cascadeDelete();
            $item->verwaltung_objekt()->cascadeDelete();
            $item->objektkategorie()->cascadeDelete();
            $item->freitexte()->cascadeDelete();
            $item->freitexte()->cascadeDelete();
        });
    }


    public function geo(): HasOne
    {
        return $this->hasOne(RealestateGeo::class, "realestate_id");
    }


    public function preis(): HasOne
    {
        return $this->hasOne(RealestatePreis::class, "realestate_id");
    }


    public function ausstattung(): HasOne
    {
        return $this->hasOne(RealestateAusstattung::class, "realestate_id");
    }


    public function flaechen(): HasOne
    {
        return $this->hasOne(RealestateFlaeche::class, "realestate_id");
    }


    public function zustand_angaben(): HasOne
    {
        return $this->hasOne(RealestateZustandAngaben::class, "realestate_id");
    }


    public function infrastruktur(): HasOne
    {
        return $this->hasOne(RealestateInfrastruktur::class, "realestate_id");
    }


    public function verwaltung_objekt(): HasOne
    {
        return $this->hasOne(RealestateVerwaltungObjekt::class, "realestate_id");
    }


    public function objektKategorie(): HasOne
    {
        return $this->hasOne(RealestateObjektkategorie::class, "realestate_id");
    }


    public function freitexte(): HasOne
    {
        return $this->hasOne(RealestateFreitexte::class, "realestate_id");
    }


    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, "agent_id");
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id");
    }


    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(Maincategory::class, "maincategory_id");
    }


    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, "subcategory_id");
    }


    public function anhaenge(): HasMany
    {
        return $this->hasMany(Anhang::class, 'realestate_id');
    }


    public function getName(): ?string
    {
        return $this->objekttitel;
    }


    public function getUrl(): ?string
    {
        return url("/immo/" . $this->id . "/" . \Str::slug($this->objekttitel));
    }


    public function isActive(): ?bool
    {
        $now = date("Y-m-d");

        if($this->verwaltung_techn_aktiv_von && $this->verwaltung_techn_aktiv_von > $now) {
            return false;
        }
        else if($this->verwaltung_techn_aktiv_von && $this->verwaltung_techn_aktiv_bis < $now) {
            return false;
        }

        return $this->published;
    }


    public function getStoragePath(string $filename = ""): string
    {
        return storage_path("app/public/immo-images/". $this->id . "/" . $filename);
    }


    public function getPublicPath(string $filename = ""): string
    {
        return url("/storage/immo-images/". $this->id . "/" . $filename);
    }


    public function getImages()
    {
        return $this->anhaenge->filter(function(Anhang $item): bool {
            return in_array($item->gruppe, Anhang::getImageGruppen());
        });
    }


    public function getOpenImmoXml(): string
    {
        return RealEstateExport::make($this)->asXml();
    }


    public function archiveIt(): static
    {
        if($this->published) {
            $this->published = false;
            $this->save();
        }

        return $this;
    }


    public function reactivateIt(): static
    {
        if(!$this->published) {
            $this->published = true;
            $this->save();
        }

        return $this;
    }
}
