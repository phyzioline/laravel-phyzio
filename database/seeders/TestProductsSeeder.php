<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;

class TestProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a random subcategory to assign products to
        $subCategory = SubCategory::where('status', 'active')->first();
        
        if (!$subCategory) {
            $this->command->error('No active subcategory found. Please create a subcategory first.');
            return;
        }

        $testProducts = [
            [
                'product_name_en' => 'Professional Massage Table',
                'product_name_ar' => 'سرير تدليك احترافي',
                'description_en' => 'High-quality adjustable massage table for physiotherapy clinics',
                'description_ar' => 'سرير تدليك قابل للتعديل عالي الجودة لعيادات العلاج الطبيعي',
                'product_price' => 4500,
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=500'
            ],
            [
                'product_name_en' => 'Electric Stimulation Device',
                'product_name_ar' => 'جهاز التحفيز الكهربائي',
                'description_en' => 'Advanced electrical muscle stimulation device for pain relief',
                'description_ar' => 'جهاز تحفيز العضلات الكهربائي المتقدم لتخفيف الألم',
                'product_price' => 6800,
                'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500'
            ],
            [
                'product_name_en' => 'Resistance Exercise Bands Set',
                'product_name_ar' => 'طقم أشرطة التمارين المقاومة',
                'description_en' => 'Complete set of resistance bands for rehabilitation exercises',
                'description_ar' => 'طقم كامل من أشرطة المقاومة لتمارين إعادة التأهيل',
                'product_price' => 350,
                'image' => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=500'
            ],
            [
                'product_name_en' => 'Therapeutic Ultrasound Machine',
                'product_name_ar' => 'جهاز الموجات فوق الصوتية العلاجي',
                'description_en' => 'Professional ultrasound therapy device for deep tissue treatment',
                'description_ar' => 'جهاز العلاج بالموجات فوق الصوتية الاحترافي لعلاج الأنسجة العميقة',
                'product_price' => 12500,
                'image' => 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=500'
            ],
            [
                'product_name_en' => 'Foam Roller Pro',
                'product_name_ar' => 'أسطوانة فوم احترافية',
                'description_en' => 'High-density foam roller for muscle recovery and therapy',
                'description_ar' => 'أسطوانة فوم عالية الكثافة لاستعادة العضلات والعلاج',
                'product_price' => 280,
                'image' => 'https://images.unsplash.com/photo-1601422407692-ec4eeec1d9b3?w=500'
            ]
        ];

        foreach ($testProducts as $productData) {
            // Create product
            $product = Product::create([
                'sub_category_id' => $subCategory->id,
                'product_name_en' => $productData['product_name_en'],
                'product_name_ar' => $productData['product_name_ar'],
                'description_en' => $productData['description_en'],
                'description_ar' => $productData['description_ar'],
                'product_price' => $productData['product_price'],
                'amount' => 10, // Stock amount
                'status' => 'active'
            ]);

            // Create product image
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $productData['image']
            ]);

            $this->command->info("Created product: {$productData['product_name_en']}");
        }

        $this->command->info('Successfully created 5 test products!');
    }
}
