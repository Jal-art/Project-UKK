{{-- resources/views/produk/index.blade.php --}}
@extends('layouts.cashify')
@section('title','Produk | Cashify')

@section('content')
  <h2 class="ttl">Produk</h2>

  <div class="produk-panel">
    {{-- Toolbar: Tambah + Pencarian --}}
    <div class="panel-toolbar">
      <a href="{{ route('produk.create') }}" class="btn-add">
        <span class="plus">+</span> Tambah Produk
      </a>

      <form class="search" onsubmit="return false" autocomplete="off">
        <input
          id="searchNama"
          class="search-input"
          type="text"
          name="q"
          value="{{ $q ?? '' }}"
          placeholder="Cari nama ..."
          inputmode="text"
          pattern="[\p{L}\s]*"
          title="Hanya huruf dan spasi"
          oninput="this.value = this.value.normalize('NFC').replace(/[^\p{L}\s]/gu,'')"
        />
        <button class="search-btn" type="button" aria-label="Cari" id="btnSearch">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <circle cx="11" cy="11" r="7" stroke="#111" stroke-width="1.7"/>
            <path d="M20 20l-3.2-3.2" stroke="#111" stroke-width="1.7"/>
          </svg>
        </button>
      </form>
    </div>

    {{-- Tabel Produk --}}
    <div class="table-shell">
      <table class="produk-table">
        <thead>
          <tr>
            <th class="col-id">ID Produk</th>
            <th class="col-nama">Nama</th>
            <th class="col-ukuran">Ukuran</th>
            <th class="col-warna">Warna</th>
            <th class="col-harga">Harga</th>
            <th class="col-stok">Stok</th>
            <th class="col-aksi">Aksi</th>
          </tr>
        </thead>

        <tbody id="produkTbody">
        @forelse($produks as $p)
          <tr>
            <td class="id-cell">{{ $p->id_produk }}</td>
            <td class="nama-cell">{{ $p->nama_produk }}</td>
            <td>{{ $p->ukuran ?: '—' }}</td>
            <td>{{ $p->warna ?: '—' }}</td>
            <td class="harga">Rp {{ number_format((float)$p->harga,0,',','.') }}</td>
            <td class="stok">{{ (int)$p->stok }}</td>
            <td>
              <div class="aksi">
                {{-- EDIT -> kuning --}}
                <a href="{{ route('produk.edit',$p) }}" class="badge badge-yellow">edit</a>
                <button
                  type="button"
                  class="badge badge-red btn-open-del"
                  data-action="{{ route('produk.destroy',$p) }}"
                  data-name="{{ $p->nama_produk }}"
                  aria-label="Hapus {{ $p->nama_produk }}"
                >hapus</button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="empty">
              <div class="empty-box">Tidak ada data produk.</div>
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Info & Pagination --}}
    <div class="panel-footer">
      <div class="hint" id="produkHint">
        @if($produks instanceof \Illuminate\Pagination\LengthAwarePaginator)
          Menampilkan <b>{{ $produks->count() }}</b> dari <b>{{ $produks->total() }}</b> item
          @if($q) untuk pencarian “{{ $q }}” @endif
        @else
          Total <b>{{ $produks->count() }}</b> item
        @endif
      </div>

      @if(method_exists($produks,'links'))
        <div class="pagi" id="pagiWrap">
          {{ $produks->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Modal Konfirmasi Hapus --}}
  <div id="delBackdrop" class="modal-backdrop" aria-hidden="true">
    <div id="delModal" class="modal" role="dialog" aria-modal="true"
         aria-labelledby="delTitle" aria-describedby="delDesc" tabindex="-1">
      <div class="modal-head">
        <div class="modal-title" id="delTitle">Hapus Produk</div>
      </div>

      <div class="modal-body" id="delDesc">
        Apakah kamu yakin ingin menghapus produk ini?
        <div id="delName"
             style="margin-top:8px;font-weight:600;color:#111;background:#f9fafb;border:1px solid #e5e7eb;padding:8px 10px;border-radius:8px;">
          {{-- diisi via JS --}}
        </div>
      </div>

      <div class="modal-actions">
        <form id="delForm" action="#" method="POST" style="margin:0">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
        <button type="button" class="btn btn-ghost" id="btnCancelDel">Batal</button>
      </div>
    </div>
  </div>

  {{-- Styling khusus halaman produk --}}
  <style>
    /* Judul + animasi */
    .ttl{
      margin:0 0 12px;
      font-weight:600;
      color:#1f2937;
      opacity:0;
      transform:translateY(-10px);
      animation:titleIn .6s cubic-bezier(.18,.89,.32,1.28) .05s forwards;
    }

    .produk-panel{
      background:#f3f4f6;
      border:1px solid #d1d5db;
      border-radius:14px;
      padding:14px;
      box-shadow:0 3px 0 rgba(0,0,0,.08), 0 12px 22px rgba(0,0,0,.08);
      opacity:0;
      transform:translateY(18px) scale(.97);
      animation:cardIn .7s cubic-bezier(.18,.89,.32,1.28) .12s forwards;
    }

    .panel-toolbar,
    .table-shell,
    .panel-footer{
      opacity:0;
      transform:translateY(10px);
      animation:itemIn .45s ease-out forwards;
    }
    .panel-toolbar{animation-delay:.22s}
    .table-shell{animation-delay:.30s}
    .panel-footer{animation-delay:.38s}

    .panel-toolbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:10px;
      flex-wrap:wrap;
    }

    /* Tambah -> HIJAU */
    .btn-add{
      display:inline-flex;
      align-items:center;
      gap:8px;
      background:#22c55e;
      color:#fff;
      text-decoration:none;
      padding:9px 14px;
      border-radius:999px;
      font-weight:600;
      box-shadow:0 6px 14px rgba(34,197,94,.24), 0 2px 0 rgba(0,0,0,.08) inset;
      transition:transform .06s, box-shadow .15s, opacity .15s;
    }
    .btn-add:hover{
      opacity:.98;
      box-shadow:0 8px 18px rgba(34,197,94,.3);
    }
    .btn-add:active{transform:translateY(1px)}
    .btn-add .plus{
      display:grid;
      place-items:center;
      width:20px;
      height:20px;
      border-radius:999px;
      background:rgba(255,255,255,.2);
      font-weight:800;
      line-height:1;
    }

    .search{
      display:flex;
      align-items:center;
      gap:8px;
    }
    .search-input{
      width:260px;
      padding:10px 12px;
      border:1px solid #d0d0d0;
      border-radius:999px;
      background:#fff;
      outline:none;
      font-size:14px;
      transition:border-color .15s, box-shadow .15s;
    }
    .search-input:focus{
      border-color:#c4c4c4;
      box-shadow:0 0 0 3px rgba(109,92,255,.12);
    }
    .search-btn{
      width:36px;
      height:36px;
      display:grid;
      place-items:center;
      border:1px solid #e5e7eb;
      border-radius:10px;
      background:#fff;
      cursor:pointer;
      transition:box-shadow .12s, transform .08s;
    }
    .search-btn:hover{
      box-shadow:0 8px 18px rgba(0,0,0,.08);
      transform:translateY(-1px);
    }
    .search-btn:active{
      transform:translateY(1px);
      box-shadow:0 4px 10px rgba(0,0,0,.06);
    }

    .table-shell{
      overflow:auto;
      border-radius:12px;
      background:#fff;
      border:1px solid #e5e7eb;
    }
    .produk-table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      min-width:720px;
    }
    .produk-table thead th{
      background:#f3f4f6;
      color:#111;
      font-weight:600;
      padding:11px 12px;
      border-bottom:1px solid #e5e7eb;
      font-size:14px;
      text-align:center;      /* header sejajar tengah */
      letter-spacing:.2px;
    }
    .produk-table tbody td{
      padding:10px 12px;
      border-bottom:1px solid #f0f2f5;
      font-size:14px;
      text-align:center;      /* isi sejajar tengah */
    }
    .produk-table tbody tr:hover td{
      background:#fafafa;
    }

    /* lebar kolom biar konsisten */
    .col-id{min-width:64px}
    .col-nama{min-width:200px}
    .col-ukuran{min-width:140px}
    .col-warna{min-width:150px}
    .col-harga{min-width:160px}
    .col-stok{min-width:100px}
    .col-aksi{min-width:170px}

    .id-cell{
      color:#374151;
    }
    .nama-cell{
      font-weight:600;
      color:#111;
      text-align:left;        /* nama tetap kiri biar enak dibaca */
    }
    .harga,
    .stok{
      font-weight:600;
      color:#111;
    }

    .aksi{
      display:flex;
      gap:8px;
      align-items:center;
      justify-content:center; /* aksi di tengah kolom */
      flex-wrap:wrap;
    }
    .badge{
      border:none;
      cursor:pointer;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:6px 10px;
      border-radius:999px;
      font-weight:700;
      font-size:12.5px;
      color:#fff;
      box-shadow:0 2px 0 rgba(0,0,0,.06) inset;
      transition:transform .06s, opacity .12s;
    }
    .badge:hover{opacity:.96}
    .badge:active{transform:translateY(1px)}
    .badge-yellow{background:#f59e0b}
    .badge-yellow:hover{opacity:.98}
    .badge-red{background:#ef4444}

    .empty{
      text-align:center;
      color:#6b7280;
    }
    .empty-box{
      display:inline-block;
      padding:12px 14px;
      border-radius:10px;
      background:#f9fafb;
      border:1px solid #e5e7eb;
    }

    .panel-footer{
      margin-top:10px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      font-size:13px;
      color:#6b7280;
    }
    .pagi :is(nav){
      display:flex;
      justify-content:flex-end;
    }

    /* Modal */
    .modal-backdrop{
      position:fixed;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      background:rgba(15,23,42,.35);
      z-index:40;
      opacity:0;
      pointer-events:none;
      transition:opacity .22s ease;
    }
    .modal-backdrop.show{
      opacity:1;
      pointer-events:auto;
    }
    .modal{
      width:100%;
      max-width:360px;
      background:#fff;
      border-radius:16px;
      padding:14px 16px 16px;
      box-shadow:0 22px 45px rgba(15,23,42,.35);
      transform:translateY(16px) scale(.96);
      opacity:0;
      animation:modalIn .24s ease-out forwards;
    }
    .modal.hiding{
      animation:modalOut .22s ease-in forwards;
    }
    .modal-head{margin-bottom:8px;}
    .modal-title{
      font-weight:700;
      font-size:15px;
      color:#111827;
    }
    .modal-body{
      font-size:14px;
      color:#374151;
      margin-bottom:12px;
    }
    .modal-actions{
      display:flex;
      justify-content:flex-end;
      gap:8px;
    }

    .btn{
      border:none;
      cursor:pointer;
      padding:9px 14px;
      border-radius:999px;
      font-weight:600;
      letter-spacing:.2px;
      font-size:13px;
      transition:transform .06s ease, box-shadow .12s ease, opacity .16s ease;
    }
    .btn:active{
      transform:translateY(1px);
      box-shadow:0 4px 0 rgba(0,0,0,.12);
    }
    .btn-danger{
      background:#ef4444;
      color:#fff;
      box-shadow:0 6px 14px rgba(239,68,68,.24);
    }
    .btn-danger:hover{opacity:.97}
    .btn-ghost{
      background:#f3f4f6;
      color:#111827;
      box-shadow:0 2px 0 rgba(0,0,0,.08) inset;
    }
    .btn-ghost:hover{opacity:.97}

    @media(max-width:720px){
      .panel-toolbar{
        flex-direction:column;
        align-items:stretch;
      }
      .search{
        justify-content:space-between;
      }
      .search-input{
        flex:1;
      }
      .panel-footer{
        flex-direction:column;
        align-items:flex-start;
      }
    }

    /* Animasi keyframes */
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
    @keyframes modalIn{
      0%{opacity:0;transform:translateY(14px) scale(.95)}
      100%{opacity:1;transform:translateY(0) scale(1)}
    }
    @keyframes modalOut{
      0%{opacity:1;transform:translateY(0) scale(1)}
      100%{opacity:0;transform:translateY(14px) scale(.95)}
    }
  </style>

  {{-- Script: live search, pagination AJAX, modal hapus --}}
  <script>
  (() => {
    const input     = document.getElementById('searchNama');
    const btnSearch = document.getElementById('btnSearch');
    const tbody     = document.getElementById('produkTbody');
    const hintBox   = document.getElementById('produkHint');
    const pagiWrap  = document.getElementById('pagiWrap');
    let typingDelay = null;
    let lastQuery   = (input?.value ?? '').trim();

    function cleanText(s=''){
      return s.normalize('NFC')
              .replace(/[^\p{L}\s]/gu,'')
              .replace(/\s+/g,' ')
              .trim();
    }

    function escapeHtml(s=''){
      return (s || '').toString().replace(/[&<>"']/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
      }[c]));
    }

    function fetchList(urlOrQuery){
      let url;
      if (typeof urlOrQuery === 'string' && urlOrQuery.startsWith('http')){
        url = new URL(urlOrQuery);
        const q = cleanText(url.searchParams.get('q') || '');
        if (q) url.searchParams.set('q', q); else url.searchParams.delete('q');
      } else {
        url = new URL(`{{ route('produk.index') }}`);
        const q = cleanText(typeof urlOrQuery === 'string' ? urlOrQuery : (input?.value ?? ''));
        if (q) url.searchParams.set('q', q);
      }
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(json => render(json, url.searchParams.get('q') || ''))
        .catch(() => {});
    }

    // EXPECTED JSON (server-side):
    // items: [{id_produk, nama_produk, ukuran, warna, harga, stok, edit_url, del_url}]
    function render(data, q){
      const items = data.items || [];
      const rows = items.length ? items.map((p) => `
        <tr>
          <td class="id-cell">${p.id_produk}</td>
          <td class="nama-cell">${escapeHtml(p.nama_produk)}</td>
          <td>${escapeHtml(p.ukuran || '—')}</td>
          <td>${escapeHtml(p.warna || '—')}</td>
          <td class="harga">Rp ${p.harga}</td>
          <td class="stok">${p.stok}</td>
          <td>
            <div class="aksi">
              <a href="${p.edit_url}" class="badge badge-yellow">edit</a>
              <button type="button" class="badge badge-red btn-open-del"
                      data-action="${p.del_url}"
                      data-name="${escapeHtml(p.nama_produk)}">hapus</button>
            </div>
          </td>
        </tr>
      `).join('') : `
        <tr>
          <td colspan="7" class="empty">
            <div class="empty-box">Tidak ada data produk.</div>
          </td>
        </tr>
      `;
      tbody.innerHTML = rows;

      if (hintBox && data.meta) {
        const base = `Menampilkan <b>${data.meta.count}</b> dari <b>${data.meta.total}</b> item`;
        hintBox.innerHTML = q ? `${base} untuk pencarian “${escapeHtml(q)}”` : base;
      }

      if (pagiWrap) {
        pagiWrap.innerHTML = data.pagination_html || '';
        bindPagination();
      }

      bindDeleteButtons();
    }

    function bindPagination(){
      if (!pagiWrap) return;
      const links = pagiWrap.querySelectorAll('a[href]');
      links.forEach(a => {
        a.addEventListener('click', (e) => {
          e.preventDefault();
          fetchList(a.href);
        });
      });
    }

    function bindDeleteButtons(){
      const openBtns   = document.querySelectorAll('.btn-open-del');
      const backdrop   = document.getElementById('delBackdrop');
      const modal      = document.getElementById('delModal');
      const delForm    = document.getElementById('delForm');
      const delNameBox = document.getElementById('delName');
      const btnCancel  = document.getElementById('btnCancelDel');
      const OUT_MS     = 220;
      let lastFocused  = null;

      function openDel(action, name) {
        lastFocused = document.activeElement;
        delForm.setAttribute('action', action);
        delNameBox.textContent = name || '(tanpa nama)';
        backdrop.classList.add('show');
        modal.classList.remove('hiding');
        setTimeout(() => delForm.querySelector('button[type="submit"]')?.focus(), 0);
        backdrop.removeAttribute('aria-hidden');
      }
      function closeDel() {
        modal.classList.add('hiding');
        setTimeout(() => {
          backdrop.classList.remove('show');
          backdrop.setAttribute('aria-hidden','true');
          modal.classList.remove('hiding');
          lastFocused?.focus();
        }, OUT_MS);
      }

      openBtns.forEach(btn => {
        btn.addEventListener('click', () => openDel(btn.dataset.action, btn.dataset.name));
      });
      btnCancel?.addEventListener('click', closeDel);
      backdrop?.addEventListener('click', (e) => {
        if (e.target === backdrop) closeDel();
      });
      addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop?.classList.contains('show')) {
          e.preventDefault();
          closeDel();
        }
      });

      document.addEventListener('keydown', (e) => {
        if (e.key !== 'Tab' || !backdrop?.classList.contains('show')) return;
        const f = modal.querySelectorAll('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])');
        const first = f[0], last = f[f.length-1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      });
    }

    // Debounce input search
    input?.addEventListener('input', () => {
      clearTimeout(typingDelay);
      typingDelay = setTimeout(() => {
        const now = cleanText(input.value);
        if (now !== lastQuery){
          lastQuery = now;
          fetchList(now);
        }
      }, 250);
    });

    btnSearch?.addEventListener('click', () => {
      const now = cleanText(input.value);
      if (now !== lastQuery){
        lastQuery = now;
        fetchList(now);
      }
    });

    bindPagination();
    bindDeleteButtons();
  })();
  </script>
@endsection
