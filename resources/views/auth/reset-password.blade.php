@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="h-screen w-full bg-[#F9F7F2] flex flex-col overflow-hidden" 
     x-data="{ password: '', password_confirmation: '', showPassword: false }">
    
    {{-- Logo Component --}}
    <x-auth-header />

    {{-- Content Layout --}}
    <main class="flex-grow flex flex-col lg:flex-row items-center justify-center p-6 lg:p-20 gap-8 lg:gap-16 overflow-y-auto">
        
        {{-- Branding Section --}}
        <div class="hidden lg:flex w-1/2 flex-col space-y-6 max-w-2xl">
            <div>
                <span class="px-4 py-1.5 border border-gray-200 rounded-full text-[10px] tracking-widest text-gray-500 font-semibold uppercase bg-white/50">
                    Security • CocoHub Account
                </span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-[1.1]">
                Secure your <br> workspace with <br> a new password.
            </h1>

            <p class="text-gray-600 text-lg text-justify max-w-md leading-relaxed">
                Choose a strong password to keep your handcrafted orders and gardening history safe.
            </p>
        </div>

        {{-- Authentication Card --}}
        <div class="w-full max-w-md lg:w-[450px] shrink-0">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-50">
                <h2 class="text-3xl font-bold text-[#6D4C41] text-center mb-8">New Password</h2>

                {{-- Reset Form --}}
                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-gray-400 cursor-not-allowed text-sm font-medium" />
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-2 ml-1 font-bold italic tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">New Password</label>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required 
                            placeholder="Min. 8 characters"
                            class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium pr-12" />
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-[44px] text-gray-400 hover:text-[#738D56] transition-colors">
                            <template x-if="!showPassword">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </template>
                            <template x-if="showPassword">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                            </template>
                        </button>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Confirm New Password</label>
                        <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required 
                            placeholder="Repeat password"
                            class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium" />
                        
                        <div class="mt-2 ml-1 h-4">
                            <p x-show="password_confirmation.length > 0 && password === password_confirmation" class="text-green-500 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                ✓ Match
                            </p>
                            <p x-show="password_confirmation.length > 0 && password !== password_confirmation" class="text-red-500 text-[10px] font-bold uppercase tracking-widest">✗ Passwords do not match</p>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                :disabled="password !== password_confirmation || password.length < 8"
                                :class="password !== password_confirmation || password.length < 8 ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full bg-[#738D56] hover:bg-[#5f7547] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#738D56]/20 transition duration-300 transform active:scale-[0.98]">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection