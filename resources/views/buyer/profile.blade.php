@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col" 
     x-data="{ 
        photoPreview: null, 
        openDeleteModal: {{ $errors->userDeletion->any() ? 'true' : 'false' }}, 
        deleteUrl: '{{ route('profile.destroy') }}',
        modalTitle: 'Deactivate Account?',
        modalMessage: 'Are you sure you want to proceed? This will permanently remove your account history and associated data.'
     }">
    
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-6 sm:px-12 lg:px-20 py-8 lg:py-12 w-full">
        
        <header class="mb-10 text-center sm:text-left">
            <span class="text-[#738D56] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-3 py-1 rounded-full">Your Account</span>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-4">Manage Profile</h1>
        </header>

        {{-- Success Messages --}}
        @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/10 animate-fade-in flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('status') === 'profile-updated' ? 'Profile details updated successfully.' : 'Security settings updated successfully.' }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- Profile Sidebar --}}
            <div class="w-full lg:w-[45%] bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50 flex flex-col items-center">
                <div class="relative mb-10 group">
                    <div class="w-40 h-40 rounded-full border-4 border-[#738D56]/20 overflow-hidden shadow-xl bg-gray-50">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <img src="{{ $user->buyerDetail->profile_picture ? asset('images/profile/' . $user->buyerDetail->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->buyerDetail->first_name).'&background=738D56&color=fff&size=200' }}" 
                                 alt="Profile" class="w-full h-full object-cover">
                        </template>
                    </div>
                    <button type="button" onclick="document.getElementById('profile_photo').click()" 
                            class="absolute bottom-2 right-2 bg-[#738D56] text-white p-3 rounded-full border-4 border-white shadow-lg hover:bg-[#5f7547] transition transform active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="w-full space-y-3">
                    <div class="bg-[#F9F7F2]/60 p-5 rounded-2xl border border-gray-100 flex justify-between items-center">
                        <span class="font-black text-gray-400 uppercase tracking-widest text-[9px]">Full Name</span>
                        <span class="font-bold text-gray-700 text-sm text-right">
                            {{ $user->buyerDetail->first_name }} {{ $user->buyerDetail->last_name }}
                        </span>
                    </div>

                    <div class="bg-[#F9F7F2]/60 p-5 rounded-2xl border border-gray-100 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-black text-gray-400 uppercase tracking-widest text-[9px]">Age</span>
                            <span class="font-bold text-gray-700 text-sm">{{ $user->buyerDetail->age ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-4">
                            <span class="font-black text-gray-400 uppercase tracking-widest text-[9px] mt-1">Address</span>
                            <span class="font-bold text-gray-700 text-sm text-right flex-1">{{ $user->buyerDetail->address ?? 'Not set' }}</span>
                        </div>
                    </div>

                    <div class="bg-[#F9F7F2]/60 p-5 rounded-2xl border border-gray-100 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-black text-gray-400 uppercase tracking-widest text-[9px]">Contact</span>
                            <span class="font-bold text-gray-700 text-sm">{{ $user->buyerDetail->phone_number ?? 'Not set' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-black text-gray-400 uppercase tracking-widest text-[9px]">Email</span>
                            <span class="font-bold text-gray-700 text-sm truncate ml-4">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="pt-6 text-center">
                        <button type="button" 
                            @click="openDeleteModal = true" 
                            class="text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-colors">
                            Deactivate My Account
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Section --}}
            <div class="w-full lg:w-[55%] space-y-8">
                {{-- Edit Info Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50">
                    <h2 class="text-xl font-bold text-[#6D4C41] mb-8">Personal Information</h2>
                    
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            ">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->buyerDetail->first_name) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Middle</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->buyerDetail->middle_name) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->buyerDetail->last_name) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Mobile Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->buyerDetail->phone_number) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Age</label>
                                <input type="number" name="age" value="{{ old('age', $user->buyerDetail->age) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Shipping Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->buyerDetail->address) }}" class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                        </div>

                        <button type="submit" class="w-full py-5 bg-[#738D56] text-white font-black uppercase tracking-widest text-[11px] rounded-2xl hover:bg-[#5f7547] transition-all shadow-xl shadow-[#738D56]/20">
                            Update Profile
                        </button>
                    </form>
                </div>

                {{-- Security Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50"
                     x-data="{ 
                        password: '', 
                        password_confirmation: '',
                        get isDirty() { return this.password.length > 0 || this.password_confirmation.length > 0 },
                        get passwordsMatch() { return this.password === this.password_confirmation && this.password.length > 0 },
                        get isLongEnough() { return this.password.length >= 8 }
                     }">
                    <h2 class="text-xl font-bold text-[#6D4C41] mb-8">Security Settings</h2>
                    
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="text-[10px] text-red-500 mt-2 ml-1 font-bold uppercase tracking-tight">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">New Password</label>
                            <input type="password" name="password" x-model="password" required 
                                   class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none"
                                   :class="isDirty && !isLongEnough ? 'border-orange-300' : ''">
                            <template x-if="isDirty && !isLongEnough">
                                <p class="text-[10px] text-orange-500 mt-2 ml-1 font-bold uppercase tracking-tight">Minimum 8 characters required</p>
                            </template>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" x-model="password_confirmation" required 
                                   class="w-full px-5 py-4 bg-[#F9F7F2]/40 border-2 border-transparent focus:border-[#738D56] rounded-2xl font-bold text-gray-700 transition-all outline-none"
                                   :class="isDirty && password_confirmation.length > 0 && !passwordsMatch ? 'border-red-300' : (passwordsMatch ? 'border-green-300' : '')">
                            
                            <div class="mt-2 ml-1 h-4">
                                <template x-if="isDirty && password_confirmation.length > 0 && !passwordsMatch">
                                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-tight">Passwords do not match</p>
                                </template>
                                <template x-if="passwordsMatch">
                                    <p class="text-[10px] text-[#738D56] font-bold uppercase tracking-tight flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Ready to update
                                    </p>
                                </template>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="!passwordsMatch || !isLongEnough"
                                class="w-full py-5 bg-gray-900 text-white font-black uppercase tracking-widest text-[11px] rounded-2xl hover:bg-black transition-all shadow-xl disabled:opacity-50 disabled:grayscale">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-buyer-footer />

    <x-delete-modal 
        id="openDeleteModal" 
        action="deleteUrl" 
        :requirePassword="true" 
    />
</div>
@endsection