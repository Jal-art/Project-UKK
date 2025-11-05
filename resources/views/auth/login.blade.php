<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masuk | Cashify</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#9e9e9e;
      --card:#d9d9d9;
      --text:#222222;
      --white:#ffffff;
      --black:#000000;
      --radius:18px;

      /* shadow */
      --card-shadow: 4px 4px 0 rgba(0,0,0,.14), 0 6px 14px rgba(0,0,0,.10);
      --input-shadow: 0 3px 0 rgba(0,0,0,.08), 0 5px 10px rgba(0,0,0,.06);
      --input-shadow-focus: 0 4px 0 rgba(0,0,0,.10), 0 8px 14px rgba(0,0,0,.10);
      --btn-shadow: 0 6px 0 rgba(0,0,0,.24), 0 12px 18px rgba(0,0,0,.14);
      --btn-shadow-active: 0 4px 0 rgba(0,0,0,.22), 0 10px 16px rgba(0,0,0,.12);

      --accent:#111; /* untuk efek shine */
    }
    *{box-sizing:border-box}
    html,body{
      height:100%; margin:0;
      font-family:"Poppins",system-ui,Arial;
      background:var(--bg); color:var(--text);
    }

    /* ========= GLOBAL ENTER ANIMATION ========= */
    body{
      opacity:0; transform:translateY(8px);
      animation: pageIn .5s ease forwards;
    }
    @keyframes pageIn{
      from{ opacity:0; transform:translateY(8px) }
      to  { opacity:1; transform:translateY(0) }
    }

    /* ========= BACKGROUND BLOBS ========= */
    .bgfx{
      position:fixed; inset:0; overflow:hidden; z-index:-1;
      filter: blur(40px);
    }
    .blob{
      position:absolute; border-radius:999px; opacity:.55; transform:translate(-50%,-50%);
      animation: float 12s ease-in-out infinite alternate;
      will-change: transform;
    }
    .blob.one{ width:420px; height:420px; left:15%; top:18%; background:#e8f0ff; animation-duration: 14s; }
    .blob.two{ width:360px; height:360px; left:85%; top:22%; background:#ffeaea; animation-duration: 13s; }
    .blob.three{ width:380px; height:380px; left:50%; top:88%; background:#eaffee; animation-duration: 16s; }

    @keyframes float{
      0%   { transform:translate(-50%,-50%) translateY(-8px) }
      100% { transform:translate(-50%,-50%) translateY(8px) }
    }

    .wrap{
      min-height:100%;
      display:flex; align-items:center; justify-content:center;
      padding:32px 16px 64px;
      perspective:1000px; /* untuk tilt */
    }

    .card{
      width:100%; max-width:420px;
      background:var(--card);
      border-radius:var(--radius);
      box-shadow: var(--card-shadow);
      padding:40px 34px 28px; position:relative;
      border:1px solid rgba(0,0,0,.04);

      /* POP-IN */
      opacity:0; transform:translateY(14px) scale(.985);
      animation: cardIn .55s cubic-bezier(.22,.61,.36,1) .1s forwards;
      transform-style:preserve-3d;
    }
    @keyframes cardIn{
      0%   { opacity:0; transform:translateY(14px) scale(.985) rotateX(2deg) }
      60%  { opacity:1; transform:translateY(-2px) scale(1.01) rotateX(0.5deg) }
      100% { opacity:1; transform:translateY(0) scale(1) rotateX(0) }
    }

    /* LOGO */
    .logo-box{
      width:120px; height:120px; margin:0 auto 22px;
      border-radius:18px; background:var(--white);
      display:grid; place-items:center;
      box-shadow: 0 8px 14px rgba(0,0,0,.10), inset 0 0 0 1px #eaeaea;
      overflow:hidden;
      opacity:0; transform:translateY(8px);
      animation: rise .5s ease .18s forwards;
    }
    .logo-box img{
      width:78%; height:auto; object-fit:contain; display:block;
      transform:scale(.98); transition: transform .2s ease;
    }
    .card:hover .logo-box img{ transform:scale(1) }

    @keyframes rise{
      to { opacity:1; transform:translateY(0) }
    }

    /* FIELDS (stagger reveal) */
    .reveal{ opacity:0; transform:translateY(10px); }
    .reveal.show{ animation: rise .45s ease forwards; }

    .field{ margin:14px 0 18px }
    label{ display:block; font-size:14px; font-weight:600; margin-bottom:8px; color:#2b2b2b }
    .input{
      width:100%; padding:12px 14px;
      border-radius:12px; border:1px solid #d0d0d0; background:var(--white); outline:none;
      box-shadow: var(--input-shadow);
      transition: box-shadow .18s, border-color .18s, transform .06s, background .3s;
      font-size:15px;
    }
    .input:focus{
      border-color:#bcbcbc;
      box-shadow: var(--input-shadow-focus);
      background:#fffdfa;
    }

    /* BUTTON + SHINE + RIPPLE */
    .btn{
      width:100%; border:none; cursor:pointer; margin-top:6px;
      padding:12px 16px; border-radius:12px;
      background:var(--black); color:var(--white);
      font-weight:600; font-size:15px; letter-spacing:.2px;
      box-shadow: var(--btn-shadow);
      transition: transform .06s ease, box-shadow .12s ease, opacity .2s ease;
      position:relative; overflow:hidden;
      isolation:isolate;
    }
    .btn::before{
      content:""; position:absolute; inset:-40%; background:
      radial-gradient(140px 140px at var(--mx,50%) var(--my,50%), rgba(255,255,255,.25), transparent 60%);
      opacity:0; transition:opacity .25s; pointer-events:none; z-index:0;
    }
    .btn:hover::before{ opacity:1 }
    .btn span{ position:relative; z-index:1 } /* text above shine */
    .btn:hover{ opacity:.96 }
    .btn:active{ transform:translateY(2px); box-shadow: var(--btn-shadow-active) }

    /* Ripple circle */
    .btn .ripple{
      position:absolute; width:8px; height:8px; border-radius:999px;
      background:rgba(255,255,255,.5); transform:translate(-50%,-50%) scale(1);
      animation: ripple .6s ease-out forwards; pointer-events:none; z-index:0;
      mix-blend-mode: screen;
    }
    @keyframes ripple{
      from{ opacity:.65; transform:translate(-50%,-50%) scale(1) }
      to  { opacity:0;   transform:translate(-50%,-50%) scale(25) }
    }

    /* Footer marquee */
    .footer{
      margin-top:16px; color:#666; font-size:14px;
      opacity:0; transform:translateY(6px);
      animation: rise .4s ease .5s forwards;
    }
    .marquee-wrap{
      position:relative; overflow:hidden; width:100%; height:24px; border-radius:8px;
    }
    .marquee-item{
      position:absolute; top:50%; transform:translateY(-50%);
      white-space:nowrap; will-change: transform; opacity:.95;
    }

    .err{
      background:#ffe8e8; border:1px solid #ffb3b3; color:#8b0000;
      padding:10px 12px; border-radius:10px; font-size:14px; margin-bottom:10px;
      opacity:0; transform:translateY(6px);
      animation: rise .4s ease .2s forwards;
    }

    @media (max-width:420px){
      .card{ padding:28px 22px 20px }
      .logo-box{ width:100px; height:100px }
    }
  </style>
</head>
<body>
  <!-- background blobs -->
  <div class="bgfx" aria-hidden="true">
    <div class="blob one"></div>
    <div class="blob two"></div>
    <div class="blob three"></div>
  </div>

  <div class="wrap">
    <main class="card" role="main" aria-labelledby="judul-login" id="loginCard">
      <div class="logo-box" aria-hidden="true">
        {{-- GANTI ke path logo kamu --}}
        <img src="{{ asset('images/rlogo.png') }}" alt="Logo Toko">
      </div>

      <h1 id="judul-login" style="position:absolute;left:-9999px;">Masuk ke Cashify</h1>

      @if ($errors->any())
        <div class="err">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.process') }}" autocomplete="on" novalidate>
        @csrf
        <div class="field reveal" style="animation-delay:.18s">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" class="input" placeholder="Masukkan Email"
                 value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field reveal" style="animation-delay:.28s">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" class="input" placeholder="Masukkan Password" required>
        </div>

        <button class="btn reveal" type="submit" style="animation-delay:.38s">
          <span>Masuk</span>
        </button>
      </form>

      <!-- Footer dengan animasi JS marquee (kanan -> kiri, berulang) -->
      <div class="footer">
        <div id="marqueeWrap" class="marquee-wrap">
          <div id="marqueeItem" class="marquee-item">
            Copyright© {{ now()->year }} — Cashify • All rights reserved
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- JS: marquee + stagger + tilt + ripple + shine cursor -->
  <script>
  (() => {
    /* ===== Marquee ===== */
    const SPEED = 70; // px/detik
    const wrap  = document.getElementById('marqueeWrap');
    const item  = document.getElementById('marqueeItem');
    let x = 0, last = null, itemW = 0, wrapW = 0;

    function measure() {
      if (!wrap || !item) return;
      item.style.transform = 'translate(-9999px,-50%)';
      itemW = item.offsetWidth;
      wrapW = wrap.clientWidth;
      x = wrapW; // mulai dari luar kanan
      item.style.transform = `translate(${x}px, -50%)`;
    }
    function tick(ts) {
      if (!last) last = ts;
      const dt = (ts - last) / 1000;
      last = ts;
      x -= SPEED * dt;
      if (x < -itemW) x = wrapW;
      item.style.transform = `translate(${x}px, -50%)`;
      requestAnimationFrame(tick);
    }
    addEventListener('resize', measure);
    addEventListener('load', () => { measure(); requestAnimationFrame(tick); });

    /* ===== Stagger on visible ===== */
    // Semua .reveal langsung diberi class show (CSS handle delay via inline style)
    requestAnimationFrame(() => {
      document.querySelectorAll('.reveal').forEach(el => el.classList.add('show'));
    });

    /* ===== Tilt / Parallax ringan pada card ===== */
    const card = document.getElementById('loginCard');
    let rafId = null;
    function onMove(e){
      const rect = card.getBoundingClientRect();
      const cx = rect.left + rect.width/2;
      const cy = rect.top + rect.height/2;
      const dx = (e.clientX - cx) / rect.width;   // -0.5 .. 0.5
      const dy = (e.clientY - cy) / rect.height;  // -0.5 .. 0.5
      const rotX = (dy * -6); // derajat
      const rotY = (dx * 6);
      cancelAnimationFrame(rafId);
      rafId = requestAnimationFrame(() => {
        card.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
      });
    }
    function onLeave(){
      cancelAnimationFrame(rafId);
      card.style.transform = 'rotateX(0) rotateY(0)';
    }
    card.addEventListener('mousemove', onMove);
    card.addEventListener('mouseleave', onLeave);

    /* ===== Button ripple + shine follow cursor ===== */
    const btn = document.querySelector('.btn');
    btn?.addEventListener('click', (e) => {
      const r = document.createElement('span');
      r.className = 'ripple';
      const rect = e.currentTarget.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      r.style.left = x + 'px';
      r.style.top  = y + 'px';
      e.currentTarget.appendChild(r);
      setTimeout(() => r.remove(), 600);
    });
    btn?.addEventListener('pointermove', (e) => {
      const rect = e.currentTarget.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      e.currentTarget.style.setProperty('--mx', x + 'px');
      e.currentTarget.style.setProperty('--my', y + 'px');
    });
  })();
  </script>
</body>
</html>
