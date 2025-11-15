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
        <span>Item tersedia</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
            <path d="M9 18l6-6-6-6"
                  stroke="currentColor"
                  stroke-width="1.7"
                  stroke-linecap="round"/>
          </svg>
        </a>
      </div>
    </article>

    <article class="card">
      <div class="head">Transaksi</div>
      <div class="body">{{ $transaksiCount ?? 30 }}</div>
      <div class="foot">
        <span>Hari ini</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
            <path d="M9 18l6-6-6-6"
                  stroke="currentColor"
                  stroke-width="1.7"
                  stroke-linecap="round"/>
          </svg>
        </a>
      </div>
    </article>

    <article class="card">
      <div class="head">Telah Terjual</div>
      <div class="body">{{ $terjualCount ?? 40 }}</div>
      <div class="foot">
        <span>Unit/hari</span>
        <a class="link" href="#" aria-disabled="true">Detail
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
            <path d="M9 18l6-6-6-6"
                  stroke="currentColor"
                  stroke-width="1.7"
                  stroke-linecap="round"/>
          </svg>
        </a>
      </div>
    </article>
  </div>

  {{-- Animasi masuk dashboard --}}
  <style>
    /* Judul dashboard juga pelan muncul */
    .dash-title{
      opacity:0;
      transform: translateY(6px);
      animation: dashTitleIn .45s ease-out forwards;
      animation-delay: .03s;
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

    /* Animasi hanya untuk kartu di dashboard (dash-cards) */
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
  </style>
@endsection
