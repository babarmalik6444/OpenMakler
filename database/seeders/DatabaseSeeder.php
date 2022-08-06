<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Roles and Users
            RolesAndPermissionsSeeder::class,
            TestUserSeeder::class,

            // OpenImmo Data
            ContactSeeder::class,
            ZustandArtenSeeder::class,
            MainAndSubcategorySeeder::class,
        ]);
    }
}
