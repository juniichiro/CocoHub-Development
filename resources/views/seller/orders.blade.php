@extends('layouts.seller')

@section('title', 'Orders')

@section('content')
<div class="flex flex-col min-h-screen">
    <div class="flex-grow">
        {{-- Header Section --}}
        <header class="flex flex-col sm:flex-row justify-between items-center mb-10 gap-4 text-center sm:text-left">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Order Tracking</h1>
                <p class="text-gray-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mt-2">Manage and monitor customer shipments</p>
            </div>
            
            <div class="bg-white px-5 py-2 rounded-full border border-gray-100 shadow-sm text-[10px] font-bold text-[#738D56] uppercase tracking-widest">
                {{ now()->format('F d, Y') }}
            </div>
        </header>

        {{-- Success Alert --}}
        @if(session('status'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold rounded-2xl animate-fade-in shadow-sm flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Order status updated successfully.</span>
            </div>
        @endif

        {{-- Filters Section --}}
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm mb-10 border border-gray-50 flex flex-col lg:flex-row gap-4">
            <form action="{{ route('seller.orders') }}" method="GET" class="flex flex-col sm:flex-row flex-grow gap-4">
                <input type="text" name="search" placeholder="Search Order # or Customer..." 
                       value="{{ request('search') }}"
                       class="flex-grow px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-sm transition-all outline-none font-medium placeholder-gray-400">
                
                <div class="relative">
                    <select name="status" class="w-full sm:w-auto px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56]/20 text-sm cursor-pointer font-bold text-gray-600 outline-none appearance-none pr-12">
                        <option value="">All Statuses</option>
                        <option value="Awaiting Shipping" {{ request('status') == 'Awaiting Shipping' ? 'selected' : '' }}>Awaiting Shipping</option>
                        <option value="On Delivery" {{ request('status') == 'On Delivery' ? 'selected' : '' }}>On Delivery</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                
                <button type="submit" class="px-10 py-4 bg-[#738D56] text-white font-bold uppercase tracking-widest text-[11px] rounded-2xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20 transform active:scale-95">
                    Apply Filters
                </button>
            </form>
        </div>

        {{-- Orders Table --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                            {{-- Increased inner padding for headers --}}
                            <th class="px-12 py-7 font-bold">Order ID</th>
                            <th class="px-12 py-7 font-bold">Customer Info</th>
                            <th class="px-12 py-7 font-bold text-center">Items</th>
                            <th class="px-12 py-7 font-bold text-center">Total Amount</th>
                            <th class="px-12 py-7 font-bold text-center">Status</th>
                            <th class="px-12 py-7 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#F9F7F2]/30 transition-colors group">
                            {{-- Increased cell padding and height --}}
                            <td class="px-12 py-8 font-bold text-gray-900 min-w-[140px]">#{{ $order->id }}</td>
                            
                            <td class="px-12 py-8">
                                <div class="max-w-[220px]">
                                    <p class="text-sm font-bold text-gray-800 leading-tight">
                                        @if($order->user->buyerDetail)
                                            {{ $order->user->buyerDetail->first_name }} {{ $order->user->buyerDetail->last_name }}
                                        @else
                                            {{ $order->user->name }}
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-[#738D56] font-bold uppercase tracking-tighter mt-1">
                                        {{ $order->user->buyerDetail->phone_number ?? '0912345678' }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-12 py-8 text-center">
                                <span class="bg-[#F9F7F2] text-gray-600 text-xs font-bold px-4 py-2 rounded-xl border border-gray-100">
                                    {{ $order->items->sum('quantity') }}
                                </span>
                            </td>

                            <td class="px-12 py-8 font-bold text-[#738D56] text-center">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="px-12 py-8 text-center">
                                <span class="px-5 py-2 rounded-full text-[9px] font-bold uppercase tracking-widest inline-block border
                                    {{ $order->status == 'Awaiting Shipping' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                    {{ $order->status == 'On Delivery' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                                    {{ $order->status == 'Completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                    {{ $order->status == 'Cancelled' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="px-12 py-8 text-right min-w-[160px]">
                                <form action="{{ route('seller.orders.update', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="relative inline-block">
                                        <select name="status" onchange="this.form.submit()" 
                                                class="appearance-none pr-10 pl-6 py-3.5 text-[11px] font-bold bg-[#F9F7F2] border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#738D56]/20 cursor-pointer text-gray-700 outline-none uppercase tracking-tighter shadow-sm transition-all hover:border-[#738D56]/30">
                                            <option value="Awaiting Shipping" {{ $order->status == 'Awaiting Shipping' ? 'selected' : '' }}>Ship</option>
                                            <option value="On Delivery" {{ $order->status == 'On Delivery' ? 'selected' : '' }}>Deliver</option>
                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Finish</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancel</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-12 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-[#F9F7F2] rounded-[1.5rem] flex items-center justify-center mb-4 text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">No orders found in the system</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-seller-footer />
    </div>
</div>
@endsection