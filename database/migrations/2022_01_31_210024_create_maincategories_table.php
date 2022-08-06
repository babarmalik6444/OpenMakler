<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('openimmo_maincategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("key", 100);
            $table->string("name", 100);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('openimmo_maincategories');
    }
};
