@extends('layouts.cashify')
@section('title','Detail Transaksi | Cashify')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:10px;flex-wrap:wrap">
    <h2 style="margin:0;font-weight:600;color:#111">Detail Transaksi</h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <a href="{{ route('transaksi.index') }}" class="btn btn-gray">Kembali</a>
      <a href="{{ route('transaksi.struk', $transaksi) }}" class="btn btn-purple">Lihat Struk</a>
    </div>
  </div>

  <div class="panel" style="margin-bottom:10px;display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:10px">
    <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px">
      <div style="color:#6b7280;font-size:12.5px">No Urut</div>
      <div style="font-size:18px;font-weight:800;color:#111">{{ $displayNo }}</div>
    </div>
    <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px">
      <div style="color:#6b7280;font-size:12.5px">ID (DB)</div>
      <div style="font-size:18px;font-weight:800;color:#111">TRX-{{ $transaksi->id_transaksi }}</div>
    </div>
    <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px">
      <div style="color:#6b7280;font-size:12.5px">Tanggal</div>
      <div style="font-weight:700;color:#111">
        {{ \Illuminate\Support\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
      </div>
    </div>
    <div class="card" style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px">
      <div style="color:#6b7280;font-size:12.5px">Total</div>
      <div style="font-size:18px;font-weight:900;color:#6d5cff">
        Rp {{ number_format($transaksi->total_harga,0,',','.') }}
      </div>
    </div>
  </div>

  <div class="table-shell" style="overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb">
    <table class="trx-table" style="width:100%;border-collapse:separate;border-spacing:0">
      <thead>
        <tr>
          <th style="background:#f3f4f6;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb">
            Produk
          </th>
          <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:140px">
            Harga
          </th>
          <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:100px">
            Qty
          </th>
          <th style="background:#f3f4f6;text-align:right;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">
            Subtotal
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse($detail as $d)
          @php
            $nama   = $d->nama_produk ?? '(tanpa nama)';
            $ukuran = $d->ukuran ?? null;
            $warna  = $d->warna  ?? null;
            $harga  = (int)($d->harga_satuan ?? 0);
            $qty    = (int)($d->jumlah ?? 0);
            $sub    = (int)($d->sub_total ?? 0);
          @endphp
          <tr>
            <td style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
              {{ $nama }}
              @if($ukuran) • {{ $ukuran }} @endif
              @if($warna)  • {{ $warna }} @endif
            </td>
            <td class="num" style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
              Rp {{ number_format($harga,0,',','.') }}
            </td>
            <td class="num" style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
              {{ $qty }}
            </td>
            <td class="num" style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
              Rp {{ number_format($sub,0,',','.') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="text-align:center;color:#6b7280;padding:12px">
              Tidak ada item.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <style>
    .btn{
      border:none; cursor:pointer; padding:9px 14px; border-radius:10px;
      font-weight:700; color:#fff; text-decoration:none; display:inline-block;
      box-shadow:0 6px 0 rgba(0,0,0,.22), 0 12px 18px rgba(0,0,0,.14);
      transition:transform .06s ease, box-shadow .12s ease, opacity .2s ease;
    }
    .btn:hover{opacity:.96}
    .btn:active{transform:translateY(2px); box-shadow:0 4px 0 rgba(0,0,0,.20), 0 10px 16px rgba(0,0,0,.12)}
    .btn-gray{background:#6b7280}
    .btn-purple{background:#6d5cff}
    .num{text-align:right;font-weight:700;color:#111}
  </style>
@endsection
