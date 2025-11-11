@extends('layouts.cashify')
@section('title','Struk | Cashify')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
    <h2 style="margin:0;font-weight:600;color:#111">Struk Pembayaran</h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <a href="{{ route('transaksi.index') }}" class="btn-add" style="background:#374151"><span class="plus">←</span> Kembali</a>
      <button onclick="window.print()" class="btn-add"><span class="plus">🖨</span> Cetak</button>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert ok">{{ session('ok') }}</div>
  @endif

  <div class="paper" id="receipt">
    <div class="r-head">
      <div class="brand">Cashify Store</div>
      <div class="meta">
        <div>Kode: <b>{{ $receipt['kode'] ?? $transaksi->kode }}</b></div>
        <div>Tanggal: <b>{{ $receipt['created_at'] ?? $transaksi->created_at?->format('d/m/Y H:i') }}</b></div>
        <div>Kasir: <b>{{ $receipt['kasir'] ?? (auth()->user()->nama_kasir ?? 'Kasir') }}</b></div>
      </div>
    </div>

    <div class="r-body">
      <table>
        <thead>
          <tr><th>Item</th><th class="num">Qty</th><th class="num">Harga</th><th class="num">Subtotal</th></tr>
        </thead>
        <tbody>
          @forelse(($receipt['items'] ?? []) as $it)
            <tr>
              <td>{{ $it['nama'] }}</td>
              <td class="num">{{ $it['qty'] }}</td>
              <td class="num">Rp {{ number_format($it['harga'],0,',','.') }}</td>
              <td class="num">Rp {{ number_format($it['subtotal'],0,',','.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="muted">Detail item tidak tersedia.</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr><th colspan="3" class="num">Total</th><th class="num">Rp {{ number_format(($receipt['total'] ?? $transaksi->total_harga),0,',','.') }}</th></tr>
          <tr><th colspan="3" class="num">Bayar</th><th class="num">Rp {{ number_format(($receipt['bayar'] ?? $transaksi->uang_bayar),0,',','.') }}</th></tr>
          <tr><th colspan="3" class="num">Kembalian</th><th class="num">Rp {{ number_format(($receipt['kembalian'] ?? $transaksi->kembalian),0,',','.') }}</th></tr>
        </tfoot>
      </table>
    </div>

    <div class="r-foot">
      Terima kasih telah berbelanja 🙏
    </div>
  </div>

  <style>
    .alert.ok{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;margin-bottom:10px;padding:10px 12px;border-radius:10px}
    .btn-add{
      display:inline-flex;align-items:center;gap:8px;background:#6d5cff;color:#fff;text-decoration:none;
      padding:9px 14px;border-radius:999px;font-weight:600;
      box-shadow:0 6px 14px rgba(109,92,255,.24), 0 2px 0 rgba(0,0,0,.08) inset;
    }
    .plus{display:grid;place-items:center;width:20px;height:20px;border-radius:999px;background:rgba(255,255,255,.2);font-weight:800;line-height:1}

    .paper{
      max-width:720px;margin:auto;background:#fff;border:1px solid #e5e7eb;border-radius:16px;
      box-shadow:0 1px 2px rgba(0,0,0,.05),0 10px 24px rgba(0,0,0,.12);
      overflow:hidden
    }
    .r-head{padding:16px 16px 0}
    .brand{font-size:18px;font-weight:700;color:#111}
    .meta{display:flex;gap:16px;flex-wrap:wrap;color:#374151;font-size:13px;margin-top:6px}
    .r-body{padding:10px 16px 16px}
    table{width:100%;border-collapse:separate;border-spacing:0}
    thead th{background:#f3f4f6;text-align:left;font-weight:600;color:#111;padding:10px;border-bottom:1px solid #e5e7eb}
    tbody td{padding:10px;border-bottom:1px solid #f5f6f8}
    tfoot th{padding:10px;border-top:1px solid #e5e7eb;background:#fafafa}
    .num{text-align:right}
    .muted{text-align:center;color:#6b7280}
    .r-foot{padding:12px 16px;background:#f9fafb;border-top:1px solid #eef2f7;text-align:center;color:#374151;font-weight:600}

    /* Print */
    @media print{
      body{background:#fff}
      .layout, .topbar, .footer{display:none !important}
      .page{padding:0}
      .paper{box-shadow:none;border:none;border-radius:0;max-width:100%}
      a, button{display:none !important}
    }
  </style>
@endsection
