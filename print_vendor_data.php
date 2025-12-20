<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;

echo "==========================================================\n";
echo "📊 VENDOR AND PRODUCT DATA REPORT\n";
echo "==========================================================\n\n";

// Find the vendor
$vendorEmail = 'phyzioline@gmail.com';
$vendor = User::where('email', $vendorEmail)->first();

if (!$vendor) {
    echo "❌ Vendor not found: {$vendorEmail}\n";
    exit(1);
}

echo "✅ VENDOR INFORMATION\n";
echo "─────────────────────────────────────────────────────────\n";
echo "ID:              {$vendor->id}\n";
echo "Name:            {$vendor->name}\n";
echo "Email:           {$vendor->email}\n";
echo "Type:            {$vendor->type}\n";
echo "Status:          {$vendor->status}\n";
echo "Phone:           " . ($vendor->phone ?? 'N/A') . "\n";
echo "Country Code:    " . ($vendor->country_code ?? 'N/A') . "\n";
echo "Bank Name:       " . ($vendor->bank_name ?? 'N/A') . "\n";
echo "Bank Account:    " . ($vendor->bank_account_name ?? 'N/A') . "\n";
echo "IBAN:            " . ($vendor->iban ?? 'N/A') . "\n";
echo "SWIFT Code:      " . ($vendor->swift_code ?? 'N/A') . "\n";
echo "Currency:        " . ($vendor->currency ?? 'N/A') . "\n";
echo "Created At:      {$vendor->created_at}\n";
echo "Updated At:      {$vendor->updated_at}\n";
echo "\n";

// Get products for this vendor
$products = Product::where('user_id', $vendor->id)
    ->with(['productImages', 'category', 'sub_category', 'tags'])
    ->get();

echo "📦 PRODUCTS FOR THIS VENDOR\n";
echo "─────────────────────────────────────────────────────────\n";
echo "Total Products:  {$products->count()}\n\n";

if ($products->count() > 0) {
    foreach ($products as $index => $product) {
        $num = $index + 1;
        echo "┌─ Product #{$num} ─────────────────────────────────────────\n";
        echo "│ ID:                {$product->id}\n";
        echo "│ SKU:               {$product->sku}\n";
        echo "│ Name (EN):         {$product->product_name_en}\n";
        echo "│ Name (AR):         {$product->product_name_ar}\n";
        echo "│ Price:             {$product->product_price}\n";
        echo "│ Amount:            {$product->amount}\n";
        echo "│ Status:            {$product->status}\n";
        echo "│ Category:          " . ($product->category ? $product->category->category_name_en : 'N/A') . "\n";
        echo "│ Sub Category:      " . ($product->sub_category ? $product->sub_category->sub_category_name_en : 'N/A') . "\n";
        echo "│ Short Desc (EN):   " . (strlen($product->short_description_en ?? '') > 50 ? substr($product->short_description_en, 0, 50) . '...' : ($product->short_description_en ?? 'N/A')) . "\n";
        echo "│ Short Desc (AR):   " . (strlen($product->short_description_ar ?? '') > 50 ? substr($product->short_description_ar, 0, 50) . '...' : ($product->short_description_ar ?? 'N/A')) . "\n";
        echo "│ Created At:        {$product->created_at}\n";
        echo "│ Updated At:        {$product->updated_at}\n";
        
        // Tags
        if ($product->tags && $product->tags->count() > 0) {
            echo "│ Tags:              ";
            $tagNames = $product->tags->pluck('tag_name_en')->toArray();
            echo implode(', ', $tagNames) . "\n";
        } else {
            echo "│ Tags:              None\n";
        }
        
        // Product Images
        echo "│\n";
        echo "│ 🖼️  IMAGES ({$product->productImages->count()}):\n";
        if ($product->productImages->count() > 0) {
            foreach ($product->productImages as $imgIndex => $image) {
                $imgNum = $imgIndex + 1;
                $imagePath = public_path($image->image);
                $exists = file_exists($imagePath) ? '✅' : '❌';
                $fileSize = file_exists($imagePath) ? number_format(filesize($imagePath) / 1024, 2) . ' KB' : 'N/A';
                echo "│   {$imgNum}. {$exists} {$image->image}\n";
                echo "│      Size: {$fileSize}\n";
            }
        } else {
            echo "│   No images found\n";
        }
        echo "└────────────────────────────────────────────────────────\n\n";
    }
} else {
    echo "⚠️  No products found for this vendor.\n\n";
}

// Summary of product images directory
echo "📂 PRODUCT IMAGES DIRECTORY\n";
echo "─────────────────────────────────────────────────────────\n";
$imagesDir = public_path('uploads/products');
if (is_dir($imagesDir)) {
    $files = glob($imagesDir . '/*');
    $jpgFiles = glob($imagesDir . '/*.jpg');
    $pngFiles = glob($imagesDir . '/*.png');
    $jpegFiles = glob($imagesDir . '/*.jpeg');
    
    echo "Directory:       {$imagesDir}\n";
    echo "Total Files:     " . count($files) . "\n";
    echo "JPG Files:       " . count($jpgFiles) . "\n";
    echo "PNG Files:       " . count($pngFiles) . "\n";
    echo "JPEG Files:      " . count($jpegFiles) . "\n";
    
    // Calculate total size
    $totalSize = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            $totalSize += filesize($file);
        }
    }
    echo "Total Size:      " . number_format($totalSize / (1024 * 1024), 2) . " MB\n";
} else {
    echo "❌ Directory not found: {$imagesDir}\n";
}

echo "\n";
echo "==========================================================\n";
echo "✅ REPORT COMPLETE\n";
echo "==========================================================\n";
