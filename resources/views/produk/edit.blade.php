{{-- resources/views/produk/edit.blade.php --}}
@extends('layouts.cashify')
@section('title','Edit Produk | Cashify')

@section('content')
  <h2 class="ttl">Edit Produk</h2>

  {{-- alert sukses / error (opsional) --}}
  @if(session('ok'))
    <div class="alert ok">{{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert err">{{ $errors->first() }}</div>
  @endif

  <div class="box">
    <form method="POST" action="{{ route('produk.update',$produk) }}" novalidate>
      @csrf @method('PUT')

      <div class="fld">
        <label>Nama</label>
        <input
          type="text"
          name="nama_produk"
          class="in @error('nama_produk') bad @enderror"
          placeholder="Nama Produk"
          value="{{ old('nama_produk', $produk->nama_produk) }}"
          required
          autofocus
        >
        @error('nama_produk') <div class="hint-err">{{ $message }}</div> @enderror
      </div>

      <div class="fld">
        <label>Ukuran</label>
        <input
          type="text"
          name="ukuran"
          class="in @error('ukuran') bad @enderror"
          placeholder="Ukuran Produk"
          value="{{ old('ukuran', $produk->ukuran) }}"
        >
        @error('ukuran') <div class="hint-err">{{ $message }}</div> @enderror
      </div>

      <div class="fld">
        <label>Warna</label>
        <input
          type="text"
          name="warna"
          class="in @error('warna') bad @enderror"
          placeholder="Warna Produk"
          value="{{ old('warna', $produk->warna) }}"
        >
        @error('warna') <div class="hint-err">{{ $message }}</div> @enderror
      </div>

      <div class="fld">
        <label>Stok</label>
        <input
          type="number" min="0" step="1"
          name="stok"
          class="in @error('stok') bad @enderror"
          placeholder="Stok Produk"
          value="{{ old('stok', $produk->stok) }}"
        >
        @error('stok') <div class="hint-err">{{ $message }}</div> @enderror
      </div>

      <div class="fld">
        <label>Harga</label>
        <input
          type="number" min="0" step="1" inputmode="numeric"
          name="harga"
          class="in @error('harga') bad @enderror"
          placeholder="Harga Produk"
          value="{{ old('harga', (int)$produk->harga) }}"
        >
        @error('harga') <div class="hint-err">{{ $message }}</div> @enderror
        <div class="hint">Masukkan angka saja (tanpa titik/koma).</div>
      </div>

      <div class="actions">
        <a href="{{ route('produk.index') }}" class="btn btn-purple">Kembali</a>
        <button type="submit" class="btn btn-green">Update</button>
      </div>
    </form>
  </div>

  {{-- gaya kecil agar mirip screenshot & nyaman dipakai --}}
  <style>
    .ttl{margin:0 0 12px;font-weight:600;color:#1f2937}
    .box{
      background:#e5e7eb;border:1px solid #d1d5db;border-radius:14px;
      padding:16px 16px 18px; max-width:930px;
      box-shadow:0 3px 0 rgba(0,0,0,.10), 0 12px 22px rgba(0,0,0,.08);
    }
    .fld{margin-bottom:12px}
    .fld label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
    .in{
      width:100%; background:#fff; border:1px solid #d1d5db; border-radius:10px;
      padding:10px 12px; font-size:14px; outline:none;
      box-shadow:0 2px 0 rgba(0,0,0,.06), 0 6px 14px rgba(0,0,0,.06);
      transition:border-color .15s, box-shadow .15s;
    }
    .in:focus{border-color:#c7cbd1; box-shadow:0 3px 0 rgba(0,0,0,.09), 0 10px 16px rgba(0,0,0,.08)}
    .bad{border-color:#fca5a5 !important; box-shadow:0 0 0 3px rgba(239,68,68,.12) !important}
    .hint{margin-top:6px;color:#6b7280;font-size:12.5px}
    .hint-err{margin-top:6px;color:#b91c1c;background:#fee2e2;border:1px solid #fecaca;padding:8px 10px;border-radius:8px;font-size:12.5px}
    .actions{display:flex;justify-content:space-between;gap:10px;margin-top:14px}
    .btn{
      border:none; cursor:pointer; padding:10px 18px; border-radius:999px;
      font-weight:600; letter-spacing:.2px; color:#fff; text-decoration:none; display:inline-block;
      box-shadow:0 6px 0 rgba(0,0,0,.22), 0 12px 18px rgba(0,0,0,.14);
      transition:transform .06s ease, box-shadow .12s ease, opacity .2s ease;
    }
    .btn:hover{opacity:.96}
    .btn:active{transform:translateY(2px); box-shadow:0 4px 0 rgba(0,0,0,.20), 0 10px 16px rgba(0,0,0,.12)}
    .btn-purple{background:#6d5cff}
    .btn-green{background:#22c55e}
    .alert{margin:0 0 10px;padding:10px 12px;border-radius:10px;font-size:14px}
    .alert.ok{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
    .alert.err{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
    @media(max-width:560px){
      .actions{flex-direction:column}
      .btn{width:100%; text-align:center}
    }
  </style>
@endsection
