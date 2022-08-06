<?php

use App\ExternalApis\OpenImmoDriver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('external_apis', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("driver")->default(OpenImmoDriver::class);
            $table->string("server");
            $table->string("port")->nullable();
            $table->string("directory")->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('external_apis');
    }
};
