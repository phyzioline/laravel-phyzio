<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate([
            'email' => 'info@phyzioline.com'
        ], [
            'image' => 'dashboard/images/Frame 127.svg',
            'description_en' => 'Phyzioline - professional physiotherapy services and products.',
            'description_ar' => 'فيزولاين - خدمات ومنتجات العلاج الطبيعي الاحترافية.',
            'address_en' => '123 Phyzioline Street',
            'address_ar' => '123 شارع فيزولاين',
            'phone' => '+0000000000'
        ]);
    }
}
