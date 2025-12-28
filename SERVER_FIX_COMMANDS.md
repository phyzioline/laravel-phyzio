# Server Fix Commands

## Issue: Tables Already Exist

The migration failed because `clinical_notes` table was partially created. Run these commands:

### Option 1: Drop and Recreate (if no important data)
```bash
# Drop the incomplete table
php artisan tinker
>>> Schema::dropIfExists('clinical_notes');
>>> exit

# Run migration
php artisan migrate

# Seed templates
php artisan db:seed --class=ClinicalTemplateSeeder
```

### Option 2: Use Fixed Migration (Recommended)
```bash
# Pull the fix
git pull origin main

# Run migration (now handles existing tables)
php artisan migrate

# Seed templates
php artisan db:seed --class=ClinicalTemplateSeeder
```

### Option 3: Manual Fix
```bash
# Check what tables exist
php artisan tinker
>>> Schema::hasTable('clinical_notes');
>>> Schema::hasTable('clinical_templates');
>>> Schema::hasTable('clinical_timeline');
>>> exit

# If clinical_notes exists but templates don't, create templates manually
# Or drop clinical_notes and re-run migration
```

## Route Cache Issue

The route cache error is a separate issue. Skip route caching for now:

```bash
# Don't run: php artisan route:cache
# Instead, just use route:clear
php artisan route:clear
```

Route caching can be done later after fixing duplicate route names.

## After Migration Success

Once migration completes successfully:

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache config and views only (skip route cache)
php artisan config:cache
php artisan view:cache
```

