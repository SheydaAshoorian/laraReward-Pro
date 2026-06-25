<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'ساعت هوشمند پرو',
                'price' => 1200,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500'
            ],
            [
                'name' => 'هدفون بی‌سیم نویز کنسلینگ',
                'price' => 850,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500'
            ],
            [
                'name' => 'پاوربانک ۲۰ هزار فست شارژ',
                'price' => 450,
                'image' => 'https://images.unsplash.com/photo-1609592424083-d92b774dfbd1?w=500'
            ],
            [
                'name' => 'کیبورد مکانیکال RGB',
                'price' => 1600,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500'
            ],
        ];

        foreach ($products ?? [] as $product) {
            Product::create($product);
        }
    }
}