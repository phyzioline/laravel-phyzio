<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name_en' => 'Cairo', 'name_ar' => 'القاهرة'],
            ['name_en' => 'Giza', 'name_ar' => 'الجيزة'],
            ['name_en' => 'Alexandria', 'name_ar' => 'الإسكندرية'],
            ['name_en' => 'Dakahlia', 'name_ar' => 'الدقهلية'],
            ['name_en' => 'Red Sea', 'name_ar' => 'البحر الأحمر'],
            ['name_en' => 'Beheira', 'name_ar' => 'البحيرة'],
            ['name_en' => 'Fayoum', 'name_ar' => 'الفيوم'],
            ['name_en' => 'Gharbia', 'name_ar' => 'الغربية'],
            ['name_en' => 'Ismailia', 'name_ar' => 'الإسماعيلية'],
            ['name_en' => 'Monufia', 'name_ar' => 'المنوفيّة'],
            ['name_en' => 'Minya', 'name_ar' => 'المنيا'],
            ['name_en' => 'Qalyubia', 'name_ar' => 'القليوبية'],
            ['name_en' => 'New Valley', 'name_ar' => 'الوادي الجديد'],
            ['name_en' => 'Suez', 'name_ar' => 'السويس'],
            ['name_en' => 'Aswan', 'name_ar' => 'أسوان'],
            ['name_en' => 'Assiut', 'name_ar' => 'أسيوط'],
            ['name_en' => 'Beni Suef', 'name_ar' => 'بني سويف'],
            ['name_en' => 'Port Said', 'name_ar' => 'بورسعيد'],
            ['name_en' => 'Damietta', 'name_ar' => 'دمياط'],
            ['name_en' => 'Sharkia', 'name_ar' => 'الشرقية'],
            ['name_en' => 'South Sinai', 'name_ar' => 'جنوب سيناء'],
            ['name_en' => 'Kafr El Sheikh', 'name_ar' => 'كفر الشيخ'],
            ['name_en' => 'Matrouh', 'name_ar' => 'مطروح'],
            ['name_en' => 'Luxor', 'name_ar' => 'الأقصر'],
            ['name_en' => 'Qena', 'name_ar' => 'قنا'],
            ['name_en' => 'North Sinai', 'name_ar' => 'شمال سيناء'],
            ['name_en' => 'Sohag', 'name_ar' => 'سوهج'],
        ];

        foreach ($governorates as $gov) {
            $governorateId = DB::table('governorates')->insertGetId([
                'name_en' => $gov['name_en'],
                'name_ar' => $gov['name_ar'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cities = $this->getCitiesForGovernorate($gov['name_en']);
            foreach ($cities as $city) {
                DB::table('cities')->insert([
                    'governorate_id' => $governorateId,
                    'name_en' => $city['name_en'],
                    'name_ar' => $city['name_ar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getCitiesForGovernorate($governorateName)
    {
        // Sample data - extend as needed
        switch ($governorateName) {
            case 'Cairo':
                return [
                    ['name_en' => 'Nasr City', 'name_ar' => 'مدينة نصر'],
                    ['name_en' => 'Maadi', 'name_ar' => 'المعادي'],
                    ['name_en' => 'Heliopolis', 'name_ar' => 'مصر الجديدة'],
                    ['name_en' => 'New Cairo', 'name_ar' => 'القاهرة الجديدة'],
                    ['name_en' => 'Shoubra', 'name_ar' => 'شبرا'],
                ];
            case 'Giza':
                return [
                    ['name_en' => '6th of October', 'name_ar' => '6 أكتوبر'],
                    ['name_en' => 'Sheikh Zayed', 'name_ar' => 'الشيخ زايد'],
                    ['name_en' => 'Dokki', 'name_ar' => 'الدقي'],
                    ['name_en' => 'Mohandessin', 'name_ar' => 'المهندسين'],
                    ['name_en' => 'Haram', 'name_ar' => 'الهرم'],
                ];
            case 'Alexandria':
                return [
                    ['name_en' => 'Smouha', 'name_ar' => 'سموحة'],
                    ['name_en' => 'Sidi Gaber', 'name_ar' => 'سيدي جابر'],
                    ['name_en' => 'Agami', 'name_ar' => 'العجمي'],
                    ['name_en' => 'Montaza', 'name_ar' => 'المنتزه'],
                ];
            // Add other governorates default cities here
            default:
                return [
                    ['name_en' => 'Main City', 'name_ar' => 'المدينة الرئيسية'],
                ];
        }
    }
}
