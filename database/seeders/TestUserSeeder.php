<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyOffice;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Truncate tables
        User::query()->truncate();
        Company::query()->truncate();

        // Data
        $items = collect([
            [
                'name' => "Admin Acme",
                'email' => "acme@example.com",
                "user_role_id" => UserRole::ROLE_OWNER,
                "company" => [
                    "id" => 1,
                    "name" => "ACME",
                    "offices" => [
                        ["name" => "ACME HQ Wien"],
                        ["name" => "ACME Berline"],
                        ["name" => "ACME Paris"],
                    ]
                ]
            ],
            [
                'name' => "Benny Berta",
                'email' => "berta@example.com",
                "user_role_id" => UserRole::ROLE_OWNER,
                "company" => [
                    "id" => 2,
                    "name" => "Berta GmbH",
                    "offices" => [
                        ["name" => "Berta HQ"]
                    ]
                ]
            ],
            [
                'name' => "Cecilie Ceta",
                'email' => "ceta@example.com",
                "user_role_id" => UserRole::ROLE_OWNER,
                "company" => [
                    "id" => 3,
                    "name" => "Ceta AG",
                    "offices" => [
                        ["name" => "Ceta HQ"]
                    ]
                ]
            ],
            [
                "name" => "Mitarbeiter1 Acme",
                "email" => "mitarbieter1-acme@example.com",
                "user_role_id" => UserRole::ROLE_USER,
                "company" => [
                    "id" => 1
                ]
            ],
            [
                "name" => "Mitarbeiter2 Acme",
                "email" => "mitarbieter2-acme@example.com",
                "user_role_id" => UserRole::ROLE_USER,
                "company" => [
                    "id" => 1
                ]
            ],
            [
                "name" => "System Admin",
                "email" => "admin@example.com",
                "user_role_id" => UserRole::ROLE_SYSTEM_ADMIN,
            ],
            [
                "name" => "System Mitarbeiter1",
                "email" => "system-mitarbeiter1@example.com",
                "user_role_id" => UserRole::ROLE_SYSTEM_USER,
            ],
        ]);

        // Loop
        $items->each(function($data){
            // User itself
            $data["password"] = bcrypt(isset($data["password"]) && $data["password"] ? $data["password"] : $data["email"]);
            $user = new User();
            $user->user_role_id = $data["user_role_id"];
            $user->password = $data["password"];
            $user->name = $data["name"];
            $user->email = $data["email"];
            $user->save();

            // Company
            if(isset($data["company"]) && $data["company"]) {
                $comp = $data["company"];

                if(isset($comp["name"]) && $comp["name"]) {
                    $company = new Company();
                    $company->id = $comp["id"];
                    $company->name = $comp["name"];
                    $company->owner_id = $user->id;
                    $company->save();

                    if(isset($comp["offices"]) && $comp["offices"]) {
                        foreach($comp["offices"] AS $off) {
                            $office = CompanyOffice::create([
                                "name" => $off["name"],
                                "company_id" => $company->id
                            ]);

                            $user->company_office_id = $office->id;
                        }
                    }
                }

                $user->company_id = $comp["id"];
                $user->save();
            }

            // Roles
        });
    }
}
