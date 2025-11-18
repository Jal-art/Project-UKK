{{-- resources/views/transaksi/show.blade.php --}}
@extends('layouts.cashify')
@section('title','Detail Transaksi | Cashify')

@section('content')
  <div class="show-head">
    <h2 class="ttl">Detail Transaksi</h2>
    <div class="head-actions">
      <a href="{{ route('transaksi.index') }}" class="btn btn-gray">Kembali</a>
      <a href="{{ route('transaksi.struk', $transaksi) }}" class="btn btn-purple">Lihat Struk</a>
    </div>
  </div>

  @php
    $kode = $transaksi->kode ?? ('TRX-'.$transaksi->id_transaksi);
  @endphp

  {{-- Ringkasan --}}
  <div class="panel-summary">
    <div class="card">
      <div class="card-label">No Urut</div>
      <div class="card-value big">{{ $displayNo }}</div>
    </div>
    <div class="card">
      <div class="card-label">Kode Transaksi</div>
      <div class="card-value big">{{ $kode }}</div>
    </div>
    <div class="card">
      <div class="card-label">Tanggal</div>
      <div class="card-value">
        {{ \Illuminate\Support\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
      </div>
    </div>
    <div class="card">
      <div class="card-label">Total</div>
      <div class="card-value big accent">
        Rp {{ number_format($transaksi->total_harga,0,',','.') }}
      </div>
    </div>
  </div>

  {{-- Tabel detail item --}}
  <div class="table-shell">
    <table class="trx-table-detail">
      <thead>
        <tr>
          <th class="col-name">Produk</th>
          <th class="col-num">Harga</th>
          <th class="col-num">Qty</th>
          <th class="col-num">Subtotal</th>
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
            <td class="cell-name">
              {{ $nama }}
              @if($ukuran) • {{ $ukuran }} @endif
              @if($warna)  • {{ $warna }} @endif
            </td>
            <td class="num">Rp {{ number_format($harga,0,',','.') }}</td>
            <td class="num">{{ $qty }}</td>
            <td class="num">Rp {{ number_format($sub,0,',','.') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="empty">Tidak ada item.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <style>
    .show-head{
      display:flex;align-items:center;justify-content:space-between;
      gap:10px;flex-wrap:wrap;margin-bottom:10px;
    }
    .ttl{
      margin:0;font-weight:600;color:#111;
      opacity:0;transform:translateY(-10px);
      animation:titleIn .6s cubic-bezier(.18,.89,.32,1.28) .05s forwards;
    }

    .head-actions{
      display:flex;gap:8px;flex-wrap:wrap;
      opacity:0;transform:translateY(8px);animation:itemIn .45s ease-out .16s forwards;
    }

    .panel-summary{
      background:#f3f4f6;border:1px solid #d1d5db;border-radius:14px;
      padding:12px;display:grid;grid-template-columns:repeat(4,minmax(150px,1fr));gap:10px;
      box-shadow:0 3px 0 rgba(0,0,0,.08), 0 12px 22px rgba(0,0,0,.08);
      margin-bottom:10px;
      opacity:0;transform:translateY(18px) scale(.97);
      animation:cardIn .7s cubic-bezier(.18,.89,.32,1.28) .12s forwards;
    }
    .card{
      background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px;
    }
    .card-label{
      color:#6b7280;font-size:12.5px;margin-bottom:4px;
    }
    .card-value{
      font-weight:700;color:#111;font-size:14px;
    }
    .card-value.big{
      font-size:18px;font-weight:800;
    }
    .card-value.accent{
      color:#6d5cff;
    }

    .table-shell{
      overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb;
      box-shadow:0 1px 2px rgba(0,0,0,.05), 0 8px 18px rgba(0,0,0,.08);
      opacity:0;transform:translateY(10px);animation:itemIn .45s ease-out .22s forwards;
    }

    .trx-table-detail{
      width:100%;border-collapse:separate;border-spacing:0;
    }

    /* HEADER: kolom produk kiri, angka kanan, tinggi sejajar */
    .trx-table-detail thead th{
      background:#f3f4f6;font-weight:600;
      padding:11px 12px;border-bottom:1px solid #e5e7eb;font-size:14px;
      vertical-align:middle;
      white-space:nowrap;
    }
    .trx-table-detail thead th.col-name{
      text-align:left;
    }
    .trx-table-detail thead th.col-num{
      text-align:right;
    }

    /* BODY: baris sejajar, teks produk kiri, angka kanan */
    .trx-table-detail tbody td{
      padding:10px 12px;border-bottom:1px solid #f0f2f5;font-size:14px;
      vertical-align:middle;
    }
    .trx-table-detail tbody tr:hover td{
      background:#fafafa;
    }

    .col-name{
      min-width:200px;
    }
    .col-num{
      width:140px;
    }

    .cell-name{
      text-align:left;
    }
    .num{
      text-align:right;font-weight:700;color:#111;
    }
    .empty{
      text-align:center;color:#6b7280;padding:12px;
    }

    .btn{
      border:none;cursor:pointer;padding:9px 14px;border-radius:999px;
      font-weight:700;color:#fff;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
      box-shadow:0 6px 0 rgba(0,0,0,.22), 0 12px 18px rgba(0,0,0,.14);
      transition:transform .06s ease, box-shadow .12s ease, opacity .2s ease;
      font-size:13px;
    }
    .btn:hover{opacity:.96}
    .btn:active{transform:translateY(2px);box-shadow:0 4px 0 rgba(0,0,0,.20), 0 10px 16px rgba(0,0,0,.12)}
    .btn-gray{background:#6b7280}
    .btn-purple{background:#6d5cff}

    @media(max-width:768px){
      .panel-summary{grid-template-columns:repeat(2,minmax(150px,1fr))}
    }
    @media(max-width:520px){
      .panel-summary{grid-template-columns:1fr}
      .head-actions{width:100%}
      .head-actions .btn{flex:1;justify-content:center}
    }

    @keyframes titleIn{
      0%{opacity:0;transform:translateY(-12px)}
      100%{opacity:1;transform:translateY(0)}
    }
    @keyframes cardIn{
      0%{opacity:0;transform:translateY(22px) scale(.96)}
      60%{opacity:1;transform:translateY(-2px) scale(1.01)}
      100%{opacity:1;transform:translateY(0) scale(1)}
    }
    @keyframes itemIn{
      0%{opacity:0;transform:translateY(10px)}
      100%{opacity:1;transform:translateY(0)}
    }
  </style>
@endsection
