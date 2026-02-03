<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $produk = Produk::with('kategori')
            ->when($search, function ($query, $search) {
                return $query->where('nama_produk', 'like', "%{$search}%");
            })
            ->paginate(10);

        $kategori = Kategori::all();

        return view('content.produk', [
            'title' => 'Daftar Produk',
            'produk' => $produk,
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id'
        ]);

        Produk::create($request->all());

        return redirect()->route('produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id'
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->route('produk')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->detailTransaksi()->count() > 0) {
            return redirect()->route('produk')->with('error', 'Produk tidak bisa dihapus karena sudah memiliki riwayat transaksi');
        }

        $produk->delete();

        return redirect()->route('produk')->with('success', 'Produk berhasil dihapus');
    }
}
