@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 min-h-[60vh] flex items-center justify-center">
    <div class="max-w-lg mx-auto text-center">
        <h1 class="text-3xl font-bold text-amber-800 mb-4">Pesanan Berhasil!</h1>
        <p class="mb-4">Nomor Pesanan: {{ $pesanan->kode_pesanan }}</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('produk.index') }}" 
               class="inline-block bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700">
                Lanjut Belanja
            </a>
            <a href="{{ route('pesanan.tracking') }}" 
               class="inline-block bg-[#8B4513] text-white px-6 py-2 rounded-lg hover:bg-amber-900">
                Pesanan Saya
            </a>
        </div>
    </div>
</div>
@endsection