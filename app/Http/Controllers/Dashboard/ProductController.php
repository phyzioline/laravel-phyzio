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
    public function index()
    {
        $data = $this->productService->index();
        return view('dashboard.pages.product.index',compact('data'));
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
        // dd($request->validated());
        $this->productService->store($request->validated());
        return redirect()->route('dashboard.products.index')->with('success','Created product');

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
        return view('dashboard.pages.product.edit',compact('product','categories', 'sub_categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $this->productService->update($id, $request->validated());
         return redirect()->route('dashboard.products.index')->with('success','Updated product');

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
     * Import products from CSV or XML.
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
        $extension = $file->getClientOriginalExtension();
        $importedCount = 0;

        if ($extension === 'csv' || $extension === 'txt') {
            // Open file with UTF-8 encoding support
            $handle = fopen($file->getRealPath(), "r");
            
            // Detect delimiter (semicolon or comma)
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            
            $header = fgetcsv($handle, 0, $delimiter); // Skip header row

            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                // Skip empty rows
                if(empty(array_filter($row))) continue;
                
                // Skip if name is empty
                if(empty($row[3])) continue;
                
                // Clean and convert encoding for Arabic text
                $productNameAr = !empty($row[4]) ? mb_convert_encoding(trim($row[4]), 'UTF-8', 'UTF-8') : ($row[3] ?? '');
                $shortDescAr = !empty($row[10]) ? mb_convert_encoding(trim($row[10]), 'UTF-8', 'UTF-8') : '';
                $longDescAr = !empty($row[11]) ? mb_convert_encoding(trim($row[11]), 'UTF-8', 'UTF-8') : '';
                
                // Clean price (remove "EGP" and spaces)
                $price = floatval(preg_replace('/[^0-9.]/', '', $row[5] ?? 0));

                $product = \App\Models\Product::create([
                    'user_id' => $userId,
                    'product_name_en' => trim($row[3] ?? ''),
                    'product_name_ar' => $productNameAr,
                    'product_price' => $price,
                    'amount' => intval($row[6] ?? 1),
                    'sku' => trim($row[7] ?? uniqid()),
                    'status' => strtolower(trim($row[8] ?? 'active')) === 'active' ? 'active' : 'inactive',
                    'short_description_en' => trim($row[9] ?? 'Imported Product'),
                    'short_description_ar' => $shortDescAr,
                    'long_description_ar' => $longDescAr,
                    // Fallback to first category
                    'category_id' => \App\Models\Category::first()->id ?? 1, 
                    'sub_category_id' => \App\Models\SubCategory::first()->id ?? 1,
                ]);

                // Handle Image Import
                if (!empty($row[11])) {
                    $this->importProductImage($product, $row[11]);
                }

                $importedCount++;
            }
            fclose($handle);
        } elseif ($extension === 'xml') {
            // ... (XML logic remains, update user_id)
            $xml = simplexml_load_file($file->getRealPath());
            foreach ($xml->product as $productData) {
                $product = \App\Models\Product::create([
                    'user_id' => $userId,
                    'product_name_en' => (string)$productData->name_en,
                    'product_name_ar' => (string)$productData->name_ar,
                    'product_price' => floatval($productData->price),
                    'amount' => intval($productData->amount),
                    'sku' => (string)$productData->sku,
                    'status' => 'active',
                    'short_description_en' => (string)$productData->description_en ?: 'Imported Product',
                    'short_description_ar' => (string)$productData->description_ar ?: 'منتج مستورد',
                    'category_id' => \App\Models\Category::first()->id ?? 1,
                    'sub_category_id' => \App\Models\SubCategory::first()->id ?? 1,
                ]);

                // Handle Image Import
                if (!empty($productData->image_link)) {
                    $this->importProductImage($product, (string)$productData->image_link);
                }

                $importedCount++;
            }
        } else {
             return redirect()->back()->with('error', 'For Excel (.xlsx) files, please convert to CSV first as server libraries are missing.');
        }

        return redirect()->back()->with('success', "Imported $importedCount products successfully for user ID: $userId.");
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
