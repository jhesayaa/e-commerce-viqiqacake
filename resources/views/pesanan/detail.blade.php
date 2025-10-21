@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pesanan.tracking') }}" class="text-amber-600 hover:text-amber-700">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="border-b pb-4 mb-4">
                <h2 class="text-2xl font-bold text-amber-800">Detail Pesanan</h2>
                <p class="text-gray-600">{{ $pesanan->kode_pesanan }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Pengiriman -->
                <div>
                    <h3 class="font-semibold text-lg mb-2">Informasi Pengiriman</h3>
                    <div class="space-y-2">
                        <p><span class="text-gray-600">Nama:</span> {{ $pesanan->nama_pelanggan }}</p>
                        <p><span class="text-gray-600">Alamat:</span> {{ $pesanan->alamat }}</p>
                        <p><span class="text-gray-600">Telepon:</span> {{ $pesanan->telepon }}</p>
                    </div>  
                </div>

                <!-- Detail Pembayaran -->
                <div>
                    <h3 class="font-semibold text-lg mb-2">Detail Pembayaran</h3>
                    <div class="space-y-2">
                        <p><span class="text-gray-600">Metode:</span> {{ ucfirst($pesanan->metode_pembayaran) }}</p>
                        
                        @if($pesanan->metode_pembayaran === 'cod')
                        <div class="mt-2 p-3 bg-amber-50 rounded-md border border-amber-200">
                            <p class="font-medium text-amber-800">Alamat Pengambilan:</p>
                            <p class="text-sm text-gray-700">Jl. Sedayu Sawo Raya, RT.06/RW.02, Bangetayu Wetan, Kec. Genuk, Kota Semarang, Jawa Tengah 50115</p>
                        </div>
                        @endif
                        
                        <p>
                            <span class="text-gray-600">Status:</span> 
                            <span class="inline-block px-2 py-1 rounded-full text-sm
                                @if($pesanan->status === 'pending') bg-yellow-200 text-yellow-800
                                @elseif($pesanan->status === 'diproses') bg-blue-200 text-blue-800
                                @elseif($pesanan->status === 'dikirim') bg-indigo-200 text-indigo-800
                                @elseif($pesanan->status === 'selesai') bg-green-200 text-green-800
                                @else bg-red-200 text-red-800 @endif">
                                {{ ucfirst($pesanan->status) }}
                            </span>
                        </p>
                        @if($pesanan->metode_pembayaran === 'transfer' && $pesanan->bukti_transfer)
                            <div class="mt-4">
                                <p class="text-gray-600 mb-2">Bukti Transfer:</p>
                                <img src="{{ asset('storage/' . $pesanan->bukti_transfer) }}" 
                                    alt="Bukti Transfer"
                                    class="w-64 h-64 object-contain rounded-lg shadow-md">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="mt-6">
                <h3 class="font-semibold text-lg mb-4">Produk yang Dipesan</h3>
                <div class="space-y-4">
                    @foreach($pesanan->items as $item)
                    <div class="flex justify-between items-center border-b pb-4">
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . $item->produk->gambar) }}" 
                                 class="w-16 h-16 object-cover rounded" 
                                 alt="{{ $item->produk->nama }}">
                            <div class="ml-4">
                                <p class="font-medium">{{ $item->produk->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $item->jumlah }}x @ Rp {{ number_format($item->harga) }}</p>
                            </div>
                        </div>
                        <p class="font-medium">Rp {{ number_format($item->subtotal) }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Subtotal</span>
                        <span>Rp {{ number_format($pesanan->total_harga) }}</span>
                    </div>
                    @if($pesanan->voucher_id)
                    <div class="flex justify-between items-center text-green-600">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($pesanan->total_harga - $pesanan->total_akhir) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center font-bold text-lg mt-2">
                        <span>Total</span>
                        <span>Rp {{ number_format($pesanan->total_akhir) }}</span>
                    </div>
                </div>

                <!-- Batalkan Pesanan -->
                @if($pesanan->status === 'pending'  )
                <div class="mt-6 border-t pt-4">
                    <h3 class="font-semibold text-lg mb-4">Tindakan</h3>
                    <form action="{{ route('pesanan.cancel', $pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
                            Batalkan Pesanan
                        </button>
                    </form>
                    <p class="text-sm text-gray-500 mt-2">
                        Pesanan yang dibatalkan akan mengembalikan stok produk.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection