<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = [
    'product_usage_ar', 'product_usage_en',
    'account_security_en', 'account_security_ar',
    'returns_refund_ar', 'returns_refund_en',
    'payment_policy_ar', 'payment_policy_en',
    'legal_compliance_ar', 'legal_compliance_en',
    'contact_support_ar', 'contact_support_en'
];

try {
    foreach ($columns as $column) {
        echo "Updating $column to LONGTEXT...\n";
        DB::statement("ALTER TABLE `tearms_conditions` MODIFY `$column` LONGTEXT NULL;");
    }
    echo "SUCCESS: All columns updated to LONGTEXT!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
