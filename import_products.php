<?php

/**
 * Standalone script to import products from TSV files
 * 
 * Usage: php import_products.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;

// Configuration
$vendorEmail = 'phyzioline@gmail.com';
$tsvFile1 = 'd:\\phyzioline web\\E-comerce\\products_2025-12-18_16_18_02.tsv';
$tsvFile2 = 'd:\\phyzioline web\\E-comerce\\products_2025-12-18_16_14_32.tsv';
$imagesFolder = 'd:\\laravel_backup_20251220_114330\\images';

echo "Starting product import...\n";

// Find vendor
$vendor = User::where('email', $vendorEmail)->first();
if (!$vendor) {
    echo "ERROR: Vendor with email {$vendorEmail} not found!\n";
    exit(1);
}

echo "Found vendor: {$vendor->name} (ID: {$vendor->id})\n\n";

// Process both TSV files
$totalImported = 0;
$totalSkipped = 0;

foreach ([$tsvFile1, $tsvFile2] as $tsvFile) {
    if (!file_exists($tsvFile)) {
        echo "WARNING: File not found: {$tsvFile}\n";
        continue;
    }

    echo "Processing: {$tsvFile}\n";
    $result = processTsvFile($tsvFile, $vendor, $imagesFolder);
    $totalImported += $result['imported'];
    $totalSkipped += $result['skipped'];
    echo "\n";
}

echo "Import completed!\n";
echo "Total imported: {$totalImported}\n";
echo "Total skipped: {$totalSkipped}\n";

function processTsvFile($tsvFile, $vendor, $imagesFolder)
{
    $handle = fopen($tsvFile, 'r');
    if (!$handle) {
        echo "ERROR: Could not open file: {$tsvFile}\n";
        return ['imported' => 0, 'skipped' => 0];
    }

    // Read header
    $header = fgetcsv($handle, 0, "\t");
    if (!$header) {
        fclose($handle);
        return ['imported' => 0, 'skipped' => 0];
    }

    // Map header columns to indices
    $columnMap = [];
    foreach ($header as $index => $column) {
        $columnMap[trim($column)] = $index;
    }

    $imported = 0;
    $skipped = 0;
    $lineNumber = 1;

    while (($row = fgetcsv($handle, 0, "\t")) !== false) {
        $lineNumber++;
        
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }

        try {
            // Extract data from row
            $title = getValue($row, $columnMap, 'title', '');
            $productId = getValue($row, $columnMap, 'id', '');
            $price = getValue($row, $columnMap, 'price', '0');
            $description = getValue($row, $columnMap, 'description', '');
            $imageLink = getValue($row, $columnMap, 'image link', '');
            $mpn = getValue($row, $columnMap, 'mpn', $productId);
            $availability = getValue($row, $columnMap, 'availability', 'in stock');
            $language = getValue($row, $columnMap, 'language', 'ar');

            // Skip if no title
            if (empty($title)) {
                $skipped++;
                continue;
            }

            // Clean price (remove currency and convert to number)
            $price = preg_replace('/[^0-9.]/', '', $price);
            $price = floatval($price);

            // Determine product names based on language
            if ($language === 'ar') {
                $productNameAr = $title;
                $productNameEn = null;
            } else {
                $productNameEn = $title;
                $productNameAr = null;
            }

            // Check if product already exists by SKU or MPN
            $sku = $mpn ?: $productId ?: 'SKU-' . time() . '-' . rand(1000, 9999);
            $existingProduct = Product::where('sku', $sku)
                ->orWhere('sku', $mpn)
                ->orWhere('sku', $productId)
                ->first();

            if ($existingProduct) {
                // Update existing product with language-specific data
                $updateData = [];
                if ($language === 'ar' && !empty($productNameAr)) {
                    $updateData['product_name_ar'] = $productNameAr;
                    $updateData['short_description_ar'] = substr($description, 0, 255);
                    $updateData['long_description_ar'] = $description;
                } elseif ($language === 'en' && !empty($productNameEn)) {
                    $updateData['product_name_en'] = $productNameEn;
                    $updateData['short_description_en'] = substr($description, 0, 255);
                    $updateData['long_description_en'] = $description;
                }
                
                if ($price > 0) {
                    $updateData['product_price'] = $price;
                }
                
                if (!empty($updateData)) {
                    $existingProduct->update($updateData);
                    echo "  Updated: {$title} (SKU: {$existingProduct->sku})\n";
                    $product = $existingProduct;
                } else {
                    echo "  Skipped (already exists): {$title} (SKU: {$sku})\n";
                    $skipped++;
                    continue;
                }
            } else {
                // Create new product
                $product = Product::create([
                    'user_id' => $vendor->id,
                    'product_name_ar' => $productNameAr ?: $title,
                    'product_name_en' => $productNameEn ?: $title,
                    'product_price' => $price,
                    'short_description_ar' => $language === 'ar' ? substr($description, 0, 255) : null,
                    'short_description_en' => $language === 'en' ? substr($description, 0, 255) : null,
                    'long_description_ar' => $language === 'ar' ? $description : null,
                    'long_description_en' => $language === 'en' ? $description : null,
                    'amount' => $availability === 'in stock' ? 100 : 0,
                    'sku' => $sku,
                    'status' => 'active',
                ]);
                echo "  Created: {$title} (SKU: {$product->sku})\n";
            }

            // Handle image (only if product doesn't have images yet)
            if (!empty($imageLink) && $product->productImages()->count() === 0) {
                processImage($product, $imageLink, $imagesFolder);
            }

            $imported++;

        } catch (\Exception $e) {
            echo "  ERROR on line {$lineNumber}: " . $e->getMessage() . "\n";
            $skipped++;
        }
    }

    fclose($handle);
    return ['imported' => $imported, 'skipped' => $skipped];
}

function getValue($row, $columnMap, $columnName, $default = '')
{
    $index = $columnMap[$columnName] ?? null;
    if ($index !== null && isset($row[$index])) {
        return trim($row[$index]);
    }
    return $default;
}

function processImage($product, $imageLink, $imagesFolder)
{
    try {
        // Extract filename from URL
        $urlParts = parse_url($imageLink);
        $path = $urlParts['path'] ?? '';
        
        // Handle both full URLs and relative paths
        if (empty($path) && strpos($imageLink, '/') !== false) {
            // If parse_url didn't work, try direct extraction
            $path = parse_url($imageLink, PHP_URL_PATH);
        }
        
        $filename = basename($path);
        
        // Decode URL-encoded filename (handle %20, %2F, etc.)
        $filename = urldecode($filename);
        
        // Remove query parameters if any
        $filename = preg_replace('/\?.*$/', '', $filename);
        
        // Clean up the filename
        $filename = trim($filename);

        // Try to find the image in the backup folder
        $sourcePath = rtrim($imagesFolder, '/\\') . '\\' . $filename;
        
        if (!file_exists($sourcePath)) {
            // Try without the timestamp prefix (format: timestamp_filename)
            $parts = explode('_', $filename, 2);
            if (count($parts) > 1) {
                $altFilename = $parts[1];
                $altSourcePath = rtrim($imagesFolder, '/\\') . '\\' . $altFilename;
                if (file_exists($altSourcePath)) {
                    $sourcePath = $altSourcePath;
                    $filename = $altFilename;
                }
            }
            
            // Try to find by partial match (search for files ending with the filename)
            if (!file_exists($sourcePath)) {
                $files = glob(rtrim($imagesFolder, '/\\') . '\\*' . $filename);
                if (!empty($files)) {
                    $sourcePath = $files[0];
                    $filename = basename($sourcePath);
                }
            }
            
            // Try reverse search (filename is at the end)
            if (!file_exists($sourcePath)) {
                $files = glob(rtrim($imagesFolder, '/\\') . '\\*' . preg_quote($filename, '/'));
                foreach ($files as $file) {
                    if (str_ends_with($file, $filename)) {
                        $sourcePath = $file;
                        $filename = basename($sourcePath);
                        break;
                    }
                }
            }
        }

        if (file_exists($sourcePath)) {
            // Create uploads/products directory if it doesn't exist
            $uploadDir = public_path('uploads/products');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Copy file with timestamp prefix
            $newFilename = time() . '_' . $filename;
            $destinationPath = $uploadDir . '\\' . $newFilename;

            if (copy($sourcePath, $destinationPath)) {
                // Create ProductImage record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/' . $newFilename,
                ]);
                echo "    Image copied: {$filename}\n";
            } else {
                echo "    WARNING: Failed to copy image: {$filename}\n";
            }
        } else {
            echo "    WARNING: Image not found: {$filename}\n";
        }
    } catch (\Exception $e) {
        echo "    WARNING: Error processing image: " . $e->getMessage() . "\n";
    }
}

