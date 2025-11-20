{{-- resources/views/transaksi/index.blade.php --}}
@extends('layouts.cashify')
@section('title','Transaksi | Cashify')

@section('content')
  <h2 class="ttl">Transaksi</h2>

  @if(session('ok')) <div class="alert ok">{{ session('ok') }}</div> @endif
  @if ($errors->any()) <div class="alert err">{{ $errors->first() }}</div> @endif

  <div class="trx-panel">
    {{-- Toolbar: Filter tanggal + Tambah --}}
    <div class="panel-toolbar">
      <form method="GET" action="{{ route('transaksi.index') }}" class="filter-form" autocomplete="off">
        <label for="tglFilter">Cari berdasarkan tanggal</label>
        <input id="tglFilter" type="date" name="tanggal" value="{{ request('tanggal') }}" />
        @if(request('tanggal'))
          <a class="btn-clear" href="{{ route('transaksi.index') }}">Reset</a>
        @endif
      </form>

      <a href="{{ route('transaksi.create') }}" class="btn-add">
        <span class="plus">+</span> Tambah Transaksi
      </a>
    </div>

    {{-- Tabel --}}
    <div class="table-shell">
      <table class="trx-table">
        <thead>
          <tr>
            <th class="col-id">ID Transaksi</th>
            <th class="col-date">Tanggal</th>
            <th class="col-num">Bayar</th>
            <th class="col-num">Kembalian</th>
            <th class="col-num">Total</th>
            <th class="col-action">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $t)
            @php
              $kode = $t->kode ?? ('TRX-'.$t->id_transaksi);
            @endphp
            <tr>
              <td class="id-cell"><b>{{ $kode }}</b></td>
              <td>{{ \Illuminate\Support\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
              <td class="num">Rp {{ number_format($t->uang_bayar,0,',','.') }}</td>
              <td class="num">Rp {{ number_format($t->kembalian,0,',','.') }}</td>
              <td class="num strong">Rp {{ number_format($t->total_harga,0,',','.') }}</td>
              <td>
                <div class="aksi">
                  <a href="{{ route('transaksi.struk', $t) }}" class="badge badge-purple" title="Lihat struk">struk</a>
                  <a href="{{ route('transaksi.show', $t) }}" class="badge badge-slate" title="Detail transaksi">detail</a>

                  {{-- tombol hapus pakai modal --}}
                  <button
                    type="button"
                    class="badge badge-red btn-open-del"
                    data-action="{{ route('transaksi.destroy', $t) }}"
                    data-name="{{ $kode }}"
                    aria-label="Hapus transaksi {{ $kode }}"
                  >hapus</button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="empty">
                <div class="empty-box">Belum ada transaksi.</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Footer / Paging --}}
    <div class="panel-footer">
      <div class="hint">
        @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
          Menampilkan <b>{{ $items->count() }}</b> dari <b>{{ $items->total() }}</b> transaksi
          @if(request('tanggal'))
            pada <b>{{ \Illuminate\Support\Carbon::parse(request('tanggal'))->format('d/m/Y') }}</b>
          @endif
        @else
          Total <b>{{ $items->count() }}</b> transaksi
        @endif
      </div>

      @if(method_exists($items,'links'))
        <div class="pagi">
          {{ $items->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Modal Konfirmasi Hapus (sama gaya produk) --}}
  <div id="delBackdrop" class="modal-backdrop" aria-hidden="true">
    <div id="delModal" class="modal" role="dialog" aria-modal="true"
         aria-labelledby="delTitle" aria-describedby="delDesc" tabindex="-1">
      <div class="modal-head">
        <div class="modal-title" id="delTitle">Hapus Transaksi</div>
      </div>

      <div class="modal-body" id="delDesc">
        Apakah kamu yakin ingin menghapus transaksi ini?
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

  <script>
    (() => {
      // auto-submit filter tanggal
      const t = document.getElementById('tglFilter');
      t?.addEventListener('change', () => t.form?.submit());

      // ====== Modal hapus ======
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
        delNameBox.textContent = name || '(tanpa kode)';
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

      // ESC menutup modal
      addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop?.classList.contains('show')) {
          e.preventDefault();
          closeDel();
        }
      });

      // Fokus trap
      document.addEventListener('keydown', (e) => {
        if (e.key !== 'Tab' || !backdrop?.classList.contains('show')) return;
        const f = modal.querySelectorAll('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])');
        if (!f.length) return;
        const first = f[0], last = f[f.length-1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      });
    })();
  </script>

  <style>
    .ttl{
      margin:0 0 12px;
      font-weight:600;
      color:#1f2937;
      opacity:0;
      transform:translateY(-10px);
      animation:titleIn .6s cubic-bezier(.18,.89,.32,1.28) .05s forwards;
    }

    .alert.ok{
      background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;
      margin-bottom:10px;padding:10px 12px;border-radius:10px;font-size:13px;
    }
    .alert.err{
      background:#fee2e2;color:#991b1b;border:1px solid #fecaca;
      margin-bottom:10px;padding:10px 12px;border-radius:10px;font-size:13px;
    }

    .trx-panel{
      background:#f3f4f6;border:1px solid #d1d5db;border-radius:14px;
      padding:14px;box-shadow:0 3px 0 rgba(0,0,0,.08), 0 12px 22px rgba(0,0,0,.08);
      opacity:0;transform:translateY(18px) scale(.97);
      animation:cardIn .7s cubic-bezier(.18,.89,.32,1.28) .12s forwards;
    }

    .panel-toolbar,
    .table-shell,
    .panel-footer{
      opacity:0;transform:translateY(10px);
      animation:itemIn .45s ease-out forwards;
    }
    .panel-toolbar{animation-delay:.22s}
    .table-shell{animation-delay:.30s}
    .panel-footer{animation-delay:.38s}

    .panel-toolbar{
      display:flex;align-items:center;justify-content:space-between;
      gap:12px;margin-bottom:10px;flex-wrap:wrap;
    }

    .filter-form{
      display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:13px;
    }
    .filter-form label{
      font-size:13px;color:#374151;font-weight:600;
    }
    .filter-form input[type="date"]{
      padding:9px 12px;border:1px solid #d0d0d0;border-radius:999px;
      background:#fff;outline:none;font-size:14px;
      transition:border-color .15s, box-shadow .15s;
    }
    .filter-form input[type="date"]:focus{
      border-color:#c4c4c4;box-shadow:0 0 0 3px rgba(109,92,255,.12);
    }

    .btn-clear{
      text-decoration:none;background:#ef4444;color:#fff;border-radius:999px;
      padding:8px 12px;font-weight:600;font-size:12.5px;
      box-shadow:0 2px 0 rgba(0,0,0,.08) inset;
      transition:opacity .15s, transform .06s;
    }
    .btn-clear:hover{opacity:.96}
    .btn-clear:active{transform:translateY(1px)}

    .btn-add{
      display:inline-flex;align-items:center;gap:8px;background:#22c55e;color:#fff;text-decoration:none;
      padding:9px 14px;border-radius:999px;font-weight:600;
      box-shadow:0 6px 14px rgba(34,197,94,.24), 0 2px 0 rgba(0,0,0,.08) inset;
      transition:transform .06s, box-shadow .15s, opacity .15s;
    }
    .btn-add:hover{opacity:.98;box-shadow:0 8px 18px rgba(34,197,94,.30)}
    .btn-add:active{transform:translateY(1px)}
    .btn-add .plus{
      display:grid;place-items:center;width:20px;height:20px;border-radius:999px;
      background:rgba(255,255,255,.2);font-weight:800;line-height:1;
    }

    .table-shell{
      overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb;
    }
    .trx-table{
      width:100%;border-collapse:separate;border-spacing:0;min-width:720px;
    }
    .trx-table thead th{
      background:#f3f4f6;color:#0f172a;font-weight:700;
      padding:11px 12px;border-bottom:1px solid #e5e7eb;font-size:14px;
      letter-spacing:.2px;
      text-align:center;      /* header sejajar tengah */
    }
    .trx-table tbody td{
      padding:10px 12px;border-bottom:1px solid #f0f2f5;font-size:14px;
      text-align:center;      /* isi sejajar tengah */
    }
    .trx-table tbody tr:hover td{background:#f6f7fb}

    .col-id{min-width:140px}
    .col-date{min-width:140px}
    .col-num{min-width:140px}
    .col-action{min-width:220px}

    .id-cell{color:#374151}
    .num{
      font-weight:600;
      color:#111;
      /* kalau nanti mau balik rata kanan, cukup tambahin: text-align:right; */
    }
    .num.strong{font-weight:800}

    .aksi{display:flex;gap:8px;align-items:center;flex-wrap:wrap}

    .badge{
      border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
      padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;
      box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s;
    }
    .badge:hover{opacity:.96}
    .badge:active{transform:translateY(1px)}
    .badge-purple{background:#6d5cff}
    .badge-slate{background:#374151}
    .badge-red{background:#ef4444}

    .empty{text-align:center;color:#6b7280}
    .empty-box{
      display:inline-block;padding:12px 14px;border-radius:10px;
      background:#f9fafb;border:1px solid #e5e7eb;
    }

    .panel-footer{
      margin-top:10px;display:flex;align-items:center;justify-content:space-between;
      gap:12px;font-size:13px;color:#6b7280;
    }
    .pagi :is(nav){display:flex;justify-content:flex-end}

    .modal-backdrop{
      position:fixed;inset:0;display:flex;align-items:center;justify-content:center;
      background:rgba(15,23,42,.35);z-index:40;
      opacity:0;pointer-events:none;transition:opacity .22s ease;
    }
    .modal-backdrop.show{
      opacity:1;pointer-events:auto;
    }
    .modal{
      width:100%;max-width:360px;background:#fff;border-radius:16px;
      padding:14px 16px 16px;box-shadow:0 22px 45px rgba(15,23,42,.35);
      transform:translateY(16px) scale(.96);opacity:0;
      animation:modalIn .24s ease-out forwards;
    }
    .modal.hiding{animation:modalOut .22s ease-in forwards}
    .modal-head{margin-bottom:8px}
    .modal-title{font-weight:700;font-size:15px;color:#111827}
    .modal-body{font-size:14px;color:#374151;margin-bottom:12px}
    .modal-actions{display:flex;justify-content:flex-end;gap:8px}

    .btn{
      border:none;cursor:pointer;padding:9px 14px;border-radius:999px;
      font-weight:600;letter-spacing:.2px;font-size:13px;
      transition:transform .06s ease, box-shadow .12s ease, opacity .16s ease;
    }
    .btn:active{
      transform:translateY(1px);box-shadow:0 4px 0 rgba(0,0,0,.12);
    }
    .btn-danger{
      background:#ef4444;color:#fff;box-shadow:0 6px 14px rgba(239,68,68,.24);
    }
    .btn-danger:hover{opacity:.97}
    .btn-ghost{
      background:#f3f4f6;color:#111827;box-shadow:0 2px 0 rgba(0,0,0,.08) inset;
    }
    .btn-ghost:hover{opacity:.97}

    @media(max-width:720px){
      .panel-toolbar{flex-direction:column;align-items:stretch}
      .panel-footer{flex-direction:column;align-items:flex-start}
    }

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
@endsection
