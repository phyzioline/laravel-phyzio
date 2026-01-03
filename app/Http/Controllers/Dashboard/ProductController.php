<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Tag;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProductService;
use App\Http\Requests\Dashboard\Product\StoreProductRequest;
use App\Http\Requests\Dashboard\Product\UpdateProductRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('can:products-index', only: ['index', 'export']),
            new Middleware('can:products-create', only: ['create', 'store', 'import']),
            new Middleware('can:products-show', only: ['show']),
            new Middleware('can:products-update', only: ['edit', 'update']),
            new Middleware('can:products-delete', only: ['destroy']),
        ];
    }

    public function __construct(public ProductService $productService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $sortBy = $request->get('sort_by', 'date_new_old');
        $data = $this->productService->index($sortBy);
        $categories = Category::where('status', 'active')->get();
        $subCategories = SubCategory::where('status', 'active')->get();
        return view('dashboard.pages.product.index', compact('data', 'categories', 'subCategories', 'sortBy'));
    }

    /**
     * List Your Products - Landing page (Amazon-style)
     */
    public function list()
    {
        $draftCount = \App\Models\Product::where('user_id', auth()->id())
            ->where('status', 'inactive')
            ->count();
        
        return view('dashboard.pages.product.list', compact('draftCount'));
    }

    /**
     * Search products in catalog
     */
    public function searchCatalog(\Illuminate\Http\Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json(['products' => []]);
        }
        
        $products = \App\Models\Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('product_name_en', 'like', "%{$query}%")
                  ->orWhere('product_name_ar', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  ->orWhere('ean', 'like', "%{$query}%")
                  ->orWhere('upc', 'like', "%{$query}%");
            })
            ->with(['category', 'sub_category', 'productImages'])
            ->limit(20)
            ->get();
        
        return response()->json(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->productService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->productService->store($request->validated());
        
        $action = $request->input('action', 'publish');
        $message = $action === 'draft' ? 'Product saved as draft' : 'Product created successfully';
        
        return redirect()->route('dashboard.products.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->show($id);
        return view('dashboard.pages.product.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productService->show($id);
        $categories     = Category::where('status', 'active')->get();
        $sub_categories = SubCategory::where('status', 'active')->get();
        $tags           = Tag::where('status', 'active')->get();
        return view('dashboard.pages.product.edit-enhanced',compact('product','categories', 'sub_categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $this->productService->update($id, $request->validated());
        
        $action = $request->input('action', 'publish');
        $message = $action === 'draft' ? 'Product saved as draft' : 'Product updated successfully';
        
        return redirect()->route('dashboard.products.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productService->destroy($id);
        return redirect()->route('dashboard.products.index')->with('success','Deleted product');

    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,export',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $request->product_ids;
        $userId = auth()->id();
        
        // Ensure user can only act on their own products (unless admin)
        if (!auth()->user()->hasRole('admin')) {
            $products = \App\Models\Product::whereIn('id', $productIds)
                ->where('user_id', $userId)
                ->pluck('id')
                ->toArray();
            $productIds = $products;
        }

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No products selected or unauthorized action.');
        }

        $count = 0;
        switch ($request->action) {
            case 'delete':
                foreach ($productIds as $id) {
                    $this->productService->destroy($id);
                    $count++;
                }
                return redirect()->back()->with('success', "Deleted $count product(s) successfully.");
                
            case 'activate':
                \App\Models\Product::whereIn('id', $productIds)->update(['status' => 'active']);
                $count = count($productIds);
                return redirect()->back()->with('success', "Activated $count product(s) successfully.");
                
            case 'deactivate':
                \App\Models\Product::whereIn('id', $productIds)->update(['status' => 'inactive']);
                $count = count($productIds);
                return redirect()->back()->with('success', "Deactivated $count product(s) successfully.");
                
            case 'export':
                // Export selected products
                $products = \App\Models\Product::whereIn('id', $productIds)->get();
                $fileName = 'products-selected-' . date('Y-m-d') . '.csv';
                
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $columns = ['ID', 'Category', 'SubCategory', 'Name (EN)', 'Name (AR)', 'Price', 'Amount', 'SKU', 'Status', 'Description (EN)', 'Description (AR)', 'Image Link'];

                $callback = function() use($products, $columns) {
                    $file = fopen('php://output', 'w');
                    fputs($file, "\xEF\xBB\xBF");
                    fputcsv($file, $columns);

                    foreach ($products as $product) {
                        $row = [
                            $product->id,
                            $product->category?->name_en ?? '',
                            $product->sub_category?->name_en ?? '',
                            $product->product_name_en,
                            $product->product_name_ar,
                            $product->product_price,
                            $product->amount,
                            $product->sku,
                            $product->status,
                            $product->short_description_en,
                            $product->short_description_ar,
                            $product->image_url,
                        ];
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Export products to CSV, XML, or Excel (CSV format).
     */
    public function export($format = 'csv')
    {
        $products = \App\Models\Product::where('user_id', auth()->id())->get(); // Vendor's products
        $fileName = 'products-' . date('Y-m-d') . '.' . $format;
        $fileFormat = strtolower($format);

        if ($fileFormat === 'csv' || $fileFormat === 'xlsx') {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['ID', 'Category', 'SubCategory', 'Name (EN)', 'Name (AR)', 'Price', 'Amount', 'SKU', 'Status', 'Description (EN)', 'Description (AR)', 'Image Link'];

            $callback = function() use($products, $columns) {
                $file = fopen('php://output', 'w');
                // BOM for Excel UTF-8
                fputs($file, "\xEF\xBB\xBF");
                
                fputcsv($file, $columns);

                foreach ($products as $product) {
                    $row = [
                        $product->id,
                        $product->category?->name_en ?? '',
                        $product->sub_category?->name_en ?? '',
                        $product->product_name_en,
                        $product->product_name_ar,
                        $product->product_price,
                        $product->amount,
                        $product->sku,
                        $product->status,
                        $product->short_description_en,
                        $product->short_description_ar,
                        $product->image_url,
                    ];
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($fileFormat === 'xml') {
            $xml = new \SimpleXMLElement('<products/>');
            foreach ($products as $product) {
                $item = $xml->addChild('product');
                $item->addChild('id', $product->id);
                $item->addChild('category', htmlspecialchars($product->category?->name_en ?? ''));
                $item->addChild('sub_category', htmlspecialchars($product->sub_category?->name_en ?? ''));
                $item->addChild('name_en', htmlspecialchars($product->product_name_en));
                $item->addChild('name_ar', htmlspecialchars($product->product_name_ar));
                $item->addChild('price', $product->product_price);
                $item->addChild('amount', $product->amount);
                $item->addChild('sku', htmlspecialchars($product->sku));
                $item->addChild('status', $product->status);
                $item->addChild('description_en', htmlspecialchars($product->short_description_en ?? ''));
                $item->addChild('description_ar', htmlspecialchars($product->short_description_ar ?? ''));
                $item->addChild('image_link', htmlspecialchars($product->image_url ?? ''));
            }

            return response($xml->asXML(), 200, [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => "attachment; filename=$fileName"
            ]);
        }
        
        return redirect()->back()->with('error', 'Unsupported format');
    }

    /**
     * Sanitize text to remove invalid UTF-8 characters that cause database errors.
     */
    private function sanitizeText($text)
    {
        if (empty($text)) {
            return '';
        }
        
        // Convert to UTF-8, handling various source encodings
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        
        // Remove invalid UTF-8 sequences
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Remove control characters except newlines, tabs, and carriage returns
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Remove problematic 4-byte UTF-8 characters (emojis and special symbols) that cause MySQL errors
        // But preserve Arabic and standard Unicode characters (U+0000 to U+FFFF)
        $text = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $text);
        
        // Remove specific problematic Windows-1252 control characters (0x80-0x9F) that cause issues
        // These are often misinterpreted as UTF-8
        $text = preg_replace('/[\x{80}-\x{9F}]/u', '', $text);
        
        // Clean HTML entities but keep valid ones
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove any remaining invalid byte sequences
        // Only keep valid UTF-8 characters
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Final validation - ensure it's valid UTF-8 for database
        if (!mb_check_encoding($text, 'UTF-8')) {
            // If still invalid, use a more aggressive approach
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            // Remove any remaining non-printable characters
            $text = preg_replace('/[^\x20-\x7E\x{A0}-\x{FFFF}]/u', '', $text);
        }
        
        return trim($text);
    }

    /**
     * Import products from CSV, XML, or Excel.
     */
    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xml,xlsx,xls',
            'vendor_email' => 'nullable|email'
        ]);

        $userId = auth()->id();
        
        // Allow Admins to import for a specific vendor
        if (auth()->user()->hasRole('admin') && $request->filled('vendor_email')) {
            $vendor = \App\Models\User::where('email', $request->vendor_email)->first();
            if (!$vendor) {
                return redirect()->back()->with('error', 'Vendor with email ' . $request->vendor_email . ' not found.');
            }
            $userId = $vendor->id;
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $importedCount = 0;
        $errors = [];

        try {
            if ($extension === 'csv' || $extension === 'txt') {
                // Open file with UTF-8 encoding support
                // Try to detect encoding and convert to UTF-8
                $fileContent = file_get_contents($file->getRealPath());
                
                // Get list of supported encodings and filter to only valid ones
                $supportedEncodings = mb_list_encodings();
                $encodingsToTry = array_intersect(['UTF-8', 'Windows-1256', 'ISO-8859-1', 'Windows-1252', 'ISO-8859-15'], $supportedEncodings);
                
                // Always include UTF-8 as first option
                if (!in_array('UTF-8', $encodingsToTry)) {
                    array_unshift($encodingsToTry, 'UTF-8');
                }
                
                // Detect encoding (only use available encodings)
                $encoding = mb_detect_encoding($fileContent, $encodingsToTry, true);
                
                // If detection fails or returns false, try UTF-8 conversion directly
                if ($encoding && $encoding !== 'UTF-8') {
                    try {
                        $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding);
                        // Write converted content to temp file
                        $tempFile = tempnam(sys_get_temp_dir(), 'csv_import_');
                        file_put_contents($tempFile, $fileContent);
                        $handle = fopen($tempFile, "r");
                    } catch (\Exception $e) {
                        // If conversion fails, use original file
                        \Log::warning('Encoding conversion failed: ' . $e->getMessage());
                        $handle = fopen($file->getRealPath(), "r");
                    }
                } else {
                    // If already UTF-8 or detection failed, use original file
                    // The sanitizeText function will handle any remaining encoding issues
                    $handle = fopen($file->getRealPath(), "r");
                }
                
                // Detect delimiter (semicolon or comma)
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
                
                $header = fgetcsv($handle, 0, $delimiter); // Skip header row

                while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                    try {
                        // Skip empty rows
                        if(empty(array_filter($row))) continue;
                        
                        // Skip if name is empty
                        if(empty($row[3])) continue;
                        
                        // Clean and sanitize text fields
                        // CSV Structure: ID;Category;SubCategory;Name (EN);Name (AR);Price;Amount;SKU;Status;Description (EN);Description (AR);Image Link
                        $productNameEn = $this->sanitizeText($row[3] ?? '');
                        $productNameAr = !empty($row[4]) ? $this->sanitizeText($row[4]) : $productNameEn;
                        $shortDescEn = $this->sanitizeText($row[9] ?? 'Imported Product');
                        $shortDescAr = !empty($row[10]) ? $this->sanitizeText($row[10]) : '';
                        // Note: CSV doesn't have long_description, so we'll leave it empty or use short description
                        $longDescAr = '';
                        $imageLink = !empty($row[11]) ? trim($row[11]) : '';
                        
                        // Clean price (remove "EGP" and spaces)
                        $price = floatval(preg_replace('/[^0-9.]/', '', $row[5] ?? 0));
                        
                        // Try to find category and subcategory by name if provided
                        $categoryId = 1;
                        $subCategoryId = 1;
                        if (!empty($row[1])) {
                            $category = \App\Models\Category::where('name_en', 'like', '%' . trim($row[1]) . '%')
                                ->orWhere('name_ar', 'like', '%' . trim($row[1]) . '%')
                                ->first();
                            if ($category) {
                                $categoryId = $category->id;
                            }
                        }
                        if (!empty($row[2])) {
                            $subCategory = \App\Models\SubCategory::where('name_en', 'like', '%' . trim($row[2]) . '%')
                                ->orWhere('name_ar', 'like', '%' . trim($row[2]) . '%')
                                ->first();
                            if ($subCategory) {
                                $subCategoryId = $subCategory->id;
                            }
                        }

                        $product = \App\Models\Product::create([
                            'user_id' => $userId,
                            'product_name_en' => $productNameEn,
                            'product_name_ar' => $productNameAr,
                            'product_price' => $price,
                            'amount' => intval($row[6] ?? 1),
                            'sku' => trim($row[7] ?? uniqid()),
                            'status' => strtolower(trim($row[8] ?? 'active')) === 'active' ? 'active' : 'inactive',
                            'short_description_en' => $shortDescEn,
                            'short_description_ar' => $shortDescAr,
                            'long_description_ar' => $longDescAr,
                            'category_id' => $categoryId, 
                            'sub_category_id' => $subCategoryId,
                        ]);

                        // Handle Image Import
                        if (!empty($imageLink)) {
                            $this->importProductImage($product, $imageLink);
                        }

                        $importedCount++;
                    } catch (\Exception $e) {
                        $errors[] = 'Row error: ' . $e->getMessage();
                        \Log::error('Product import row error: ' . $e->getMessage() . ' | Row data: ' . json_encode($row));
                        continue;
                    }
                }
                fclose($handle);
                
                // Clean up temp file if created
                if (isset($tempFile) && file_exists($tempFile)) {
                    unlink($tempFile);
                }
            } elseif ($extension === 'xlsx' || $extension === 'xls') {
                // Excel file support using PhpSpreadsheet
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Skip header row
                array_shift($rows);
                
                foreach ($rows as $row) {
                    try {
                        // Skip empty rows
                        if(empty(array_filter($row))) continue;
                        
                        // Skip if name is empty
                        if(empty($row[3])) continue;
                        
                        // Clean and sanitize text fields
                        // Excel Structure: ID;Category;SubCategory;Name (EN);Name (AR);Price;Amount;SKU;Status;Description (EN);Description (AR);Image Link
                        $productNameEn = $this->sanitizeText($row[3] ?? '');
                        $productNameAr = !empty($row[4]) ? $this->sanitizeText($row[4]) : $productNameEn;
                        $shortDescEn = $this->sanitizeText($row[9] ?? 'Imported Product');
                        $shortDescAr = !empty($row[10]) ? $this->sanitizeText($row[10]) : '';
                        // Note: Excel doesn't have long_description, so we'll leave it empty
                        $longDescAr = '';
                        $imageLink = !empty($row[11]) ? trim($row[11]) : '';
                        
                        // Clean price (remove "EGP" and spaces)
                        $price = floatval(preg_replace('/[^0-9.]/', '', $row[5] ?? 0));
                        
                        // Try to find category and subcategory by name if provided
                        $categoryId = 1;
                        $subCategoryId = 1;
                        if (!empty($row[1])) {
                            $category = \App\Models\Category::where('name_en', 'like', '%' . trim($row[1]) . '%')
                                ->orWhere('name_ar', 'like', '%' . trim($row[1]) . '%')
                                ->first();
                            if ($category) {
                                $categoryId = $category->id;
                            }
                        }
                        if (!empty($row[2])) {
                            $subCategory = \App\Models\SubCategory::where('name_en', 'like', '%' . trim($row[2]) . '%')
                                ->orWhere('name_ar', 'like', '%' . trim($row[2]) . '%')
                                ->first();
                            if ($subCategory) {
                                $subCategoryId = $subCategory->id;
                            }
                        }

                        $product = \App\Models\Product::create([
                            'user_id' => $userId,
                            'product_name_en' => $productNameEn,
                            'product_name_ar' => $productNameAr,
                            'product_price' => $price,
                            'amount' => intval($row[6] ?? 1),
                            'sku' => trim($row[7] ?? uniqid()),
                            'status' => strtolower(trim($row[8] ?? 'active')) === 'active' ? 'active' : 'inactive',
                            'short_description_en' => $shortDescEn,
                            'short_description_ar' => $shortDescAr,
                            'long_description_ar' => $longDescAr,
                            'category_id' => $categoryId, 
                            'sub_category_id' => $subCategoryId,
                        ]);

                        // Handle Image Import
                        if (!empty($imageLink)) {
                            $this->importProductImage($product, $imageLink);
                        }

                        $importedCount++;
                    } catch (\Exception $e) {
                        $errors[] = 'Row error: ' . $e->getMessage();
                        \Log::error('Product import row error: ' . $e->getMessage() . ' | Row data: ' . json_encode($row));
                        continue;
                    }
                }
            } elseif ($extension === 'xml') {
                $xml = simplexml_load_file($file->getRealPath());
                foreach ($xml->product as $productData) {
                    try {
                        $product = \App\Models\Product::create([
                            'user_id' => $userId,
                            'product_name_en' => $this->sanitizeText((string)$productData->name_en),
                            'product_name_ar' => $this->sanitizeText((string)$productData->name_ar),
                            'product_price' => floatval($productData->price),
                            'amount' => intval($productData->amount),
                            'sku' => (string)$productData->sku,
                            'status' => 'active',
                            'short_description_en' => $this->sanitizeText((string)$productData->description_en ?: 'Imported Product'),
                            'short_description_ar' => $this->sanitizeText((string)$productData->description_ar ?: 'منتج مستورد'),
                            'category_id' => \App\Models\Category::first()->id ?? 1,
                            'sub_category_id' => \App\Models\SubCategory::first()->id ?? 1,
                        ]);

                        // Handle Image Import
                        if (!empty($productData->image_link)) {
                            $this->importProductImage($product, (string)$productData->image_link);
                        }

                        $importedCount++;
                    } catch (\Exception $e) {
                        $errors[] = 'Row error: ' . $e->getMessage();
                        \Log::error('Product import row error: ' . $e->getMessage());
                        continue;
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Unsupported file format. Please use CSV, XML, or Excel files.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }

        $message = "Imported $importedCount products successfully for user ID: $userId.";
        if (!empty($errors)) {
            $message .= ' Some rows had errors. Check logs for details.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Helper to download and save product image from URL.
     */
    private function importProductImage($product, $imageUrl)
    {
        try {
            $contents = file_get_contents($imageUrl);
            if ($contents) {
                $info = pathinfo($imageUrl);
                $extension = strtolower($info['extension'] ?? 'jpg');
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $extension = 'jpg'; // Fallback
                }
                
                $fileName = 'import_' . time() . '_' . uniqid() . '.' . $extension;
                $directory = public_path('uploads/products');
                
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $path = $directory . '/' . $fileName;
                file_put_contents($path, $contents);
                
                // Create image record
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/' . $fileName
                ]);
            }
        } catch (\Exception $e) {
            // Log error or ignore if image fails
            \Log::error('Failed to import image for product ' . $product->id . ': ' . $e->getMessage());
        }
    }
}
