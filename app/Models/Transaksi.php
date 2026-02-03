<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['kode_transaksi', 'tanggal', 'id_user', 'total', 'bayar', 'kembali', 'metode_pembayaran'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
