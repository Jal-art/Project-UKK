<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = $req->query('tanggal');

        if (!$tanggal) {
            $tanggal = Carbon::today()->toDateString();
        } else {
            $tanggal = Carbon::parse($tanggal)->toDateString();
        }

        $items = Transaksi::query()
            ->whereDate('tanggal', $tanggal)
            ->orderByDesc('id_transaksi')
            ->paginate(10);

        return view('transaksi.index', compact('items', 'tanggal'));
    }

    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('transaksi.create', compact('produks'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'tanggal'            => 'required|date',
            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|integer|exists:produks,id_produk',
            'items.*.qty'        => 'required|integer|min:1',
            'uang_bayar'         => 'required|numeric|min:0',
        ]);

        $map      = collect($data['items']);
        $prodIds  = $map->pluck('produk_id')->all();
        $produkBy = Produk::whereIn('id_produk', $prodIds)->get()->keyBy('id_produk');

        $detailRows = [];
        $grand      = 0;

        foreach ($map as $row) {
            $pid = (int) $row['produk_id'];
            $qty = (int) $row['qty'];

            $prod = $produkBy[$pid] ?? null;
            if (!$prod) abort(422, 'Produk tidak ditemukan.');

            if ($qty > (int)$prod->stok) {
                return back()
                    ->withErrors("Qty untuk {$prod->nama_produk} melebihi stok ({$prod->stok}).")
                    ->withInput();
            }

            $harga = (int)$prod->harga;
            $sub   = $harga * $qty;
            $grand += $sub;

            $detailRows[] = [
                'id_produk'    => $pid,
                'nama_produk'  => $prod->nama_produk,
                'ukuran'       => $prod->ukuran,
                'warna'        => $prod->warna,
                'harga_satuan' => $harga,
                'jumlah'       => $qty,
                'sub_total'    => $sub,
            ];
        }

        $uangBayar = (float)$data['uang_bayar'];
        if ($uangBayar < $grand) {
            return back()->withErrors('Uang bayar kurang dari total.')->withInput();
        }
        $kembalian = $uangBayar - $grand;

        // ✅ simpan transaksi + detail + stok (WIB ikut config app)
        $trx = DB::transaction(function () use ($r, $detailRows, $grand, $uangBayar, $kembalian) {

            $trx = Transaksi::create([
                'id_kasir'    => auth()->user()->id_kasir ?? 1,
                'tanggal'     => Carbon::parse($r->input('tanggal'))->toDateString(),
                'uang_bayar'  => $uangBayar,
                'kembalian'   => $kembalian,
                'total_harga' => $grand,
            ]);

            $now = now(); // ✅ now() pakai Asia/Jakarta kalau config app sudah bener

            $rows = array_map(function ($x) use ($trx, $now) {
                return [
                    'id_transaksi' => $trx->id_transaksi,
                    'id_produk'    => $x['id_produk'],
                    'nama_produk'  => $x['nama_produk'],
                    'ukuran'       => $x['ukuran'],
                    'warna'        => $x['warna'],
                    'harga_satuan' => $x['harga_satuan'],
                    'jumlah'       => $x['jumlah'],
                    'sub_total'    => $x['sub_total'],
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }, $detailRows);

            if (!empty($rows)) {
                DB::table('detail_transaksis')->insert($rows);
            }

            foreach ($detailRows as $x) {
                DB::table('produks')
                    ->where('id_produk', $x['id_produk'])
                    ->decrement('stok', $x['jumlah']);
            }

            return $trx;
        });

        // ✅ setelah transaksi langsung ke struk
        return redirect()
            ->route('transaksi.struk', $trx->id_transaksi)
            ->with('ok', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaksi $transaksi)
    {
        $displayNo = $this->getDisplayNoAsc($transaksi->id_transaksi);

        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)
            ->orderBy('id_detail_transaksi')
            ->get();

        return view('transaksi.show', compact('transaksi', 'detail', 'displayNo'));
    }

    public function struk(Transaksi $transaksi)
    {
        $displayNo = $this->getDisplayNoAsc($transaksi->id_transaksi);

        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)
            ->orderBy('id_detail_transaksi')
            ->get();

        return view('transaksi.struk', compact('transaksi', 'detail', 'displayNo'));
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('ok', 'Transaksi dihapus');
    }

    private function getDisplayNoAsc(int $idTransaksi): int
    {
        return Transaksi::where('id_transaksi', '<=', $idTransaksi)->count();
    }
}
