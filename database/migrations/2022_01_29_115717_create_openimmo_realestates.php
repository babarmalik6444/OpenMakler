<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up()
    {
        Schema::create('openimmo_realestates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("creator_id")->nullable(true)->index(); // Foreign key auf user
            $table->foreignId("agent_id")->nullable(true)->index(); // Foreign key auf user
            $table->foreignId("company_id")->nullable(true)->index(); // Foreign key auf user

            // Kategorien (objektkategorie->objektart)
            $table->foreignId("maincategory_id")->nullable(true)->index();
            $table->foreignId("subcategory_id")->nullable(true)->index();

            // Freitext-Objekttitel
            $table->text("objekttitel")->nullable(); // freitexte->objekttitel

            // ----------- verwaltung_techn -----------------
            $table->string("verwaltung_techn_objektnr_intern", 100)->nullable(true);
            $table->string("verwaltung_techn_objektnr_extern", 100)->nullable(true);
            $table->string("verwaltung_techn_openimmo_obid", 100)->nullable(true);
            $table->string("verwaltung_techn_kennung_ursprung", 100)->nullable(true);
            $table->date("verwaltung_techn_aktiv_von")->nullable(true);
            $table->date("verwaltung_techn_aktiv_bis")->nullable(true);
            $table->boolean("verwaltung_techn_weitergabe_generell")->nullable();

            // ----------- anhaenge ------------------
            // ----------- bieterverfahren ------------------
            // ----------- versteigerung ------------------
            // ----------- bewertung ------------------
            // ----------- user_defined_anyfield ------------------
            // ----------- kontaktperson ------------------
            // ----------- weitere_adresse[] ------------------

            $table->timestamps();
        });

        Schema::create('openimmo_realestate_geo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->string("ort", 100)->nullable();
            $table->string("plz", 10)->nullable();
            $table->json("geokoordinaten")->nullable(); // geo->geokoordinaten@breitengrad + geo->geokoordinaten@laengengrad
            $table->string("strasse", 100)->nullable();
            $table->string("hausnummer", 100)->nullable();
            $table->string("bundesland", 100)->nullable();
            $table->char("land", 3)->nullable();
            // Ignored fields: gemeindecode, flur, flurstueck, gemarkung
            $table->unsignedSmallInteger("etage",)->nullable();
            $table->unsignedSmallInteger("anzahl_etagen")->nullable();
            // Ignored fields: <lage_im_bau LINKS="false" RECHTS="true" VORNE="true" HINTEN="false"/>
            $table->string("wohnungsnr", 100)->nullable();
            /* Ignored fields: geo_lage_gebiet
              <xsd:enumeration value="WOHN"/>
                <xsd:enumeration value="GEWERBE"/>
                <xsd:enumeration value="INDUSTRIE"/>
                <xsd:enumeration value="MISCH"/>
                <xsd:enumeration value="NEUBAU"/>
                <xsd:enumeration value="ORTSLAGE"/>
                <xsd:enumeration value="SIEDLUNG"/>
                <xsd:enumeration value="STADTRAND"/>
                <xsd:enumeration value="STADTTEIL"/>
                <xsd:enumeration value="STADTZENTRUM"/>
                <xsd:enumeration value="NEBENZENTRUM"/>
                <xsd:enumeration value="1A"/>
                <xsd:enumeration value="1B"/>
             */
            $table->string("regionaler_zusatz", 200)->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_preise', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->decimal("kaufpreis", 12, 2)->nullable();        // Wenn "Auf Anfrage" dann Wert = 0
            $table->decimal("kaufpreisnetto", 12, 2)->nullable();
            $table->decimal("kaufpreisbrutto", 12, 2)->nullable();
            $table->decimal("nettokaltmiete", 8, 2)->nullable();
            $table->decimal("kaltmiete", 8, 2)->nullable();
            $table->decimal("warmmiete", 8, 2)->nullable();
            $table->boolean("heizkosten_enthalten")->default(0)->nullable();
            $table->decimal("heizkosten", 8, 2)->nullable();
            $table->boolean("zzg_mehrwertsteuer")->default(0)->nullable();
            $table->decimal("mietzuschlaege", 8, 2)->nullable();
            $table->decimal("hauptmietzinsnetto", 8, 2)->nullable();
            $table->decimal("hauptmietzinsust", 8, 2)->nullable(); // hauptmietzinsnetto@hauptmietzinsust
            $table->decimal("pauschalmiete", 8, 2)->nullable();
            $table->decimal("betriebskostennetto", 8, 2)->nullable();
            $table->decimal("betriebskostenust", 8, 2)->nullable(); // betriebskostennetto@betriebskostenust
            $table->decimal("mietpreis_pro_qm")->nullable();
            $table->decimal("mieteinnahmen_ist")->nullable();
            $table->string("mieteinnahmen_ist_periode", 100)->nullable(); // mieteinnahmen_ist@periode
            $table->decimal("mieteinnahmen_soll")->nullable();
            $table->string("mieteinnahmen_soll_periode", 100)->nullable(); // mieteinnahmen_soll@periode
            $table->text("aussen_courtage")->nullable();
            $table->text("innen_courtage")->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_ausstattung', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            // Befeuerung: <befeuerung OEL="0" GAS="1" ELEKTRO="0" ALTERNATIV="0" SOLAR="1" ERDWAERME="0" LUFTWP="0" FERN="0" BLOCK="0" WASSER-ELEKTRO="0" PELLET="0"/>
            $table->boolean("befeuerung_oel")->nullable();
            $table->boolean("befeuerung_gas")->nullable();
            $table->boolean("befeuerung_elektro")->nullable();
            $table->boolean("befeuerung_alternativ")->nullable();
            $table->boolean("befeuerung_solar")->nullable();
            $table->boolean("befeuerung_erdwaerme")->nullable();
            $table->boolean("befeuerung_luftwp")->nullable();
            $table->boolean("befeuerung_fern")->nullable();
            $table->boolean("befeuerung_block")->nullable();
            $table->boolean("befeuerung_wasser_elektro")->nullable();
            $table->boolean("befeuerung_pellet")->nullable();
            // <stellplatzart GARAGE="1" TIEFGARAGE="1" CARPORT="1" FREIPLATZ="1" PARKHAUS="1" DUPLEX="1"/>
            $table->boolean("stellplatzart_garage")->nullable();
            $table->boolean("stellplatzart_tiefgarage")->nullable();
            $table->boolean("stellplatzart_carport")->nullable();
            $table->boolean("stellplatzart_freiplatz")->nullable();
            $table->boolean("stellplatzart_parkhaus")->nullable();
            $table->boolean("stellplatzart_duplex")->nullable();
            // Other
            $table->boolean("wintergarten")->nullable();
            $table->boolean("sauna")->nullable();
            $table->boolean("barrierefrei")->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_flaechen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->decimal("wohnflaeche")->nullable();
            $table->decimal("grundstuecksflaeche")->nullable();
            $table->decimal("kellerflaeche")->nullable();
            $table->decimal("gartenflaeche")->nullable();
            $table->decimal("nutzflaeche")->nullable();
            $table->decimal("vermietbare_flaeche")->nullable();
            $table->decimal("balkon_terrasse_flaeche")->nullable();
            $table->unsignedSmallInteger("anzahl_wohneinheiten")->nullable();
            $table->boolean("einliegerwohnung")->nullable();
            $table->unsignedSmallInteger("anzahl_wohn_schlafzimmer")->nullable();
            $table->unsignedSmallInteger("anzahl_balkone")->nullable();
            $table->unsignedSmallInteger("anzahl_terrassen")->nullable();
            $table->unsignedSmallInteger("anzahl_logia")->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_zustand_angaben', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->char("baujahr", 4)->nullable();
            $table->text("letztemodernisierung")->nullable();
            $table->foreignId("zustand_art")->nullable(); // zustand_angaben->zustand@zustand_art
            // energiepass
            $table->decimal("energiepass_energieverbrauchkennwert")->nullable();
            $table->boolean("energiepass_mitwarmwasser")->nullable();
            $table->string("energiepass_gueltig_bis")->nullable();
            $table->string("energiepass_primaerenergietraeger")->nullable();
            $table->string("energiepass_stromwert")->nullable();
            $table->string("energiepass_waermewert")->nullable();
            $table->char("energiepass_wertklasse", 2)->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_infrastruktur', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            // <ausblick blick="BERGE"/>
            $table->boolean("ausblick_ferne")->nullable();
            $table->boolean("ausblick_see")->nullable();
            $table->boolean("ausblick_berge")->nullable();
            $table->boolean("ausblick_meer")->nullable();
            // <distanzen distanz_zu="HAUPTSCHULE">22.00</distanzen>
            $table->decimal("distanz_zu_flughafen")->nullable();
            $table->decimal("distanz_zu_fernbahnhof")->nullable();
            $table->decimal("distanz_zu_autobahn")->nullable();
            $table->decimal("distanz_zu_us_bahn")->nullable();
            $table->decimal("distanz_zu_bus")->nullable();
            $table->decimal("distanz_zu_kindergaerten")->nullable();
            $table->decimal("distanz_zu_grundschule")->nullable();
            $table->decimal("distanz_zu_hauptschule")->nullable();
            $table->decimal("distanz_zu_realschule")->nullable();
            $table->decimal("distanz_zu_gesamtschule")->nullable();
            $table->decimal("distanz_zu_gymnasium")->nullable();
            $table->decimal("distanz_zu_zentrum")->nullable();
            $table->decimal("distanz_zu_einkaufsmoeglichkeiten")->nullable();
            $table->decimal("distanz_zu_gaststaetten")->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_verwaltung_objekt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->string("verfuegbar_ab")->nullable();
            $table->date("abdatum")->nullable();
            $table->date("bisdatum")->nullable();
            $table->boolean("haustiere")->nullable();
            $table->boolean("denkmalgeschuetzt")->nullable();
            $table->boolean("gewerbliche_nutzung")->nullable();
            $table->boolean("hochhaus")->nullable();
            $table->boolean("vermietet")->nullable();
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_objektkategorie', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->boolean("nutzungsart_wohnen")->nullable();
            $table->boolean("nutzungsart_gewerbe")->nullable();
            $table->boolean("nutzungsart_anlage")->nullable();
            $table->boolean("nutzungsart_waz")->nullable();
            $table->boolean("vermarktungsart_kauf")->nullable();
            $table->boolean("vermarktungsart_miete_pacht")->nullable();
            $table->boolean("vermarktungsart_erbpacht")->nullable();
            $table->boolean("vermarktungsart_leasing")->nullable();
            // <objektart><zimmer zimmertyp="ZIMMER"/><objektart_zusatz>Moebeliert</objektart_zusatz></objektart>
            $table->timestamps();
        });

        Schema::create('openimmo_realestate_freitexte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id")->nullable(true)->index(); // Foreign key auf user
            $table->text("lage")->nullable();
            $table->text("ausstatt_beschr")->nullable();
            $table->text("objektbeschreibung")->nullable();
            $table->text("sonstige_angaben")->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('openimmo_realestates');
        Schema::dropIfExists('openimmo_realestate_geo');
        Schema::dropIfExists('openimmo_realestate_preise');
        Schema::dropIfExists('openimmo_realestate_ausstattung');
        Schema::dropIfExists('openimmo_realestate_flaechen');
        Schema::dropIfExists('openimmo_realestate_zustand_angaben');
        Schema::dropIfExists('openimmo_realestate_infrastruktur');
        Schema::dropIfExists('openimmo_realestate_verwaltung_objekt');
        Schema::dropIfExists('openimmo_realestate_objektkategorie');
        Schema::dropIfExists('openimmo_realestate_freitexte');
    }
};
