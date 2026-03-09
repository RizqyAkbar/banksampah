<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Jogja Resik</title>
    
    <link rel="icon" href="{{ asset('images/logoclient.png') }}" type="image/png">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #F4F7F6; /* Abu-abu sangat soft/bersih */
            margin: 0;
            padding: 0;
        }
        
        /* Container untuk mengunci layout agar selalu di tengah */
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        /* Desain Card Utama (Persis seperti gambar) */
        .login-card {
            width: 100%;
            max-width: 420px; /* Mengunci lebar agar tidak melebar */
            background-color: #FFFFFF;
            border-radius: 24px;
            padding: 2.5rem;
            /* Bayangan super halus di sekitar kartu */
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        /* Desain bayangan menyala untuk tombol hijau */
        .btn-green-glow {
            background-color: #0FA958;
            box-shadow: 0 8px 20px -6px rgba(15, 169, 88, 0.6);
            transition: all 0.3s ease;
        }
        .btn-green-glow:hover {
            background-color: #0D924C;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(15, 169, 88, 0.7);
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="login-card">
            
            <div class="flex flex-col items-center mb-8 text-center">
                <div class="w-14 h-14 bg-[#F0FDF4] rounded-2xl flex items-center justify-center mb-4 border border-green-100">
                    <img src="{{ asset('images/logoclient.png') }}" alt="Logo" class="h-8 w-auto">
                </div>
                <h1 class="text-[22px] font-bold text-gray-900 mb-1">Masuk ke Dashboard</h1>
                <p class="text-[13px] text-gray-500">Silakan masukkan email dan kata sandi Anda.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 rounded-lg bg-red-50 p-3 text-center text-[13px] font-medium text-red-600 border border-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label for="email" class="block text-[14px] font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Contoh: admin@jogjaresik.com"
                        class="w-full bg-[#F9FAFB] border border-gray-200 text-gray-900 text-[15px] rounded-xl focus:ring-2 focus:ring-[#0FA958] focus:border-[#0FA958] block px-4 py-3.5 outline-none transition-all">
                </div>

                <div class="mb-8">
                    <label for="password" class="block text-[14px] font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full bg-[#F9FAFB] border border-gray-200 text-gray-900 text-[15px] rounded-xl focus:ring-2 focus:ring-[#0FA958] focus:border-[#0FA958] block px-4 py-3.5 outline-none transition-all">
                </div>

                <button type="submit"
                    class="w-full text-white font-semibold rounded-full text-[15px] px-5 py-3.5 text-center btn-green-glow active:scale-[0.98]">
                    Masuk
                </button>
            </form>

        </div>
    </div>

</body>
</html>