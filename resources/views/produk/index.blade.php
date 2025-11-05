{{-- resources/views/produk/index.blade.php --}}
@extends('layouts.cashify')
@section('title','Produk | Cashify')

@section('content')
  {{-- Judul halaman --}}
  <h2 style="margin:0 0 12px;font-weight:600;color:#111">Produk</h2>

  <div class="produk-panel">
    {{-- Toolbar: Tambah + Pencarian --}}
    <div class="panel-toolbar">
      <a href="{{ route('produk.create') }}" class="btn-add">
        <span class="plus">+</span> Tambah Produk
      </a>

      <form method="GET" action="{{ route('produk.index') }}" class="search">
        <input
          class="search-input"
          type="text"
          name="q"
          value="{{ request('q') }}"
          placeholder="Cari nama / warna / ukuran" />
        <button class="search-btn" type="submit" aria-label="Cari">
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
            <th style="width:64px">id</th>
            <th>Nama</th>
            <th style="width:140px">Ukuran</th>
            <th style="width:150px">Warna</th>
            <th style="width:160px">Harga</th>
            <th style="width:100px">Stok</th>
            <th style="width:170px">Aksi</th>
          </tr>
        </thead>

        <tbody>
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
                <a href="{{ route('produk.edit',$p) }}" class="badge badge-green">edit</a>

                {{-- Tombol Hapus -> buka modal konfirmasi (gaya sama dengan logout) --}}
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
      <div class="hint">
        @if($produks instanceof \Illuminate\Pagination\LengthAwarePaginator)
          Menampilkan <b>{{ $produks->count() }}</b> dari <b>{{ $produks->total() }}</b> item
          @if(request('q')) untuk pencarian “{{ request('q') }}” @endif
        @else
          Total <b>{{ $produks->count() }}</b> item
        @endif
      </div>

      @if(method_exists($produks,'links'))
        <div class="pagi">
          {{ $produks->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Modal Konfirmasi Hapus (reuse style modal Logout dari layout) --}}
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
    .produk-panel{
      background:#f3f4f6;border:1px solid #d1d5db;border-radius:14px;
      padding:14px;box-shadow:0 1px 2px rgba(0,0,0,.05), 0 8px 18px rgba(0,0,0,.08);
    }
    .panel-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px}
    .btn-add{
      display:inline-flex;align-items:center;gap:8px;background:#6d5cff;color:#fff;text-decoration:none;
      padding:9px 14px;border-radius:999px;font-weight:600;
      box-shadow:0 6px 14px rgba(109,92,255,.24), 0 2px 0 rgba(0,0,0,.08) inset;
      transition:transform .06s, box-shadow .15s, opacity .15s;
    }
    .btn-add:hover{opacity:.98;box-shadow:0 8px 18px rgba(109,92,255,.3)}
    .btn-add:active{transform:translateY(1px)}
    .btn-add .plus{display:grid;place-items:center;width:20px;height:20px;border-radius:999px;background:rgba(255,255,255,.2);font-weight:800;line-height:1}

    .search{display:flex;align-items:center;gap:8px}
    .search-input{width:260px;padding:10px 12px;border:1px solid #d0d0d0;border-radius:999px;background:#fff;outline:none}
    .search-input:focus{border-color:#c4c4c4;box-shadow:0 0 0 3px rgba(109,92,255,.12)}
    .search-btn{width:36px;height:36px;display:grid;place-items:center;border:1px solid #e5e7eb;border-radius:10px;background:#fff;cursor:pointer;transition:box-shadow .12s}
    .search-btn:hover{box-shadow:0 8px 18px rgba(0,0,0,.08)}

    .table-shell{overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb}
    .produk-table{width:100%;border-collapse:separate;border-spacing:0}
    .produk-table thead th{
      background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;font-size:14px
    }
    .produk-table tbody td{padding:10px 12px;border-bottom:1px solid #f0f2f5;font-size:14px}
    .produk-table tbody tr:hover td{background:#fafafa}
    .id-cell{text-align:center;color:#374151}
    .nama-cell{font-weight:600;color:#111}
    .harga,.stok{font-weight:600;color:#111}
    .stok{text-align:center}

    .aksi{display:flex;gap:8px;align-items:center}
    .badge{
      border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
      padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;
      box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s;
    }
    .badge:hover{opacity:.96}
    .badge:active{transform:translateY(1px)}
    .badge-green{background:#22c55e}
    .badge-red{background:#ef4444}

    .empty{text-align:center;color:#6b7280}
    .empty-box{display:inline-block;padding:12px 14px;border-radius:10px;background:#f9fafb;border:1px solid #e5e7eb}

    .panel-footer{margin-top:10px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;color:#6b7280}
    .pagi :is(nav){display:flex;justify-content:flex-end}

    @media(max-width:720px){
      .panel-toolbar{flex-direction:column;align-items:stretch}
      .search{justify-content:space-between}
      .search-input{flex:1}
    }
  </style>

  {{-- Script modal hapus (match animasi modal logout) --}}
  <script>
  (() => {
    const openBtns   = document.querySelectorAll('.btn-open-del');
    const backdrop   = document.getElementById('delBackdrop');
    const modal      = document.getElementById('delModal');
    const delForm    = document.getElementById('delForm');
    const delNameBox = document.getElementById('delName');
    const btnCancel  = document.getElementById('btnCancelDel');
    const OUT_MS     = 220; // sama dengan modal logout di layout
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
    backdrop?.addEventListener('click', (e) => { if (e.target === backdrop) closeDel(); });
    addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && backdrop?.classList.contains('show')) { e.preventDefault(); closeDel(); }
    });

    // Focus trap sederhana
    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Tab' || !backdrop?.classList.contains('show')) return;
      const f = modal.querySelectorAll('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])');
      const first = f[0], last = f[f.length-1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  })();
  </script>
@endsection
