<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
      // 1. Create Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@inventory.com'],
            [
                'name'      => 'Admin User',
                'password'  => Hash::make('password123'), // Change this in production!
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Create Default Staff User
        User::firstOrCreate(
            ['email' => 'staff@inventory.com'],
            [
                'name'      => 'Staff User',
                'password'  => Hash::make('password123'),
                'role'      => 'staff',
                'is_active' => true,
            ]
        );
    
        $categories = ['Electronics', 'Office Supplies', 'Furniture', 'Clothing', 'Food & Beverage'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        $suppliers = [
            ['name' => 'TechCorp', 'email' => 'supply@techcorp.com', 'phone' => '555-0101'],
            ['name' => 'OfficeWorld', 'email' => 'orders@officeworld.com', 'phone' => '555-0102'],
            ['name' => 'MegaSupply', 'email' => 'info@megasupply.com', 'phone' => '555-0103'],
        ];
        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        $products = [
            ['name' => 'Laptop Pro 15"', 'sku' => 'LAP-001', 'price' => 1299.99, 'quantity' => 25, 'category_id' => 1, 'supplier_id' => 1],
            ['name' => 'Wireless Mouse', 'sku' => 'MSE-001', 'price' => 29.99, 'quantity' => 8, 'low_stock_threshold' => 10, 'category_id' => 1, 'supplier_id' => 1],
            ['name' => 'Office Chair', 'sku' => 'CHR-001', 'price' => 349.99, 'quantity' => 12, 'category_id' => 3, 'supplier_id' => 2],
            ['name' => 'Printer Paper A4', 'sku' => 'PPR-001', 'price' => 9.99, 'quantity' => 5, 'low_stock_threshold' => 20, 'category_id' => 2, 'supplier_id' => 2],
            ['name' => 'USB-C Hub', 'sku' => 'USB-001', 'price' => 49.99, 'quantity' => 30, 'category_id' => 1, 'supplier_id' => 1],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(['sku' => $prod['sku']], array_merge(['low_stock_threshold' => 10], $prod));
        }
    }
}
