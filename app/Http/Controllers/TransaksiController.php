<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = $req->query('tanggal');

        $q = Transaksi::query()
            ->when($tanggal, fn($s) =>
                $s->whereDate('tanggal', Carbon::parse($tanggal)->toDateString())
            )
            ->orderByDesc('id_transaksi');

        $items = $q->paginate(10);

        return view('transaksi.index', compact('items', 'tanggal'));
    }

    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('transaksi.create', compact('produks'));
    }

    public function store(Request $r)
    {
        // Ambil & validasi input dasar
        $data = $r->validate([
            'tanggal' => 'required|date',
            // items dikirim dari JS sebagai: items[produk_id][produk_id], items[produk_id][qty]
            'items'   => 'required|array|min:1',
            'items.*.produk_id' => 'required|integer|exists:produks,id_produk',
            'items.*.qty'       => 'required|integer|min:1',
            'uang_bayar'        => 'required|numeric|min:0',
        ]);

        // Ambil detail produk yang dipilih
        $map = collect($data['items']);
        $prodIds = $map->pluck('produk_id')->all();

        $produkList = Produk::whereIn('id_produk', $prodIds)->get()->keyBy('id_produk');

        // Hitung total & cek stok server-side
        $items = [];
        $grand = 0;

        foreach ($map as $row) {
            $pid = (int)$row['produk_id'];
            $qty = (int)$row['qty'];

            $prod = $produkList[$pid] ?? null;
            if (!$prod) abort(422, 'Produk tidak ditemukan.');

            if ($qty > (int)$prod->stok) {
                return back()->withErrors("Qty untuk {$prod->nama_produk} melebihi stok ({$prod->stok}).")->withInput();
            }

            $harga = (int)$prod->harga;
            $sub   = $harga * $qty;
            $grand += $sub;

            $items[] = [
                'id_produk' => $pid,
                'nama'      => trim($prod->nama_produk . ($prod->ukuran ? ' • '.$prod->ukuran : '') . ($prod->warna ? ' • '.$prod->warna : '')),
                'harga'     => $harga,
                'qty'       => $qty,
                'subtotal'  => $sub,
            ];
        }

        // Validasi uang bayar >= total
        $uangBayar  = (float)$data['uang_bayar'];
        if ($uangBayar < $grand) {
            return back()->withErrors('Uang bayar kurang dari total.')->withInput();
        }
        $kembalian = $uangBayar - $grand;

        // Simpan transaksi + kurangi stok dalam transaksi DB
        DB::transaction(function () use ($r, $items, $grand, $uangBayar, $kembalian) {
            $trx = Transaksi::create([
                'id_kasir'     => auth()->user()->id_kasir ?? 1, // fallback 1 kalau auth tidak punya id_kasir
                'tanggal'      => Carbon::parse($r->input('tanggal'))->toDateString(),
                'total_harga'  => $grand,
                'uang_bayar'   => $uangBayar,
                'kembalian'    => $kembalian,
            ]);

            // Kurangi stok
            foreach ($items as $it) {
                Produk::where('id_produk', $it['id_produk'])->decrement('stok', $it['qty']);
            }

            // Siapkan data struk (karena tidak ada tabel detail)
            session()->put('receipt', [
                'transaksi_id' => $trx->id_transaksi,
                'kode'         => $trx->kode,
                'tanggal'      => Carbon::parse($trx->tanggal)->format('d/m/Y'),
                'kasir'        => (auth()->user()->nama_kasir ?? 'Kasir'),
                'items'        => $items,
                'total'        => $grand,
                'bayar'        => $uangBayar,
                'kembalian'    => $kembalian,
                'created_at'   => now()->format('d/m/Y H:i'),
            ]);
        });

        // Ambil transaksi terakhir dari receipt
        $rid = session('receipt.transaksi_id');

        return redirect()->route('transaksi.struk', $rid)
            ->with('ok', 'Pembayaran berhasil. Struk siap dicetak.');
    }

    public function show(Transaksi $transaksi)
    {
        // Detail sesuai migration: tanggal, total_harga, uang_bayar, kembalian (+ kasir)
        return view('transaksi.show', compact('transaksi'));
    }

    public function struk(Transaksi $transaksi)
    {
        // Ambil struk dari session (jika setelah bayar). Jika kosong, tampilkan header basic dari transaksi.
        $receipt = session('receipt');

        // Safety: pastikan receipt cocok dengan ID current transaksi
        if (!$receipt || (int)($receipt['transaksi_id'] ?? 0) !== (int)$transaksi->id_transaksi) {
            $receipt = [
                'transaksi_id' => $transaksi->id_transaksi,
                'kode'         => $transaksi->kode,
                'tanggal'      => $transaksi->tanggal?->format('d/m/Y'),
                'kasir'        => (auth()->user()->nama_kasir ?? 'Kasir'),
                'items'        => [], // tidak ada detail tersimpan
                'total'        => (float)$transaksi->total_harga,
                'bayar'        => (float)$transaksi->uang_bayar,
                'kembalian'    => (float)$transaksi->kembalian,
                'created_at'   => $transaksi->created_at?->format('d/m/Y H:i'),
            ];
        }

        return view('transaksi.struk', compact('transaksi', 'receipt'));
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('ok', 'Transaksi dihapus');
    }
}
