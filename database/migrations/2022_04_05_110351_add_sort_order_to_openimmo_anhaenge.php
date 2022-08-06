<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::table('openimmo_anhaenge', function (Blueprint $table) {
            $table->integer("sort_order")->default(0)->after("gruppe");
        });
    }


    public function down()
    {
        Schema::table('openimmo_anhaenge', function (Blueprint $table) {
            $table->dropColumn("sort_order");
        });
    }
};
