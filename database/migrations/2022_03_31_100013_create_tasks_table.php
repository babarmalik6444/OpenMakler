<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{


    public function up()
    {
        Schema::create('tasklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId("company_id");
            $table->foreignId("user_id")->nullable();
            $table->foreignId("company_office_id")->nullable();
            $table->string("name");
            $table->char("visibility", 1)->default("a");
            $table->json("tasks")->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('tasklists');
    }
};
