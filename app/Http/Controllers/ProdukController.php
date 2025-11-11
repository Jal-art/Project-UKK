<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index(Request $req)
    {
        // Sanitasi: hanya huruf & spasi (untuk pencarian nama)
        $raw = (string) $req->q;
        $q = preg_replace('/[^\p{L}\s]/u', '', $raw);
        $q = preg_replace('/\s+/u', ' ', trim($q));

        $produks = Produk::when($q !== '', fn($s) =>
                $s->where('nama_produk', 'like', "%{$q}%") // hanya cari di nama_produk
            )
            ->orderBy('id_produk', 'asc')   // <<< urut dari id paling kecil
            ->paginate(10)
            ->withQueryString();

        // AJAX (live search & pagination)
        if ($req->ajax()) {
            return response()->json([
                'items' => $produks->map(fn($p) => [
                    'id_produk'   => $p->id_produk,
                    'nama_produk' => $p->nama_produk,
                    'ukuran'      => $p->ukuran ?: '—',
                    'warna'       => $p->warna ?: '—',
                    'harga'       => number_format((float)$p->harga, 0, ',', '.'),
                    'stok'        => (int) $p->stok,
                    'edit_url'    => route('produk.edit', $p),
                    'del_url'     => route('produk.destroy', $p),
                ])->values(),
                'meta' => [
                    'count'        => $produks->count(),
                    'total'        => $produks->total(),
                    'current_page' => $produks->currentPage(),
                    'last_page'    => $produks->lastPage(),
                    'next_page'    => $produks->nextPageUrl(),
                    'prev_page'    => $produks->previousPageUrl(),
                ],
                'pagination_html' => (string) $produks->withQueryString()->links(),
            ]);
        }

        return view('produk.index', [
            'produks' => $produks,
            'q'       => $q,
        ]);
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $r)
    {
        // Unik: nama_produk tidak boleh duplikat untuk kombinasi ukuran & warna yang sama
        $data = $r->validate([
            'nama_produk' => [
                'required', 'string', 'max:120',
                Rule::unique('produks', 'nama_produk')->where(fn($q) =>
                    $q->where('ukuran', $r->input('ukuran'))
                      ->where('warna',  $r->input('warna'))
                ),
            ],
            'ukuran'      => 'nullable|string|max:50',
            'warna'       => 'nullable|string|max:50',
            'stok'        => 'nullable|integer|min:0',
            'harga'       => 'nullable|numeric|min:0',
        ], [
            'nama_produk.unique' => 'Produk dengan kombinasi ukuran & warna tersebut sudah ada.',
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
            'nama_produk' => [
                'required', 'string', 'max:120',
                Rule::unique('produks', 'nama_produk')
                    ->ignore($produk->id_produk, 'id_produk')
                    ->where(fn($q) =>
                        $q->where('ukuran', $r->input('ukuran'))
                          ->where('warna',  $r->input('warna'))
                    ),
            ],
            'ukuran'      => 'nullable|string|max:50',
            'warna'       => 'nullable|string|max:50',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
        ], [
            'nama_produk.unique' => 'Produk dengan kombinasi ukuran & warna tersebut sudah ada.',
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
