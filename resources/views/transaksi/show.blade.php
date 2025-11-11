@extends('layouts.cashify')
@section('title','Struk Transaksi | Cashify')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:10px;flex-wrap:wrap">
    <h2 style="margin:0;font-weight:600;color:#111">Struk Transaksi #{{ $trx->id_transaksi }}</h2>
    <div style="display:flex;gap:8px">
      <a href="{{ route('transaksi.index') }}" class="btn" style="background:#6b7280;color:#fff;text-decoration:none;border:none;border-radius:10px;padding:9px 14px;font-weight:700">Kembali</a>
      <button onclick="window.print()" class="btn" style="background:#6d5cff;color:#fff;border:none;border-radius:10px;padding:9px 14px;font-weight:700">Cetak Struk</button>
    </div>
  </div>

  <div class="panel" id="printArea" style="max-width:720px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 1px 2px rgba(0,0,0,.05), 0 8px 18px rgba(0,0,0,.08);padding:16px">
    <div style="text-align:center;margin-bottom:10px">
      <div style="font-weight:800;font-size:18px;color:#111">TOKO BAJU</div>
      <div style="color:#6b7280;font-size:12.5px">Jl. Contoh No. 123 — Telp 08xx</div>
    </div>

    <div style="display:flex;justify-content:space-between;font-size:13px;color:#374151;margin-bottom:8px">
      <div>Tanggal: <b>{{ \Illuminate\Support\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</b></div>
      <div>ID: <b>{{ $trx->id_transaksi }}</b></div>
    </div>

    <div class="table-shell" style="overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb">
      <table style="width:100%;border-collapse:separate;border-spacing:0">
        <thead>
          <tr>
            <th style="background:#f3f4f6;text-align:left;font-weight:600;padding:8px 10px;border-bottom:1px solid #e5e7eb">Item</th>
            <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:8px 10px;border-bottom:1px solid #e5e7eb;width:120px">Harga</th>
            <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:8px 10px;border-bottom:1px solid #e5e7eb;width:80px">Qty</th>
            <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:8px 10px;border-bottom:1px solid #e5e7eb;width:140px">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @if(count($items))
            @foreach($items as $it)
              <tr>
                <td style="padding:8px 10px;border-bottom:1px solid #f0f2f5">{{ $it['nama'] }}</td>
                <td style="padding:8px 10px;border-bottom:1px solid #f0f2f5;text-align:right">Rp {{ number_format($it['harga'],0,',','.') }}</td>
                <td style="padding:8px 10px;border-bottom:1px solid #f0f2f5;text-align:right">{{ $it['qty'] }}</td>
                <td style="padding:8px 10px;border-bottom:1px solid #f0f2f5;text-align:right">Rp {{ number_format($it['sub'],0,',','.') }}</td>
              </tr>
            @endforeach
          @else
            {{-- Jika masuk dari index (tanpa flash items), tampilkan ringkasan saja --}}
            <tr><td colspan="4" style="padding:10px;text-align:center;color:#6b7280">Detail item tidak tersedia.</td></tr>
          @endif
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" style="text-align:right;padding:8px 10px;border-top:1px solid #eef2f7">Total</th>
            <th style="text-align:right;padding:8px 10px;border-top:1px solid #eef2f7">Rp {{ number_format($trx->total_harga,0,',','.') }}</th>
          </tr>
          <tr>
            <th colspan="3" style="text-align:right;padding:8px 10px">Bayar</th>
            <th style="text-align:right;padding:8px 10px">Rp {{ number_format($trx->uang_bayar,0,',','.') }}</th>
          </tr>
          <tr>
            <th colspan="3" style="text-align:right;padding:8px 10px">Kembalian</th>
            <th style="text-align:right;padding:8px 10px">Rp {{ number_format($trx->kembalian,0,',','.') }}</th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div style="text-align:center;margin-top:10px;color:#6b7280;font-size:12.5px">
      Terima kasih telah berbelanja.
    </div>
  </div>

  <style>
    @media print {
      body * { visibility: hidden; }
      #printArea, #printArea * { visibility: visible; }
      #printArea { position: absolute; left: 0; top: 0; width: 100%; }
      .topbar, .sidebar { display:none !important; }
    }
  </style>
@endsection
