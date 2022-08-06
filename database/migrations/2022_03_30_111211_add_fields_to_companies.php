<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string("street")->nullable()->after("owner_id");
            $table->string("zip")->nullable()->after("owner_id");
            $table->string("city")->nullable()->after("owner_id");
            $table->char("country", 3)->nullable()->after("owner_id");
            $table->string("phone")->nullable()->after("owner_id");
            $table->string("email")->nullable()->after("owner_id");
            $table->string("uid")->nullable()->after("owner_id");
        });
    }
};
