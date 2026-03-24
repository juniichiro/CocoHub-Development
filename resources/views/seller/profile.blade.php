@extends('layouts.seller')

@section('title', 'Profile')

@section('content')
<div class="flex flex-col min-h-screen" 
     x-data="{ 
        photoPreview: null 
     }">
    
    <div class="flex-grow">
        
        {{-- Section Header --}}
        <header class="mb-10 text-center sm:text-left">
            <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Your Profile</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Manage Profile</h2>
        </header>

        {{-- Status Notifications --}}
        @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm font-bold rounded-2xl animate-fade-in flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('status') === 'profile-updated' ? 'Profile information updated successfully.' : 'Password changed successfully.' }}</span>
            </div>
        @endif

        {{-- Validation Error Alert --}}
        @if($errors->has('profile_photo'))
            <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 text-sm font-bold rounded-2xl animate-shake flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ $errors->first('profile_photo') }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start mb-12">
            
            {{-- Account Details --}}
            <div class="w-full lg:w-3/5 bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-gray-50 flex flex-col items-center">

                {{-- Profile Picture--}}
                <div class="relative mb-12">
                    <div class="w-40 h-40 rounded-full border-4 border-[#F9F7F2] overflow-hidden shadow-sm {{ $errors->has('profile_photo') ? 'border-red-200' : '' }}">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        
                        <template x-if="!photoPreview">
                            <img src="{{ $user->sellerDetail->profile_picture ? asset('images/profile/' . $user->sellerDetail->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->sellerDetail->first_name).'&background=738D56&color=fff&size=200' }}" 
                                 class="w-full h-full object-cover" alt="Profile">
                        </template>
                    </div>

                    <button type="button" 
                            onclick="document.getElementById('profile_photo').click()"
                            class="absolute bottom-2 right-2 bg-[#738D56] p-2 rounded-full text-white hover:bg-[#5f7547] transition-all border-4 border-white shadow-sm hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>

                {{-- Account Data --}}
                <div class="w-full space-y-2 px-4">
                    <div class="flex justify-between items-center py-5 border-b border-gray-50">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Full Name</span>
                        <span class="font-bold text-gray-700">
                            {{ $user->sellerDetail->first_name }} {{ $user->sellerDetail->last_name }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 sm:gap-12">
                        <div class="flex justify-between items-center py-5 border-b border-gray-50">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Age</span>
                            <span class="font-bold text-gray-700">{{ $user->sellerDetail->age ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-5 border-b border-gray-50">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Location</span>
                            <span class="font-bold text-gray-700 truncate max-w-[120px]" title="{{ $user->sellerDetail->address }}">
                                {{ $user->sellerDetail->address ?? 'Not Set' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-5 border-b border-gray-50">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Mobile</span>
                        <span class="font-bold text-gray-700">{{ $user->sellerDetail->phone_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-5">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Email</span>
                        <span class="font-bold text-gray-700">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Form Section --}}
            <div class="w-full lg:w-2/5 space-y-8">

                {{-- Edit Information Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50">
                    <h3 class="text-lg font-bold text-[#6D4C41] mb-8">Edit Profile</h3>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="hidden">
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                ">
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Complete Name</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <input type="text" name="first_name" value="{{ old('first_name', $user->sellerDetail->first_name) }}" placeholder="First" class="px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->sellerDetail->middle_name) }}" placeholder="Middle" class="px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                                <input type="text" name="last_name" value="{{ old('last_name', $user->sellerDetail->last_name) }}" placeholder="Last" class="px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Mobile</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->sellerDetail->phone_number) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Age</label>
                                <input type="number" name="age" value="{{ old('age', $user->sellerDetail->age) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Store Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->sellerDetail->address) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 bg-[#738D56] text-white font-bold rounded-2xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/10 transform active:scale-[0.98]">
                                Update Store Profile
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Security Settings Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50"
                     x-data="{ 
                        password: '', 
                        password_confirmation: '',
                        get isDirty() { return this.password.length > 0 || this.password_confirmation.length > 0 },
                        get passwordsMatch() { return this.password === this.password_confirmation && this.password.length > 0 },
                        get isLongEnough() { return this.password.length >= 8 }
                     }">
                    <h3 class="text-lg font-bold text-[#6D4C41] mb-6">Security</h3>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="text-[10px] text-red-500 mt-1 ml-2 font-bold uppercase tracking-tight">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">New Password</label>
                            <input type="password" name="password" x-model="password" required 
                                   class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700"
                                   :class="isDirty && !isLongEnough ? 'ring-2 ring-orange-200' : ''">
                            
                            <template x-if="isDirty && !isLongEnough">
                                <p class="text-[10px] text-orange-500 mt-1 ml-2 font-bold uppercase tracking-tighter">At least 8 characters required</p>
                            </template>
                        </div>

                        <div>
                            <label class="block text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" x-model="password_confirmation" required 
                                   class="w-full px-5 py-4 bg-[#F9F7F2]/60 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#738D56]/20 font-medium text-gray-700"
                                   :class="isDirty && password_confirmation.length > 0 && !passwordsMatch ? 'ring-2 ring-red-200' : (passwordsMatch ? 'ring-2 ring-green-100' : '')">
                            
                            <div class="mt-2 ml-2 h-4">
                                <template x-if="isDirty && password_confirmation.length > 0 && !passwordsMatch">
                                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-tighter">Passwords do not match</p>
                                </template>
                                <template x-if="passwordsMatch">
                                    <p class="text-[10px] text-[#738D56] font-bold uppercase tracking-tighter flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Ready to save
                                    </p>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    :disabled="!passwordsMatch || !isLongEnough"
                                    class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition-all shadow-lg shadow-black/10 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98]">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <div class="mt-4">
        <x-seller-footer />
    </div>
</div>
@endsection