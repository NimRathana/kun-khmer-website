<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TbMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_menus')->insert([
            [
                'name' => 'Menu System',
                'url' => 'menu_system',
                'parent_id' => null,
                'order' => 2,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'parent_id' => null,
                'order' => 1,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
