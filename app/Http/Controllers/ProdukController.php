<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $req)
    {
        $q = $req->q;
        $produks = Produk::when($q, fn($s) =>
                $s->where('nama_produk', 'like', "%{$q}%")
            )
            ->orderBy('id_produk', 'asc')
            ->paginate(10);

        return view('produk.index', compact('produks', 'q'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
  'nama_produk' => 'required|string|max:120',
  'ukuran'      => 'nullable|string|max:50',
  'warna'       => 'nullable|string|max:50',
  'stok'        => 'nullable|integer|min:0',
  'harga'       => 'nullable|integer|min:0',
]);


        Produk::create($data);

        return redirect()->route('produk.index')->with('ok', 'Produk disimpan');
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $r, Produk $produk)
    {
        $data = $r->validate([
            'nama_produk' => 'required|string|max:120',
            'ukuran'      => 'nullable|string|max:50',
            'warna'       => 'nullable|string|max:50',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
        ]);

        $produk->update($data);

        return redirect()->route('produk.index')->with('ok', 'Produk diupdate');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return back()->with('ok', 'Produk dihapus');
    }
}
