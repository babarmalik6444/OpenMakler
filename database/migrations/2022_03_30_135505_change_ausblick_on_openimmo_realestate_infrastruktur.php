<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::table('openimmo_realestate_infrastruktur', function (Blueprint $table) {
            $table->dropColumn(["ausblick_ferne", "ausblick_see", "ausblick_berge", "ausblick_meer"]);
            $table->string("ausblick")->nullable()->after("realestate_id");
        });
    }
};
