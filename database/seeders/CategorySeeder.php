<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Featured Products',
                'name_bn' => 'বিশেষ পণ্যসমূহ',
                'slug' => 'featured-products',
                'description' => 'Special and featured products from the hills',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Organic Food',
                'name_bn' => 'জৈব খাদ্য পণ্য',
                'slug' => 'organic-food',
                'description' => 'Organic food products',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Spices & Herbs',
                'name_bn' => 'মসলা ও ভেষজ',
                'slug' => 'spices-herbs',
                'description' => 'Authentic spices and herbs',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Dairy Products',
                'name_bn' => 'দুগ্ধজাত পণ্য',
                'slug' => 'dairy-products',
                'description' => 'Fresh dairy products',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'name_bn' => 'পানীয় দ্রব্য',
                'slug' => 'beverages',
                'description' => 'Tea, coffee and beverages',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
