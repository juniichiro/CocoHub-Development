@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="h-screen w-full bg-[#F9F7F2] flex flex-col overflow-hidden">

    {{-- Logo Component --}}
    <x-logo />

    <main class="flex-grow flex flex-col lg:flex-row items-center justify-center p-6 lg:p-20 gap-8 lg:gap-16 overflow-y-auto">

        {{-- Branding Section --}}
        <div class="hidden lg:flex w-1/2 flex-col space-y-6 max-w-2xl">
            <div>
                <span class="px-4 py-1.5 border border-gray-200 rounded-full text-[10px] tracking-widest text-gray-500 font-semibold uppercase bg-white/50">
                    Sustainable • Handcrafted • Filipino-Made
                </span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-[1.1]">
                Reset your <br> connection to <br> nature.
            </h1>

            <p class="text-gray-600 text-lg text-justify max-w-md leading-relaxed">
                Forgot your password? No worries. Enter your email and we'll send you a link to get back into your account.
            </p>

            <div class="text-[11px] tracking-[0.3em] text-gray-300 font-black uppercase pt-6">
                COCOHUB | LUMIERE
            </div>
        </div>

        {{-- Authentication Card --}}
        <div class="w-full max-w-md lg:w-[450px] shrink-0">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-50">
                <h2 class="text-3xl font-bold text-[#6D4C41] text-center mb-4">Password Reset</h2>
                
                <p class="text-[11px] text-gray-400 text-center mb-8 uppercase font-bold tracking-wider leading-relaxed">
                    {{ __('Enter your email address to receive a password reset link.') }}
                </p>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl animate-fade-in">
                        <p class="text-[#738D56] text-xs font-bold text-center">
                            {{ session('status') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            placeholder="your@email.com"
                            class="w-full px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium" />
                        
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-2 ml-1 font-bold italic tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-4 pt-2">
                        <button type="submit" class="w-full bg-[#738D56] hover:bg-[#5f7547] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#738D56]/20 transition duration-300 transform active:scale-[0.98]">
                            Email Password Reset Link
                        </button>

                        <a href="{{ route('login') }}" class="w-full text-center text-xs font-bold text-gray-400 hover:text-[#6D4C41] transition-colors uppercase tracking-widest">
                            ← Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection