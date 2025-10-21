@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-serif text-amber-800 mb-6">Pesanan Saya</h1>
        
        <div class="space-y-4">
            @foreach($pesanan as $order)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600">Nomor Pesanan:</p>
                        <p class="font-semibold text-amber-800">{{ $order->kode_pesanan }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600">Total:</p>
                        <p class="font-semibold text-amber-800">Rp {{ number_format($order->total_akhir) }}</p>
                        @if($order->voucher_id)
                        <p class="text-xs text-green-600">Termasuk diskon voucher</p>
                        @endif
                    </div> 
                </div>
                
                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="relative pt-1">
                                <!-- Status Badge -->
                                <div class="flex mb-2 items-center justify-between">
                                    <div class="text-right">
                                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full 
                                            @if($order->status === 'pending') bg-yellow-200 text-yellow-800
                                            @elseif($order->status === 'diproses') bg-blue-200 text-blue-800
                                            @elseif($order->status === 'dikirim') bg-indigo-200 text-indigo-800
                                            @elseif($order->status === 'selesai') bg-green-200 text-green-800
                                            @else bg-red-200 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Progress Bar -->
                                <div class="flex mb-2 items-center justify-between space-x-4">
                                    <div class="h-2 w-full bg-gray-200 rounded-full">
                                        <div class="h-2 rounded-full 
                                            @if($order->status === 'pending') w-1/5 bg-yellow-500
                                            @elseif($order->status === 'diproses') w-2/5 bg-blue-500
                                            @elseif($order->status === 'dikirim') w-3/5 bg-indigo-500
                                            @elseif($order->status === 'selesai') w-full bg-green-500
                                            @else w-0 bg-red-500 @endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm text-gray-600">
                        {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                    </span>
                    <a href="{{ route('pesanan.detail', $order) }}" 
                       class="text-amber-600 hover:text-amber-700">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            @endforeach

            @if($pesanan->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-600">Belum ada pesanan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection