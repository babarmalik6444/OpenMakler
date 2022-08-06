<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('company_external_apis', function (Blueprint $table) {
            $table->id();
            $table->foreignId("company_id");
            $table->foreignId("external_api_id");
            $table->json("settings")->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('company_external_apis');
    }
};
