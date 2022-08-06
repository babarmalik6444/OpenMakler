<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("vorname", 100)->nullable();
            $table->string("name", 100)->nullable();
            $table->string("titel", 100)->nullable();
            $table->string("anrede", 50)->nullable();
            $table->string("anrede_brief", 50)->nullable();
            $table->string("firma", 100)->nullable();
            $table->string("zusatzfeld", 100)->nullable();
            $table->string("strasse", 100)->nullable();
            $table->string("hausnummer", 100)->nullable();
            $table->string("plz", 10)->nullable();
            $table->string("ort", 100)->nullable();
            $table->string("postfach", 100)->nullable();
            $table->string("postf_plz", 10)->nullable();
            $table->string("postf_ort", 100)->nullable();
            // <land iso_land="DEU"/>
            $table->char("land", 3)->nullable();
            $table->string("email_zentrale", 100)->nullable();
            $table->string("email_direkt", 100)->nullable();
            $table->string("email_privat", 100)->nullable();
            $table->string("tel_zentrale", 100)->nullable();
            $table->string("tel_durchw", 100)->nullable();
            $table->string("tel_fax", 100)->nullable();
            $table->string("tel_privat", 100)->nullable();
            $table->string("personennummer", 100)->nullable();
            $table->string("freitextfeld", 100)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
