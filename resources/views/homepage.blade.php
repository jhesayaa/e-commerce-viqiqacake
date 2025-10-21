@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-b from-amber-50 to-amber-100/15 relative overflow-hidden h-auto">
    <!-- Landing Page -->
    <div class="max-w-6xl mx-auto px-4 pt-12 pb-11"> 
        <!-- Main Card Container -->
        <div class="bg-orange-50 rounded-2xl shadow-lg p-4 lg:p-6 flex flex-col lg:flex-row gap-8 relative overflow-hidden">
            <!-- Left Section -->
            <div class="w-full lg:w-1/2">
                <!-- Brown Line -->
                <div class="w-16 h-1 bg-amber-800 mb-4"></div>
    
                <!-- Tag Kue Terbaik -->
                <div class="inline-block bg-amber-50 px-3 py-1 rounded-lg mb-3">
                    <span class="text-amber-800 font-medium text-sm">✨ Kue Terbaik di Kota Semarang</span>
                </div>
                
                <h1 class="text-4xl font-serif text-amber-800 mb-3">
                    Viqiqa Cake
                </h1>
    
                <p class="text-base text-amber-700 mb-6">
                    Ciptakan Momen Hangatmu Bersama Viqiqa Cake. Ayo Dibeli Sekarang!   
                </p>
    
                <!-- Feature List -->
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 bg-amber-100 px-3 py-2 rounded-full">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-amber-700 text-sm">Dibuat dengan Bahan Berkualitas</span>
                    </div>
                    <div class="flex items-center gap-2 bg-amber-100 px-3 py-2 rounded-full">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-amber-700 text-sm">Pengiriman Tepat Waktu</span>
                    </div>
                    <div class="flex items-center gap-2 bg-amber-100 px-3 py-2 rounded-full">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-amber-700 text-sm">Kepuasan Pelanggan Terjamin</span>
                    </div>
                </div>
    
                <!-- CTA Button -->
                <button class="group relative px-6 py-2 bg-amber-800 rounded-full transition-all transform hover:scale-105 overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2 text-white font-medium text-sm">
                        Pesan Sekarang  
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-700 to-amber-600 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                </button>
    
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mt-14 pt-6 border-t border-amber-200">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-800">500+</div>
                        <div class="text-amber-600 text-xs mt-1">Pelanggan Puas</div>
                    </div>
                    <div class="text-center border-x border-amber-200">
                        <div class="text-2xl font-bold text-amber-800">50+</div>
                        <div class="text-amber-600 text-xs mt-1">Varian Kue</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-800">4.9</div>
                        <div class="text-amber-600 text-xs mt-1">Rating</div>
                    </div>
                </div>
            </div>
    
            <!-- Right Section - Owner Image -->
            <div class="w-full lg:w-1/2 relative group">
                <div class="relative transform group-hover:scale-[1.02] transition-all duration-500">
                    <div class="bg-[#dbbd96] rounded-xl p-4 relative overflow-hidden">
                        <!-- Frame dengan hover effect -->
                        <div class="border-4 border-amber-900/20 rounded-lg overflow-hidden group-hover:border-amber-900/30 transition-colors">
                            <img 
                                src="{{ asset('img/tante-hanik.png') }}"
                                alt="Ibu Haniah - Owner Viqiqa Cake" 
                                class="w-full transform group-hover:scale-105 transition-transform duration-700"
                            >
                        </div>
    
                        <!-- Wheat Decorations dengan animasi -->
                        <img src="{{ asset('images/wheat-left.png') }}" alt="" class="absolute left-0 top-1/2 -translate-y-1/2 w-12 group-hover:-translate-x-2 transition-transform"
                        >
                        <img src="{{ asset('images/wheat-right.png') }}" alt="" class="absolute right-0 top-1/2 -translate-y-1/2 w-12 group-hover:translate-x-2 transition-transform"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Section dengan background yang selaras -->
<div class="bg-gradient-to-b from-amber-100/10 to-amber-50 relative overflow-hidden">

    <div class="max-w-6xl mx-auto px-4 py-12 relative z-10">
        <!-- Section Header dengan Dekorasi -->
        <div class="text-center mb-12">
            <div class="w-20 h-1 bg-amber-800 mx-auto mb-4"></div>
            <h1 class="text-4xl font-serif text-amber-800">Menu Favorit</h1>
            <p class="text-amber-600 mt-2">Produk terlaris pilihan pelanggan kami</p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($produks->take(3) as $produk)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- Image Container -->
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        
                        <!-- Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-amber-100/90 backdrop-blur-sm text-amber-800 text-xs px-2 py-1 rounded-full">
                                Terlaris
                            </span>
                        </div>
                    </div>

                    <!-- Content Container -->
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-amber-900">
                                {{ $produk->nama }}
                            </h3>
                            
                            <!-- Rating -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm text-amber-700">5.0</span>
                            </div>
                        </div>

                        <!-- Stok -->
                        <p class="text-amber-700 text-md font-semibold">
                            Stok : {{ $produk->stok }}
                        </p>

                        <!-- Variants -->
                        <p class="text-amber-700 text-sm mb-4">
                            Varian: {{ $produk->deskripsi }}
                        </p>
                        
                        <!-- Price and Cart Section -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex flex-col">
                                <span class="text-lg font-bold text-amber-800">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <button class="px-4 py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-700 transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Button dengan slide effect -->
    <div class="text-center mt-10">
        <a href="/produk" 
        class="group relative inline-flex items-center gap-2 px-8 py-3 bg-amber-800 text-white rounded-full overflow-hidden">
            <span class="relative z-10">Lihat Semua Produk</span>
            
            <!-- Arrow animation -->
            <i class="fa-solid fa-arrow-right relative z-10 transform transition-all duration-300
                    group-hover:translate-x-2 group-hover:scale-110"></i>
            
            <!-- Slide overlay -->
            <div class="absolute left-0 top-0 h-full w-full bg-gradient-to-r from-amber-700 to-amber-600
                        transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500">
            </div>
        </a>
    </div>
    </div>
</div>

<!-- Testimonial Section -->
<div class="bg-gradient-to-b from-amber-50 to-amber-50 relative overflow-hidden py-16">
    <div class="max-w-6xl mx-auto px-4 relative z-10">
        <!-- Flex Container untuk Layout Kiri-Kanan -->
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Section - Header Text -->
            <div class="lg:w-1/3 flex flex-col justify-center">
                <div>
                    <div class="w-20 h-1 bg-amber-800 mb-4"></div>
                    <h2 class="text-5xl font-serif text-amber-800 mb-4">Testimoni</h2>
                    <p class="text-xl text-amber-600">Apa kata mereka tentang Viqiqa Cake</p>
                </div>
            </div>

            <!-- Right Section - Testimonial Cards dalam Grid 2x2 -->
            <div class="lg:w-2/3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-xl shadow-lg border-l-4 border-amber-400">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold text-amber-900">Windaway 13</h4>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-amber-700">5.0</span>
                                </div>
                            </div>
                            <p class="text-amber-700">"Kue-kue dari Viqiqa Cake selalu jadi favorit keluarga. Rasanya enak dan konsisten. Pelayanannya juga ramah!"</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-xl shadow-lg border-l-4 border-amber-400">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold text-amber-900">Nadiaardiwinata</h4>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-amber-700">5.0</span>
                                </div>
                            </div>
                            <p class="text-amber-700">"Saya selalu pesan kue untuk acara kantor di sini. Packing rapi dan pengiriman selalu tepat waktu. Thank you Viqiqa Cake!"</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-xl shadow-lg border-l-4 border-amber-400">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold text-amber-900">Theresia</h4>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-amber-700">5.0</span>
                                </div>
                            </div>
                            <p class="text-amber-700">"Pisang bolen favorit keluarga! Rasanya pas, tidak terlalu manis. Teksturnya juga lembut. Recommended banget!"</p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-xl shadow-lg border-l-4 border-amber-400">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold text-amber-900">Aldina</h4>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-amber-700">5.0</span>
                                </div>
                            </div>
                            <p class="text-amber-700">"Roti boy nya super enak, tekstur lembut dan rasa coklat yang pas. Aroma kopi yang harum bikin nagih. Recommended untuk pencinta kopi!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
