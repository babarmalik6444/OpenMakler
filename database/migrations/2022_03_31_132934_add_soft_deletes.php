<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    protected $tables = [
        "company_offices",
        "contacts",
        "customer_requests",
        "openimmo_anhaenge",
        "openimmo_realestates",
        "openimmo_realestate_ausstattung",
        "openimmo_realestate_flaechen",
        "openimmo_realestate_freitexte",
        "openimmo_realestate_geo",
        "openimmo_realestate_infrastruktur",
        "openimmo_realestate_objektkategorie",
        "openimmo_realestate_preise",
        "openimmo_realestate_verwaltung_objekt",
        "openimmo_realestate_zustand_angaben",
    ];

    public function up()
    {
        foreach($this->tables AS $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }


    public function down()
    {
        foreach($this->tables AS $table) {
            Schema::table($table, function (Blueprint $table) {
                //$table->dropColumn();
            });
        }
    }
};
