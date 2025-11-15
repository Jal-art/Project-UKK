{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.cashify')
@section('title','Laporan | Cashify')

@section('content')
@php
  use Illuminate\Support\Carbon;

  // fallback kalau controller belum ngirim, biar aman
  $from  = $from  ?? request('from');
  $to    = $to    ?? request('to');
  $rows  = $rows  ?? collect();
  $grand = $grand ?? [
      'trx_count' => (int)$rows->sum('trx_count'),
      'qty_sum'   => (int)$rows->sum('qty_sum'),
      'total_sum' => (int)$rows->sum('total_sum'),
  ];

  $fromLabel = $from ? Carbon::parse($from)->format('d/m/Y') : 'Semua';
  $toLabel   = $to   ? Carbon::parse($to)->format('d/m/Y')   : 'Semua';
@endphp

<div class="lap-head">
  <h2>Laporan Penjualan</h2>
</div>

<div class="lap-panel">
  {{-- Header + filter --}}
  <div class="lap-panel-header">
    <span class="lap-pill">Periode</span>

    <form class="lap-filter" action="{{ route('laporan.index') }}" method="GET" autocomplete="off">
      <div class="lap-filter-group">
        <label for="from">Dari</label>
        <input id="from" type="date" name="from" value="{{ $from }}">
      </div>
      <div class="lap-filter-group">
        <label for="to">Sampai</label>
        <input id="to" type="date" name="to" value="{{ $to }}">
      </div>
      <div class="lap-filter-actions">
        <button type="submit" class="btn-main">Terapkan</button>
        <a href="{{ route('laporan.index') }}" class="btn-ghost-small">Reset</a>
      </div>
    </form>
  </div>

  {{-- Ringkasan kecil --}}
  <div class="lap-period-summary">
    <span>
      Periode:
      <strong>{{ $fromLabel }}</strong> s/d <strong>{{ $toLabel }}</strong>
    </span>

    <div class="lap-mini-stats">
      <span>Transaksi: <strong>{{ number_format((int)$grand['trx_count'],0,',','.') }}</strong></span>
      <span>Item terjual: <strong>{{ number_format((int)$grand['qty_sum'],0,',','.') }}</strong></span>
      <span>Omzet: <strong>Rp {{ number_format((int)$grand['total_sum'],0,',','.') }}</strong></span>
    </div>
  </div>

  {{-- Tabel --}}
  <div class="lap-table-wrap">
    <table class="lap-table">
      <thead>
        <tr>
          <th style="width: 180px;">Tanggal</th>
          <th style="width: 160px;">Jumlah Transaksi</th>
          <th style="width: 160px;">Jumlah Penjualan (Qty)</th>
          <th class="num" style="width: 200px;">Total Transaksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ Carbon::parse($r->tanggal)->format('Y-m-d') }}</td>
            <td class="num">{{ (int)($r->trx_count ?? 0) }}</td>
            <td class="num">{{ (int)($r->qty_sum   ?? 0) }}</td>
            <td class="num">Rp {{ number_format((int)($r->total_sum ?? 0),0,',','.') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="lap-empty">
              Belum ada transaksi pada periode ini.
            </td>
          </tr>
        @endforelse

        @if($rows->count() > 0)
          <tr class="lap-total-row">
            <td class="lap-total-label" colspan="3">
              Jumlah keseluruhan
            </td>
            <td class="num">
              Rp {{ number_format((int)$grand['total_sum'],0,',','.') }}
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<style>
  .lap-head{
    margin-bottom:14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
  }
  .lap-head h2{
    margin:0;
    font-weight:600;
    font-size:20px;
    color:#111827;
  }

  .lap-panel{
    background:#f3f4f6;
    border-radius:14px;
    padding:14px;
    box-shadow:0 10px 25px rgba(15,23,42,.08);
    border:1px solid #e5e7eb;
  }

  .lap-panel-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:14px;
    margin-bottom:10px;
    flex-wrap:wrap;
  }

  .lap-pill{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 16px;
    border-radius:999px;
    background:#f97373;
    color:#fff;
    font-weight:600;
    font-size:13px;
    box-shadow:0 6px 12px rgba(248,113,113,.45);
  }

  .lap-filter{
    display:flex;
    gap:10px;
    align-items:flex-end;
    flex-wrap:wrap;
  }
  .lap-filter-group{
    display:flex;
    flex-direction:column;
    gap:6px;
    font-size:13px;
    color:#4b5563;
  }
  .lap-filter-group input[type="date"]{
    padding:7px 10px;
    border-radius:9px;
    border:1px solid #d1d5db;
    background:#fff;
    font-family:inherit;
    font-size:13px;
    min-width:145px;
  }
  .lap-filter-actions{
    display:flex;
    gap:8px;
    align-items:flex-end;
  }
  .btn-main{
    border:none;
    border-radius:999px;
    padding:8px 14px;
    background:#6d5cff;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    font-size:13px;
    box-shadow:0 8px 18px rgba(109,92,255,.45);
  }
  .btn-main:hover{opacity:.96}
  .btn-main:active{
    transform:translateY(1px);
    box-shadow:0 5px 12px rgba(109,92,255,.35);
  }

  .btn-ghost-small{
    border-radius:999px;
    padding:7px 12px;
    border:1px solid #e5e7eb;
    background:#fff;
    color:#111827;
    text-decoration:none;
    font-weight:500;
    font-size:13px;
  }

  .lap-period-summary{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    font-size:13px;
    color:#4b5563;
    padding:8px 10px;
    border-radius:10px;
    background:#e5e7eb;
    margin-bottom:10px;
  }
  .lap-mini-stats{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  .lap-table-wrap{
    overflow:auto;
    border-radius:12px;
    background:#fff;
    border:1px solid #e5e7eb;
  }

  .lap-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
  }
  .lap-table thead th{
    background:#f9fafb;
    padding:11px 12px;
    font-size:13px;
    font-weight:600;
    color:#111827;
    border-bottom:1px solid #e5e7eb;
    text-align:left;
  }
  .lap-table tbody td{
    padding:10px 12px;
    font-size:13px;
    border-bottom:1px solid #f3f4f6;
  }
  .lap-table tbody tr:nth-child(odd):not(.lap-total-row) td{
    background:#fcfcfc;
  }
  .lap-table .num{
    text-align:right;
    font-weight:600;
    color:#111827;
  }

  .lap-empty{
    text-align:center;
    color:#6b7280;
    padding:16px 12px;
  }

  .lap-total-row td{
    background:#fefce8;
    font-weight:600;
    border-top:1px solid #e5e7eb;
  }

  @media(max-width:720px){
    .lap-panel-header{
      flex-direction:column;
      align-items:flex-start;
    }
    .lap-period-summary{
      flex-direction:column;
      align-items:flex-start;
    }
  }
</style>
@endsection
