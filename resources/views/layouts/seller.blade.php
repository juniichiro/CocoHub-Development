<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Updated Title: Shows "Dashboard | Logo Name" or similar --}}
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/coco-hub.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="antialiased bg-[#F9F7F2]">
    <div class="flex min-h-screen overflow-x-hidden">
        
        {{-- Fixed Sidebar Component --}}
        <x-seller-sidebar />

        {{-- Main Content Area --}}
        <div class="flex-grow flex flex-col lg:ml-64 w-full">
            
            {{-- Mobile Header --}}
            <header class="lg:hidden bg-white border-b border-gray-100 p-4 sticky top-0 z-30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/coco-hub.png') }}" class="w-8 h-8" alt="Logo">
                    <span class="text-lg font-bold text-[#6D4C41]">Coco<span class="text-[#738D56]">Hub</span></span>
                </div>
                
                <button @click="sidebarOpen = true" class="p-2 text-gray-500 hover:bg-[#F9F7F2] rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </header>

            {{-- Page Content --}}
            <main class="p-6 sm:p-8 lg:p-12">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>