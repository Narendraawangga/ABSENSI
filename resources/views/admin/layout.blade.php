@extends('layouts.app')
@section('title', 'Admin Dashboard - Sistem Magang TMD')
@section('content')
<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden" style="background:#f3f4f6;">

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen"
         class="fixed inset-0 z-20 transition-opacity bg-black/40 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col overflow-y-auto transition duration-300 transform lg:translate-x-0 lg:static lg:inset-auto"
           style="background: linear-gradient(180deg, #111827 0%, #1f2937 100%); box-shadow: 4px 0 24px rgba(0,0,0,0.15);">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-6 py-6 mb-2">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/logo/logo-bps-kolut.png') }}" alt="Logo BPS" class="h-10 w-auto brightness-0 invert">
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Divider --}}
        <div class="mx-4 h-px bg-white/20 mb-4"></div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-house-door-fill text-lg w-5 text-center"></i>
                <span>Dashboard</span>
                @if(request()->routeIs('admin.dashboard'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>

            <a href="{{ route('admin.activities') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.activities*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-journal-text text-lg w-5 text-center"></i>
                <span>Laporan Harian</span>
                @if(request()->routeIs('admin.activities*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>

            <a href="{{ route('admin.interns') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.interns*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-people-fill text-lg w-5 text-center"></i>
                <span>Data Magang</span>
                @if(request()->routeIs('admin.interns*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>

            <a href="{{ route('admin.leave-requests') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.leave-requests*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-calendar-check-fill text-lg w-5 text-center"></i>
                <span>Persetujuan Izin</span>
                @if(request()->routeIs('admin.leave-requests*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>

            <a href="{{ route('admin.evaluations') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.evaluations*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-patch-check-fill text-lg w-5 text-center"></i>
                <span>Evaluasi</span>
                @if(request()->routeIs('admin.evaluations*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <i class="bi bi-bar-chart-fill text-lg w-5 text-center"></i>
                <span>Laporan Kehadiran</span>
                @if(request()->routeIs('admin.reports*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                @endif
            </a>
        </nav>

        {{-- Bottom user snippet --}}
        <div class="mx-3 mb-4 p-3 rounded-xl bg-gray-800/50 border border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-inner">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-white font-bold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 uppercase tracking-wider" style="font-size: 0.65rem;">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="flex items-center justify-between px-4 lg:px-6 py-4 bg-white border-b border-gray-200 shadow-sm z-10">
            <div class="flex items-center gap-3">
                {{-- Mobile menu button --}}
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>

                {{-- Page breadcrumb hint --}}
                <div class="hidden md:flex items-center gap-2">
                    <span class="text-sm font-black text-gray-800 uppercase tracking-widest">Administrator</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-sm text-gray-500 font-medium">Control Panel</span>
                </div>
            </div>

            {{-- Right: user menu --}}
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-gray-50 transition-all border border-transparent hover:border-gray-200">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm text-white bg-blue-600 shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700 hidden md:block">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-xs text-gray-400"></i>
                </button>

                <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10"></div>

                <div x-show="dropdownOpen"
                     class="absolute right-0 z-20 w-48 mt-2 rounded-xl bg-white shadow-lg border border-gray-100 overflow-hidden"
                     style="display: none;">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                        <i class="bi bi-person-circle text-gray-400"></i> Profil Saya
                    </a>
                    <div class="h-px bg-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full text-left px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-6 bg-gray-50">
            @yield('admin_content')
        </main>
    </div>
</div>
@endsection
