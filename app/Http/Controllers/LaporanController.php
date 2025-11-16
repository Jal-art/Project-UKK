<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // ambil input periode
        $from = $request->query('from');
        $to   = $request->query('to');

        // JIKA user belum pilih apa-apa → pakai HARI INI utk from & to
        if (!$from && !$to) {
            $fromDate = $toDate = Carbon::today()->toDateString();
        } else {
            // normalisasi ke tanggal (Y-m-d) atau null
            $fromDate = $from ? Carbon::parse($from)->toDateString() : null;
            $toDate   = $to   ? Carbon::parse($to)->toDateString()   : null;
        }

        // query gabungan transaksis + detail_transaksis
        $q = DB::table('transaksis as t')
            ->leftJoin('detail_transaksis as d', 'd.id_transaksi', '=', 't.id_transaksi')
            ->selectRaw('
                DATE(t.tanggal) as tanggal,
                COUNT(DISTINCT t.id_transaksi) as trx_count,
                COALESCE(SUM(d.jumlah), 0) as qty_sum,
                COALESCE(SUM(t.total_harga), 0) as total_sum
            ')
            ->groupBy(DB::raw('DATE(t.tanggal)'))
            ->orderBy('tanggal', 'asc');

        if ($fromDate) {
            $q->whereDate('t.tanggal', '>=', $fromDate);
        }
        if ($toDate) {
            $q->whereDate('t.tanggal', '<=', $toDate);
        }

        $rows = $q->get();

        // total keseluruhan
        $grand = [
            'trx_count' => (int) $rows->sum('trx_count'),
            'qty_sum'   => (int) $rows->sum('qty_sum'),
            'total_sum' => (int) $rows->sum('total_sum'),
        ];

        return view('laporan.index', [
            'from'  => $fromDate,
            'to'    => $toDate,
            'rows'  => $rows,
            'grand' => $grand,
        ]);
    }
}
