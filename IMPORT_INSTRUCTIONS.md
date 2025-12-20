# Product Import Instructions

This script imports products from TSV files and associates images from a backup folder.

## Files Created

1. **import_products.php** - Standalone PHP script (recommended)
2. **app/Console/Commands/ImportProductsFromTsv.php** - Artisan command

## Configuration

The script is pre-configured with:
- **Vendor Email**: phyzioline@gmail.com
- **TSV File 1**: d:\phyzioline web\E-comerce\products_2025-12-18_16_18_02.tsv
- **TSV File 2**: d:\phyzioline web\E-comerce\products_2025-12-18_16_14_32.tsv
- **Images Folder**: d:\laravel_backup_20251220_114330\images

## How to Run

### Option 1: Using the Standalone Script (Recommended)

```bash
cd d:\laravel
php import_products.php
```

### Option 2: Using Artisan Command

```bash
cd d:\laravel
php artisan products:import-tsv "phyzioline@gmail.com" "d:\phyzioline web\E-comerce\products_2025-12-18_16_18_02.tsv" "d:\phyzioline web\E-comerce\products_2025-12-18_16_14_32.tsv" "d:\laravel_backup_20251220_114330\images"
```

## What the Script Does

1. **Finds the vendor** by email (phyzioline@gmail.com)
2. **Reads both TSV files** (Arabic and English versions)
3. **Creates or updates products**:
   - If a product with the same SKU/MPN exists, it updates the language-specific fields
   - If it's a new product, it creates a new record
4. **Copies images** from the backup folder to `public/uploads/products/`
5. **Associates images** with products via ProductImage records

## Product Mapping

- **Title** → product_name_ar or product_name_en (based on language)
- **Price** → product_price (cleaned of currency symbols)
- **Description** → long_description_ar or long_description_en
- **ID/MPN** → sku
- **Availability** → amount (100 if in stock, 0 if out of stock)
- **Image Link** → Image file copied and associated

## Notes

- Products are set to `active` status by default
- If a product already exists, only the language-specific fields are updated
- Images are only added if the product doesn't already have images
- The script will skip products with empty titles
- Duplicate products (same SKU) will be updated instead of creating duplicates

## Troubleshooting

1. **Vendor not found**: Make sure the vendor with email `phyzioline@gmail.com` exists in the database
2. **File not found**: Check that the TSV file paths are correct
3. **Images not found**: The script will try multiple methods to find images:
   - Exact filename match
   - Filename without timestamp prefix
   - Partial filename match
4. **Permission errors**: Make sure the script has write permissions to `public/uploads/products/`

