<?php

namespace App\OpenImmoV1;

use App\Models\Openimmo\RealEstate;
use Ujamii\OpenImmo\API\Anbieter;
use Ujamii\OpenImmo\API\Anhaenge;
use Ujamii\OpenImmo\API\Anhang;
use Ujamii\OpenImmo\API\Ausblick;
use Ujamii\OpenImmo\API\Ausstattung;
use Ujamii\OpenImmo\API\Befeuerung;
use Ujamii\OpenImmo\API\Betriebskostennetto;
use Ujamii\OpenImmo\API\BueroPraxen;
use Ujamii\OpenImmo\API\Daten;
use Ujamii\OpenImmo\API\Distanzen;
use Ujamii\OpenImmo\API\Einzelhandel;
use Ujamii\OpenImmo\API\Energiepass;
use Ujamii\OpenImmo\API\Flaechen;
use Ujamii\OpenImmo\API\Freitexte;
use Ujamii\OpenImmo\API\FreizeitimmobilieGewerblich;
use Ujamii\OpenImmo\API\Gastgewerbe;
use Ujamii\OpenImmo\API\Geo;
use Ujamii\OpenImmo\API\Geokoordinaten;
use Ujamii\OpenImmo\API\Grundstueck;
use Ujamii\OpenImmo\API\HallenLagerProd;
use Ujamii\OpenImmo\API\Hauptmietzinsnetto;
use Ujamii\OpenImmo\API\Haus;
use Ujamii\OpenImmo\API\Immobilie;
use Ujamii\OpenImmo\API\Infrastruktur;
use Ujamii\OpenImmo\API\Kaufpreis;
use Ujamii\OpenImmo\API\Kaufpreisnetto;
use Ujamii\OpenImmo\API\Kontaktperson;
use Ujamii\OpenImmo\API\Land;
use Ujamii\OpenImmo\API\LandUndForstwirtschaft;
use Ujamii\OpenImmo\API\Nutzungsart;
use Ujamii\OpenImmo\API\Objektart;
use Ujamii\OpenImmo\API\Objektkategorie;
use Ujamii\OpenImmo\API\Openimmo;
use Ujamii\OpenImmo\API\Parken;
use Ujamii\OpenImmo\API\Preise;
use Ujamii\OpenImmo\API\Sonstige;
use Ujamii\OpenImmo\API\Stellplatzart;
use Ujamii\OpenImmo\API\Uebertragung;
use Ujamii\OpenImmo\API\Vermarktungsart;
use Ujamii\OpenImmo\API\VerwaltungObjekt;
use Ujamii\OpenImmo\API\VerwaltungTechn;
use Ujamii\OpenImmo\API\Wohnung;
use Ujamii\OpenImmo\API\Zimmer;
use Ujamii\OpenImmo\API\ZinshausRenditeobjekt;
use Ujamii\OpenImmo\API\Zustand;
use Ujamii\OpenImmo\API\ZustandAngaben;
use function PHPUnit\Framework\matches;

class RealEstateExport
{
    protected RealEstate $realEstate;
    private Openimmo $openImmo;


    public static function make(RealEstate $realEstate): static
    {
        $obj = new static();
        $obj->realEstate = $realEstate;
        $obj->openImmo = new Openimmo();

        // Data
        $obj->uebertragung($realEstate);
        $obj->anbieter($realEstate);

        return $obj;
    }


    public function asXml()
    {
        return \JMS\Serializer\SerializerBuilder::create()
            ->build()
            ->serialize($this->openImmo, 'xml');
    }


    private function anbieter(RealEstate $realEstate)
    {
        $anbieter = new Anbieter();
        $anbieter->setFirma($realEstate->company->getName());
        $anbieter->setImmobilie([
            $this->immobilie($realEstate)
        ]);

        // Set Anbieter
        $this->openImmo->setAnbieter([$anbieter]);
    }


    private function uebertragung(RealEstate $realEstate)
    {
        // <uebertragung art="ONLINE" umfang="VOLL" modus="NEW" version="1.2.7" sendersoftware="OIGEN" senderversion="0.9" techn_email="" timestamp="2014-06-01T10:00:00" regi_id="ABCD143"/>
        $uebertragung = new Uebertragung();
        $uebertragung->setArt(Uebertragung::ART_OFFLINE);
        $uebertragung->setUmfang(Uebertragung::UMFANG_VOLL);
        $uebertragung->setModus(Uebertragung::MODUS_NEW);
        $uebertragung->setVersion("1.2.7");
        $uebertragung->setSendersoftware("OpenMakler");
        $uebertragung->setSenderversion("1.0");
        $uebertragung->setTechnEmail(config("app.email"));
        $uebertragung->setTimestamp(now());

        // Set Übertrag
        $this->openImmo->setUebertragung($uebertragung);
    }


    private function immobilie(RealEstate $realEstate): Immobilie
    {
        $immo = new Immobilie();
        $immo->setObjektkategorie($this->objektkategorie($realEstate));
        $immo->setGeo($this->geo($realEstate));
        $immo->setKontaktperson($this->kontaktperson($realEstate));
        $immo->setPreise($this->preise($realEstate));
        $immo->setFlaechen($this->flaechen($realEstate));
        $immo->setAusstattung($this->ausstattung($realEstate));
        $immo->setZustandAngaben($this->zustand_angaben($realEstate));
        $immo->setInfrastruktur($this->infrastruktur($realEstate));
        $immo->setFreitexte($this->freitexte($realEstate));
        $immo->setVerwaltungObjekt($this->verwaltung_objekt($realEstate));
        $immo->setVerwaltungTechn($this->verwaltung_techn($realEstate));
        $immo->setAnhaenge($this->anhaenge($realEstate));

        return $immo;
    }


    private function objektkategorie(RealEstate $realEstate): Objektkategorie
    {
        $item = $realEstate->objektkategorie;
        $obj = new Objektkategorie();
        $obj->setNutzungsart(
            new Nutzungsart($item->nutzungsart_wohnen, $item->nutzungsart_gewerbe, $item->nutzungsart_anlage, $item->nutzungsart_waz)
        );
        $obj->setVermarktungsart(
            new Vermarktungsart($item->vermarktungsart_kauf, $item->vermarktungsart_miete_pacht, $item->vermarktungsart_erbpacht, $item->vermarktungsart_leasing)
        );
        $obj->setObjektart($this->objektart($realEstate));

        //dd($realEstate->mainCategory, $realEstate->subCategory, $objektart);

        return $obj;
    }


    private function objektart(RealEstate $realEstate): Objektart
    {
        $objektart = new Objektart();
        if($realEstate->mainCategory) {
            $main = $realEstate->mainCategory->key;
            $sub = $realEstate->subCategory->key;

            match ($main) {
                "zimmer" => $objektart->setZimmer([
                    new Zimmer(Zimmer::ZIMMERTYP_ZIMMER)
                ]),
                "wohnung" => $objektart->setWohnung([
                    new Wohnung($sub)
                ]),
                "haus" => $objektart->setHaus([
                    new Haus($sub)
                ]),
                "grundstueck" => $objektart->setGrundstueck([
                    new Grundstueck($sub)
                ]),
                "buero_praxen" => $objektart->setBueroPraxen([
                    new BueroPraxen($sub)
                ]),
                "einzelhandel" => $objektart->setEinzelhandel([
                    new Einzelhandel($sub)
                ]),
                "gastgewerbe" => $objektart->setGastgewerbe([
                    new Gastgewerbe($sub)
                ]),
                "hallen_lager_prod" => $objektart->setHallenLagerProd([
                    new HallenLagerProd($sub)
                ]),
                "land_und_forstwirtschaft" => $objektart->setLandUndForstwirtschaft([
                    new LandUndForstwirtschaft($sub)
                ]),
                "parken" => $objektart->setParken([
                    new Parken($sub)
                ]),
                "sonstige" => $objektart->setSonstige([
                    new Sonstige($sub)
                ]),
                "freizeitimmobilie_gewerblich" => $objektart->setFreizeitimmobilieGewerblich([
                    new FreizeitimmobilieGewerblich($sub)
                ]),
                "zinshaus_renditeobjekt" => $objektart->setZinshausRenditeobjekt([
                    new ZinshausRenditeobjekt($sub)
                ]),
            };
        }

        return $objektart;
    }


    private function geo(RealEstate $realEstate): Geo
    {
        $item = $realEstate->geo;
        $obj = new Geo();
        $obj->setPlz($item->plz);
        $obj->setOrt($item->ort);
        $obj->setStrasse($item->strasse);
        $obj->setHausnummer($item->hausnummer);
        $obj->setWohnungsnr($item->wohnungsnr);
        $obj->setBundesland($item->bundesland);
        $obj->setLand(new Land($item->land));
        $obj->setRegionalerZusatz($item->regionaler_zusatz);
        $obj->setEtage($item->etage);
        $obj->setAnzahlEtagen($item->anzahl_etagen);
        if($item->geokoordinaten && isset($item->geokoordinaten->lat)) {
            $obj->setGeokoordinaten(new Geokoordinaten($item->geokoordinaten->lat, $item->geokoordinaten->lng));
        }

        return $obj;
    }


    private function kontaktperson(RealEstate $realEstate): Kontaktperson
    {
        $agent = $realEstate->agent;
        $office = $realEstate->companyOffice;
        $obj = new Kontaktperson();

        // Zentrale
        $obj->setEmailZentrale($realEstate->company->email);
        $obj->setTelZentrale($realEstate->company->phone);

        // Direkt
        $obj->setEmailDirekt($agent->email);
        $obj->setTelHandy($agent->phone);
        $obj->setName($agent->getName());
        $obj->setFirma($agent->company->getName());
        $obj->setStrasse($office->strasse);
        $obj->setHausnummer($office->hausnummer);
        $obj->setPlz($office->plz);
        $obj->setOrt($office->ort);
        $obj->setPosition($office->postfach);

        /*
         * <email_zentrale>foo@bar.de</email_zentrale>
        <email_direkt>foo@bar.de</email_direkt>
        <tel_zentrale>1</tel_zentrale>
        <tel_durchw>2</tel_durchw>
        <tel_fax>3</tel_fax>
        <tel_handy>4</tel_handy>
        <name/>
        <vorname/>
        <titel/>
        <anrede/>
        <anrede_brief/>
        <firma/>
        <zusatzfeld/>
        <strasse/>
        <hausnummer/>
        <plz/>
        <ort/>
        <postfach/>
        <postf_plz/>
        <postf_ort/>
        <land iso_land="DEU"/>
        <email_privat>foo@bar.de</email_privat>
        <email_sonstige emailart="EM_DIREKT" bemerkung="1">foo@bar.de</email_sonstige>
        <email_feedback>yx@y.de</email_feedback>
        <tel_privat>1</tel_privat>
        <tel_sonstige telefonart="TEL_PRIVAT" bemerkung="">1</tel_sonstige>
        <url/>
        <adressfreigabe>false</adressfreigabe>
        <personennummer/>
        <immobilientreuhaenderid>In .AT</immobilientreuhaenderid>
        <foto location="EXTERN">
        ...
        </foto>
        <freitextfeld/>
         */

        return $obj;
    }


    private function preise(RealEstate $realEstate): Preise
    {
        $item = $realEstate->preis;
        $obj = new Preise();
        $obj
            ->setKaufpreis(new Kaufpreis(null, $item->kaufpreis))
            ->setKaufpreisnetto(new Kaufpreisnetto($item->kaufpreisnetto))
            ->setKaufpreisbrutto($item->kaufpreisbrutto)
            ->setNettokaltmiete($item->nettokaltmiete)
            ->setKaltmiete($item->kaltmiete)
            ->setWarmmiete($item->warmmiete)
            ->setHeizkostenEnthalten($item->heizkosten_enthalten)
            ->setHeizkosten($item->heizkosten)
            ->setZzgMehrwertsteuer($item->zzg_mehrwertsteuer)
            ->setMietzuschlaege($item->mietzuschlaege)
            ->setHauptmietzinsnetto(new Hauptmietzinsnetto($item->hauptmietzinsust, $item->hauptmietzinsnetto))
            ->setBetriebskostennetto(new Betriebskostennetto($item->betriebskostenust, $item->betriebskostennetto))
            ->setMietpreisProQm($item->mietpreis_pro_qm)
        ;

        return $obj;
    }


    private function flaechen(RealEstate $realEstate): Flaechen
    {
        $item = $realEstate->preis;
        $obj = new Flaechen();
        $obj->setWohnflaeche($item->wohnflaeche);
        $obj->setGrundstuecksflaeche($item->grundstuecksflaeche);
        $obj->setKellerflaeche($item->kellerflaeche);
        $obj->setGartenflaeche($item->gartenflaeche);
        $obj->setNutzflaeche($item->nutzflaeche);
        $obj->setVermietbareFlaeche($item->vermietbare_flaeche);
        $obj->setAnzahlWohneinheiten($item->anzahl_wohneinheiten);
        $obj->setEinliegerwohnung($item->einliegerwohnung);
        $obj->setAnzahlWohnSchlafzimmer($item->anzahl_wohn_schlafzimmer);
        $obj->setAnzahlBalkone($item->anzahl_balkone);
        $obj->setAnzahlTerrassen($item->anzahl_terrassen);
        $obj->setAnzahlLogia($item->anzahl_logia);
        $obj->setBalkonTerrasseFlaeche($item->balkon_terrasse_flaeche);

        return $obj;
    }


    private function ausstattung(RealEstate $realEstate): Ausstattung
    {
        $item = $realEstate->ausstattung;
        $obj = new Ausstattung();

        // Befeuerung
        $befeuerung = new Befeuerung();
        $befeuerung->setOel($item->befeuerung_oel);
        $befeuerung->setGas($item->befeuerung_gas);
        $befeuerung->setElektro($item->befeuerung_elektro);
        $befeuerung->setAlternativ($item->befeuerung_alternativ);
        $befeuerung->setSolar($item->befeuerung_solar);
        $befeuerung->setErdwaerme($item->befeuerung_erdwaerme);
        $befeuerung->setLuftwp($item->befeuerung_luftwp);
        $befeuerung->setFern($item->befeuerung_fern);
        $befeuerung->setBlock($item->befeuerung_block);
        $befeuerung->setWasserElektro($item->befeuerung_wasser_elektro);
        $befeuerung->setPellet($item->befeuerung_pellet);
        $obj->setBefeuerung($befeuerung);

        // Stellplatz
        $stellplatz = new Stellplatzart();
        $stellplatz->setGarage($item->stellplatzart_garage);
        $stellplatz->setTiefgarage($item->stellplatzart_tiefgarage);
        $stellplatz->setCarport($item->stellplatzart_carport);
        $stellplatz->setFreiplatz($item->stellplatzart_freiplatz);
        $stellplatz->setParkhaus($item->stellplatzart_parkhaus);
        $stellplatz->setDuplex($item->stellplatzart_duplex);
        $obj->setStellplatzart([$stellplatz]);

        // Other
        $obj->setBarrierefrei($item->wintergarten);
        $obj->setSauna($item->sauna);
        $obj->setBarrierefrei($item->barrierefrei);

        return $obj;
    }


    private function zustand_angaben(RealEstate $realEstate): ZustandAngaben
    {
        $item = $realEstate->zustand_angaben;
        $obj = new ZustandAngaben();
        $obj->setBaujahr($item->baujahr);
        $obj->setZustand(new Zustand($item->zustandArt->key));
        $obj->setLetztemodernisierung($item->letztemodernisierung);
        $obj->setEnergiepass([
            (new Energiepass())
                ->setEnergieverbrauchkennwert($item->energiepass_energieverbrauchkennwert)
                ->setMitwarmwasser($item->energiepass_mitwarmwasser)
                ->setGueltigBis($item->energiepass_gueltig_bis)
                ->setPrimaerenergietraeger($item->energiepass_primaerenergietraeger)
                ->setStromwert($item->energiepass_stromwert)
                ->setWaermewert($item->energiepass_waermewert)
                ->setWertklasse($item->energiepass_wertklasse)
        ]);

        return $obj;
    }


    private function infrastruktur(RealEstate $realEstate): ?Infrastruktur
    {
        $item = $realEstate->infrastruktur;
        if(!$item) return null;
        $obj = new Infrastruktur();

        // new Ausblick()
        if($item->ausblick) {
            $ausblick = new Ausblick();
            $ausblick->setBlick($item->ausblick);
            $obj->setAusblick($ausblick);
        }

        // Distanzen
        $distanzen = [];
        $item->distanz_zu_flughafen && $distanzen[] = new Distanzen("FLUGHAFEN", $item->distanz_zu_flughafen);
        $item->distanz_zu_fernbahnhof && $distanzen[] = new Distanzen("FERNBAHNHOF", $item->distanz_zu_fernbahnhof);
        $item->distanz_zu_autobahn && $distanzen[] = new Distanzen("AUTOBAHN", $item->distanz_zu_autobahn);
        $item->distanz_zu_us_bahn && $distanzen[] = new Distanzen("US_BAHN", $item->distanz_zu_us_bahn);
        $item->distanz_zu_bus && $distanzen[] = new Distanzen("BUS", $item->distanz_zu_bus);
        $item->distanz_zu_kindergaerten && $distanzen[] = new Distanzen("KINDERGAERTEN", $item->distanz_zu_kindergaerten);
        $item->distanz_zu_hauptschule && $distanzen[] = new Distanzen("HAUPTSCHULE", $item->distanz_zu_hauptschule);
        $item->distanz_zu_realschule && $distanzen[] = new Distanzen("REALSCHULE", $item->distanz_zu_realschule);
        $item->distanz_zu_gesamtschule && $distanzen[] = new Distanzen("GESAMTSCHULE", $item->distanz_zu_gesamtschule);
        $item->distanz_zu_gymnasium && $distanzen[] = new Distanzen("GYMNASIUM", $item->distanz_zu_gymnasium);
        $item->distanz_zu_zentrum && $distanzen[] = new Distanzen("ZENTRUM", $item->distanz_zu_zentrum);
        $item->distanz_zu_einkaufsmoeglichkeiten && $distanzen[] = new Distanzen("EINKAUFSMOEGLICHKEITEN", $item->distanz_zu_einkaufsmoeglichkeiten);
        $item->distanz_zu_gaststaetten && $distanzen[] = new Distanzen("GASTSTAETTEN", $item->distanz_zu_gaststaetten);
        $obj->setDistanzen($distanzen);

        return $obj;
    }


    private function freitexte(RealEstate $realEstate): Freitexte
    {
        $item = $realEstate->freitexte;
        $obj = new Freitexte();
        $obj->setObjekttitel($realEstate->objekttitel);
        $obj->setLage($item->lage);
        $obj->setAusstattBeschr($item->ausstatt_beschr);
        $obj->setObjektbeschreibung($item->objektbeschreibung);
        $obj->setSonstigeAngaben($item->sonstige_angaben);

        return $obj;
    }


    private function verwaltung_objekt(RealEstate $realEstate): VerwaltungObjekt
    {
        $item = $realEstate->verwaltung_objekt;
        $obj = new VerwaltungObjekt();
        $obj->setVerfuegbarAb($item->verfuegbar_ab);
        $obj->setAbdatum(new \DateTime($item->abdatum));
        $obj->setBisdatum(new \DateTime($item->bisdatum));
        $obj->setHaustiere($item->haustiere);
        $obj->setDenkmalgeschuetzt($item->denkmalgeschuetzt);
        $obj->setGewerblicheNutzung($item->gewerbliche_nutzung);
        $obj->setHochhaus($item->hochhaus);
        $obj->setVermietet($item->vermietet);

        return $obj;
    }


    private function verwaltung_techn(RealEstate $realEstate): VerwaltungTechn
    {
        $obj = new VerwaltungTechn();
        $realEstate->verwaltung_techn_objektnr_intern && $obj->setObjektnrIntern($realEstate->verwaltung_techn_objektnr_intern);
        $realEstate->verwaltung_techn_objektnr_extern && $obj->setObjektnrExtern($realEstate->verwaltung_techn_objektnr_extern);
        $realEstate->verwaltung_techn_openimmo_obid && $obj->setOpenimmoObid($realEstate->verwaltung_techn_openimmo_obid);
        $realEstate->verwaltung_techn_kennung_ursprung && $obj->setKennungUrsprung($realEstate->verwaltung_techn_kennung_ursprung);
        $realEstate->verwaltung_techn_aktiv_von && $obj->setAktivVon(new \DateTime($realEstate->verwaltung_techn_aktiv_von));
        $realEstate->verwaltung_techn_aktiv_bis && $obj->setAktivBis(new \DateTime($realEstate->verwaltung_techn_aktiv_bis));
        $realEstate->verwaltung_techn_weitergabe_generell && $obj->setWeitergabeGenerell($realEstate->verwaltung_techn_weitergabe_generell);

        return $obj;
    }


    private function anhaenge(RealEstate $realEstate): Anhaenge
    {
        $items = $realEstate->anhaenge;
        $obj = new Anhaenge();
        $array = [];

        foreach($items AS $item) {
            /**
             * @var \App\Models\Openimmo\Anhang $item
             */

            $array[] = new Anhang(Anhang::LOCATION_REMOTE, $item->gruppe, $item->anhangtitel, strtoupper($item->format), null, new Daten($item->getUrl()));
        }

        return $obj->setAnhang($array);
    }
}
