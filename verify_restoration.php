<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Tag;

echo "==========================================================\n";
echo "🔍 RESTORATION VERIFICATION REPORT\n";
echo "==========================================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Check vendor
echo "📧 Checking Vendor: phyzioline@gmail.com\n";
echo "─────────────────────────────────────────────────────────\n";
$vendor = User::where('email', 'phyzioline@gmail.com')->first();
if ($vendor) {
    echo "✅ Vendor found!\n";
    echo "   ID: {$vendor->id}\n";
    echo "   Name: {$vendor->name}\n";
    echo "   Type: {$vendor->type}\n";
    echo "   Status: {$vendor->status}\n";
    $success[] = "Vendor account exists";
} else {
    echo "❌ Vendor NOT found!\n";
    $errors[] = "Vendor phyzioline@gmail.com does not exist in database";
}
echo "\n";

// Check all users
$totalUsers = User::count();
$vendors = User::where('type', 'vendor')->count();
$buyers = User::where('type', 'buyer')->count();
$therapists = User::where('type', 'therapist')->count();

echo "👥 User Statistics\n";
echo "─────────────────────────────────────────────────────────\n";
echo "Total Users: {$totalUsers}\n";
echo "Vendors: {$vendors}\n";
echo "Buyers: {$buyers}\n";
echo "Therapists: {$therapists}\n";
$success[] = "Found {$totalUsers} total users";
echo "\n";

// Check products
echo "📦 Product Statistics\n";
echo "─────────────────────────────────────────────────────────\n";
$totalProducts = Product::count();
echo "Total Products: {$totalProducts}\n";

if ($totalProducts == 0) {
    $errors[] = "No products found in database";
} else {
    $success[] = "Found {$totalProducts} products";
}

if ($vendor) {
    $vendorProducts = Product::where('user_id', $vendor->id)->count();
    echo "Products for phyzioline@gmail.com: {$vendorProducts}\n";
    
    if ($vendorProducts == 0) {
        $warnings[] = "Vendor has no products assigned";
    } else {
        $success[] = "Vendor has {$vendorProducts} products";
    }
}
echo "\n";

// Check categories
echo "🏷️  Category Statistics\n";
echo "─────────────────────────────────────────────────────────\n";
$categories = Category::count();
$subCategories = SubCategory::count();
$tags = Tag::count();
echo "Categories: {$categories}\n";
echo "Sub-Categories: {$subCategories}\n";
echo "Tags: {$tags}\n";
if ($categories == 0) $warnings[] = "No categories found";
if ($subCategories == 0) $warnings[] = "No sub-categories found";
if ($tags == 0) $warnings[] = "No tags found";
echo "\n";

// Check product images in database
echo "🖼️  Product Images (Database)\n";
echo "─────────────────────────────────────────────────────────\n";
$imageRecords = ProductImage::count();
echo "Total Image Records: {$imageRecords}\n";

if ($imageRecords == 0) {
    $errors[] = "No product image records in database";
} else {
    $success[] = "Found {$imageRecords} product image records";
}
echo "\n";

// Check physical image files
echo "📂 Product Images (Physical Files)\n";
echo "─────────────────────────────────────────────────────────\n";
$imagesDir = public_path('uploads/products');
if (is_dir($imagesDir)) {
    $files = glob($imagesDir . '/*');
    $jpgFiles = glob($imagesDir . '/*.jpg');
    $pngFiles = glob($imagesDir . '/*.png');
    $jpegFiles = glob($imagesDir . '/*.jpeg');
    
    $totalFiles = count($files);
    echo "Directory: {$imagesDir}\n";
    echo "Total Files: {$totalFiles}\n";
    echo "JPG Files: " . count($jpgFiles) . "\n";
    echo "PNG Files: " . count($pngFiles) . "\n";
    echo "JPEG Files: " . count($jpegFiles) . "\n";
    
    if ($totalFiles == 0) {
        $errors[] = "No image files found in uploads/products directory";
    } else {
        $success[] = "Found {$totalFiles} image files";
        
        // Expected: 198 files
        if ($totalFiles == 198) {
            echo "✅ Expected file count matches (198 files)\n";
            $success[] = "All 198 images restored successfully";
        } else {
            $warnings[] = "Expected 198 files, found {$totalFiles}";
        }
    }
} else {
    echo "❌ Directory not found: {$imagesDir}\n";
    $errors[] = "Product images directory does not exist";
}
echo "\n";

// Check for orphaned images (files without database records)
echo "🔗 Image Integrity Check\n";
echo "─────────────────────────────────────────────────────────\n";
$dbImages = ProductImage::pluck('image')->toArray();
$missingFiles = 0;
$orphanedRecords = 0;

foreach ($dbImages as $imagePath) {
    $fullPath = public_path($imagePath);
    if (!file_exists($fullPath)) {
        $missingFiles++;
    }
}

if ($missingFiles > 0) {
    echo "⚠️  {$missingFiles} database records point to missing files\n";
    $warnings[] = "{$missingFiles} image records have missing files";
} else {
    echo "✅ All database image records have corresponding files\n";
    $success[] = "All image records have valid files";
}
echo "\n";

// Sample product check
if ($vendor && $vendorProducts > 0) {
    echo "🔍 Sample Product Check\n";
    echo "─────────────────────────────────────────────────────────\n";
    $sampleProduct = Product::where('user_id', $vendor->id)
        ->with(['productImages', 'category', 'sub_category'])
        ->first();
    
    if ($sampleProduct) {
        echo "Sample Product: {$sampleProduct->product_name_en}\n";
        echo "SKU: {$sampleProduct->sku}\n";
        echo "Price: {$sampleProduct->product_price}\n";
        echo "Category: " . ($sampleProduct->category ? $sampleProduct->category->category_name_en : 'N/A') . "\n";
        echo "Images: {$sampleProduct->productImages->count()}\n";
        
        if ($sampleProduct->productImages->count() > 0) {
            $firstImage = $sampleProduct->productImages->first();
            $imageExists = file_exists(public_path($firstImage->image)) ? '✅' : '❌';
            echo "First Image: {$imageExists} {$firstImage->image}\n";
        }
    }
    echo "\n";
}

// Summary
echo "==========================================================\n";
echo "📊 SUMMARY\n";
echo "==========================================================\n\n";

if (count($success) > 0) {
    echo "✅ SUCCESSES ({" . count($success) . "}):\n";
    foreach ($success as $msg) {
        echo "   • {$msg}\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  WARNINGS ({" . count($warnings) . "}):\n";
    foreach ($warnings as $msg) {
        echo "   • {$msg}\n";
    }
    echo "\n";
}

if (count($errors) > 0) {
    echo "❌ ERRORS ({" . count($errors) . "}):\n";
    foreach ($errors as $msg) {
        echo "   • {$msg}\n";
    }
    echo "\n";
}

// Final verdict
echo "==========================================================\n";
if (count($errors) == 0 && count($warnings) == 0) {
    echo "🎉 RESTORATION SUCCESSFUL!\n";
    echo "All data has been restored correctly.\n";
} elseif (count($errors) == 0) {
    echo "✅ RESTORATION MOSTLY SUCCESSFUL\n";
    echo "Data restored with minor warnings. Review warnings above.\n";
} else {
    echo "❌ RESTORATION INCOMPLETE\n";
    echo "Critical errors found. Please review and fix errors above.\n";
}
echo "==========================================================\n";
