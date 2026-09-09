<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Motorcycle;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $setting = Setting::firstOrCreate([], [
            'shop_name' => 'Moto Service WebApp',
            'shop_phone' => '+95 000 000 000',
            'shop_address' => 'Yangon, Myanmar',
            'invoice_footer' => 'Drive safe. Come back anytime.',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => '0900000000'],
            [
                'name' => 'Aung Aung',
                'address' => 'Yangon',
                'customer_type' => 'Walk-in',
                'note' => 'Sample customer',
            ]
        );

        Motorcycle::firstOrCreate(
            ['customer_id' => $customer->id, 'plate_number' => 'YGN-12345'],
            [
                'brand' => 'Honda',
                'model' => 'Wave Alpha',
                'color' => 'Black',
                'engine_number' => 'ENG-1001',
                'frame_number' => 'FRM-1001',
                'kilometer' => 12500,
                'wheel_type' => 'Tubeless',
                'cover_condition' => 'Good',
                'note' => 'Demo motorcycle',
            ]
        );

        $items = [
            ['name' => 'Engine Oil Refill', 'item_type' => 'Service Charge', 'category' => 'Maintenance', 'sale_price' => 5000, 'unit' => 'job', 'active' => true],
            ['name' => 'Brake Adjustment', 'item_type' => 'Service Charge', 'category' => 'Maintenance', 'sale_price' => 3000, 'unit' => 'job', 'active' => true],
            ['name' => 'Motorcycle Tire Tube', 'item_type' => 'Spare Part', 'category' => 'Tyre', 'sale_price' => 12000, 'cost_price' => 9000, 'stock_quantity' => 20, 'unit' => 'pcs', 'active' => true],
            ['name' => 'Chain Set', 'item_type' => 'Spare Part', 'category' => 'Transmission', 'sale_price' => 45000, 'cost_price' => 38000, 'stock_quantity' => 8, 'unit' => 'set', 'active' => true],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
