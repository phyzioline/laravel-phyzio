<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportProductsFromTsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import-tsv 
                            {vendor_email : The email of the vendor}
                            {tsv_file1 : Path to first TSV file}
                            {tsv_file2 : Path to second TSV file}
                            {images_folder : Path to images folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from TSV files and associate images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vendorEmail = $this->argument('vendor_email');
        $tsvFile1 = $this->argument('tsv_file1');
        $tsvFile2 = $this->argument('tsv_file2');
        $imagesFolder = $this->argument('images_folder');

        // Find vendor
        $vendor = User::where('email', $vendorEmail)->first();
        if (!$vendor) {
            $this->error("Vendor with email {$vendorEmail} not found!");
            return 1;
        }

        $this->info("Found vendor: {$vendor->name} (ID: {$vendor->id})");

        // Process both TSV files
        $totalImported = 0;
        $totalSkipped = 0;

        foreach ([$tsvFile1, $tsvFile2] as $tsvFile) {
            if (!file_exists($tsvFile)) {
                $this->warn("File not found: {$tsvFile}");
                continue;
            }

            $this->info("Processing: {$tsvFile}");
            $result = $this->processTsvFile($tsvFile, $vendor, $imagesFolder);
            $totalImported += $result['imported'];
            $totalSkipped += $result['skipped'];
        }

        $this->info("Import completed!");
        $this->info("Total imported: {$totalImported}");
        $this->info("Total skipped: {$totalSkipped}");

        return 0;
    }

    private function processTsvFile($tsvFile, $vendor, $imagesFolder)
    {
        $handle = fopen($tsvFile, 'r');
        if (!$handle) {
            $this->error("Could not open file: {$tsvFile}");
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
                $title = $this->getValue($row, $columnMap, 'title', '');
                $productId = $this->getValue($row, $columnMap, 'id', '');
                $price = $this->getValue($row, $columnMap, 'price', '0');
                $description = $this->getValue($row, $columnMap, 'description', '');
                $imageLink = $this->getValue($row, $columnMap, 'image link', '');
                $mpn = $this->getValue($row, $columnMap, 'mpn', $productId);
                $availability = $this->getValue($row, $columnMap, 'availability', 'in stock');
                $language = $this->getValue($row, $columnMap, 'language', 'ar');

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
                        $this->info("Updated: {$title} (SKU: {$existingProduct->sku})");
                        $product = $existingProduct;
                    } else {
                        $this->warn("Product with SKU {$sku} already exists, skipping...");
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
                    $this->info("Created: {$title} (SKU: {$product->sku})");
                }

                // Handle image (only if product doesn't have images yet)
                if (!empty($imageLink) && $product->productImages()->count() === 0) {
                    $this->processImage($product, $imageLink, $imagesFolder);
                }

                $imported++;

            } catch (\Exception $e) {
                $this->error("Error on line {$lineNumber}: " . $e->getMessage());
                $skipped++;
            }
        }

        fclose($handle);
        return ['imported' => $imported, 'skipped' => $skipped];
    }

    private function getValue($row, $columnMap, $columnName, $default = '')
    {
        $index = $columnMap[$columnName] ?? null;
        if ($index !== null && isset($row[$index])) {
            return trim($row[$index]);
        }
        return $default;
    }

    private function processImage($product, $imageLink, $imagesFolder)
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
            $sourcePath = rtrim($imagesFolder, '/\\') . DIRECTORY_SEPARATOR . $filename;
            
            if (!file_exists($sourcePath)) {
                // Try without the timestamp prefix (format: timestamp_filename)
                $parts = explode('_', $filename, 2);
                if (count($parts) > 1) {
                    $altFilename = $parts[1];
                    $altSourcePath = rtrim($imagesFolder, '/\\') . DIRECTORY_SEPARATOR . $altFilename;
                    if (file_exists($altSourcePath)) {
                        $sourcePath = $altSourcePath;
                        $filename = $altFilename;
                    }
                }
                
                // Try to find by partial match (search for files ending with the filename)
                if (!file_exists($sourcePath)) {
                    $files = glob(rtrim($imagesFolder, '/\\') . DIRECTORY_SEPARATOR . '*' . $filename);
                    if (!empty($files)) {
                        $sourcePath = $files[0];
                        $filename = basename($sourcePath);
                    }
                }
                
                // Try reverse search (filename is at the end)
                if (!file_exists($sourcePath)) {
                    $files = glob(rtrim($imagesFolder, '/\\') . DIRECTORY_SEPARATOR . '*' . preg_quote($filename, '/'));
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
                $destinationPath = $uploadDir . DIRECTORY_SEPARATOR . $newFilename;

                if (copy($sourcePath, $destinationPath)) {
                    // Create ProductImage record
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'uploads/products/' . $newFilename,
                    ]);
                    $this->info("  Image copied: {$filename}");
                } else {
                    $this->warn("  Failed to copy image: {$filename}");
                }
            } else {
                $this->warn("  Image not found: {$filename}");
            }
        } catch (\Exception $e) {
            $this->warn("  Error processing image: " . $e->getMessage());
        }
    }
}

