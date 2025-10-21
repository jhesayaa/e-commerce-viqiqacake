<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-commerce Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Di bagian head, tambahkan: -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-amber-50 flex flex-col min-h-screen">
    @auth
        @php
            $user = Auth::user();
        @endphp
    @endauth

    @php
    $cartCount = 0;
    if(auth()->check()) {
        $cart = \App\Models\Cart::where('user_id', auth()->id())
                                ->where('status', 'active')
                                ->first();
        if($cart) {
            $cartCount = $cart->items->sum('quantity');
        }
    }
@endphp
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-amber-700 to-amber-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Left - Brand -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <!-- Optional: Add logo -->
                        <img src="" alt="">
                        <span class="text-2xl font-bold text-white tracking-wider">ViqiqaCake</span>
                    </a>
                </div>

                <!-- Center - Navigation Links (Desktop Only) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="/"
                        class="py-2 px-4 text-white hover:bg-amber-600 rounded-full transition-all duration-200 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Home</span>
                    </a>
                    <a href="/produk"
                        class="py-2 px-4 text-white hover:bg-amber-600 rounded-full transition-all duration-200 flex items-center space-x-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Produk</span>
                    </a>
                </div>

                <!-- Right - Cart & Login -->
                <div class="flex items-center space-x-4">
                    <a href="/cart" class="relative group">
                        <div class="p-2 hover:bg-amber-600 rounded-full transition-all duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="absolute -top-2 -right-2 bg-amber-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center transform scale-100 group-hover:scale-110 transition-transform">
                                {{ $cartCount }}
                            </span>
                        </div>
                    </a>

                    <!-- Mobile menu button -->
                    <button class="mobile-menu-button md:hidden text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop user dropdown - hanya ditampilkan di desktop -->
                    <div class="hidden md:block">
                        @guest
                            <a href="{{ route('login') }}"
                                class="py-2 px-4 text-white hover:bg-amber-600 rounded-full transition-all duration-200 flex items-center space-x-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Login</span>
                            </a>
                        @else
                            <div class="relative group">
                                <button
                                    class="py-2 px-4 text-white hover:bg-amber-600 rounded-full transition-all duration-200 flex items-center space-x-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ Auth::user()->name }}</span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block">
                                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-amber-100">Profile</a>
                                    <a href="{{ route('pesanan.tracking') }}" class="block px-4 py-2 text-gray-800 hover:bg-amber-100">Pesanan Saya</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-gray-800 hover:bg-amber-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>  
        </div>
    </nav>

    <!-- Mobile Menu (improved version) -->
    <div class="mobile-menu hidden md:hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm">
        <div class="bg-gradient-to-b from-amber-700 to-amber-800 h-screen w-2/3 max-w-xs p-4 transform transition-all duration-300">
            <div class="flex justify-between items-center mb-6 border-b border-amber-600 pb-4">
                <span class="text-xl font-bold text-white">Menu</span>
                <button class="mobile-menu-close text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-col space-y-4">
                <a href="/" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <a href="/produk" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Produk</span>
                </a>
                <a href="/about" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>About</span>
                </a>
                <div class="border-t border-amber-600 my-2 pt-4">
                    @guest
                    <a href="{{ route('login') }}" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Login</span>
                    </a>
                    @else
                    <a href="{{ route('profile.index') }}" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('pesanan.tracking') }}" class="text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Pesanan Saya</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-white hover:bg-amber-600 px-4 py-3 rounded-lg transition-colors flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <!-- Wave Separator sebelum footer -->
    <div class="relative ">
        <svg class="w-full h-24" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="none">
            <path fill="#8B4513"
                d="M0,160L40,144C80,128,160,96,240,85.3C320,75,400,85,480,101.3C560,117,640,139,720,154.7C800,171,880,181,960,165.3C1040,149,1120,107,1200,90.7C1280,75,1360,85,1400,90.7L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
            </path>
        </svg>
    </div>

    <footer class="bg-[#8B4513] text-white relative overflow-hidden mt-auto">
        <!-- Decorative Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-32 h-32 bg-amber-700/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-32 h-32 bg-amber-600/20 rounded-full blur-3xl"></div>
        </div>
        <!-- Main Footer Content -->
        <div class="max-w-6xl mx-auto px-4 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- About Section -->
                <div class="space-y-4">
                    <h3 class="text-xl font-serif text-amber-100">Why Us?</h3>
                    <div class="w-12 h-0.5 bg-amber-600"></div>
                    <p class="text-amber-100/80">Viqiqa Cake - Toko kue terbaik di Semarang yang menghadirkan berbagai
                        varian kue dengan kualitas terbaik untuk momen spesial Anda.</p>
                </div>
                <!-- Contact Section -->
                <div class="space-y-4">
                    <h3 class="text-xl font-serif text-amber-100">Contact</h3>
                    <div class="w-12 h-0.5 bg-amber-600"></div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-amber-100/80">
                            <i class="fa-solid fa-envelope text-amber-400"></i>
                            <span>viqiqacake@gmail.com</span>
                        </div>
                        <div class="flex items-center gap-3 text-amber-100/80">
                            <i class="fa-solid fa-phone text-amber-400"></i>
                            <span>+62 123-456-7890</span>
                        </div>
                        <div class="flex items-center gap-3 text-amber-100/80">
                            <i class="fa-solid fa-location-dot text-amber-400"></i>
                            <span>Semarang, Jawa Tengah</span>
                        </div>
                    </div>
                </div>
                <!-- Social Media Section -->
                <div class="space-y-4">
                    <h3 class="text-xl font-serif text-amber-100">Follow Us</h3>
                    <div class="w-12 h-0.5 bg-amber-600"></div>
                    <div class="flex gap-4">
                        <!--instagram-->
                        <a href="https://www.instagram.com/viqiqacake/" class="group">
                            <div
                                class="w-10 h-10 rounded-full bg-amber-700/50 flex items-center justify-center
                                      transition-all duration-300 group-hover:bg-amber-600">
                                <i
                                    class="fab fa-instagram text-amber-100 group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                        <a href="#" class="group">
                            <div
                                class="w-10 h-10 rounded-full bg-amber-700/50 flex items-center justify-center
                                      transition-all duration-300 group-hover:bg-amber-600">
                                <i
                                    class="fab fa-whatsapp text-amber-100 group-hover:scale-110 transition-transform"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Copyright -->
            <div class="mt-2 pt-8 border-t border-amber-700/50 text-center text-amber-100/60">
                <p>&copy; 2024 Jhesaya Giovani Andromeda</p>
            </div>
        </div>
    </footer>

    @yield('scripts')

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Script untuk mobile menu yang telah ditingkatkan
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const mobileMenu = document.querySelector('.mobile-menu');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                // Tampilkan menu dengan animasi
                mobileMenu.classList.remove('hidden');
                // Disable scroll pada body
                document.body.style.overflow = 'hidden';
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', () => {
                // Sembunyikan menu dengan animasi
                mobileMenu.classList.add('hidden');
                // Enable scroll pada body
                document.body.style.overflow = 'auto';
            });
        }

        // Tutup menu jika klik di luar area menu
        if (mobileMenu) {
            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu) {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Auto hide notification after 3 seconds
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 3000);
    </script>

    <!-- Script untuk Notifikasi Cancel dari User    -->
    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50" id="success-notification">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-notification').style.display = 'none';
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50" id="error-notification">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('error-notification').style.display = 'none';
        }, 5000);
    </script>
    @endif
</body>

</html>