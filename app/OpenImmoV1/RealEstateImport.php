<?php

namespace App\OpenImmoV1;

use App\Models\Openimmo\Anhang;
use App\Models\Openimmo\Import;
use App\Models\Openimmo\Maincategory;
use App\Models\Openimmo\RealEstate;
use App\Models\Openimmo\RealestateAusstattung;
use App\Models\Openimmo\RealestateFlaeche;
use App\Models\Openimmo\RealestateFreitexte;
use App\Models\Openimmo\RealestateGeo;
use App\Models\Openimmo\RealestateInfrastruktur;
use App\Models\Openimmo\RealestateObjektkategorie;
use App\Models\Openimmo\RealestatePreis;
use App\Models\Openimmo\RealestateVerwaltungObjekt;
use App\Models\Openimmo\RealestateZustandAngaben;
use App\Models\Openimmo\Subcategory;
use App\Models\Openimmo\ZustandArt;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Ujamii\OpenImmo\API\Immobilie;

class RealEstateImport
{
    protected Immobilie $immo;
    protected RealEstate $realEstate;
    protected Import $importModel;
    protected string $pathToXmlFile;
    protected bool $debug;


    public static function make(Immobilie $immo, Import $importModel, string $pathToXmlFile, bool $debug = false): self
    {
        $obj = new static();
        $obj->immo = $immo;
        $obj->importModel = $importModel;
        $obj->pathToXmlFile = $pathToXmlFile;
        $obj->debug = $debug;

        return $obj;
    }


    public function import()
    {
        $this->createRealEstate();
        $this->createGeo();
        $this->createPreise();
        $this->createAusstattung();
        $this->createFlaechen();
        $this->createZustandAngaben();
        $this->createInfrastruktur();
        $this->createVerwaltungObjekt();
        $this->creeateObjektkategorie();
        $this->createFreitexte();
        $this->anhaenge();
    }


    private function debug(mixed $data, ?string $title = null)
    {
        $this->debug && dump($title, $data);
    }


    private function user(): User
    {
        return $this->importModel->user;
    }


    private function createRealEstate()
    {
        $immo = $this->immo;
        $this->realEstate = new RealEstate([
            "creator_id" => $this->user()->id,
            "company_id" => $this->user()->company_id,
            "agent_id" => $this->user()->id,
            "company_office_id" => $this->user()->company_office_id,
            "import_id" => $this->importModel->id,
            "objekttitel" => $immo->getFreitexte()->getObjekttitel(),

            // verwaltung_techn
            "verwaltung_techn_objektnr_intern" => $immo->getVerwaltungTechn()->getObjektnrIntern(),
            "verwaltung_techn_objektnr_extern" => $immo->getVerwaltungTechn()->getObjektnrExtern(),
            "verwaltung_techn_openimmo_obid" => $immo->getVerwaltungTechn()->getOpenimmoObid(),
            "verwaltung_techn_kennung_ursprung" => $immo->getVerwaltungTechn()->getKennungUrsprung(),
            "verwaltung_techn_aktiv_von" => $immo->getVerwaltungTechn()->getAktivVon(),
            "verwaltung_techn_aktiv_bis" => $immo->getVerwaltungTechn()->getAktivBis(),
            "verwaltung_techn_weitergabe_generell" => $immo->getVerwaltungTechn()->getWeitergabeGenerell(),
        ]);

        // getObjektart
        $this->objektArt();

        // Save + debug
        $this->realEstate->save();
        $this->debug($this->realEstate->toArray(), "createRealEstate");
    }


    private function objektArt()
    {
        // We do not use "objektartZusatz"
        $arten = [
            "bueroPraxen", "einzelhandel", "freizeitimmobilieGewerblich", "gastgewerbe", "grundstueck", "hallenLagerProd", "haus", "landUndForstwirtschaft", "parken", "sonstige", "wohnung", "zimmer", "zinshausRenditeobjekt",
        ];
        $objektart = $this->immo->getObjektkategorie()->getObjektart();

        foreach($arten AS $art)
        {
            $name = "get" . ucfirst($art);
            $val = $objektart->$name();

            if($val) {
                $val = (array)current($val);

                if($art) {
                    $this->realEstate->maincategory_id = optional(
                        Maincategory::query()->where("key", $art)->first()
                    )->id;
                }

                if($val && ($val = current($val))) {
                    if($sub = Subcategory::where("key", $val)->first()) {
                        $this->realEstate->subcategory_id = $sub->id;
                    }
                }
            }
        }
    }


    private function createGeo()
    {
        $geo = $this->immo->getGeo();
        $geokoordinaten = $geo->getGeokoordinaten();

        $obj = new RealestateGeo([
            "ort" => $geo->getOrt(),
            "plz" => $geo->getPlz(),
            //"geokoordinaten" => $geokoordinaten ? new Point($geokoordinaten->getBreitengrad(), $geokoordinaten->getLaengengrad()) : null,
            "geokoordinaten" => $geokoordinaten ? ["lat" => $geokoordinaten->getBreitengrad(), "lng" => $geokoordinaten->getLaengengrad()] : null,
            "strasse" => $geo->getStrasse(),
            "hausnummer" => $geo->getHausnummer(),
            "bundesland" => $geo->getBundesland(),
            "land" => $geo->getLand()->getIsoLand(),
            "etage" => $geo->getEtage(),
            "anzahl_etagen" => $geo->getAnzahlEtagen(),
            "wohnungsnr" => $geo->getWohnungsnr(),
            "regionaler_zusatz" => $geo->getRegionalerZusatz(),
        ]);
        $this->realEstate->geo()->save($obj);
        $this->debug($obj->toArray(), "createGeo");
    }


    private function createPreise()
    {
        $preis = $this->immo->getPreise();
        $obj = new RealestatePreis([
            "kaufpreis" => optional($preis->getKaufpreis())->getValue(),
            "kaufpreisnetto" => optional($preis->getKaufpreisnetto())->getValue(),
            "kaufpreisbrutto" => optional($preis->getKaufpreisbrutto())->getValue(),
            "nettokaltmiete" => optional($preis->getNettokaltmiete())->getValue(),
            "kaltmiete" => optional($preis->getKaltmiete())->getValue(),
            "warmmiete" => optional($preis->getWarmmiete())->getValue(),
            "heizkosten_enthalten" => optional($preis->getHeizkostenEnthalten())->getValue(),
            "heizkosten" => optional($preis->getHeizkosten())->getValue(),
            "zzg_mehrwertsteuer" => optional($preis->getZzgMehrwertsteuer())->getValue(),
            "mietzuschlaege" => optional($preis->getMietzuschlaege())->getValue(),
            "hauptmietzinsnetto" => optional($preis->getHauptmietzinsnetto())->getValue(),
            "hauptmietzinsust" => optional($preis->getHauptmietzinsnetto())->getHauptmietzinsust(),
            "betriebskostennetto" => optional($preis->getBetriebskostennetto())->getValue(),
            "betriebskostenust" => optional($preis->getBetriebskostennetto())->getBetriebskostenust(),
            "mietpreis_pro_qm" => ($preis->getMietpreisProQm()),
            "mieteinnahmen_ist" => optional($preis->getMieteinnahmenIst())->getValue(),
            "mieteinnahmen_ist_periode" => optional($preis->getMieteinnahmenIst())->getPeriode(),
            "mieteinnahmen_soll" => optional($preis->getMieteinnahmenSoll())->getValue(),
            "mieteinnahmen_soll_periode" => optional($preis->getMieteinnahmenSoll())->getPeriode(),
            "aussen_courtage" => optional($preis->getAussenCourtage())->getValue(),
            "innen_courtage" => optional($preis->getInnenCourtage())->getValue(),
        ]);

        $this->realEstate->preis()->save($obj);
        $this->debug($obj->toArray(), "createPreise");
    }


    private function createAusstattung()
    {
        $aus = $this->immo->getAusstattung();
        $stellplatz = optional(current($aus->getStellplatzart()));
        $befeuerung = optional($aus->getBefeuerung());
        $obj = new RealestateAusstattung([
            // Befeuerung
            "befeuerung_oel" => $befeuerung->getOel(),
            "befeuerung_gas" => $befeuerung->getGas(),
            "befeuerung_elektro" => $befeuerung->getElektro(),
            "befeuerung_alternativ" => $befeuerung->getAlternativ(),
            "befeuerung_solar" => $befeuerung->getSolar(),
            "befeuerung_erdwaerme" => $befeuerung->getErdwaerme(),
            "befeuerung_luftwp" => $befeuerung->getLuftwp(),
            "befeuerung_fern" => $befeuerung->getFern(),
            "befeuerung_block" => $befeuerung->getBlock(),
            "befeuerung_wasser_elektro" => $befeuerung->getWasserElektro(),
            "befeuerung_pellet" => $befeuerung->getPellet(),
            // Stellplatzart
            "stellplatzart_garage" => $stellplatz->getGarage(),
            "stellplatzart_tiefgarage" => $stellplatz->getTiefgarage(),
            "stellplatzart_carport" => $stellplatz->getCarport(),
            "stellplatzart_freiplatz" => $stellplatz->getFreiplatz(),
            "stellplatzart_parkhaus" => $stellplatz->getParkhaus(),
            "stellplatzart_duplex" => $stellplatz->getDuplex(),
            // Other
            "wintergarten" => $aus->getWintergarten(),
            "sauna" => $aus->getSauna(),
            "barrierefrei" => $aus->getBarrierefrei(),
        ]);

        $this->realEstate->ausstattung()->save($obj);
        $this->debug($obj->toArray(), "createAusstattung");
    }


    private function createFlaechen()
    {
        $item = $this->immo->getFlaechen();
        $obj = new RealestateFlaeche([
            "wohnflaeche" => $item->getWohnflaeche(),
            "grundstuecksflaeche" => $item->getGrundstuecksflaeche(),
            "kellerflaeche" => $item->getKellerflaeche(),
            "gartenflaeche" => $item->getGartenflaeche(),
            "nutzflaeche" => $item->getNutzflaeche(),
            "vermietbare_flaeche" => $item->getVermietbareFlaeche(),
            "anzahl_wohneinheiten" => $item->getAnzahlWohneinheiten(),
            "einliegerwohnung" => $item->getEinliegerwohnung(),
            "anzahl_wohn_schlafzimmer" => $item->getAnzahlWohnSchlafzimmer(),
            "anzahl_balkone" => $item->getAnzahlBalkone(),
            "anzahl_terrassen" => $item->getAnzahlTerrassen(),
            "anzahl_logia" => $item->getAnzahlLogia(),
            "balkon_terrasse_flaeche" => $item->getBalkonTerrasseFlaeche(),
        ]);

        $this->realEstate->flaechen()->save($obj);
        $this->debug($obj->toArray(), "createFlaechen");
    }


    private function createZustandAngaben()
    {
        $item = $this->immo->getZustandAngaben();
        $pass = current($item->getEnergiepass());

        // ZustandArt
        if($zustandArtKey = optional($item->getZustand())->getZustandArt()) {
            $zustandArt = optional(ZustandArt::findArt($zustandArtKey))->id;
        }
        else {
            $zustandArt = null;
        }

        $obj = new RealestateZustandAngaben([
            "baujahr" => $item->getBaujahr(),
            "zustand_art" => $zustandArt,
            "letztemodernisierung" => $item->getLetztemodernisierung(),
            "energiepass_energieverbrauchkennwert" => optional($pass)->getEnergieverbrauchkennwert() ?: null,
            "energiepass_mitwarmwasser" => optional($pass)->getMitwarmwasser(),
            "energiepass_gueltig_bis" => optional($pass)->getGueltigBis(),
            "energiepass_primaerenergietraeger" => optional($pass)->getPrimaerenergietraeger(),
            "energiepass_stromwert" => optional($pass)->getStromwert(),
            "energiepass_waermewert" => optional($pass)->getWaermewert(),
            "energiepass_wertklasse" => optional($pass)->getWertklasse(),
        ]);

        $this->realEstate->zustand_angaben()->save($obj);
        $this->debug($obj->toArray(), "createZustandAngaben");
    }


    private function createInfrastruktur()
    {
        $item = $this->immo->getInfrastruktur();
        $obj = new RealestateInfrastruktur([
            "ausblick" => optional($item->getAusblick())->getBlick(),
        ]);

        foreach($item->getDistanzen() AS $distanz) {
            $key = "distanz_zu_" . \Str::snake(strtolower($distanz->getDistanzZu()));
            $obj->$key = $distanz->getValue();
        }

        $this->realEstate->infrastruktur()->save($obj);
        $this->debug($obj->toArray(), "createInfrastruktur");
    }


    private function createVerwaltungObjekt()
    {
        $item = $this->immo->getVerwaltungObjekt();
        $obj = new RealestateVerwaltungObjekt([
            "verfuegbar_ab" => $item->getVerfuegbarAb(),
            "abdatum" => $item->getAbdatum(),
            "bisdatum" => $item->getBisdatum(),
            "haustiere" => $item->getHaustiere(),
            "denkmalgeschuetzt" => $item->getDenkmalgeschuetzt(),
            "gewerbliche_nutzung" => $item->getGewerblicheNutzung(),
            "hochhaus" => $item->getHochhaus(),
            "vermietet" => $item->getVermietet(),
        ]);

        $this->realEstate->verwaltung_objekt()->save($obj);
        $this->debug($obj->toArray(), "createVerwaltungObjekt");
    }


    private function creeateObjektkategorie()
    {
        $item = $this->immo->getObjektkategorie();
        $obj = new RealestateObjektkategorie([
            "nutzungsart_wohnen" => $item->getNutzungsart()->getWohnen(),
            "nutzungsart_gewerbe" => $item->getNutzungsart()->getGewerbe(),
            "nutzungsart_anlage" => $item->getNutzungsart()->getAnlage(),
            "nutzungsart_waz" => $item->getNutzungsart()->getWaz(),
            "vermarktungsart_kauf" => $item->getVermarktungsart()->getKauf(),
            "vermarktungsart_miete_pacht" => $item->getVermarktungsart()->getMietePacht(),
            "vermarktungsart_erbpacht" => $item->getVermarktungsart()->getErbpacht(),
            "vermarktungsart_leasing" => $item->getVermarktungsart()->getLeasing(),
        ]);
        $this->realEstate->verwaltung_objekt()->save($obj);
        $this->debug($obj->toArray(), "creeateObjektkategorie");
    }


    private function createFreitexte()
    {
        $item = $this->immo->getFreitexte();
        $obj = new RealestateFreitexte([
            "lage" => $item->getLage(),
            "ausstatt_beschr" => $item->getAusstattBeschr(),
            "objektbeschreibung" => $item->getObjektbeschreibung(),
            "sonstige_angaben" => $item->getSonstigeAngaben(),
        ]);
        $this->realEstate->verwaltung_objekt()->save($obj);
        $this->debug($obj->toArray(), "createFreitexte");
    }


    private function anhaenge()
    {
        $items = $this->immo->getAnhaenge()->getAnhang();

        foreach($items AS $item) {
            $location = $item->getLocation();
            $filename = \Str::random(15) . "." . $item->getFormat();

            if($location == "EXTERN" || $location == "REMOTE") {
                $url = $item->getDaten()->getPfad();
                $path = $this->realEstate->getStoragePath();
                @mkdir($path, 0777, true);

                try {
                    if (filter_var($url, FILTER_VALIDATE_URL) === false) { // Is not a url
                        $url = $this->pathToXmlFile . "/". $url;
                    }
                    copy($url, $path.$filename);
                }
                catch (\Exception $e) {
                    continue;
                }
            }
            else if($location == "INTERN") {
                $base64 = $item->getDaten()->getAnhanginhalt(); // Base64
                throw new \Exception("Dateianhang ist Base64");
            }

            // Create anhang
            Anhang::create([
                "realestate_id" => $this->realEstate->id,
                "anhangtitel" => $item->getAnhangtitel(),
                "format" => $item->getFormat(),
                "gruppe" => $item->getGruppe(),
                "filename" => $filename
            ]);
        }
    }
}
