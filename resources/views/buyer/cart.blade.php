@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<div class="min-h-screen bg-[#F9F7F2] flex flex-col" x-data="cartManager(@js($cart->items ?? []))">
    
    @include('layouts.navigation')

    {{-- Cart Header Section --}}
    <main class="flex-grow max-w-7xl mx-auto px-6 sm:px-12 lg:px-20 py-8 lg:py-12 w-full">
        <header class="mb-8 lg:mb-10 text-center md:text-left flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <span class="text-[#738D56] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-3 py-1 rounded-full">Your Cart</span>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-3">Shopping Cart</h1>
            </div>
            
            {{-- Bulk Selection Toggle --}}
            @if(count($cart->items ?? []) > 0)
                <label class="flex items-center justify-center md:justify-start gap-3 cursor-pointer group bg-white px-5 py-3 rounded-2xl border border-gray-100 shadow-sm hover:border-[#738D56]/30 transition-all">
                    <input type="checkbox" 
                           @click="toggleAll" 
                           :checked="allSelected" 
                           class="w-5 h-5 rounded-lg border-2 border-gray-300 text-[#738D56] focus:ring-[#738D56] focus:ring-offset-0 transition cursor-pointer">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-[#738D56]">Select All Items</span>
                </label>
            @endif
        </header>

        {{-- Cart Form  --}}
        <form action="{{ route('buyer.checkout') }}" method="GET" id="cart-form">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Item List Container --}}
                <div class="w-full lg:w-2/3 bg-white rounded-[2.5rem] p-6 sm:p-10 shadow-sm border border-gray-50">
                    <h2 class="text-xl font-bold text-gray-800 mb-8">Items in Cart</h2>
                    
                    <div class="space-y-8">
                        @forelse($cart->items ?? [] as $item)
                            {{-- Individual Cart Item --}}
                            <div class="flex flex-col sm:grid sm:grid-cols-12 items-start sm:items-center gap-6 pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                                
                                {{-- Item Selection Checkbox --}}
                                <div class="sm:col-span-1">
                                    <input type="checkbox" 
                                           name="selected_items[]" 
                                           value="{{ $item->id }}"
                                           x-model="selectedItems"
                                           class="w-6 h-6 rounded-xl border-2 border-gray-300 text-[#738D56] focus:ring-[#738D56] focus:ring-offset-0 transition shadow-sm cursor-pointer">
                                </div>

                                {{-- Product Image --}}
                                <div class="w-full sm:col-span-3 lg:col-span-2 aspect-square sm:aspect-auto sm:h-24 rounded-2xl overflow-hidden shadow-sm bg-gray-50 border border-gray-50">
                                    <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.png')) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                {{-- Item Data --}}
                                <div class="flex-grow w-full sm:col-span-5 lg:col-span-6 space-y-2">
                                    <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $item->product->name }}</h3>
                                    <div class="flex items-center gap-4">
                                        <div class="inline-flex items-center bg-[#F9F7F2] rounded-full p-1 border border-gray-100">
                                            <button type="button" @click="updateQty({{ $item->id }}, 'dec')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition font-black">-</button>
                                            <span class="px-3 text-sm font-black text-gray-700">{{ $item->quantity }}</span>
                                            <button type="button" @click="updateQty({{ $item->id }}, 'inc')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-[#738D56] transition font-black">+</button>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Subtotal and Unit Pricing --}}
                                <div class="w-full sm:col-span-3 flex sm:flex-col justify-between sm:justify-center items-center sm:items-end border-t sm:border-t-0 pt-4 sm:pt-0">
                                    <span class="text-xl font-black text-gray-900">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">₱{{ number_format($item->product->price, 2) }} EA</p>
                                </div>
                            </div>
                        @empty
                            {{-- Empty State Display --}}
                            <div class="py-20 text-center flex flex-col items-center">
                                <div class="w-20 h-20 bg-[#F9F7F2] rounded-[2rem] flex items-center justify-center mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Your cart is empty</p>
                                <a href="{{ route('buyer.product') }}" class="mt-4 text-[#738D56] text-xs font-bold uppercase underline">Browse Products</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Order Summary Sidebar --}}
                <div class="w-full lg:w-1/3 space-y-4 lg:sticky lg:top-28">
                    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-gray-50">
                        <h2 class="text-xl font-bold text-gray-800 mb-8">Order Totals</h2>
                        
                        {{-- Calculation Breakdown --}}
                        <div class="space-y-5">
                            <div class="flex justify-between items-center text-sm sm:text-base">
                                <span class="text-gray-400 font-medium">Selected Subtotal</span>
                                <span class="text-gray-800 font-bold">₱<span x-text="formatNumber(subtotal)">0.00</span></span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-5 border-b border-gray-100 text-sm sm:text-base">
                                <span class="text-gray-400 font-medium">Delivery Fee</span>
                                <span class="text-gray-800 font-bold">₱<span x-text="formatNumber(deliveryFee)">0.00</span></span>
                            </div>
                            
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-lg font-bold text-gray-900">Total Due</span>
                                <span class="text-2xl font-black text-[#738D56]">₱<span x-text="formatNumber(total)">0.00</span></span>
                            </div>

                            {{-- Checkout Actions --}}
                            <div class="space-y-4 pt-8">
                                <button type="submit" 
                                        :disabled="selectedItems.length === 0"
                                        class="block w-full py-5 bg-[#738D56] hover:bg-[#5f7547] text-white text-center font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-[#738D56]/20 transition-all transform active:scale-95 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed">
                                    Checkout Selected (<span x-text="selectedItems.length">0</span>)
                                </button>
                                
                                <a href="{{ route('buyer.product') }}" class="block w-full py-5 bg-white border-2 border-gray-100 text-gray-400 text-center font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-gray-50 hover:border-gray-200 transition-all">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    {{-- Footer Component --}}
    <x-buyer-footer />
</div>
@endsection