@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="h-screen w-full bg-[#F9F7F2] flex flex-col overflow-hidden" x-data="{ showPassword: false }">
    
    <header class="w-full py-4 px-8 lg:px-20 bg-white border-b border-gray-100 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/coco-hub.png') }}" alt="CocoHub Logo" class="h-10 w-10 object-contain">
            <div class="flex flex-col">
                <h1 class="text-xl font-extrabold leading-none tracking-tight">
                    <span class="text-[#6D4C41]">Coco</span><span class="text-[#738D56]">Hub</span>
                </h1>
                <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Lumiere</span>
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col lg:flex-row items-center justify-center p-6 lg:p-20 gap-8 lg:gap-16 overflow-y-auto">
        
        <div class="hidden lg:flex w-1/2 flex-col space-y-6 max-w-2xl">
            <div>
                <span class="px-4 py-1.5 border border-gray-200 rounded-full text-[10px] tracking-widest text-gray-500 font-semibold uppercase bg-white/50">
                    Sustainable • Handcrafted • Filipino-Made
                </span>
            </div>

            <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 leading-[1.1]">
                Natural coconut <br> coir products for a <br> greener everyday <br> life.
            </h1>

            <p class="text-gray-600 text-lg text-justify max-w-md leading-relaxed">
                Discover beautifully crafted coir products for gardening, home, and sustainable living.
            </p>

            <div class="flex gap-4 pt-2">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">100%</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Natural Fiber</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">50k</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Likes</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">Eco</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Packaging</span>
                </div>
            </div>

            <div class="text-[11px] tracking-[0.3em] text-gray-300 font-black uppercase pt-6">
                COCOHUB | LUMIERE
            </div>
        </div>

        <div class="w-full max-w-md lg:w-[450px] shrink-0">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-50">
                <h2 class="text-4xl font-bold text-[#6D4C41] text-center mb-8">Login</h2>

                @if (session('status'))
                    <div class="mb-4 font-bold text-sm text-green-600 text-center bg-green-50 py-2 rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            placeholder="user@cocohub.ph"
                            class="w-full px-5 py-3.5 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all" />
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password</label>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required 
                            placeholder="Password"
                            class="w-full px-5 py-3.5 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all pr-12" />
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-[38px] text-gray-400 hover:text-[#738D56] transition-colors">
                            <template x-if="!showPassword">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </template>
                            <template x-if="showPassword">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </template>
                        </button>

                        <div class="flex justify-end mt-2">
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold text-gray-400 hover:text-[#738D56] transition-colors uppercase tracking-wide" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2">
                        <button type="submit" class="w-full bg-[#738D56] hover:bg-[#5f7547] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#738D56]/20 transition duration-300 transform active:scale-[0.98]">
                            Login
                        </button>

                        <a href="{{ route('register') }}" class="w-full text-center border-2 border-gray-100 text-gray-400 font-bold py-3.5 rounded-2xl hover:bg-gray-50 hover:text-gray-600 transition duration-300">
                            Create an Account
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection