<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['name_en' => 'Equipment', 'name_ar' => 'معدات'],
            ['status' => 'active']
        );

        SubCategory::firstOrCreate(
            ['category_id' => $category->id, 'name_en' => 'Therapy Equipment', 'name_ar' => 'معدات العلاج'],
            ['status' => 'active']
        );
    }
}
