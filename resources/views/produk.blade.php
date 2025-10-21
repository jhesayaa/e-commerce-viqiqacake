@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-amber-200/20 to-amber-50 relative overflow-hidden">
    <!-- Header Section -->
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-serif text-amber-800 mb-4">Produk Kami</h1>
            <p class="text-amber-600">Temukan berbagai pilihan kue berkualitas untuk momen spesial Anda</p>
        </div>
        

        <!-- Filter Kategori -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="{{ route('produk.index') }}" class="px-6 py-2 rounded-full transition-all {{ !request('kategori') ? 'bg-amber-800 text-white' : 'bg-white text-amber-800 hover:bg-amber-100' }}">
                Semua
            </a>
            
            @foreach($kategoris as $kategori)
                <a href="{{ route('produk.index', ['kategori' => $kategori->id]) }}" class="px-6 py-2 rounded-full transition-all {{ request('kategori') == $kategori->id ? 'bg-amber-800 text-white' : 'bg-white text-amber-800 hover:bg-amber-100' }}">
                    {{ $kategori->nama }}
                </a>
            @endforeach
        </div>

        <!-- Search Bar -->
        <div class="max-w-xl mx-auto mb-12">
            <div class="relative">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" id="kategori" value="{{ request('kategori') }}">
                @endif
                
                <input type="text" 
                    id="search"
                    placeholder="Cari produk..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-3 rounded-full bg-white border-2 border-amber-100 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200"
                    >
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-amber-800">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($produks as $produk)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- Image Container -->
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-amber-900 mb-2">{{ $produk->nama }}</h3>
                        <p class="text-amber-700 text-md font-semibold">
                            Stok : {{ $produk->stok }}
                        </p>
                        <p class="text-sm text-amber-700 mb-4">{{ $produk->deskripsi }}</p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-amber-800">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </span>
                            <!-- Ubah button cart menjadi form -->
                            <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="p-2 bg-amber-800 text-white rounded-lg hover:bg-amber-700 transition-all">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let timeoutId;
    
    const searchInput = document.getElementById('search');
    const kategoriInput = document.getElementById('kategori');
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(() => {
            let url = new URL(window.location.href);
            
            // Update search parameter
            if (this.value) {
                url.searchParams.set('search', this.value);
            } else {
                url.searchParams.delete('search');
            }
            
            // Preserve kategori parameter if exists
            if (kategoriInput && kategoriInput.value) {
                url.searchParams.set('kategori', kategoriInput.value);
            }
            
            window.location.href = url.toString();
        }, 500); // Delay 500ms untuk menghindari terlalu banyak request
    });
</script>
@endsection