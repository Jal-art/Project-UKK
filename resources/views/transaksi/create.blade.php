@extends('layouts.cashify')
@section('title','Tambah Transaksi | Cashify')

@section('content')
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
    <h2 style="margin:0;font-weight:600;color:#111">Tambah Transaksi</h2>
    {{-- HAPUS tombol kembali di header --}}
  </div>

  @if ($errors->any())
    <div class="alert err" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:10px 12px;border-radius:10px;margin-bottom:12px">{{ $errors->first() }}</div>
  @endif

  <div class="panel trx-form">
    <form id="trxForm" method="POST" action="{{ route('transaksi.store') }}" novalidate>
      @csrf

      <div class="grid" style="display:grid;grid-template-columns:1fr;gap:12px">
        <div class="fld">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal</label>
          <input type="date" name="tanggal" class="in" value="{{ now()->toDateString() }}" style="width:100%;background:#fff;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none">
        </div>

        <div class="fld">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Pilih Produk</label>
          <div class="pick" style="display:flex;gap:8px;align-items:center">
            <select id="produkSelect" class="in" style="flex:1;background:#fff;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none">
              <option value="">— pilih —</option>
              @foreach($produks as $p)
                <option value="{{ $p->id_produk }}"
                        data-harga="{{ (int)$p->harga }}"
                        data-stok="{{ (int)$p->stok }}">
                  {{ $p->nama_produk }} {{ $p->ukuran ? '• '.$p->ukuran : '' }} {{ $p->warna ? '• '.$p->warna : '' }}
                </option>
              @endforeach
            </select>
            <input id="qtyInput" class="in" type="number" min="1" step="1" value="1" style="max-width:120px;background:#fff;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none">
            {{-- Tambah = HIJAU (tema Produk) --}}
            <button type="button" id="btnAdd" class="btn green" style="border:none;border-radius:10px;padding:10px 14px;font-weight:700;color:#fff;background:#22c55e;cursor:pointer">Tambah</button>
          </div>
          <div id="stokInfo" class="hint" style="margin-top:6px;color:#6b7280;font-size:12.5px"></div>
        </div>
      </div>

      <div class="table-shell" style="margin-top:12px;overflow:auto;border-radius:12px;background:#fff;border:1px solid #e5e7eb">
        <table class="d-table" id="cartTable" style="width:100%;border-collapse:separate;border-spacing:0">
          <thead>
            <tr>
              <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb">Produk</th>
              <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:120px">Harga</th>
              <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:120px">Qty</th>
              <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:160px">Subtotal</th>
              <th style="background:#f3f4f6;color:#111;text-align:left;font-weight:600;padding:11px 12px;border-bottom:1px solid #e5e7eb;width:120px">Aksi</th>
            </tr>
          </thead>
          <tbody id="cartBody">
            <tr class="empty-row"><td colspan="5" class="empty" style="text-align:center;color:#6b7280;padding:12px">Belum ada item.</td></tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" style="text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">Total</th>
              <th id="grandTotal" class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">Rp 0</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      {{-- Pembayaran --}}
      <div class="panel" style="margin-top:12px;display:grid;grid-template-columns:repeat(3,minmax(160px,1fr));gap:10px">
        <div>
          <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Total Bayar</div>
          <input id="totalInput" type="text" class="in" readonly style="width:100%;background:#f9fafb;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none" value="Rp 0">
        </div>
        <div>
          <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Uang Diterima</div>
          <input id="uangBayarInput" name="uang_bayar" type="number" min="0" step="1" class="in" placeholder="Masukkan nominal" style="width:100%;background:#fff;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none">
        </div>
        <div>
          <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Kembalian</div>
          <input id="kembalianInput" type="text" class="in" readonly style="width:100%;background:#f9fafb;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;font-size:14px;outline:none" value="Rp 0">
        </div>
      </div>

      {{-- field hidden items[] untuk submit --}}
      <div id="itemsHolder"></div>

      {{-- FOOTER FORM: Kembali kiri (UNGU), Bayar kanan (HIJAU) --}}
      <div style="display:flex;justify-content:space-between;gap:10px;margin-top:14px;flex-wrap:wrap">
        <a href="{{ route('transaksi.index') }}"
           class="btn"
           style="background:#6d5cff;color:#fff;text-decoration:none;border:none;border-radius:10px;padding:10px 18px;font-weight:700">
           Kembali
        </a>
        <button type="submit" class="btn" style="background:#22c55e;color:#fff;border:none;border-radius:10px;padding:10px 18px;font-weight:700">
          Bayar
        </button>
      </div>
    </form>
  </div>

  <script>
    (() => {
      const select   = document.getElementById('produkSelect');
      const qtyEl    = document.getElementById('qtyInput');
      const addBtn   = document.getElementById('btnAdd');
      const body     = document.getElementById('cartBody');
      const totalEl  = document.getElementById('grandTotal');
      const holder   = document.getElementById('itemsHolder');
      const stokInfo = document.getElementById('stokInfo');

      const totalInput     = document.getElementById('totalInput');
      const uangBayarInput = document.getElementById('uangBayarInput');
      const kembalianInput = document.getElementById('kembalianInput');

      const cart = new Map(); // key: produk_id -> {nama, harga, qty, stok}

      function rupiah(n){ return 'Rp ' + (n||0).toLocaleString('id-ID'); }

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
          tr.innerHTML = '<td colspan="5" class="empty" style="text-align:center;color:#6b7280;padding:12px">Belum ada item.</td>';
          body.appendChild(tr);
        } else {
          cart.forEach((item, pid) => {
            const row = document.createElement('tr');
            const sub = item.harga * item.qty;
            sum += sub;
            row.innerHTML = `
              <td style="padding:10px 12px;border-bottom:1px solid #f0f2f5">${item.nama}</td>
              <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">${rupiah(item.harga)}</td>
              <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">${item.qty}</td>
              <td class="num" style="font-weight:700;color:#111;text-align:right;padding:10px 12px;border-bottom:1px solid #f0f2f5">${rupiah(sub)}</td>
              <td style="padding:10px 12px;border-bottom:1px solid #f0f2f5">
                <button type="button" class="badge-del" data-id="${pid}" style="background:#ef4444;border:none;color:#fff;padding:6px 10px;border-radius:999px;cursor:pointer;font-weight:700">hapus</button>
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
      select.addEventListener('change', updateStokInfo);
      updateStokInfo();

      addBtn.addEventListener('click', () => {
        const pid = select.value;
        if(!pid) return;
        const opt   = select.options[select.selectedIndex];
        const nama  = opt.textContent.trim();
        const harga = parseInt(opt.dataset.harga || '0', 10);
        const stok  = parseInt(opt.dataset.stok  || '0', 10);
        const q     = Math.max(1, parseInt(qtyEl.value || '1', 10));

        if(q > stok){
          alert('Qty melebihi stok! Tersedia: ' + stok);
          return;
        }

        if(cart.has(pid)){
          const cur = cart.get(pid);
          const newQty = cur.qty + q;
          if(newQty > stok){
            alert('Qty melebihi stok! Tersedia: ' + stok);
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
