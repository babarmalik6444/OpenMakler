<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::table('openimmo_realestates', function (Blueprint $table) {
            $table->foreignId("import_id")->after("company_id")->nullable();
        });
    }


    public function down()
    {
        Schema::table('openimmo_realestates', function (Blueprint $table) {
            //
        });
    }
};
