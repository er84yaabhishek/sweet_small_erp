<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Categories
            ['name' => 'view_categories', 'display_name' => 'View Categories'],
            ['name' => 'create_category', 'display_name' => 'Create Category'],
            ['name' => 'edit_category', 'display_name' => 'Edit Category'],
            ['name' => 'delete_category', 'display_name' => 'Delete Category'],
            // Units
            ['name' => 'view_units', 'display_name' => 'View Units'],
            ['name' => 'create_unit', 'display_name' => 'Create Unit'],
            ['name' => 'edit_unit', 'display_name' => 'Edit Unit'],
            ['name' => 'delete_unit', 'display_name' => 'Delete Unit'],
            // Items
            ['name' => 'view_items', 'display_name' => 'View Items'],
            ['name' => 'create_item', 'display_name' => 'Create Item'],
            ['name' => 'edit_item', 'display_name' => 'Edit Item'],
            ['name' => 'delete_item', 'display_name' => 'Delete Item'],
            // Suppliers
            ['name' => 'view_suppliers', 'display_name' => 'View Suppliers'],
            ['name' => 'create_supplier', 'display_name' => 'Create Supplier'],
            ['name' => 'edit_supplier', 'display_name' => 'Edit Supplier'],
            ['name' => 'delete_supplier', 'display_name' => 'Delete Supplier'],
            // Purchases
            ['name' => 'view_purchases', 'display_name' => 'View Purchases'],
            ['name' => 'create_purchase', 'display_name' => 'Create Purchase'],
            ['name' => 'delete_purchase', 'display_name' => 'Delete Purchase'],
            // Purchase Returns
            ['name' => 'view_purchase_returns', 'display_name' => 'View Purchase Returns'],
            ['name' => 'create_purchase_return', 'display_name' => 'Create Purchase Return'],
            // Recipes
            ['name' => 'view_recipes', 'display_name' => 'View Recipes'],
            ['name' => 'create_recipe', 'display_name' => 'Create Recipe'],
            ['name' => 'edit_recipe', 'display_name' => 'Edit Recipe'],
            ['name' => 'delete_recipe', 'display_name' => 'Delete Recipe'],
            // Production Logs
            ['name' => 'view_production_logs', 'display_name' => 'View Production Logs'],
            ['name' => 'create_production_log', 'display_name' => 'Create Production Log'],
            // Sales
            ['name' => 'view_sales', 'display_name' => 'View Sales'],
            ['name' => 'create_sale', 'display_name' => 'Create Sale'],
            ['name' => 'delete_sale', 'display_name' => 'Delete Sale'],
            // Sale Returns
            ['name' => 'view_sale_returns', 'display_name' => 'View Sale Returns'],
            ['name' => 'create_sale_return', 'display_name' => 'Create Sale Return'],
            // Expenses
            ['name' => 'view_expenses', 'display_name' => 'View Expenses'],
            ['name' => 'create_expense', 'display_name' => 'Create Expense'],
            ['name' => 'edit_expense', 'display_name' => 'Edit Expense'],
            ['name' => 'delete_expense', 'display_name' => 'Delete Expense'],
            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm['name']], $perm);
        }

        $this->command->info('Permissions seeded successfully!');
    }
}