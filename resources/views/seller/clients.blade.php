@extends('layouts.seller')

@section('title', 'Clients')

@section('content')
<div class="flex flex-col min-h-screen" 
     x-data="{ 
        openDeleteModal: false, 
        deleteUrl: '', 
        modalTitle: '', 
        modalMessage: '' 
     }">
    
    <div class="flex-grow">
        {{-- Section Header --}}
        <header class="mb-10 text-center sm:text-left">
            <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Your Network</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Manage Accounts</h2>
        </header>

        {{-- Status Notifications --}}
        @if(session('status') === 'account-deleted' || session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm font-bold rounded-2xl animate-fade-in flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('status') === 'account-deleted' ? 'Account removed successfully.' : session('success') }}</span>
            </div>
        @endif

        {{-- Account Management Card --}}
        <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50">
            <div class="mb-10 text-center sm:text-left">
                <h3 class="text-lg font-medium text-gray-400">Manage user accounts and platform permissions</h3>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('seller.clients') }}" class="mb-10">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search by name, email, or city..." 
                        class="w-full px-8 py-5 bg-[#F9F7F2]/60 border-none rounded-2xl text-sm outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all shadow-sm font-medium placeholder-gray-400">
                    
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-4">
                        @if(request('search'))
                            <a href="{{ route('seller.clients') }}" class="text-xs font-bold text-gray-400 hover:text-red-400 transition-colors">
                                Clear
                            </a>
                        @endif
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#738D56]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Client Data Table --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                            <th class="px-12 pb-6 font-bold">Client Name</th>
                            <th class="px-12 pb-6 font-bold">Email</th>
                            <th class="px-12 pb-6 font-bold">Phone</th>
                            <th class="px-12 pb-6 font-bold text-center">Location</th>
                            <th class="px-12 pb-6 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($clients as $client)
                        <tr class="hover:bg-[#F9F7F2]/30 transition-colors group">
                            <td class="px-12 py-8 font-bold text-gray-800 min-w-[200px]">
                                {{ $client->buyerDetail->first_name ?? 'N/A' }} 
                                {{ $client->buyerDetail->last_name ?? '' }}
                            </td>
                            <td class="px-12 py-8 text-gray-500 font-medium">{{ $client->email }}</td>
                            <td class="px-12 py-8 text-gray-500 font-medium">{{ $client->buyerDetail->phone_number ?? 'No Phone' }}</td>
                            <td class="px-12 py-8 text-center min-w-[180px]">
                                <span class="text-gray-400 text-xs italic truncate block max-w-[150px] mx-auto" title="{{ $client->buyerDetail->address }}">
                                    {{ $client->buyerDetail->address ?? 'No Address set' }}
                                </span>
                            </td>
                            <td class="px-12 py-8 text-right">
                                <button type="button" 
                                    @click="
                                        openDeleteModal = true; 
                                        deleteUrl = '{{ route('seller.clients.destroy', $client->id) }}';
                                        modalTitle = 'Remove Account?';
                                        modalMessage = 'Are you sure you want to delete the account for {{ $client->buyerDetail->first_name ?? 'this user' }}? This action cannot be undone.';
                                    "
                                    class="w-10 h-10 inline-flex items-center justify-center bg-white border border-gray-100 rounded-xl text-gray-300 hover:text-red-500 hover:border-red-100 hover:shadow-sm transition-all transform active:scale-90" 
                                    title="Delete Account">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <div class="w-20 h-20 bg-[#F9F7F2] rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold uppercase tracking-widest text-[10px] text-gray-400">No accounts found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Footer Summary --}}
            <div class="mt-8 flex justify-between items-center border-t border-gray-50 pt-6">
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                    Records Found: {{ $clients->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Footer Component --}}
    <div class="mt-12">
        <x-seller-footer />
    </div>

    {{-- Delete Modal --}}
    <x-delete-modal id="openDeleteModal" action="deleteUrl" />
</div>
@endsection