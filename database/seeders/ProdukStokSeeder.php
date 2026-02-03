<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Produk::all();

        foreach ($products as $product) {
            // Fill stock with random numbers between 5 and 100
            $product->update([
                'stok' => rand(5, 100)
            ]);
        }
    }
}
