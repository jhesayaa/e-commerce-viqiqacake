<!DOCTYPE html>
<html>
<head>
    <title>Register - ViqiqaCake</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-amber-50 font-['Poppins']">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-amber-800 py-6 px-4 text-center">
                <a href="/" class="inline-block">
                    <h1 class="text-3xl font-bold text-amber-50">ViqiqaCake</h1>
                </a>
            </div>
            
            <div class="p-8">
                <h2 class="text-2xl font-bold text-amber-800 text-center mb-8">Buat Akun Baru</h2>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="name" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>
                    
                    <!-- Tambahkan nomor telepon -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Nomor Telepon</label>
                        <input type="tel" name="phone" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>
                    
                    <!-- Tambahkan alamat -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Alamat Lengkap</label>
                        <textarea name="address" 
                                 class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base"
                                 rows="3" required></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>

                    @if ($errors->any())
                        <div class="p-4 bg-red-50 text-red-600 rounded-lg">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" 
                            class="w-full bg-amber-600 text-white py-4 rounded-lg hover:bg-amber-700 transition duration-300 font-medium text-base">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-6 text-center text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-amber-600 font-medium hover:text-amber-700">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>