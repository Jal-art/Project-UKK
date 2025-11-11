@extends('layouts.cashify')
@section('title','Transaksi | Cashify')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
    <h2 style="margin:0;font-weight:600;color:#111">Transaksi</h2>
    {{-- tombol tambah dipindah ke panel filter di bawah --}}
  </div>

  @if(session('ok'))
    <div class="alert ok">{{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert err">{{ $errors->first() }}</div>
  @endif

  {{-- Panel filter + tombol tambah (di dalam border, kanan) --}}
  <div class="panel" style="margin-bottom:10px">
    <div class="toolbar" style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap">
      <form method="GET" action="{{ route('transaksi.index') }}" class="filter-date" autocomplete="off" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <label for="tglFilter" style="font-size:13px;color:#374151;font-weight:600">Cari berdasarkan tanggal</label>
        <input id="tglFilter" type="date" name="tanggal" value="{{ request('tanggal') }}"
               style="padding:9px 12px;border:1px solid #d0d0d0;border-radius:999px;background:#fff;outline:none;font-size:14px" />
        @if(request('tanggal'))
          <a class="btn-clear" href="{{ route('transaksi.index') }}"
             style="text-decoration:none;background:#ef4444;color:#fff;border-radius:999px;padding:8px 12px;font-weight:600">Reset</a>
        @endif
      </form>

      <a href="{{ route('transaksi.create') }}" class="btn-add"
         style="display:inline-flex;align-items:center;gap:8px;background:#6d5cff;color:#fff;text-decoration:none;
                padding:9px 14px;border-radius:999px;font-weight:600;
                box-shadow:0 6px 14px rgba(109,92,255,.24), 0 2px 0 rgba(0,0,0,.08) inset;
                transition:transform .06s, box-shadow .15s, opacity .15s">
        <span class="plus" style="display:grid;place-items:center;width:20px;height:20px;border-radius:999px;
                                   background:rgba(255,255,255,.2);font-weight:800;line-height:1">+</span>
        Tambah Transaksi
      </a>
    </div>
  </div>

  <div class="table-shell" style="overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb">
    <table class="trx-table" style="width:100%;border-collapse:separate;border-spacing:0">
      <thead>
        <tr>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">ID</th>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:150px">Tanggal</th>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">Total</th>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">Bayar</th>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">Kembalian</th>
          <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:240px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $t)
          @php
            $no = method_exists($items,'firstItem') ? $items->firstItem() + $loop->index : $loop->iteration;
          @endphp
          <tr>
            <td class="id-cell" style="color:#374151;padding:10px 12px;border-bottom:1px solid #f0f2f5"><b>{{ $t->kode }}</b></td>
            <td style="padding:10px 12px;border-bottom:1px solid #f0f2f5">{{ \Illuminate\Support\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
            <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">Rp {{ number_format($t->total_harga,0,',','.') }}</td>
            <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">Rp {{ number_format($t->uang_bayar,0,',','.') }}</td>
            <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">Rp {{ number_format($t->kembalian,0,',','.') }}</td>
            <td style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
              <div class="aksi" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('transaksi.struk', $t) }}" class="badge badge-purple" title="Lihat struk"
                   style="border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
                          padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;background:#6d5cff;
                          box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s">struk</a>

                <a href="{{ route('transaksi.show', $t) }}" class="badge badge-slate" title="Detail transaksi"
                   style="border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
                          padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;background:#374151;
                          box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s">detail</a>

                <button type="button" class="badge badge-red btn-open-del"
                        data-action="{{ route('transaksi.destroy', $t) }}"
                        data-name="{{ $t->kode.' • '. \Illuminate\Support\Carbon::parse($t->tanggal)->format('d/m/Y') }}"
                        aria-label="Hapus transaksi {{ $t->id_transaksi }}"
                        style="border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;
                               padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;background:#ef4444;
                               box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s">
                  hapus
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="empty" style="text-align:center;color:#6b7280">
              <div class="empty-box" style="display:inline-block;padding:12px 14px;border-radius:10px;background:#f9fafb;border:1px solid #e5e7eb">
                Belum ada transaksi.
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="panel-footer" style="margin-top:10px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;color:#6b7280">
    <div class="hint">
      @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
        Menampilkan <b>{{ $items->count() }}</b> dari <b>{{ $items->total() }}</b> transaksi
        @if(request('tanggal')) pada <b>{{ \Illuminate\Support\Carbon::parse(request('tanggal'))->format('d/m/Y') }}</b> @endif
      @else
        Total <b>{{ $items->count() }}</b> transaksi
      @endif
    </div>

    @if(method_exists($items,'links'))
      <div class="pagi" style="display:flex;justify-content:flex-end">
        {{ $items->withQueryString()->links() }}
      </div>
    @endif
  </div>

  {{-- Modal konfirmasi hapus --}}
  <div id="delBackdrop" class="modal-backdrop" aria-hidden="true">
    <div id="delModal" class="modal" role="dialog" aria-modal="true"
         aria-labelledby="delTitle" aria-describedby="delDesc" tabindex="-1">
      <div class="modal-head"><div class="modal-title" id="delTitle">Hapus Transaksi</div></div>
      <div class="modal-body" id="delDesc">
        Apakah kamu yakin ingin menghapus transaksi ini?
        <div id="delName"
             style="margin-top:8px;font-weight:600;color:#111;background:#f9fafb;border:1px solid #e5e7eb;padding:8px 10px;border-radius:8px;">
          {{-- diisi JS --}}
        </div>
      </div>
      <div class="modal-actions" style="display:flex;gap:10px;padding:0 16px 16px">
        <form id="delForm" action="#" method="POST" style="margin:0">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger" style="background:#ef4444;color:#fff;border:none;border-radius:10px;padding:9px 13px;font-weight:600;cursor:pointer">Ya, Hapus</button>
        </form>
        <button type="button" class="btn btn-ghost" id="btnCancelDel" style="background:#fff;border:1px solid #e5e7eb;color:#111;border-radius:10px;padding:9px 13px;font-weight:600;cursor:pointer">Batal</button>
      </div>
    </div>
  </div>

  <script>
    (() => {
      const tgl = document.getElementById('tglFilter');
      tgl?.addEventListener('change', () => tgl.form?.submit());

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
        delNameBox.textContent = name || '(tanpa info)';
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

      openBtns.forEach(btn => btn.addEventListener('click', () => openDel(btn.dataset.action, btn.dataset.name)));
      btnCancel?.addEventListener('click', closeDel);
      backdrop?.addEventListener('click', (e) => { if (e.target === backdrop) closeDel(); });
      addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop?.classList.contains('show')) { e.preventDefault(); closeDel(); }
      });

      // focus trap
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
