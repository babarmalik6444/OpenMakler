<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::table('company_offices', function (Blueprint $table) {
            $table->string("email")->nullable()->after("name");
            $table->string("phone")->nullable()->after("name");
            $table->string("strasse")->nullable()->after("name");
            $table->string("hausnummer")->nullable()->after("name");
            $table->string("plz")->nullable()->after("name");
            $table->string("ort")->nullable()->after("name");
            $table->string("postfach")->nullable()->after("name");
        });
    }
};
