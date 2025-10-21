<!DOCTYPE html>
<html>
<head>
    <title>Login - ViqiqaCake</title>
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
                <h2 class="text-2xl font-bold text-amber-800 text-center mb-8">Selamat Datang Kembali</h2>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-base" 
                               required>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" 
                            class="w-full bg-amber-600 text-white py-4 rounded-lg hover:bg-amber-700 transition duration-300 font-medium text-base">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-amber-600 font-medium hover:text-amber-700">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>