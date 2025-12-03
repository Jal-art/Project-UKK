{{-- resources/views/produk/create.blade.php --}}
@extends('layouts.cashify')
@section('title','Tambah Produk | Cashify')

@section('content')
<h2 class="ttl">Tambah Produk</h2>

@if(session('ok'))
<div class="alert ok">{{ session('ok') }}</div>
@endif
@if ($errors->any())
<div class="alert err">{{ $errors->first() }}</div>
@endif

<div class="box">
  <form method="POST" action="{{ route('produk.store') }}" novalidate autocomplete="off">
    @csrf

    <div class="fld full">
      <label>Nama</label>
      <input
        type="text"
        name="nama_produk"
        class="in @error('nama_produk') bad @enderror"
        placeholder="Masukkan Nama Produk"
        value="{{ old('nama_produk') }}"
        required
        autofocus
        autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" inputmode="text"
        data-no-suggest>
      @error('nama_produk') <div class="hint-err">{{ $message }}</div> @enderror
    </div>

    <div class="fld">
      <label>Ukuran</label>
      <select
        name="ukuran"
        class="in @error('ukuran') bad @enderror">
        <option value="">Pilih Ukuran Produk</option>

        <option value="S" {{ old('ukuran', $produk->ukuran ?? '') == 'S' ? 'selected' : '' }}>S</option>
        <option value="M" {{ old('ukuran', $produk->ukuran ?? '') == 'M' ? 'selected' : '' }}>M</option>
        <option value="L" {{ old('ukuran', $produk->ukuran ?? '') == 'L' ? 'selected' : '' }}>L</option>
        <option value="XL" {{ old('ukuran', $produk->ukuran ?? '') == 'XL' ? 'selected' : '' }}>XL</option>
        <option value="XXL" {{ old('ukuran', $produk->ukuran ?? '') == 'XXL' ? 'selected' : '' }}>XXL</option>
        <option value="XXXL" {{ old('ukuran', $produk->ukuran ?? '') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
      </select>

      @error('ukuran')
      <div class="hint-err">{{ $message }}</div>
      @enderror
    </div>


    <div class="fld">
      <label>Warna</label>
      <input
        type="text"
        name="warna"
        class="in @error('warna') bad @enderror"
        placeholder="Masukkan Warna Produk"
        value="{{ old('warna') }}"
        autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" inputmode="text"
        data-no-suggest>
      @error('warna') <div class="hint-err">{{ $message }}</div> @enderror
    </div>

    <div class="fld">
      <label>Stok</label>
      <input
        type="number" min="0" step="1"
        name="stok"
        class="in @error('stok') bad @enderror"
        placeholder="Masukkan Stok Produk"
        value="{{ old('stok') }}"
        autocomplete="off" inputmode="numeric">
      @error('stok') <div class="hint-err">{{ $message }}</div> @enderror
    </div>

    <div class="fld">
      <label>Harga</label>
      <input
        type="number" min="0" step="1" inputmode="numeric"
        name="harga"
        class="in @error('harga') bad @enderror"
        placeholder="Masukkan Harga Produk"
        value="{{ old('harga') }}"
        autocomplete="off">
      @error('harga') <div class="hint-err">{{ $message }}</div> @enderror
      <div class="hint">Masukkan angka saja (tanpa titik/koma).</div>
    </div>

    <div class="actions full">
      <a href="{{ route('produk.index') }}" class="btn btn-gray" aria-label="Kembali ke daftar produk">Kembali</a>
      <button type="submit" class="btn btn-green">Simpan</button>
    </div>
  </form>
</div>

<style>
  .ttl {
    margin: 0 0 12px;
    font-weight: 600;
    color: #1f2937;
    opacity: 0;
    transform: translateY(-10px);
    animation: titleIn .6s cubic-bezier(.18, .89, .32, 1.28) .05s forwards;
  }

  .alert.ok {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    margin-bottom: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13px;
  }

  .alert.err {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
    margin-bottom: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13px;
  }

  .box {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 14px;
    padding: 16px 16px 18px;
    max-width: 930px;
    width: 100%;
    margin: 0 auto;
    box-shadow: 0 3px 0 rgba(0, 0, 0, .08), 0 12px 22px rgba(0, 0, 0, .08);
    opacity: 0;
    transform: translateY(18px) scale(.97);
    animation: cardIn .7s cubic-bezier(.18, .89, .32, 1.28) .12s forwards;
  }

  .fld {
    margin-bottom: 12px;
    opacity: 0;
    transform: translateY(10px);
    animation: itemIn .45s ease-out forwards;
  }

  .box form .fld:nth-of-type(1) {
    animation-delay: .22s
  }

  .box form .fld:nth-of-type(2) {
    animation-delay: .28s
  }

  .box form .fld:nth-of-type(3) {
    animation-delay: .34s
  }

  .box form .fld:nth-of-type(4) {
    animation-delay: .40s
  }

  .box form .fld:nth-of-type(5) {
    animation-delay: .46s
  }

  .fld label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
  }

  .in {
    width: 100%;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
    outline: none;
    box-shadow: 0 2px 0 rgba(0, 0, 0, .06), 0 6px 14px rgba(0, 0, 0, .06);
    transition: border-color .15s, box-shadow .15s, transform .12s;
  }

  .in:focus {
    border-color: #c4c4c4;
    box-shadow: 0 3px 0 rgba(0, 0, 0, .09), 0 10px 16px rgba(0, 0, 0, .08);
    transform: translateY(-1px);
  }

  .bad {
    border-color: #fca5a5 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, .12) !important;
  }

  .hint {
    margin-top: 6px;
    color: #6b7280;
    font-size: 12.5px;
  }

  .hint-err {
    margin-top: 6px;
    color: #b91c1c;
    background: #fee2e2;
    border: 1px solid #fecaca;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 12.5px;
  }

  .actions {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 14px;
    opacity: 0;
    transform: translateY(10px);
    animation: itemIn .45s ease-out .52s forwards;
  }

  /* === BUTTON STYLE SAMA DENGAN TRANSAKSI === */
  .btn {
    border: none;
    cursor: pointer;
    padding: 9px 14px;
    border-radius: 999px;
    font-weight: 600;
    letter-spacing: .2px;
    font-size: 13px;
    color: #fff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 0 rgba(0, 0, 0, .22), 0 12px 18px rgba(0, 0, 0, .14);
    transition: transform .06s ease, box-shadow .12s ease, opacity .2s ease;
  }

  .btn:hover {
    opacity: .96
  }

  .btn:active {
    transform: translateY(2px);
    box-shadow: 0 4px 0 rgba(0, 0, 0, .20), 0 10px 16px rgba(0, 0, 0, .12)
  }

  .btn-green {
    background: #22c55e;
  }

  .btn-gray {
    background: #6b7280;
  }

  @media(max-width:560px) {
    .actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
      text-align: center;
    }
  }

  /* Desktop lebar: 2 kolom */
  @media(min-width:900px) {
    .box form {
      display: grid;
      grid-template-columns: repeat(2, minmax(260px, 1fr));
      column-gap: 16px;
      row-gap: 12px;
    }

    .fld {
      margin-bottom: 0;
    }

    .fld.full {
      grid-column: 1 / 3;
    }

    .actions.full {
      grid-column: 1 / 3;
      justify-content: flex-end;
    }
  }

  input:-webkit-autofill {
    transition: background-color 9999s ease-out, color 9999s ease-out;
    -webkit-text-fill-color: inherit !important;
  }

  @keyframes titleIn {
    0% {
      opacity: 0;
      transform: translateY(-12px)
    }

    100% {
      opacity: 1;
      transform: translateY(0)
    }
  }

  @keyframes cardIn {
    0% {
      opacity: 0;
      transform: translateY(22px) scale(.96)
    }

    60% {
      opacity: 1;
      transform: translateY(-2px) scale(1.01)
    }

    100% {
      opacity: 1;
      transform: translateY(0) scale(1)
    }
  }

  @keyframes itemIn {
    0% {
      opacity: 0;
      transform: translateY(10px)
    }

    100% {
      opacity: 1;
      transform: translateY(0)
    }
  }
</style>

<script>
  (() => {
    const els = document.querySelectorAll('input[data-no-suggest]');
    els.forEach(el => {
      el.readOnly = true;
      el.addEventListener('focus', () => el.readOnly = false, {
        once: true
      });
    });
    requestAnimationFrame(() => {
      document.querySelectorAll('input').forEach(i => {
        if (i.autocomplete !== 'off') i.setAttribute('autocomplete', 'off');
        i.setAttribute('data-lpignore', 'true');
      });
    });
  })();
</script>
@endsection