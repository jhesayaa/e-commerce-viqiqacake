@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-amber-100/50 to-amber-50 relative overflow-hidden">
        <!-- Header Section -->
        <div class="max-w-6xl mx-auto px-4 pt-12">
            <div class="text-center mb-12">
                <div class="w-20 h-1 bg-amber-800 mx-auto mb-4"></div>
                <h1 class="text-4xl font-serif text-amber-800">Tentang Kami</h1>
                <p class="text-amber-600 mt-2">Mengenal lebih dekat dengan Viqiqa Cake</p>
            </div>
        </div>

        <!-- Story Section -->
        <div class="max-w-2xl mx-auto px-4 py-12 -mt-10">
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-serif text-amber-800 mb-4">Cerita Viqiqa Cake</h2>
                <p class="text-amber-700 mb-6">
                    [Cerita tentang awal mula Viqiqa Cake, perjalanan bisnis, dll]
                </p>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="max-w-6xl mx-auto px-4 py-12 -mt-10">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Vision -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-serif text-amber-800 mb-4">Visi</h2>
                    <p class="text-amber-700">
                        [Visi Viqiqa Cake]
                    </p>
                </div>

                <!-- Mission -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-serif text-amber-800 mb-4">Misi</h2>
                    <ul class="text-amber-700 space-y-2">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-amber-500 mt-1"></i>
                            <span>[Misi 1]</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-amber-500 mt-1"></i>
                            <span>[Misi 2]</span>
                        </li>
                        <!-- Tambahkan misi lainnya -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-serif text-amber-800 mb-4">Lokasi Toko Kue Dan Bahan Roti Viqiqa</h2>

                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0"
                            marginwidth="0"
                            src="https://maps.google.com/maps?width=658&amp;height=415&amp;hl=en&amp;q=toko roti dan bahan kue viqiqa&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                        </iframe>
                        <a href="https://sprunkin.com/">Sprunki</a>
                    </div>
                    <style>
                        .mapouter {
                            position: relative;
                            text-align: right;
                            width: 100%;
                            height: 415px;
                        }

                        .gmap_canvas {
                            overflow: hidden;
                            background: none !important;
                            width: 100%;
                            height: 415px;
                        }

                        .gmap_iframe {
                            height: 415px !important;
                        }
                    </style>
                </div>

                <div class="mt-6 text-amber-700">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-amber-500"></i>
                        Jl. Sedayu Sawo Raya, RT.06/RW.02, Bangetayu Wetan, Kec. Genuk, Kota Semarang, Jawa Tengah 50115
                    </p>
                    <p class="flex items-center gap-2 mt-2">
                        <i class="fas fa-clock text-amber-500"></i>
                        Buka Setiap Hari: 06.00 - 20.00 WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
