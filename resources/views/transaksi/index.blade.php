@extends('layouts.cashify')
@section('title','Transaksi | Cashify')

@section('content')
  <div class="page-head">
    <h2>Transaksi</h2>
  </div>

  @if(session('ok')) <div class="alert ok">{{ session('ok') }}</div> @endif
  @if ($errors->any()) <div class="alert err">{{ $errors->first() }}</div> @endif

  {{-- Filter & Tambah --}}
  <div class="panel mb-10">
    <div class="toolbar">
      <form method="GET" action="{{ route('transaksi.index') }}" class="filter-form" autocomplete="off">
        <label for="tglFilter">Cari berdasarkan tanggal</label>
        <input id="tglFilter" type="date" name="tanggal" value="{{ request('tanggal') }}" />
        @if(request('tanggal'))
          <a class="btn-clear" href="{{ route('transaksi.index') }}">Reset</a>
        @endif
      </form>

      <a href="{{ route('transaksi.create') }}" class="btn-primary">
        <span class="plus">+</span> Tambah Transaksi
      </a>
    </div>
  </div>

  {{-- Tabel --}}
  <div class="table-shell">
    <table class="trx-table">
      <thead>
        <tr>
          <th class="col-id">ID</th>
          <th class="col-date">Tanggal</th>
          <th class="col-num">Bayar</th>
          <th class="col-num">Kembalian</th>
          <th class="col-num">Total</th>
          <th class="col-action">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $t)
          <tr>
            <td class="id-cell"><b>{{ $t->kode ?? ('TRX-'.$t->id_transaksi) }}</b></td>
            <td>{{ \Illuminate\Support\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
            <td class="num">Rp {{ number_format($t->uang_bayar,0,',','.') }}</td>
            <td class="num">Rp {{ number_format($t->kembalian,0,',','.') }}</td>
            <td class="num strong">Rp {{ number_format($t->total_harga,0,',','.') }}</td>
            <td>
              <div class="aksi">
                <a href="{{ route('transaksi.struk', $t) }}" class="badge badge-purple" title="Lihat struk">struk</a>
                <a href="{{ route('transaksi.show', $t) }}" class="badge badge-slate" title="Detail transaksi">detail</a>

                <form action="{{ route('transaksi.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" style="display:inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="badge badge-red">hapus</button>
                </form>
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
        @if(request('tanggal')) pada <b>{{ \Illuminate\Support\Carbon::parse(request('tanggal'))->format('d/m/Y') }}</b> @endif
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

  <script>
    (() => {
      const t = document.getElementById('tglFilter');
      t?.addEventListener('change', () => t.form?.submit());
    })();
  </script>

  <style>
    /* ====== Layout basics ====== */
    .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
    .page-head h2{margin:0;font-weight:600;color:#111}

    .alert.ok{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;margin-bottom:10px;padding:10px 12px;border-radius:10px}
    .alert.err{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;margin-bottom:10px;padding:10px 12px;border-radius:10px}

    .mb-10{margin-bottom:10px}

    /* ====== Panel & toolbar ====== */
    .panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px}
    .panel .toolbar{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;padding:12px}

    .filter-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .filter-form label{font-size:13px;color:#374151;font-weight:600}
    .filter-form input[type="date"]{
      padding:9px 12px;border:1px solid #d0d0d0;border-radius:999px;background:#fff;outline:none;font-size:14px
    }
    .btn-clear{
      text-decoration:none;background:#ef4444;color:#fff;border-radius:999px;padding:8px 12px;font-weight:600
    }

    /* Tambah -> HIJAU */
    .btn-primary{
      display:inline-flex;align-items:center;gap:8px;background:#22c55e;color:#fff;text-decoration:none;
      padding:9px 14px;border-radius:999px;font-weight:600;
      box-shadow:0 6px 14px rgba(34,197,94,.24), 0 2px 0 rgba(0,0,0,.08) inset;
      transition:transform .06s, box-shadow .15s, opacity .15s
    }
    .btn-primary:hover{opacity:.98;box-shadow:0 8px 18px rgba(34,197,94,.30)}
    .btn-primary:active{transform:translateY(1px)}
    .btn-primary .plus{display:grid;place-items:center;width:20px;height:20px;border-radius:999px;background:rgba(255,255,255,.2);font-weight:800;line-height:1}

    /* ====== Table ====== */
    .table-shell{overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb}
    .trx-table{width:100%;border-collapse:separate;border-spacing:0;min-width:720px}
    .trx-table thead th{
      position:sticky;top:0;z-index:1;
      background:#f8fafc;color:#0f172a;text-align:left;font-weight:700;
      padding:12px;border-bottom:1px solid #e5e7eb;font-size:14px;letter-spacing:.2px
    }
    .trx-table tbody td{padding:11px 12px;border-bottom:1px solid #f0f2f5;font-size:14px}
    .trx-table tbody tr:nth-child(odd) td{background:#fafafa}
    .trx-table tbody tr:hover td{background:#f6f7fb}

    .col-id{min-width:140px}
    .col-date{min-width:140px}
    .col-num{min-width:140px;text-align:right}
    .col-action{min-width:220px}

    .id-cell{color:#374151}
    .num{font-weight:600;color:#111;text-align:right}
    .num.strong{font-weight:800}

    .aksi{display:flex;gap:8px;align-items:center;flex-wrap:wrap}

    .badge{
      border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
      padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;
      box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s
    }
    .badge:hover{opacity:.96}
    .badge:active{transform:translateY(1px)}
    .badge-purple{background:#6d5cff}
    .badge-slate{background:#374151}
    .badge-red{background:#ef4444}

    .empty{text-align:center;color:#6b7280}
    .empty-box{display:inline-block;padding:12px 14px;border-radius:10px;background:#f9fafb;border:1px solid #e5e7eb}

    .panel-footer{margin-top:10px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;color:#6b7280}
    .pagi :is(nav){display:flex;justify-content:flex-end}
  </style>
@endsection
