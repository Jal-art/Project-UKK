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

      /* shadow tipis */
      --card-shadow: 4px 4px 0 rgba(0,0,0,.14), 0 6px 14px rgba(0,0,0,.10);
      --input-shadow: 0 3px 0 rgba(0,0,0,.08), 0 5px 10px rgba(0,0,0,.06);
      --input-shadow-focus: 0 4px 0 rgba(0,0,0,.10), 0 8px 14px rgba(0,0,0,.10);
      --btn-shadow: 0 6px 0 rgba(0,0,0,.24), 0 12px 18px rgba(0,0,0,.14);
      --btn-shadow-active: 0 4px 0 rgba(0,0,0,.22), 0 10px 16px rgba(0,0,0,.12);
    }
    *{box-sizing:border-box}
    html,body{
      height:100%; margin:0;
      font-family:"Poppins",system-ui,Arial;
      background:var(--bg); color:var(--text);
    }
    .wrap{
      min-height:100%;
      display:flex; align-items:center; justify-content:center;
      padding:32px 16px 64px;
    }
    .card{
      width:100%; max-width:420px;
      background:var(--card);
      border-radius:var(--radius);
      box-shadow: var(--card-shadow);
      padding:40px 34px 28px; position:relative;
      border:1px solid rgba(0,0,0,.04);
    }

    /* LOGO: pakai gambar kamu */
    .logo-box{
      width:120px; height:120px; margin:0 auto 22px;
      border-radius:18px; background:var(--white);
      display:grid; place-items:center;
      box-shadow: 0 8px 14px rgba(0,0,0,.10), inset 0 0 0 1px #eaeaea;
      overflow:hidden; /* biar img ikut rounded */
    }
    .logo-box img{
      width:78%; height:auto; object-fit:contain; display:block;
    }

    .field{ margin:14px 0 18px }
    label{ display:block; font-size:14px; font-weight:600; margin-bottom:8px; color:#2b2b2b }
    .input{
      width:100%; padding:12px 14px;
      border-radius:12px; border:1px solid #d0d0d0; background:var(--white); outline:none;
      box-shadow: var(--input-shadow);
      transition: box-shadow .18s, border-color .18s, transform .06s;
      font-size:15px;
    }
    .input:focus{
      border-color:#bcbcbc;
      box-shadow: var(--input-shadow-focus);
    }

    .btn{
      width:100%; border:none; cursor:pointer; margin-top:6px;
      padding:12px 16px; border-radius:12px;
      background:var(--black); color:var(--white);
      font-weight:600; font-size:15px; letter-spacing:.2px;
      box-shadow: var(--btn-shadow);
      transition: transform .06s ease, box-shadow .12s ease, opacity .2s ease;
    }
    .btn:hover{ opacity:.96 }
    .btn:active{ transform:translateY(2px); box-shadow: var(--btn-shadow-active) }

    /* Footer area untuk marquee JS */
    .footer{
      margin-top:16px; color:#666; font-size:14px;
    }
    .marquee-wrap{
      position:relative; overflow:hidden; width:100%; height:24px;
      border-radius:8px;
    }
    .marquee-item{
      position:absolute; top:50%; transform:translateY(-50%);
      white-space:nowrap; will-change: transform;
      opacity:.95;
    }

    .err{
      background:#ffe8e8; border:1px solid #ffb3b3; color:#8b0000;
      padding:10px 12px; border-radius:10px; font-size:14px; margin-bottom:10px;
    }

    @media (max-width:420px){
      .card{ padding:28px 22px 20px }
      .logo-box{ width:100px; height:100px }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <main class="card" role="main" aria-labelledby="judul-login">
      <div class="logo-box" aria-hidden="true">
        {{-- GANTI ke path logo kamu --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo Toko">
      </div>

      <h1 id="judul-login" style="position:absolute;left:-9999px;">Masuk ke Cashify</h1>

      @if ($errors->any())
        <div class="err">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.process') }}" autocomplete="on" novalidate>
        @csrf
        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" class="input" placeholder="Masukkan Email"
                 value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" class="input" placeholder="Masukkan Password" required>
        </div>

        <button class="btn" type="submit">Masuk</button>
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

  <!-- JS marquee: masuk dari kanan, keluar kiri, loop -->
  <script>
  (() => {
    const SPEED = 70; // px per detik (atur kecepatan di sini)

    const wrap  = document.getElementById('marqueeWrap');
    const item  = document.getElementById('marqueeItem');

    let x = 0;
    let last = null;
    let itemW = 0;
    let wrapW = 0;

    function measure() {
      // sembunyikan dulu di luar untuk ukur akurat
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

      // kalau sudah lewat kiri, reset ke kanan
      if (x < -itemW) {
        x = wrapW;
      }

      item.style.transform = `translate(${x}px, -50%)`;
      requestAnimationFrame(tick);
    }

    const onResize = () => measure();

    window.addEventListener('resize', onResize);
    window.addEventListener('load', () => {
      measure();
      requestAnimationFrame(tick);
    });
  })();
  </script>
</body>
</html>
