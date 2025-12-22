# Run Migrations on Server

## ✅ You're on the right track!

You successfully pulled the code to your server. Now let's run the migrations there.

## Commands to Run on Server

**Reconnect to your server** and run these commands:

```bash
# 1. Connect to server
ssh phyziolinegit@147.93.85.27

# 2. Navigate to your project
cd /home/phyziolinegit/htdocs/phyzioline.com

# 3. First, let's preview what migrations will run (safe check)
php artisan migrate:status

# 4. Run the migrations
php artisan migrate

# 5. Verify the new tables were created
php artisan tinker
# Then in tinker:
# \Schema::hasTable('shipments')
# \Schema::hasTable('vendor_wallets')
# exit
```

## What to Expect

You should see output like:
```
Migration table created successfully.
Migrating: 2025_12_22_140000_create_shipments_table
Migrated:  2025_12_22_140000_create_shipments_table (XX.XXms)
Migrating: 2025_12_22_140100_create_vendor_wallets_table
Migrated:  2025_12_22_140100_create_vendor_wallets_table (XX.XXms)
... (5 more migrations)
```

## If You Get Errors

**Permission errors**: Contact your hosting provider
**Database connection errors**: Check `.env` file for database credentials

## After Success

Once migrations run successfully, message me and I'll continue with:
- Building the OrderService (order splitting logic)
- Creating the vendor dashboard
- Adding admin shipping management

---
**Note**: Local migrations aren't needed - your development is on the server.
