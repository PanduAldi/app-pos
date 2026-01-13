<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = DB::table('transaksi')->get();
        $products = DB::table('produk')->get();

        if ($products->isEmpty()) {
            $this->command->info('No products found, skipping DetailTransaksiSeeder.');
            return;
        }

        foreach ($transaksis as $transaksi) {
            $total = 0;
            $numberOfItems = rand(1, 5);

            for ($i = 0; $i < $numberOfItems; $i++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $subtotal = $product->harga * $qty;

                DB::table('detail_transaksi')->insert([
                    'id_transaksi' => $transaksi->id,
                    'id_produk' => $product->id,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $total += $subtotal;
            }

            // Update Transaksi Total and Payment
            $bayar = ceil($total / 50000) * 50000; // Simulating payment with next 50k bill or exact
            if ($bayar < $total) $bayar = $total; // Ensure payment is sufficient

            DB::table('transaksi')
                ->where('id', $transaksi->id)
                ->update([
                    'total' => $total,
                    'bayar' => $bayar,
                    'kembali' => $bayar - $total,
                ]);
        }
    }
}
