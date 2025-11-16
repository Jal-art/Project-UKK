{{-- resources/views/kasir/dashboard.blade.php --}}
@extends('layouts.cashify')

@section('title', 'Dashboard | Cashify')

@section('content')
  <h2 class="dash-title">Dashboard</h2>

  <div class="cards dash-cards">
    <article class="card">
      <div class="head">Stok Produk</div>
      <div class="body">{{ $stokCount ?? 400 }}</div>
      <div class="foot">
        <span class="meta">Item tersedia</span>
        <span class="meta-pill">Ringkasan stok</span>
      </div>
    </article>

    <article class="card">
      <div class="head">Transaksi</div>
      <div class="body">{{ $transaksiCount ?? 30 }}</div>
      <div class="foot">
        <span class="meta">Hari ini</span>
        <span class="meta-pill">Ringkasan transaksi</span>
      </div>
    </article>

    <article class="card">
      <div class="head">Telah Terjual</div>
      <div class="body">{{ $terjualCount ?? 40 }}</div>
      <div class="foot">
        <span class="meta">Unit/hari</span>
        <span class="meta-pill">Ringkasan penjualan</span>
      </div>
    </article>
  </div>

  {{-- Animasi & styling khusus dashboard --}}
  <style>
    /* Judul dashboard pelan muncul */
    .dash-title{
      opacity:0;
      transform: translateY(6px);
      animation: dashTitleIn .45s ease-out forwards;
      animation-delay: .03s;
      margin-bottom: 14px;
      font-weight: 600;
      color:#111827;
    }

    @keyframes dashTitleIn{
      from{
        opacity:0;
        transform: translateY(10px);
      }
      to{
        opacity:1;
        transform: translateY(0);
      }
    }

    /* Kartu di dashboard aja yang dianimasikan */
    .dash-cards{
      margin-bottom: 4px;
    }

    .dash-cards .card{
      opacity:0;
      transform: translateY(14px) scale(.97);
      animation: dashCardIn .55s cubic-bezier(.16,.84,.44,1) forwards;
      will-change: transform, opacity;
    }

    /* Stagger / delay biar satu-satu, tapi halus */
    .dash-cards .card:nth-child(1){ animation-delay: .10s; }
    .dash-cards .card:nth-child(2){ animation-delay: .18s; }
    .dash-cards .card:nth-child(3){ animation-delay: .26s; }

    @keyframes dashCardIn{
      0%{
        opacity:0;
        transform: translateY(18px) scale(.97);
      }
      55%{
        opacity:1;
        transform: translateY(2px) scale(1.005);
      }
      100%{
        opacity:1;
        transform: translateY(0) scale(1);
      }
    }

    /* Sedikit perapihan isi card untuk dashboard */
    .dash-cards .card .body{
      font-size: 32px;
      letter-spacing: .5px;
    }

    .dash-cards .card .foot{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:8px;
      font-size: 12.5px;
    }

    .dash-cards .card .foot .meta{
      color:#4b5563;
      white-space:nowrap;
    }

    .dash-cards .card .foot .meta-pill{
      padding:4px 10px;
      border-radius:999px;
      background:#e5e7eb;
      border:1px solid #d1d5db;
      font-size:12px;
      font-weight:500;
      color:#374151;
      white-space:nowrap;
    }

    /* Biar ga kerasa link/klik */
    .dash-cards .card .foot .meta-pill{
      cursor: default;
    }

    /* Kalau user prefer reduce motion, matikan animasi */
    @media (prefers-reduced-motion: reduce){
      .dash-title{
        opacity:1 !important;
        transform:none !important;
        animation:none !important;
      }
      .dash-cards .card{
        opacity:1 !important;
        transform:none !important;
        animation:none !important;
      }
    }

    /* Responsif dikit untuk layar kecil */
    @media (max-width: 640px){
      .dash-cards .card .body{
        font-size: 26px;
      }
      .dash-cards .card .foot{
        flex-direction:column;
        align-items:flex-start;
      }
    }
  </style>
@endsection
