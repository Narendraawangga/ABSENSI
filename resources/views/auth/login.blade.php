@extends('layouts.app')

@section('title', 'Login - SIMAGANG BPS Kolaka Utara')

@section('content')
<div class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden" style="background: linear-gradient(135deg, #1a5fa8 0%, #2e8b3e 100%);">

    {{-- Background elegant blobs --}}
    <div class="absolute w-[600px] h-[600px] rounded-full opacity-30 blur-[100px] -top-32 -left-32" style="background: radial-gradient(circle, #a3e635, #22c55e);"></div>
    <div class="absolute w-[500px] h-[500px] rounded-full opacity-30 blur-[80px] -bottom-20 -right-20" style="background: radial-gradient(circle, #e07b1a, #f5921e);"></div>

    {{-- Glassmorphism Card --}}
    <div class="relative z-10 w-full max-w-md rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden p-10"
         style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2);">
         
        {{-- Header --}}
        <div class="mb-10 text-center">
            <div class="inline-block p-3 rounded-2xl bg-white/10 border border-white/20 mb-4 shadow-lg backdrop-blur-md">
                <img src="{{ asset('storage/logo/logo-bps-kolut.png') }}" alt="Logo BPS" class="h-12 w-auto drop-shadow-md brightness-0 invert">
            </div>
            <h2 class="text-3xl font-bold mb-2 text-white" style="font-family:'Fredoka One',cursive; letter-spacing:.02em;">
                SIMAGANG
            </h2>
            <p class="text-white/70 text-sm" style="font-family:'Nunito',sans-serif;">Silakan masuk ke akun Anda.</p>
        </div>

        {{-- Error messages --}}
        @if($errors->any())
        <div class="mb-6 p-4 rounded-xl text-sm border bg-red-500/20 border-red-500/30 text-white backdrop-blur-md">
            <div class="flex items-start gap-2">
                <i class="bi bi-exclamation-circle-fill text-lg mt-0.5 text-red-200"></i>
                <ul class="list-disc pl-3 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            {{-- Username --}}
            <div class="group relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-person-fill text-white/50 text-lg"></i>
                </div>
                <input type="text" id="username" name="username"
                       value="{{ old('username') }}" required autofocus
                       placeholder="Username atau Email"
                       class="w-full pl-12 pr-4 py-4 rounded-xl text-sm font-medium outline-none transition-all duration-300 border border-white/10 bg-white/5 text-white placeholder-white/50 focus:bg-white/10 focus:border-white/30 focus:shadow-[0_0_15px_rgba(255,255,255,0.1)]">
            </div>

            {{-- Password --}}
            <div class="group relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-key-fill text-white/50 text-lg"></i>
                </div>
                <input type="password" id="password" name="password"
                       required placeholder="Password"
                       class="w-full pl-12 pr-4 py-4 rounded-xl text-sm font-medium outline-none transition-all duration-300 border border-white/10 bg-white/5 text-white placeholder-white/50 focus:bg-white/10 focus:border-white/30 focus:shadow-[0_0_15px_rgba(255,255,255,0.1)]">
            </div>

            {{-- Remember me --}}
            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/10 text-[#a3e635] focus:ring-[#a3e635] focus:ring-offset-0 focus:ring-offset-transparent accent-[#a3e635]">
                    <span class="text-sm text-white/80 font-medium" style="font-family:'Nunito',sans-serif;">Ingat Saya</span>
                </label>
                <a href="#" class="text-sm font-bold text-white/80 hover:text-white transition-colors" style="font-family:'Nunito',sans-serif;">Lupa password?</a>
            </div>

            {{-- Submit button --}}
            <button type="submit"
                    class="w-full mt-4 py-4 rounded-xl text-blue-900 font-bold text-base transition-all duration-300 shadow-lg flex justify-center items-center gap-2 bg-[#a3e635] hover:bg-[#bef264] hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(163,230,53,0.3)]">
                <span style="font-family:'Fredoka One',cursive; letter-spacing:.05em;">MASUK PORTAL</span>
                <i class="bi bi-box-arrow-in-right text-xl"></i>
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center text-xs text-white/40 font-medium" style="font-family:'Nunito',sans-serif;">
            &copy; {{ date('Y') }} BPS Kab. Kolaka Utara
        </div>
    </div>
</div>
@endsection
