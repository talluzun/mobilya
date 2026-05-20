<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Sandalye',
            'Koltuk',
            'Masa',
            'Yemek Takımı',
            'Özel Tasarım',
        ];

        foreach ($categories as $name) {
            $slug = Str::slug($name);

            DB::table('categories')->updateOrInsert(
                ['slug' => $slug],
                ['name' => $name, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
