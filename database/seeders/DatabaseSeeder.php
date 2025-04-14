<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DataDukunganSeeder;
use JenisSuratSeeder;
use UnitKerjaSeeder;
use ShieldSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DataDukunganSeeder::class;
        JenisSuratSeeder::class;
        UnitKerjaSeeder::class;
        ShieldSeeder::class;
    }
}

