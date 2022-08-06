<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId("company_id");
            $table->foreignId("contact_id")->nullable();
            $table->foreignId("realestate_id");
            $table->foreignId("agent_id")->nullable();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->text("message");
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('customer_requests');
    }
};
