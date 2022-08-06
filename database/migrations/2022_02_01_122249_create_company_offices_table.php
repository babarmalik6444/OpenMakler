<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up()
    {
        Schema::create('company_offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("company_id");
            $table->string("name", 100);
            $table->timestamps();
        });

        Schema::table("users", function (Blueprint $table){
            $table->foreignId("company_office_id")->nullable(true)->after("company_id")->index();
        });
    }


    public function down()
    {
        Schema::dropIfExists('company_offices');

        Schema::table("users", function (Blueprint $table){
            $table->dropColumn("company_office_id");
        });
    }
};
