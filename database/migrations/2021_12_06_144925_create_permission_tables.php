<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 100);
            $table->string("label", 100);
            $table->timestamps();
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("user_role_id");
            $table->string("name", 100);
            $table->string("label", 100);
            $table->foreignId("owner_id")->nullable(true);
            $table->timestamps();
        });

        Schema::table("users", function (Blueprint $table) {
            $table->foreignId("user_role_id")->default(\App\Models\UserRole::ROLE_SYSTEM_USER)->index()->after("id");
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("user_permissions");
        Schema::drop("user_roles");
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("user_role_id");
        });
    }
}
