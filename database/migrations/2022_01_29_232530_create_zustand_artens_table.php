<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('openimmo_zustand_arten', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("key");
            $table->string("name");
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('openimmo_zustand_arten');
    }
};
