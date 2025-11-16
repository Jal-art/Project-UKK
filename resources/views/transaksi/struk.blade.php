{{-- resources/views/transaksi/struk.blade.php --}}
@extends('layouts.cashify')
@section('title','Struk Transaksi | Cashify')

@section('content')
  @php
    $kode   = $transaksi->kode ?? ('TRX-'.$transaksi->id_transaksi);
    $waktu  = $transaksi->created_at ?? $transaksi->tanggal;
  @endphp

  {{-- Header (disembunyikan saat print) --}}
  <div class="screen-toolbar no-print">
    <h2 class="ttl">Struk #{{ $displayNo }} ({{ $kode }})</h2>
    <div class="btns">
      <a href="{{ route('transaksi.index') }}" class="btn btn-gray">Kembali</a>
      <button type="button" onclick="window.print()" class="btn btn-purple">Cetak</button>
    </div>
  </div>

  {{-- Area struk yang diprint --}}
  <div id="printArea" class="ticket">
    {{-- Header toko --}}
    <div class="center bold up">CASHIFY</div>
    <div class="center small muted">
      {{ config('app.store_address','Jl. Contoh No. 123 - Kota') }}<br>
      Telp. {{ config('app.store_phone','08xx-xxxx-xxxx') }}
    </div>

    {{-- Tanggal & jam transaksi --}}
    <div class="center small" style="margin-top:6px">
      {{ \Carbon\Carbon::parse($waktu)->locale('id')->translatedFormat('l, d/m/Y H:i') }}
    </div>

    <div class="sep"></div>

    {{-- Daftar item --}}
    @forelse($detail as $d)
      @php
        $nama   = $d->nama_produk ?? '(tanpa nama)';
        $ukuran = $d->ukuran ?? null;
        $warna  = $d->warna ?? null;
        $harga  = (int)($d->harga_satuan ?? 0);
        $qty    = (int)($d->jumlah ?? 0);
        $sub    = (int)($d->sub_total ?? 0);
      @endphp
      <div class="row">
        <div class="name">
          {{ $nama }}@if($ukuran) • {{ $ukuran }}@endif @if($warna) • {{ $warna }}@endif
        </div>
        <div class="amt right">Rp {{ number_format($sub,0,',','.') }}</div>
      </div>
      <div class="subrow">
        Rp {{ number_format($harga,0,',','.') }}
        <span class="xqty">x {{ $qty }}</span>
      </div>
    @empty
      <div class="center muted">Detail item tidak tersedia.</div>
    @endforelse

    <div class="sep dotted"></div>

    {{-- Ringkasan pembayaran --}}
    <div class="row total">
      <div class="name">Total Belanja</div>
      <div class="amt right">Rp {{ number_format($transaksi->total_harga,0,',','.') }}</div>
    </div>
    <div class="row">
      <div class="name">Tunai</div>
      <div class="amt right">Rp {{ number_format($transaksi->uang_bayar,0,',','.') }}</div>
    </div>
    <div class="row">
      <div class="name">Kembali</div>
      <div class="amt right">Rp {{ number_format($transaksi->kembalian,0,',','.') }}</div>
    </div>

    <div class="sep"></div>

    {{-- Footer --}}
    <div class="center small" style="margin-top:6px">Terima kasih</div>
    <div class="center small muted">
      kasir: {{ auth()->user()->nama_kasir ?? auth()->user()->name ?? 'Kasir' }}<br>
      No. {{ $kode }}
    </div>
  </div>

  <style>
    .screen-toolbar{
      display:flex;align-items:center;justify-content:space-between;
      gap:10px;margin-bottom:12px;flex-wrap:wrap;
    }
    .ttl{
      margin:0;font-weight:600;color:#111;
      opacity:0;transform:translateY(-10px);
      animation:titleIn .6s cubic-bezier(.18,.89,.32,1.28) .05s forwards;
    }
    .btns{
      display:flex;gap:8px;flex-wrap:wrap;
      opacity:0;transform:translateY(8px);animation:itemIn .45s ease-out .16s forwards;
    }

    .btn{
      border:none;border-radius:999px;padding:9px 14px;font-weight:700;cursor:pointer;
      text-decoration:none;color:#fff;display:inline-flex;align-items:center;justify-content:center;
      box-shadow:0 6px 0 rgba(0,0,0,.22), 0 12px 18px rgba(0,0,0,.14);
      transition:transform .06s, box-shadow .12s, opacity .2s;
      font-size:13px;
    }
    .btn:hover{opacity:.96}
    .btn:active{transform:translateY(2px);box-shadow:0 4px 0 rgba(0,0,0,.20), 0 10px 16px rgba(0,0,0,.12)}
    .btn-gray{background:#6b7280}
    .btn-purple{background:#6d5cff}

    .ticket{
      width: 302px;margin: 0 auto;padding: 8px 10px 12px;
      background:#fff;color:#111;
      border:1px solid #e5e7eb;border-radius:8px;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Courier New", monospace;
      font-size: 12px; line-height: 1.35;
      word-break: break-word;
      box-shadow:0 3px 10px rgba(0,0,0,.12);
      animation:cardIn .5s ease-out .1s forwards;
      opacity:0;transform:translateY(10px) scale(.98);
    }
    .center{text-align:center} .right{text-align:right}
    .bold{font-weight:700} .up{text-transform:uppercase}
    .small{font-size:11px} .muted{color:#6b7280}
    .sep{border-top:1px solid #e5e7eb;margin:8px 0}
    .sep.dotted{border-top:1px dashed #c7cbd1}
    .row{display:flex;align-items:flex-start;justify-content:space-between;gap:8px}
    .row .name{flex:1}
    .row .amt{min-width:100px;text-align:right}
    .subrow{
      display:flex;justify-content:space-between;padding-left:10px;color:#6b7280;
      margin-top:-2px;margin-bottom:4px;
    }
    .subrow .xqty{margin-left:auto}
    .total .name,.total .amt{font-weight:700}

    @media print{
      @page{ margin: 4mm }
      body *{ visibility: hidden; }
      #printArea, #printArea *{ visibility: visible; }
      #printArea{ position:absolute; left:0; top:0; }
      .no-print{ display:none !important; }
      .ticket{ border:none;border-radius:0;width:58mm;padding:0;box-shadow:none; }
      .sep{ margin:6px 0 }
    }

    @keyframes titleIn{
      0%{opacity:0;transform:translateY(-12px)}
      100%{opacity:1;transform:translateY(0)}
    }
    @keyframes itemIn{
      0%{opacity:0;transform:translateY(10px)}
      100%{opacity:1;transform:translateY(0)}
    }
    @keyframes cardIn{
      0%{opacity:0;transform:translateY(16px) scale(.96)}
      100%{opacity:1;transform:translateY(0) scale(1)}
    }
  </style>
@endsection
