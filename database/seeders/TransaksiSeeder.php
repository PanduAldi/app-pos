<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        if (empty($users)) {
            $this->command->info('No users found, skipping TransaksiSeeder.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            DB::table('transaksi')->insert([
                'kode_transaksi' => 'TRX-' . strtoupper(Str::random(10)),
                'tanggal' => now()->subDays(rand(0, 30)),
                'id_user' => $users[array_rand($users)],
                'total' => 0, // Will be updated by DetailTransaksiSeeder
                'bayar' => 0, // Will be updated by DetailTransaksiSeeder
                'kembali' => 0, // Will be updated by DetailTransaksiSeeder
                'metode_pembayaran' => ['cash', 'qris', 'transfer'][rand(0, 2)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
