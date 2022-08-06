<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Openimmo\RealEstate;
use App\Models\UserPermission;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Cleanup
        Schema::disableForeignKeyConstraints();
        UserRole::query()->truncate();
        Schema::enableForeignKeyConstraints();

        // Add new
        $roles = [
            [
                "id" => UserRole::ROLE_SYSTEM_ADMIN,
                "name" => "System Admin",
                "label" => "System Admin"
            ],
            [
                "id" => UserRole::ROLE_SYSTEM_USER,
                "name" => "System User",
                "label" => "System Benutzer"
            ],
            [
                "id" => UserRole::ROLE_OWNER,
                "name" => "Hauptnutzer",
                "label" => "Hauptnutzer"
            ],
            [
                "id" => UserRole::ROLE_USER,
                "name" => "User",
                "label" => "User"
            ],
            [
                "id" => UserRole::ROLE_FREELANCER,
                "name" => "Freier Mitarbeiter",
                "label" => "Freier Mitarbeiter"
            ],
        ];

        foreach($roles AS $data) {
            /**
             * @var UserRole $role
             */
            $role = UserRole::create([
                "id" => $data["id"],
                "name" => $data["name"],
                "label" => $data["label"],
            ]);
        }
    }
}
