<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('openimmo_anhaenge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("realestate_id");
            $table->string("anhangtitel");
            $table->string("filename");
            $table->string("format");
            $table->string("gruppe");
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('openimmo_anhaenge');
    }
};
