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
            RolePermissionSeeder::class,
            CreateDefaultUserSeeder::class,
            AffiliateNetworkSeeder::class,
            YahooDomainSeeder::class,
            UserAgentSeeder::class,
        ]);
    }
}
