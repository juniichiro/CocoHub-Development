@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    @include('layouts.navigation')

    {{-- Error Message --}}
    @if(session('status') === 'cart-empty')
        <div class="fixed top-24 right-4 sm:right-8 z-50 p-4 bg-red-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 animate-fade-in flex items-center gap-4 max-w-[90vw]">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Your cart is empty! Add items before checking out.</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/60 hover:text-white transition p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <main class="flex-grow max-w-7xl mx-auto px-6 sm:px-12 lg:px-20 py-8 lg:py-12 w-full">
        {{-- Checkout Header --}}
        <header class="mb-10 text-center sm:text-left">
            <span class="text-[#738D56] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-3 py-1 rounded-full">Secure Transaction</span>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-4">Checkout</h1>
        </header>

        @if(!$cart || $cart->items->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-[2.5rem] p-12 sm:p-20 text-center shadow-sm border border-gray-50 animate-fade-in">
                <div class="w-20 h-20 bg-[#F9F7F2] rounded-full flex items-center justify-center mx-auto mb-6 text-[#738D56]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No items selected</h2>
                <p class="text-gray-400 mb-8 max-w-xs mx-auto font-medium">Please go back to your cart and select the items you wish to purchase.</p>
                <a href="{{ route('buyer.cart') }}" class="inline-block px-10 py-4 bg-[#738D56] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95">
                    Back to Cart
                </a>
            </div>
        @else
            {{-- Checkout Form --}}
            <form action="{{ route('buyer.checkout.process') }}" method="POST" class="flex flex-col lg:flex-row gap-8 items-start">
                @csrf
                @foreach($cart->items as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                @endforeach

                {{-- Step 1: Customer Information --}}
                <div class="w-full lg:w-3/5 bg-white rounded-[2.5rem] p-6 sm:p-10 shadow-sm border border-gray-50">
                    <h2 class="text-xl font-bold text-[#6D4C41] mb-8 flex items-center gap-3">
                        <span class="w-8 h-8 bg-[#6D4C41]/10 rounded-full flex items-center justify-center text-sm">1</span>
                        Customer Information
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Complete Name</label>
                                <input type="text" name="customer_name" 
                                    value="{{ old('customer_name', (Auth::user()->buyerDetail->first_name ?? '') . ' ' . (Auth::user()->buyerDetail->last_name ?? '')) }}" 
                                    class="w-full px-5 py-4 bg-[#F9F7F2]/50 border-2 border-transparent focus:border-[#738D56] rounded-2xl outline-none transition-all font-bold text-gray-700 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Mobile Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->buyerDetail->phone_number ?? '') }}" 
                                    placeholder="+63"
                                    class="w-full px-5 py-4 bg-[#F9F7F2]/50 border-2 border-transparent focus:border-[#738D56] rounded-2xl outline-none transition-all font-bold text-gray-700 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly
                                class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl text-gray-400 font-bold outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Shipping Address</label>
                            <textarea name="shipping_address" rows="3" required
                                class="w-full px-5 py-4 bg-[#F9F7F2]/50 border-2 border-transparent focus:border-[#738D56] rounded-2xl outline-none transition-all font-bold text-gray-700">@if(Auth::user()->buyerDetail){{ Auth::user()->buyerDetail->address }}@endif</textarea>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Payment & Fulfillment --}}
                <div class="w-full lg:w-2/5 space-y-6" x-data="{ paymentCategory: 'Cash on Delivery' }">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 bg-[#738D56]/10 text-[#738D56] rounded-full flex items-center justify-center text-sm">2</span>
                            Payment & Fulfillment
                        </h2>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Receive via</label>
                                <select name="fulfillment_method" class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl outline-none font-bold text-gray-700 focus:border-[#738D56] transition-colors appearance-none cursor-pointer">
                                    <option value="Delivery">Delivery</option>
                                    <option value="Pickup">Pickup</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Payment Method</label>
                                <select x-model="paymentCategory" name="payment_method" required 
                                    class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl outline-none font-bold text-gray-700 focus:border-[#738D56] transition-colors appearance-none cursor-pointer">
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>
                            {{-- Payment Demo Block --}}
                            <div x-show="paymentCategory === 'Online Payment'" x-transition x-cloak class="pt-2">
                                <div class="p-4 border-2 border-blue-50 rounded-2xl bg-blue-50/30 flex items-center gap-4">
                                    <div class="w-12 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center overflow-hidden p-1">
                                        <img src="{{ asset('images/GCash-Logo.png') }}" class="object-contain w-full h-full">
                                    </div>
                                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">GCash (Demo Integration)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50 lg:sticky lg:top-28">
                        <h2 class="text-lg font-bold text-gray-800 mb-6">Order Summary</h2>
                        <div class="space-y-4">
                            @php 
                                $subtotal = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
                                $deliveryFee = 80;
                            @endphp
                            
                            {{-- Item Breakdown --}}
                            <div class="max-h-32 overflow-y-auto mb-4 space-y-3 pr-2 custom-scrollbar">
                                @foreach($cart->items as $item)
                                <div class="flex justify-between items-center text-xs font-bold">
                                    <span class="text-gray-400">{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span class="text-gray-700">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                                @endforeach
                            </div>

                            {{-- Totals --}}
                            <div class="space-y-3 pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400 font-medium">Subtotal</span>
                                    <span class="text-gray-800 font-bold">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pb-4">
                                    <span class="text-gray-400 font-medium">Delivery Fee</span>
                                    <span class="text-gray-800 font-bold">₱{{ number_format($deliveryFee, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <span class="text-base font-bold text-gray-900">Total Due</span>
                                    <span class="text-2xl font-black text-[#738D56]">₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 py-5 bg-[#738D56] hover:bg-[#5f7547] text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-[#738D56]/20 transition-all transform active:scale-95">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </main>
    <x-buyer-footer />
</div>
@endsection