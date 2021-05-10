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
            GeoTargetCsvSeeder::class,
            YahooDomainSeeder::class,
            RolePermissionSeeder::class,
            CreateDefaultUserSeeder::class,
            AffiliateNetworkSeeder::class,
            UserAgentSeeder::class,
        ]);
    }
}
