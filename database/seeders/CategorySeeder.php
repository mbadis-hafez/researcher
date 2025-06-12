<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Painting',
            'Drawing, Collage Or Other Work On Paper',
            'Photography/ تصوير فوتوغرافي',
            'Digital Art',
            'Print',
            'Sculptures',
            'Artifacts',
            'Books',
            'Ceramics & Glass',
            'Fiber works/tapestry',
            'Fossils/minerals',
            'Furniture',
            'Gouache',
            'Jewellery',
            'Judaica',
            'Manuscripts',
            'Mascots',
            'Rugs',
            'Textiles',
            'Wood',
            'Unclassified'
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'is_active' => true
            ]);
        }
    }
}
