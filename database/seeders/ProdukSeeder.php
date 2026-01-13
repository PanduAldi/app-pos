<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $makanan = DB::table('kategori')->where('nama_kategori', 'Makanan')->value('id');
        $minuman = DB::table('kategori')->where('nama_kategori', 'Minuman')->value('id');
        $snack = DB::table('kategori')->where('nama_kategori', 'Snack')->value('id');

        $products = [
            // Makanan
            [
                'nama_produk' => 'Nasi Goreng Spesial',
                'harga' => 25000,
                'id_kategori' => $makanan,
            ],
            [
                'nama_produk' => 'Mie Goreng Seafood',
                'harga' => 28000,
                'id_kategori' => $makanan,
            ],
            [
                'nama_produk' => 'Ayam Bakar Madu',
                'harga' => 30000,
                'id_kategori' => $makanan,
            ],
            [
                'nama_produk' => 'Soto Ayam',
                'harga' => 22000,
                'id_kategori' => $makanan,
            ],
            [
                'nama_produk' => 'Beef Burger',
                'harga' => 35000,
                'id_kategori' => $makanan,
            ],
            // Minuman
            [
                'nama_produk' => 'Kopi Susu Gula Aren',
                'harga' => 18000,
                'id_kategori' => $minuman,
            ],
            [
                'nama_produk' => 'Ice Americano',
                'harga' => 15000,
                'id_kategori' => $minuman,
            ],
            [
                'nama_produk' => 'Matcha Latte',
                'harga' => 22000,
                'id_kategori' => $minuman,
            ],
            [
                'nama_produk' => 'Lemon Tea',
                'harga' => 12000,
                'id_kategori' => $minuman,
            ],
            [
                'nama_produk' => 'Chocolate Milkshake',
                'harga' => 25000,
                'id_kategori' => $minuman,
            ],
            // Snack
            [
                'nama_produk' => 'Kentang Goreng',
                'harga' => 15000,
                'id_kategori' => $snack,
            ],
            [
                'nama_produk' => 'Roti Bakar Coklat Keju',
                'harga' => 20000,
                'id_kategori' => $snack,
            ],
            [
                'nama_produk' => 'Onion Rings',
                'harga' => 18000,
                'id_kategori' => $snack,
            ],
            [
                'nama_produk' => 'Pisang Goreng Keju',
                'harga' => 16000,
                'id_kategori' => $snack,
            ],
            [
                'nama_produk' => 'Nachos',
                'harga' => 22000,
                'id_kategori' => $snack,
            ],
        ];

        foreach ($products as $product) {
            DB::table('produk')->insert(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
