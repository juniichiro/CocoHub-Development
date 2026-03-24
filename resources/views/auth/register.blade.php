@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="h-screen w-full bg-[#F9F7F2] flex flex-col overflow-hidden" 
     x-data="{ 
        password: '', 
        password_confirmation: '', 
        showPassword: false, 
        showConfirmPassword: false 
     }">
    
    {{-- Logo Component --}}
    <x-logo />

    {{-- Content Layout --}}
    <main class="flex-grow flex flex-col lg:flex-row items-center justify-center p-6 lg:p-12 gap-8 lg:gap-16 overflow-y-auto">
        
        {{-- Branding Section --}}
        <div class="hidden lg:flex w-1/2 flex-col space-y-6 max-w-2xl">
            <div>
                <span class="px-4 py-1.5 border border-gray-200 rounded-full text-[10px] tracking-widest text-gray-500 font-semibold uppercase bg-white/50">
                    Sustainable • Handcrafted • Filipino-Made
                </span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-[1.1]">
                Natural coconut <br> coir products for a <br> greener everyday <br> life.
            </h1>

            <p class="text-gray-600 text-lg max-w-md leading-relaxed">
                Discover beautifully crafted coir products for gardening, home, and sustainable living.
            </p>

            <div class="flex gap-4 pt-2">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">100%</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Natural Fiber</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">50k</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Likes</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">Eco</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Packaging</span>
                </div>
            </div>
        </div>

        {{-- Authentication Card --}}
        <div class="w-full max-w-xl lg:w-[550px] shrink-0 py-8">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-50">
                <h2 class="text-3xl font-bold text-[#6D4C41] text-center mb-6">Create Account</h2>
                
                {{-- Registration Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="name" id="hidden_name" value="{{ old('name') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="First"
                                onblur="formatInput(this)"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium capitalize" />
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Middle"
                                onblur="formatInput(this)"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium capitalize" />
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required placeholder="Last"
                                onblur="formatInput(this)"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium capitalize" />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Mobile Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="Mobile"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium" />
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Age</label>
                            <input type="number" name="age" value="{{ old('age') }}" required placeholder="Age"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Residential Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" required placeholder="Complete Address"
                            onblur="formatInput(this)"
                            class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium capitalize" />
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com"
                            class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required placeholder="Min 8 chars"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium pr-12" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-[42px] text-gray-400 hover:text-[#738D56] transition-colors">
                                <template x-if="!showPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                </template>
                            </button>
                        </div>
                        <div class="relative">
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Confirm Password</label>
                            <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required placeholder="Confirm"
                                class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-gray-700 placeholder-gray-300 transition-all text-sm font-medium pr-12" />
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-4 top-[42px] text-gray-400 hover:text-[#738D56] transition-colors">
                                <template x-if="!showConfirmPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </template>
                                <template x-if="showConfirmPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div class="h-3 ml-2">
                        <template x-if="password_confirmation.length > 0">
                            <div>
                                <p x-show="password === password_confirmation" class="text-green-500 text-[10px] font-bold flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    Passwords match
                                </p>
                                <p x-show="password !== password_confirmation" class="text-red-500 text-[10px] font-bold">✗ Passwords do not match</p>
                            </div>
                        </template>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="password !== password_confirmation || password.length < 8"
                                :class="password !== password_confirmation || password.length < 8 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#5f7547]'"
                                class="w-full bg-[#738D56] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#738D56]/20 transition duration-300 transform active:scale-[0.98]">
                            Register
                        </button>
                    </div>

                    <p class="text-center text-[11px] text-gray-400 font-medium">
                        Already have an Account? <a href="{{ route('login') }}" class="text-[#738D56] font-bold hover:underline">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection