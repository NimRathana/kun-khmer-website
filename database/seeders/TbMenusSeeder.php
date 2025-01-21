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
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'parent_id' => null,
                'order' => 1,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
                'name' => 'Parameter',
                'url' => 'parameter',
                'parent_id' => null,
                'order' => 3,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company Profile',
                'url' => 'company_profile',
                'parent_id' => null,
                'order' => 4,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'News Type',
                'url' => 'news_type',
                'parent_id' => null,
                'order' => 5,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'News Information',
                'url' => 'news_information',
                'parent_id' => null,
                'order' => 6,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'About News Type',
                'url' => 'about_news_type',
                'parent_id' => null,
                'order' => 7,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sponsor',
                'url' => 'sponsor',
                'parent_id' => null,
                'order' => 8,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'About News Description',
                'url' => 'about_news_description',
                'parent_id' => null,
                'order' => 9,
                'icon' => 'mdi-monitor-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
