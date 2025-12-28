<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CORELASI</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-[#F3F4F6] h-screen w-screen flex items-center justify-center overflow-hidden relative">

    {{-- Background Decoration --}}
    <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] bg-indigo-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vw] bg-violet-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-2000"></div>

    <div class="container mx-auto px-4 relative z-10 flex justify-center">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white mb-6 shadow-xl shadow-indigo-500/30 ring-4 ring-white/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Welcome Back!</h1>
                <p class="text-slate-500 mt-2">Sign in to your teacher account</p>
            </div>

            <div class="glass-panel p-8 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.04)] border border-white/60">
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" required
                               class="w-full px-5 py-3.5 rounded-xl border border-slate-200 bg-white/60 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 font-medium"
                               placeholder="guru@corelasi.com">
                        @error('email') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2 ml-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-5 py-3.5 rounded-xl border border-slate-200 bg-white/60 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 font-medium"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-slate-500 font-medium">Remember me</span>
                        </label>
                        <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Forgot Password?</a>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-lg shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                        Sign In
                    </button>
                    
                </form>
            </div>

            <p class="text-center text-slate-400 text-sm mt-8 font-medium">
                &copy; {{ date('Y') }} Corelasi Education.
            </p>

        </div>
    </div>
</body>
</html>
