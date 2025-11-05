{{-- resources/views/kasir/dashboard.blade.php --}}
@extends('layouts.cashify')

@section('title', 'Dashboard | Cashify')

@section('content')
  <h2>Dashboard</h2>

  <div class="cards">
    <article class="card">
      <div class="head">Stok Produk</div>
      <div class="body">{{ $stokCount ?? 400 }}</div>
      <div class="foot">
        <span>Item tersedia</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
        </a>
      </div>
    </article>

    <article class="card">
      <div class="head">Transaksi</div>
      <div class="body">{{ $transaksiCount ?? 30 }}</div>
      <div class="foot">
        <span>Hari ini</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
        </a>
      </div>
    </article>

    <article class="card">
      <div class="head">Telah Terjual</div>
      <div class="body">{{ $terjualCount ?? 40 }}</div>
      <div class="foot">
        <span>Unit/hari</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
        </a>
      </div>
    </article>
  </div>
@endsection
