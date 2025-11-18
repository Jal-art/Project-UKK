{{-- resources/views/transaksi/create.blade.php --}}
@extends('layouts.cashify')
@section('title','Tambah Transaksi | Cashify')

@section('content')
  <h2 class="ttl">Tambah Transaksi</h2>

  @if ($errors->any())
    <div class="alert err">{{ $errors->first() }}</div>
  @endif

  {{-- alert client-side (qty melebihi stok, dll) --}}
  <div id="jsAlert" class="alert err" style="display:none;"></div>

  <div class="trx-box">
    <form id="trxForm" method="POST" action="{{ route('transaksi.store') }}" novalidate autocomplete="off">
      @csrf

      {{-- Baris: tanggal + pilih produk --}}
      <div class="grid-top">
        <div class="fld">
          <label>Tanggal</label>
          <input
            type="date"
            name="tanggal"
            class="in"
            value="{{ old('tanggal', now()->toDateString()) }}"
          >
        </div>

        <div class="fld">
          <label>Pilih Produk</label>
          <div class="pick">
            <select id="produkSelect" class="in">
              <option value="">— pilih —</option>
              @foreach($produks as $p)
                <option value="{{ $p->id_produk }}"
                        data-harga="{{ (int)$p->harga }}"
                        data-stok="{{ (int)$p->stok }}">
                  {{ $p->nama_produk }}
                  {{ $p->ukuran ? '• '.$p->ukuran : '' }}
                  {{ $p->warna ? '• '.$p->warna : '' }}
                </option>
              @endforeach
            </select>
            <input
              id="qtyInput"
              class="in"
              type="number"
              min="1"
              step="1"
              value="1"
              inputmode="numeric"
            >
            <button type="button" id="btnAdd" class="btn btn-green">Tambah</button>
          </div>
          <div id="stokInfo" class="hint"></div>
        </div>
      </div>

      {{-- Tabel keranjang --}}
      <div class="table-shell cart-shell">
        <table class="d-table" id="cartTable">
          <thead>
            <tr>
              <th>Produk</th>
              <th style="width:120px">Harga</th>
              <th style="width:90px">Qty</th>
              <th style="width:160px">Subtotal</th>
              <th style="width:90px">Aksi</th>
            </tr>
          </thead>
          <tbody id="cartBody">
            <tr class="empty-row">
              <td colspan="5" class="empty">Belum ada item.</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="tfoot-label">Total</th>
              <th id="grandTotal" class="num">Rp 0</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      {{-- Pembayaran --}}
      <div class="pay-panel">
        <div class="pay-item">
          <div class="pay-label">Total Bayar</div>
          <input id="totalInput" type="text" class="in in-readonly" readonly value="Rp 0">
        </div>
        <div class="pay-item">
          <div class="pay-label">Uang Diterima</div>
          <input
            id="uangBayarInput"
            name="uang_bayar"
            type="number"
            min="0"
            step="1"
            class="in"
            placeholder="Masukkan nominal"
            inputmode="numeric"
          >
        </div>
        <div class="pay-item">
          <div class="pay-label">Kembalian</div>
          <input id="kembalianInput" type="text" class="in in-readonly" readonly value="Rp 0">
        </div>
      </div>

      {{-- field hidden items[] --}}
      <div id="itemsHolder"></div>

      {{-- Footer form --}}
      <div class="actions">
        <a href="{{ route('transaksi.index') }}" class="btn btn-gray">Kembali</a>
        <button type="submit" class="btn btn-green">Bayar</button>
      </div>
    </form>
  </div>

  <style>
    .ttl{
      margin:0 0 12px;font-weight:600;color:#1f2937;
      opacity:0;transform:translateY(-10px);
      animation:titleIn .6s cubic-bezier(.18,.89,.32,1.28) .05s forwards;
    }

    .alert.err{
      background:#fee2e2;color:#991b1b;border:1px solid #fecaca;
      padding:10px 12px;border-radius:10px;margin-bottom:12px;font-size:13px;
    }

    .trx-box{
      background:#f3f4f6;
      border:1px solid #d1d5db;
      border-radius:14px;
      padding:16px;
      max-width:980px;
      width:100%;
      margin:0 auto;
      box-shadow:0 3px 0 rgba(0,0,0,.08), 0 12px 22px rgba(0,0,0,.08);
      opacity:0;transform:translateY(18px) scale(.97);
      animation:cardIn .7s cubic-bezier(.18,.89,.32,1.28) .12s forwards;
    }

    .grid-top{
      display:grid;grid-template-columns:1fr;gap:12px;
      opacity:0;transform:translateY(10px);animation:itemIn .45s ease-out .22s forwards;
    }

    .fld{margin-bottom:0}
    .fld label{
      display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;
    }

    .pick{
      display:flex;gap:8px;align-items:center;flex-wrap:wrap;
    }

    .in{
      width:100%;background:#fff;border:1px solid #d1d5db;border-radius:10px;
      padding:10px 12px;font-size:14px;outline:none;
      box-shadow:0 2px 0 rgba(0,0,0,.06), 0 6px 14px rgba(0,0,0,.06);
      transition:border-color .15s, box-shadow .15s, transform .12s;
    }
    .in:focus{
      border-color:#c4c4c4;
      box-shadow:0 3px 0 rgba(0,0,0,.09), 0 10px 16px rgba(0,0,0,.08);
      transform:translateY(-1px);
    }
    .in-readonly{
      background:#f9fafb;
    }

    #qtyInput{
      max-width:110px;
    }

    .hint{
      margin-top:6px;color:#6b7280;font-size:12.5px;
    }

    .cart-shell{
      margin-top:12px;
      opacity:0;transform:translateY(10px);animation:itemIn .45s ease-out .3s forwards;
    }

    .table-shell{
      overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb;
    }

    .d-table{
      width:100%;border-collapse:separate;border-spacing:0;min-width:720px;
    }
    .d-table thead th{
      background:#f3f4f6;color:#111;text-align:left;font-weight:600;
      padding:11px 12px;border-bottom:1px solid #e5e7eb;font-size:14px;
    }
    .d-table tbody td{
      padding:10px 12px;border-bottom:1px solid #f0f2f5;font-size:14px;
    }
    .d-table tbody tr:hover td{
      background:#fafafa;
    }
    .empty{text-align:center;color:#6b7280}

    .tfoot-label{
      text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5;font-weight:600;color:#374151;
    }
    .num{
      font-weight:700;color:#111;text-align:right;
      padding:10px 12px;border-bottom:1px solid #f0f2f5;
    }

    .badge-del{
      border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;
      padding:6px 10px;border-radius:999px;font-weight:700;font-size:12.5px;color:#fff;background:#ef4444;
      box-shadow:0 2px 0 rgba(0,0,0,.06) inset;transition:transform .06s, opacity .12s;
    }
    .badge-del:hover{opacity:.96}
    .badge-del:active{transform:translateY(1px)}

    .pay-panel{
      margin-top:12px;
      display:grid;grid-template-columns:repeat(3,minmax(160px,1fr));gap:10px;
      background:#e5e7eb;border-radius:12px;border:1px solid #d1d5db;
      padding:10px 12px;
      opacity:0;transform:translateY(10px);animation:itemIn .45s ease-out .36s forwards;
    }
    .pay-label{
      font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;
    }

    .actions{
      display:flex;justify-content:space-between;gap:10px;margin-top:14px;flex-wrap:wrap;
      opacity:0;transform:translateY(10px);animation:itemIn .45s ease-out .44s forwards;
    }

    .btn{
      border:none;cursor:pointer;padding:9px 14px;border-radius:999px;
      font-weight:600;letter-spacing:.2px;font-size:13px;
      color:#fff;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
      box-shadow:0 6px 0 rgba(0,0,0,.22), 0 12px 18px rgba(0,0,0,.14);
      transition:transform .06s ease, box-shadow .12s ease, opacity .2s ease;
    }
    .btn:hover{opacity:.96}
    .btn:active{transform:translateY(2px);box-shadow:0 4px 0 rgba(0,0,0,.20), 0 10px 16px rgba(0,0,0,.12)}
    .btn-green{background:#22c55e}
    .btn-gray{background:#6b7280}

    @media(max-width:768px){
      .grid-top{grid-template-columns:1fr}
      .pick{flex-wrap:wrap}
      #qtyInput{max-width:100%}
      .pay-panel{grid-template-columns:1fr}
      .actions{flex-direction:column}
      .btn{width:100%;text-align:center}
      .d-table{min-width:0}
    }

    input:-webkit-autofill{
      transition: background-color 9999s ease-out, color 9999s ease-out;
      -webkit-text-fill-color: inherit !important;
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
  </style>

  <script>
    (() => {
      const select   = document.getElementById('produkSelect');
      const qtyEl    = document.getElementById('qtyInput');
      const addBtn   = document.getElementById('btnAdd');
      const body     = document.getElementById('cartBody');
      const totalEl  = document.getElementById('grandTotal');
      const holder   = document.getElementById('itemsHolder');
      const stokInfo = document.getElementById('stokInfo');
      const jsAlert  = document.getElementById('jsAlert');

      const totalInput     = document.getElementById('totalInput');
      const uangBayarInput = document.getElementById('uangBayarInput');
      const kembalianInput = document.getElementById('kembalianInput');

      const cart = new Map(); // key: produk_id -> {nama, harga, qty, stok}

      function rupiah(n){ return 'Rp ' + (n||0).toLocaleString('id-ID'); }

      function showJsAlert(msg){
        if(!jsAlert) return;
        jsAlert.textContent = msg;
        jsAlert.style.display = 'block';
      }

      function hideJsAlert(){
        if(!jsAlert) return;
        jsAlert.style.display = 'none';
        jsAlert.textContent = '';
      }

      function recomputePayment(sum){
        totalInput.value = rupiah(sum);
        const bayar = parseFloat(uangBayarInput.value||'0');
        const kmbl  = Math.max(0, bayar - sum);
        kembalianInput.value = rupiah(kmbl);
      }

      function render(){
        body.innerHTML = '';
        let sum = 0;
        if(cart.size === 0){
          const tr = document.createElement('tr');
          tr.className = 'empty-row';
          tr.innerHTML = '<td colspan="5" class="empty">Belum ada item.</td>';
          body.appendChild(tr);
        } else {
          cart.forEach((item, pid) => {
            const row = document.createElement('tr');
            const sub = item.harga * item.qty;
            sum += sub;
            row.innerHTML = `
              <td>${item.nama}</td>
              <td class="num">${rupiah(item.harga)}</td>
              <td class="num">${item.qty}</td>
              <td class="num">${rupiah(sub)}</td>
              <td>
                <button type="button" class="badge-del" data-id="${pid}">hapus</button>
              </td>
            `;
            body.appendChild(row);
          });
        }
        totalEl.textContent = rupiah(sum);

        // rebuild hidden inputs
        holder.innerHTML = '';
        cart.forEach((item, pid) => {
          const p = document.createElement('input');
          p.type = 'hidden'; p.name = 'items['+pid+'][produk_id]'; p.value = pid;
          const q = document.createElement('input');
          q.type = 'hidden'; q.name = 'items['+pid+'][qty]'; q.value = item.qty;
          holder.appendChild(p); holder.appendChild(q);
        });

        recomputePayment(sum);
      }

      function updateStokInfo(){
        const opt = select.options[select.selectedIndex];
        const s = opt?.dataset?.stok;
        stokInfo.textContent = s ? `Stok tersedia: ${s}` : '';
      }
      select.addEventListener('change', () => {
        hideJsAlert();
        updateStokInfo();
      });
      updateStokInfo();

      addBtn.addEventListener('click', () => {
        hideJsAlert();

        const pid = select.value;
        if(!pid) return;
        const opt   = select.options[select.selectedIndex];
        const nama  = opt.textContent.trim();
        const harga = parseInt(opt.dataset.harga || '0', 10);
        const stok  = parseInt(opt.dataset.stok  || '0', 10);
        const q     = Math.max(1, parseInt(qtyEl.value || '1', 10));

        if(q > stok){
          showJsAlert('Qty melebihi stok! Tersedia: ' + stok);
          return;
        }

        if(cart.has(pid)){
          const cur = cart.get(pid);
          const newQty = cur.qty + q;
          if(newQty > stok){
            showJsAlert('Qty melebihi stok! Tersedia: ' + stok);
            return;
          }
          cur.qty = newQty;
          cart.set(pid, cur);
        } else {
          cart.set(pid, {nama, harga, qty: q, stok});
        }
        render();
      });

      body.addEventListener('click', (e) => {
        const id = e.target?.dataset?.id;
        if(e.target.classList.contains('badge-del') && id){
          hideJsAlert();
          cart.delete(id);
          render();
        }
      });

      uangBayarInput.addEventListener('input', () => {
        let sum = 0;
        cart.forEach((item) => sum += item.harga * item.qty);
        recomputePayment(sum);
      });

      // matikan auto-suggest yang ganggu
      document.addEventListener('focusin', (e) => {
        if (e.target.matches('input,select,textarea')) {
          e.target.setAttribute('autocomplete','off');
          e.target.setAttribute('autocapitalize','off');
          e.target.setAttribute('autocorrect','off');
          e.target.setAttribute('spellcheck','false');
        }
      });
    })();
  </script>
@endsection
