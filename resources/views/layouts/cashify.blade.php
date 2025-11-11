{{-- resources/views/layouts/cashify.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Cashify')</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#fff; --text:#1f2937; --muted:#6b7280; --sidebar:#111827; --line:#1f2937;
      --card:#f3f4f6; --card-top:#1f2937; --accent:#6d5cff; --danger:#ef4444; --success:#22c55e;
      --radius:14px; --shadow:0 1px 2px rgba(0,0,0,.05), 0 8px 24px rgba(0,0,0,.08);
    }
    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family:"Poppins",system-ui,Arial;color:var(--text);background:var(--bg)}

    /* GRID UTAMA */
    .layout{
      display:grid;
      grid-template-columns:232px 1fr;
      min-height:100vh;
      align-items:start; /* penting agar sticky bekerja */
    }

    /* SIDEBAR (Sticky) */
    .sidebar{
      background:var(--sidebar);
      color:#fff;
      padding:16px 14px;
      display:flex; flex-direction:column;
      position:sticky;   /* nempel di viewport */
      top:0;             /* mulai dari atas */
      height:100vh;      /* penuh tinggi layar */
      overflow:auto;     /* kalau menu panjang, sidebar-nya yang scroll */
      overscroll-behavior:contain;
    }
    .brand{display:flex;align-items:center;gap:10px;padding:6px 4px}
    .brand .logo{width:44px;height:44px;border-radius:10px;background:#f9fafb;display:grid;place-items:center;overflow:hidden}
    .brand .logo img{width:100%;height:100%;object-fit:cover}
    .brand .title{font-size:20px;font-weight:600;letter-spacing:.2px}
    .sline{height:1px;background:#1f2937;margin:12px 0}
    .menu{display:flex;flex-direction:column;gap:4px}
    .menu a{
      display:flex;align-items:center;gap:10px;color:#e5e7eb;text-decoration:none;
      padding:9px 10px;border-radius:10px;transition:background .15s
    }
    .menu a:hover{background:#0b1220}
    .menu .icon{width:18px;height:18px;color:#e5e7eb;flex:0 0 18px}
    .menu .text{font-size:14px}

    /* KONTEN + TOPBAR (Topbar juga sticky) */
    .content{display:flex;flex-direction:column;min-height:100vh}
    .topbar{
      position:sticky; top:0; z-index:40;   /* biar ikut nempel saat scroll */
      height:56px;background:#e5e7eb;border-bottom:1px solid #d1d5db;
      display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0 16px
    }
    .tb-left{display:flex;align-items:center;gap:10px;min-width:0}
    .tb-right{display:flex;align-items:center;gap:12px;position:relative}
    .store-name{font-size:14px;color:#4b5563;user-select:none}

    /* Welcome kiri topbar (teks polos, bukan card/chip) */
    .welcome-top{
      display:block;
      padding:0;
      border:none;
      border-radius:0;
      background:transparent;
      color:#374151;
      font-size:14px;
      line-height:1.2;
      min-width:0;
    }
    .welcome-top .who{
      font-weight:700;
      color:#111;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:28vw;
    }
    @media (max-width:820px){ .welcome-top .who{max-width:50vw} }
    @media (max-width:520px){
      .welcome-top{font-size:13px}
      .welcome-top .who{max-width:52vw}
    }

    /* User chip + caret-only dropdown */
    .user-chip{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:999px;padding:6px 10px;box-shadow:var(--shadow)}
    .avatar{width:28px;height:28px;border-radius:999px;display:grid;place-items:center;background:#111;color:#fff;font-weight:700;font-size:11px;letter-spacing:.4px}
    .uname{font-size:13px;color:#111;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .caret-btn{border:none;background:transparent;padding:4px;border-radius:8px;cursor:pointer;display:grid;place-items:center;line-height:0}
    .caret-btn:focus{outline:2px solid #9ca3af; outline-offset:2px}
    .caret{opacity:.8; transition: transform .18s ease, opacity .18s ease;}
    .caret-btn.active .caret{ transform: rotate(180deg); }

    /* Dropdown (animated) */
    .dropdown{
      position:absolute; right:0; top:56px; width:180px; background:#f7f7f7; border:1px solid #d8d8d8;
      border-radius:12px; box-shadow:0 12px 24px rgba(0,0,0,.12); overflow:hidden; z-index:30;

      opacity:0; visibility:hidden; pointer-events:none;
      transform-origin: top right; transform: translateY(-8px) scale(.98);
      transition: opacity .18s ease, transform .18s ease, visibility 0s linear .18s;
    }
    .dropdown.show{ opacity:1; visibility:visible; pointer-events:auto; transform: translateY(0) scale(1); transition: opacity .18s ease, transform .18s ease, visibility 0s; }
    .dd-item{display:flex;align-items:center;gap:8px;padding:10px 12px;color:#222;text-decoration:none;transition:background .12s;font-size:14px;width:100%;text-align:left;background:none;border:none;cursor:pointer}
    .dd-item:hover{background:#eaeaea}
    .dropdown.show .dd-item{ animation: ddIn .22s ease both; }
    @keyframes ddIn{ from{ transform: translateY(-6px); opacity:0; } to{ transform: translateY(0); opacity:1; } }

    /* Page / utilities */
    .page{padding:18px 22px}
    .page h2{margin:0 0 12px;font-weight:600;color:#111;font-size:20px}
    .panel{background:#fff;border:1px solid #e5e7eb;border-radius:var(--radius);box-shadow:var(--shadow);padding:16px}
    .cards{display:grid;grid-template-columns:repeat(3,minmax(200px,1fr));gap:14px}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
    .card .head{background:var(--card-top);color:#fff;padding:10px 12px;font-weight:600;font-size:13px}
    .card .body{display:grid;place-items:center;height:92px;font-size:30px;font-weight:700;color:#1f2937}
    .card .foot{background:#f9fafb;border-top:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center;padding:8px 12px;font-size:12.5px;color:#374151}
    .card .foot .link{display:inline-flex;align-items:center;gap:6px;text-decoration:none;color:#374151}
    .card .foot .link:hover{color:var(--accent)}

    /* Footer marquee */
    .footer{margin-top:auto;padding:10px 22px 16px;color:#6b7280;font-size:13px}
    .marquee-wrap{position:relative;overflow:hidden;width:100%;height:22px;border-radius:8px}
    .marquee-item{position:absolute;top:50%;transform:translateY(-50%);white-space:nowrap;opacity:.95}

    /* Modal (logout) */
    .modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;padding:16px;z-index:50;opacity:0;transition:opacity .25s ease}
    .modal-backdrop.show{display:flex;opacity:1}
    .modal{width:100%;max-width:420px;background:#fff;border-radius:16px;box-shadow:0 18px 50px rgba(0,0,0,.25);transform:translateY(10px) scale(.985);opacity:0;animation:modalIn .28s cubic-bezier(.22,.61,.36,1) both}
    .modal.hiding{animation:modalOut .22s ease both}
    .modal-head{display:flex;align-items:center;gap:10px;padding:14px 16px;border-bottom:1px solid #f3f4f6}
    .modal-title{font-weight:600}
    .modal-body{padding:16px;color:var(--muted);font-size:14px}
    .modal-actions{display:flex;gap:10px;padding:0 16px 16px}
    .btn{display:inline-flex;align-items:center;gap:8px;border:none;border-radius:10px;padding:9px 13px;font-weight:600;cursor:pointer}
    .btn-danger{background:#ef4444;color:#fff}
    .btn-ghost{background:#fff;border:1px solid #e5e7eb;color:#111}
    @keyframes modalIn{0%{transform:translateY(14px) scale(.96);opacity:0;filter:blur(2px)}60%{transform:translateY(0) scale(1.01);opacity:1;filter:blur(.5px)}100%{transform:translateY(0) scale(1);opacity:1;filter:blur(0)}}
    @keyframes modalOut{0%{transform:translateY(0) scale(1);opacity:1;filter:blur(0)}100%{transform:translateY(8px) scale(.985);opacity:0;filter:blur(2px)}}

    /* Reduce motion */
    @media (prefers-reduced-motion: reduce){
      .dropdown{transition:none; transform:none;}
      .dropdown.show{transform:none;}
      .dropdown.show .dd-item{animation:none;}
      .modal,.modal.hiding{animation:none;}
      .modal-backdrop{transition:none;}
    }

    /* RESPONSIVE */
    @media (max-width:1024px){ .layout{grid-template-columns:200px 1fr} }
    @media (max-width:820px){ .cards{grid-template-columns:repeat(2,minmax(200px,1fr))} .uname{max-width:120px} }
    @media (max-width:520px){
      .layout{grid-template-columns:1fr}
      .cards{grid-template-columns:1fr}
      /* matikan sticky di layar kecil (opsional) */
      .sidebar{position:static; height:auto; overflow:visible}
    }
  </style>
</head>
<body>
<div class="layout">
  {{-- Sidebar --}}
  <aside class="sidebar">
    <div class="brand">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Logo"></div>
      <div class="title">Cashlfy</div>
    </div>
    <div class="sline"></div>
    <nav class="menu">
      <a href="{{ route('dashboard') }}">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M3 11l9-7 9 7v8a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2v-8z" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Dashboard</span>
      </a>
      <a href="{{ route('produk.index') }}">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M6 7l1-3h10l1 3M6 7h12l-1 10H7L6 7zm3 4h6" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Produk</span>
      </a>
      <a href="{{ route('transaksi.index') }}">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M7 11h10M9 15h6M6 19h12" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Transaksi</span>
      </a>
      <a href="#" aria-disabled="true" style="opacity:.6;pointer-events:none;">
        <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M4 19V5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="1.5"/><path d="M14 3v5h5" stroke="currentColor" stroke-width="1.5"/></svg>
        <span class="text">Laporan (nanti)</span>
      </a>
    </nav>
  </aside>

  {{-- Content --}}
  <section class="content">
    {{-- Topbar (sticky) --}}
    <div class="topbar">
      <div class="tb-left">
        @php
          $nama = auth()->user()->nama_kasir ?? 'Kasir';
          $parts = preg_split('/\s+/', trim($nama)); $initials = '';
          foreach ($parts as $i => $p) { if ($i > 1) break; $initials .= mb_substr($p, 0, 1); }
          $initials = mb_strtoupper($initials);
        @endphp
        <div class="welcome-top" role="status" aria-live="polite" title="Selamat datang di Cashify">
          Selamat datang di <strong>Cashify</strong>
        </div>
      </div>

      <div class="tb-right">
        <span class="store-name">Toko Baju</span>

        <div class="user-chip" aria-label="Profil pengguna">
          <div class="avatar" aria-hidden="true">{{ $initials }}</div>
          <div class="uname">{{ $nama }}</div>
          <button id="caretBtn" class="caret-btn" aria-haspopup="menu" aria-expanded="false" aria-controls="userMenu">
            <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M6 9l6 6 6-6" stroke="#333" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <div id="userMenu" class="dropdown" role="menu" aria-label="User menu">
          <button class="dd-item" id="btnOpenLogoutModal" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M16 17l5-5-5-5M21 12H9" stroke="#222" stroke-width="1.6"/>
              <path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3" stroke="#222" stroke-width="1.6"/>
            </svg> Keluar
          </button>
        </div>
      </div>
    </div>

    {{-- Halaman --}}
    <div class="page">@yield('content')</div>

    {{-- Footer --}}
    <div class="footer">
      <div id="marqueeWrap" class="marquee-wrap">
        <div id="marqueeItem" class="marquee-item">Copyright© {{ now()->year }} — Cashify • All rights reserved</div>
      </div>
    </div>
  </section>
</div>

{{-- Modal Logout --}}
<div id="logoutBackdrop" class="modal-backdrop" aria-hidden="true">
  <div id="logoutModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="logoutTitle" aria-describedby="logoutDesc" tabindex="-1">
    <div class="modal-head"><div class="modal-title" id="logoutTitle">Konfirmasi Logout</div></div>
    <div class="modal-body" id="logoutDesc">Kamu yakin ingin keluar dari aplikasi?</div>
    <div class="modal-actions">
      <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="margin:0">@csrf
        <button type="submit" class="btn btn-danger">Ya, Keluar</button>
      </form>
      <button type="button" class="btn btn-ghost" id="btnCancelLogout">Batal</button>
    </div>
  </div>
</div>

<script>
(() => {
  /* ===== Marquee ===== */
  const SPEED = 70;
  const wrap = document.getElementById('marqueeWrap');
  const item = document.getElementById('marqueeItem');
  let x = 0, last = null, wI = 0, wW = 0;
  function measure(){ if(!wrap||!item) return; item.style.transform='translate(-9999px,-50%)'; wI=item.offsetWidth; wW=wrap.clientWidth; x=wW; item.style.transform=`translate(${x}px,-50%)`; }
  function tick(ts){ if(!last) last=ts; const dt=(ts-last)/1000; last=ts; x-=SPEED*dt; if(x<-wI) x=wW; item.style.transform=`translate(${x}px,-50%)`; requestAnimationFrame(tick); }
  addEventListener('resize', measure);
  addEventListener('load', () => { measure(); requestAnimationFrame(tick); });

  /* ===== Dropdown: caret-only + anim ===== */
  const caretBtn = document.getElementById('caretBtn');
  const menu  = document.getElementById('userMenu');

  caretBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    const shown = menu.classList.toggle('show');
    caretBtn.classList.toggle('active', shown);
    caretBtn.setAttribute('aria-expanded', shown ? 'true' : 'false');
    if (shown) menu.querySelector('.dd-item')?.focus();
  });

  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && e.target !== caretBtn) {
      menu.classList.remove('show');
      caretBtn.classList.remove('active');
      caretBtn.setAttribute('aria-expanded', 'false');
    }
  });

  addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      menu.classList.remove('show');
      caretBtn.classList.remove('active');
      caretBtn.setAttribute('aria-expanded', 'false');
    }
  });

  /* ===== Modal Logout ===== */
  const btnOpen   = document.getElementById('btnOpenLogoutModal');
  const backdrop  = document.getElementById('logoutBackdrop');
  const modal     = document.getElementById('logoutModal');
  const btnCancel = document.getElementById('btnCancelLogout');
  let lastFocused = null;
  const OUT_MS = 220;

  function openModal() {
    lastFocused = document.activeElement;
    backdrop.classList.add('show');
    modal.classList.remove('hiding');
    setTimeout(() => document.querySelector('#logoutForm button')?.focus(), 0);

    menu?.classList.remove('show');
    caretBtn?.classList.remove('active');
    caretBtn?.setAttribute('aria-expanded','false');
    backdrop.removeAttribute('aria-hidden');
  }

  function closeModal() {
    modal.classList.add('hiding');
    setTimeout(() => {
      backdrop.classList.remove('show');
      backdrop.setAttribute('aria-hidden', 'true');
      modal.classList.remove('hiding');
      lastFocused?.focus();
    }, OUT_MS);
  }

  btnOpen?.addEventListener('click', openModal);
  btnCancel?.addEventListener('click', closeModal);
  backdrop?.addEventListener('click', (e) => { if (e.target === backdrop) closeModal(); });
  addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && backdrop?.classList.contains('show')) { e.preventDefault(); closeModal(); }
  });

  // Focus trap sederhana di modal
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Tab' || !backdrop?.classList.contains('show')) return;
    const f = modal.querySelectorAll('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])');
    const first = f[0], last = f[f.length-1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });
})();
</script>
</body>
</html>
