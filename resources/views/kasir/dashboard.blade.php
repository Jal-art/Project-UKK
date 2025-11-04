<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Cashify</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#ffffff; --text:#222; --sidebar:#171717; --line:#2a2a2a;
      --card:#d9d9d9; --card-top:#262626; --danger:#e03c3c;
      --shadow:4px 6px 0 rgba(0,0,0,.16), 0 6px 14px rgba(0,0,0,.08);
      --radius:12px;
    }
    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family:"Poppins",system-ui,Arial;color:var(--text);background:var(--bg)}
    /* Grid 2 kolom: sidebar + area konten */
    .layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh}

    /* Sidebar */
    .sidebar{background:var(--sidebar);color:#fff;padding:18px 16px;display:flex;flex-direction:column}
    .brand{display:flex;align-items:center;gap:12px;padding:8px 6px}
    .brand .logo{width:52px;height:52px;border-radius:12px;background:#f5f5f5;display:grid;place-items:center;overflow:hidden}
    .brand .logo img{width:100%;height:100%;object-fit:cover}
    .brand .title{font-size:22px;font-weight:600}
    .sline{height:1px;background:var(--line);margin:14px 0}
    .menu{display:flex;flex-direction:column}
    .menu a{display:flex;align-items:center;gap:12px;color:#eaeaea;text-decoration:none;padding:10px;border-radius:10px}
    .menu a:hover{background:#232323}
    .menu .icon{width:18px;height:18px;color:#eaeaea}
    .menu .text{font-size:15px}
    .logout{margin-top:auto}
    .logout .btn{
      width:100%;display:flex;align-items:center;justify-content:center;gap:8px;
      background:var(--danger);color:#fff;border:none;border-radius:10px;cursor:pointer;
      padding:10px 12px;font-weight:600;box-shadow:0 6px 14px rgba(0,0,0,.18);
      transition:transform .06s, opacity .15s;
    }
    .logout .btn:hover{opacity:.95}
    .logout .btn:active{transform:translateY(1px)}

    /* Content: jadikan kolom fleksibel agar footer bisa menempel di bawah */
    .content{
      display:flex;flex-direction:column;min-height:100vh; /* ini penting untuk footer bawah */
      background:var(--bg)
    }
    .topbar{height:52px;background:#d1d1d1;border-bottom:1px solid #bcbcbc;display:flex;align-items:center;padding:0 20px}
    .page{padding:22px 28px}
    .page h2{margin:0 0 16px 0;font-weight:600;color:#3a3a3a}

    /* Cards */
    .cards{display:grid;grid-template-columns:repeat(3,minmax(220px,1fr));gap:22px}
    .card{background:var(--card);border-radius:12px;box-shadow:var(--shadow);overflow:hidden}
    .card .head{background:var(--card-top);color:#fff;padding:12px 14px;font-weight:600}
    .card .body{display:grid;place-items:center;height:120px;font-size:44px;font-weight:600;color:#2b2b2b}
    .card .foot{background:#e5e5e5;border-top:1px solid #cfcfcf;display:flex;justify-content:space-between;align-items:center;padding:10px 14px;font-weight:500;color:#333}
    .card .arrow{transition:transform .15s}
    .card:hover .arrow{transform:translateX(4px)}

    /* Footer (marquee JS) — ditempatkan PALING BAWAH dengan margin-top:auto */
    .footer{ margin-top:auto; padding:12px 28px 18px; color:#666; font-size:14px; }
    .marquee-wrap{
      position:relative; overflow:hidden; width:100%; height:24px; border-radius:8px;
    }
    .marquee-item{
      position:absolute; top:50%; transform:translateY(-50%);
      white-space:nowrap; will-change: transform; opacity:.95;
    }

    @media (max-width:1024px){
      .layout{grid-template-columns:200px 1fr}
      .cards{grid-template-columns:repeat(2,minmax(220px,1fr))}
    }
    @media (max-width:720px){
      .layout{grid-template-columns:1fr}
      .cards{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
      </div>
      <div class="title">Cash1fy</div>
    </div>

    <div class="sline"></div>

    <nav class="menu">
      <a href="{{ route('dashboard') }}">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M3 11l9-7 9 7v8a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2v-8z" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Dashboard</span>
      </a>
      <a href="#" aria-disabled="true" style="opacity:.6;pointer-events:none;">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M6 7l1-3h10l1 3M6 7h12l-1 10H7L6 7zm3 4h6" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Produk (nanti)</span>
      </a>
      <a href="#" aria-disabled="true" style="opacity:.6;pointer-events:none;">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M7 11h10M9 15h6M6 19h12" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Transaksi (nanti)</span>
      </a>
      <a href="#" aria-disabled="true" style="opacity:.6;pointer-events:none;">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M4 19V5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="1.5"/><path d="M14 3v5h5" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Laporan (nanti)</span>
      </a>
    </nav>

    <div class="sline" style="margin-top:16px;"></div>

    <div class="logout">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn" type="submit" title="Keluar">
          Keluar
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3" stroke="#fff" stroke-width="1.8"/>
            <path d="M16 17l5-5-5-5M21 12H9" stroke="#fff" stroke-width="1.8"/>
          </svg>
        </button>
      </form>
    </div>
  </aside>

  <!-- CONTENT -->
  <section class="content">
    <div class="topbar"></div>

    <div class="page">
      <h2>Dashboard</h2>

      <div class="cards">
        <article class="card">
          <div class="head">Stok Produk</div>
          <div class="body">{{ $stokCount ?? 400 }}</div>
          <div class="foot"><span>Informasi Lanjutan</span><span class="arrow">›</span></div>
        </article>

        <article class="card">
          <div class="head">Transaksi</div>
          <div class="body">{{ $transaksiCount ?? 30 }}</div>
          <div class="foot"><span>Informasi Lanjutan</span><span class="arrow">›</span></div>
        </article>

        <article class="card">
          <div class="head">Telah Terjual</div>
          <div class="body">{{ $terjualCount ?? 40 }}</div>
          <div class="foot"><span>Informasi Lanjutan</span><span class="arrow">›</span></div>
        </article>
      </div>
    </div>

    <!-- FOOTER: nempel di bawah (sticky), anim JS kanan -> kiri -->
    <div class="footer">
      <div id="marqueeWrap" class="marquee-wrap">
        <div id="marqueeItem" class="marquee-item">
         Copyright© {{ now()->year }} — Cashify • All rights reserved
        </div>
      </div>
    </div>

  </section>
</div>

<!-- JS marquee -->
<script>
(() => {
  const SPEED = 70; // px/detik

  const wrap  = document.getElementById('marqueeWrap');
  const item  = document.getElementById('marqueeItem');

  let x = 0, last = null, itemW = 0, wrapW = 0;

  function measure() {
    item.style.transform = 'translate(-9999px,-50%)';
    itemW = item.offsetWidth;
    wrapW = wrap.clientWidth;
    x = wrapW; // mulai dari kanan luar
    item.style.transform = `translate(${x}px, -50%)`;
  }

  function tick(ts) {
    if (!last) last = ts;
    const dt = (ts - last) / 1000;
    last = ts;

    x -= SPEED * dt;
    if (x < -itemW) x = wrapW; // reset kalau sudah lewat kiri

    item.style.transform = `translate(${x}px, -50%)`;
    requestAnimationFrame(tick);
  }

  window.addEventListener('resize', measure);
  window.addEventListener('load', () => { measure(); requestAnimationFrame(tick); });
})();
</script>
</body>
</html>
