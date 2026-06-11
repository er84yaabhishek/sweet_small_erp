<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeaturePermission;

class FeaturePermissionSeeder extends Seeder
{
    public function run()
    {
        $features = [
            ['feature_name' => 'POS_BILLING', 'is_enabled' => true],
            ['feature_name' => 'PURCHASE', 'is_enabled' => true],
            ['feature_name' => 'INVENTORY', 'is_enabled' => true],
            ['feature_name' => 'PRODUCTION', 'is_enabled' => true],
            ['feature_name' => 'MULTI_PAYMENT', 'is_enabled' => true],
            ['feature_name' => 'GST', 'is_enabled' => false],
            ['feature_name' => 'ACCOUNTING', 'is_enabled' => false],
            ['feature_name' => 'EXPENSES', 'is_enabled' => false],
            ['feature_name' => 'BARCODE', 'is_enabled' => false],
            ['feature_name' => 'MOBILE_APP', 'is_enabled' => false],
            ['feature_name' => 'CUSTOMER_CREDIT', 'is_enabled' => false],
        ];

        foreach ($features as $feature) {
            FeaturePermission::updateOrCreate(
                ['feature_name' => $feature['feature_name']],
                ['is_enabled' => $feature['is_enabled']]
            );
        }

        $this->command->info('Feature permissions seeded successfully!');
    }
}