@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-serif text-amber-800 mb-6">Profil Saya</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar navigasi profil -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center text-amber-800 text-2xl font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <h2 class="text-center font-medium text-lg">{{ auth()->user()->name }}</h2>
                        <p class="text-center text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <nav>
                        <ul class="space-y-2">
                            <li>
                                <a href="#informasi-personal" class="block py-2 px-3 rounded-md bg-amber-100 text-amber-800 font-medium">
                                    Informasi Personal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pesanan.tracking') }}" class="block py-2 px-3 rounded-md hover:bg-amber-50 text-gray-600 hover:text-amber-800 transition">
                                    Pesanan Saya
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Konten utama profil -->
            <div class="md:col-span-2">
                <!-- Informasi personal -->
                <div id="informasi-personal" class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-amber-800 mb-4">Informasi Personal</h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                   class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg" readonly>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Alamat Utama</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                        
                        <div>
                            <button type="submit" 
                                    class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Ringkasan Akun -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-amber-800 mb-4">Ringkasan Akun</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-amber-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total Pesanan</p>
                            <p class="text-2xl font-semibold text-amber-800">{{ $totalOrders ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-amber-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Bergabung Sejak</p>
                            <p class="text-2xl font-semibold text-amber-800">{{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection