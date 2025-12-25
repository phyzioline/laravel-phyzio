<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixProductEncoding extends Command
{
    protected $signature = 'products:fix-encoding {--dry-run : Show what would be fixed without making changes}';
    protected $description = 'Fix encoding issues in product names and descriptions';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Starting product encoding fix...');
        
        $products = Product::all();
        $fixed = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            $updated = false;
            $updates = [];
            
            // Fix product_name_ar
            $originalAr = $product->getAttributes()['product_name_ar'] ?? '';
            $fixedAr = $this->fixTextEncoding($originalAr);
            if ($fixedAr !== $originalAr) {
                $updates['product_name_ar'] = $fixedAr;
                $updated = true;
            }
            
            // Fix product_name_en
            $originalEn = $product->getAttributes()['product_name_en'] ?? '';
            $fixedEn = $this->fixTextEncoding($originalEn);
            if ($fixedEn !== $originalEn) {
                $updates['product_name_en'] = $fixedEn;
                $updated = true;
            }
            
            // Fix descriptions
            $fields = ['short_description_ar', 'short_description_en', 'long_description_ar', 'long_description_en'];
            foreach ($fields as $field) {
                $original = $product->getAttributes()[$field] ?? '';
                $fixed = $this->fixTextEncoding($original);
                if ($fixed !== $original) {
                    $updates[$field] = $fixed;
                    $updated = true;
                }
            }
            
            if ($updated) {
                if (!$dryRun) {
                    $product->update($updates);
                }
                $fixed++;
                $this->line("Fixed product ID: {$product->id}");
            } else {
                $skipped++;
            }
        }
        
        if ($dryRun) {
            $this->info("DRY RUN: Would fix {$fixed} products, skip {$skipped}");
        } else {
            $this->info("Fixed {$fixed} products, skipped {$skipped}");
        }
        
        return 0;
    }
    
    protected function fixTextEncoding($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        // If already has proper Arabic, return as is
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return $text;
        }
        
        $supportedEncodings = mb_list_encodings();
        
        // Try to fix double-encoded text
        $strategies = [
            // ISO-8859-1 -> Windows-1256 -> UTF-8
            function($t) use ($supportedEncodings) {
                if (in_array('ISO-8859-1', $supportedEncodings) && in_array('Windows-1256', $supportedEncodings)) {
                    $step1 = @mb_convert_encoding($t, 'Windows-1256', 'ISO-8859-1');
                    if ($step1) {
                        $step2 = @mb_convert_encoding($step1, 'UTF-8', 'Windows-1256');
                        if ($step2 && preg_match('/[\x{0600}-\x{06FF}]/u', $step2)) {
                            return $step2;
                        }
                    }
                }
                return null;
            },
            // Windows-1252 -> Windows-1256 -> UTF-8
            function($t) use ($supportedEncodings) {
                if (in_array('Windows-1252', $supportedEncodings) && in_array('Windows-1256', $supportedEncodings)) {
                    $step1 = @mb_convert_encoding($t, 'Windows-1256', 'Windows-1252');
                    if ($step1) {
                        $step2 = @mb_convert_encoding($step1, 'UTF-8', 'Windows-1256');
                        if ($step2 && preg_match('/[\x{0600}-\x{06FF}]/u', $step2)) {
                            return $step2;
                        }
                    }
                }
                return null;
            },
            // Direct Windows-1256
            function($t) use ($supportedEncodings) {
                if (in_array('Windows-1256', $supportedEncodings)) {
                    $result = @mb_convert_encoding($t, 'UTF-8', 'Windows-1256');
                    if ($result && preg_match('/[\x{0600}-\x{06FF}]/u', $result)) {
                        return $result;
                    }
                }
                return null;
            },
        ];
        
        foreach ($strategies as $strategy) {
            $fixed = $strategy($text);
            if ($fixed) {
                return $fixed;
            }
        }
        
        return $text;
    }
}

