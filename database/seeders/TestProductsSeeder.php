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
                'short_description_en' => 'High-quality adjustable massage table for physiotherapy clinics',
                'short_description_ar' => 'سرير تدليك قابل للتعديل عالي الجودة لعيادات العلاج الطبيعي',
                'long_description_en' => 'Adjustable height and sturdy construction for clinic use',
                'long_description_ar' => 'قابل لتعديل الارتفاع وبناء قوي للاستخدام في العيادات',
                'product_price' => 4500,
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=500'
            ],
            [
                'product_name_en' => 'Electric Stimulation Device',
                'product_name_ar' => 'جهاز التحفيز الكهربائي',
                'short_description_en' => 'Advanced electrical muscle stimulation device for pain relief',
                'short_description_ar' => 'جهاز تحفيز العضلات الكهربائي المتقدم لتخفيف الألم',
                'long_description_en' => 'Portable and user-friendly with multiple intensity levels',
                'long_description_ar' => 'محمول وسهل الاستخدام مع مستويات شدة متعددة',
                'product_price' => 6800,
                'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500'
            ],
            [
                'product_name_en' => 'Resistance Exercise Bands Set',
                'product_name_ar' => 'طقم أشرطة التمارين المقاومة',
                'short_description_en' => 'Complete set of resistance bands for rehabilitation exercises',
                'short_description_ar' => 'طقم كامل من أشرطة المقاومة لتمارين إعادة التأهيل',
                'long_description_en' => 'Includes multiple resistance levels and handles',
                'long_description_ar' => 'يشمل مستويات مقاومة متعددة ومقابض',
                'product_price' => 350,
                'image' => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=500'
            ],
            [
                'product_name_en' => 'Therapeutic Ultrasound Machine',
                'product_name_ar' => 'جهاز الموجات فوق الصوتية العلاجي',
                'short_description_en' => 'Professional ultrasound therapy device for deep tissue treatment',
                'short_description_ar' => 'جهاز العلاج بالموجات فوق الصوتية الاحترافي لعلاج الأنسجة العميقة',
                'long_description_en' => 'Suitable for clinics and rehabilitation centers',
                'long_description_ar' => 'مناسب للعيادات ومراكز إعادة التأهيل',
                'product_price' => 12500,
                'image' => 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=500'
            ],
            [
                'product_name_en' => 'Foam Roller Pro',
                'product_name_ar' => 'أسطوانة فوم احترافية',
                'short_description_en' => 'High-density foam roller for muscle recovery and therapy',
                'short_description_ar' => 'أسطوانة فوم عالية الكثافة لاستعادة العضلات والعلاج',
                'long_description_en' => 'Durable and ideal for pre/post workout recovery',
                'long_description_ar' => 'متين ومثالي لاستعادة ما قبل/بعد التمرين',
                'product_price' => 280,
                'image' => 'https://images.unsplash.com/photo-1601422407692-ec4eeec1d9b3?w=500'
            ]
        ];

        // Ensure we have a user to assign as vendor
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        foreach ($testProducts as $productData) {
            // Create product
            $product = Product::create([
                'user_id' => $user->id,
                'sub_category_id' => $subCategory->id,
                'product_name_en' => $productData['product_name_en'],
                'product_name_ar' => $productData['product_name_ar'],
                'short_description_en' => $productData['short_description_en'] ?? null,
                'short_description_ar' => $productData['short_description_ar'] ?? null,
                'long_description_en' => $productData['long_description_en'] ?? null,
                'long_description_ar' => $productData['long_description_ar'] ?? null,
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
