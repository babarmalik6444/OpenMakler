<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('openimmo_imports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("user_id");
            $table->foreignId("company_id");
            $table->string("filename")->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('openimmo_imports');
    }
};
