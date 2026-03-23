@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col" x-data="cartManager()">
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-8 lg:px-20 py-8 lg:py-12 w-full">
        <header class="mb-8 lg:mb-10 text-center lg:text-left flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Your Cart</span>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Shopping Cart</h1>
            </div>
            
            @if(count($cart->items ?? []) > 0)
                <label class="flex items-center gap-3 cursor-pointer group bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm hover:border-[#738D56]/30 transition-all">
                    <input type="checkbox" 
                           @click="toggleAll" 
                           :checked="allSelected" 
                           class="w-5 h-5 rounded-lg border-2 border-gray-300 text-[#738D56] focus:ring-[#738D56] focus:ring-offset-0 transition cursor-pointer">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-[#738D56]">Select All Items</span>
                </label>
            @endif
        </header>

        <form action="{{ route('buyer.checkout') }}" method="GET" id="cart-form">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">
                
                <div class="w-full lg:w-2/3 bg-white rounded-[2rem] lg:rounded-[2.5rem] p-5 lg:p-8 shadow-sm border border-gray-50">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 lg:mb-8">Items in Cart</h2>
                    
                    <div class="space-y-6 lg:space-y-8">
                        @forelse($cart->items ?? [] as $item)
                            <div class="flex flex-col sm:grid sm:grid-cols-12 items-start sm:items-center gap-4 lg:gap-6 pb-6 lg:pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                                
                                {{-- Selection Checkbox --}}
                                <div class="sm:col-span-1 flex items-center justify-center">
                                    <input type="checkbox" 
                                           name="selected_items[]" 
                                           value="{{ $item->id }}"
                                           x-model="selectedItems"
                                           class="w-6 h-6 rounded-xl border-2 border-gray-300 text-[#738D56] focus:ring-[#738D56] focus:ring-offset-0 transition shadow-sm cursor-pointer">
                                </div>

                                <div class="w-full sm:col-span-2 h-32 sm:h-24 shrink-0 rounded-2xl overflow-hidden shadow-sm bg-gray-50">
                                    <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.png')) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                <div class="flex-grow w-full sm:col-span-6 space-y-1">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $item->product->name }}</h3>
                                    <div class="flex items-center gap-4 mt-2">
                                        <div class="inline-flex items-center bg-[#F9F7F2] rounded-full px-2 py-1 border border-gray-100 shadow-inner">
                                            <button type="button" @click="updateQty({{ $item->id }}, 'dec')" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-black">-</button>
                                            <span class="px-3 text-sm font-bold text-gray-700">{{ $item->quantity }}</span>
                                            <button type="button" @click="updateQty({{ $item->id }}, 'inc')" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#738D56] transition font-black">+</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="w-full sm:col-span-3 flex sm:flex-col justify-between sm:justify-center items-center sm:items-end">
                                    <span class="text-xl font-black text-gray-800">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">₱{{ number_format($item->product->price, 2) }} EA</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <p class="text-gray-400 font-medium italic">Your cart is empty.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Summary Sidebar --}}
                <div class="w-full lg:w-1/3 space-y-4 lg:sticky lg:top-28">
                    <div class="bg-white rounded-[2rem] lg:rounded-[2.5rem] p-6 lg:p-8 shadow-sm border border-gray-50">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 lg:mb-8">Order Totals</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm lg:text-base">
                                <span class="text-gray-400 font-medium">Selected Subtotal</span>
                                <span class="text-gray-800 font-bold">₱<span x-text="formatNumber(subtotal)">0.00</span></span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-4 border-b border-gray-100 text-sm lg:text-base">
                                <span class="text-gray-400 font-medium">Delivery Fee</span>
                                <span class="text-gray-800 font-bold">₱<span x-text="formatNumber(deliveryFee)">0.00</span></span>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-bold text-gray-900">Total Due</span>
                                <span class="text-2xl font-black text-[#738D56]">₱<span x-text="formatNumber(total)">0.00</span></span>
                            </div>

                            <div class="space-y-3 pt-6">
                                <button type="submit" 
                                        :disabled="selectedItems.length === 0"
                                        class="block w-full py-4 bg-[#738D56] hover:bg-[#5f7547] text-white text-center font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 transition-all transform active:scale-95 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed">
                                    Checkout Selected (<span x-text="selectedItems.length">0</span>)
                                </button>
                                
                                <a href="{{ route('buyer.product') }}" class="block w-full py-4 bg-white border border-gray-200 text-gray-400 text-center font-bold rounded-2xl hover:bg-gray-50 transition-all">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <x-buyer-footer />
</div>

<script>
    function cartManager() {
        return {
            selectedItems: [],
            items: @json($cart->items ?? []),
            deliveryFeeAmount: 80,
            
            get subtotal() {
                return this.items
                    .filter(item => this.selectedItems.includes(item.id.toString()))
                    .reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
            },

            get deliveryFee() {
                return this.subtotal > 0 ? this.deliveryFeeAmount : 0;
            },

            get total() {
                return this.subtotal + this.deliveryFee;
            },

            get allSelected() {
                return this.items.length > 0 && this.selectedItems.length === this.items.length;
            },

            toggleAll() {
                if (this.allSelected) {
                    this.selectedItems = [];
                } else {
                    this.selectedItems = this.items.map(item => item.id.toString());
                }
            },

            formatNumber(num) {
                return new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
            },

            updateQty(itemId, action) {
                console.log(`Updating item ${itemId}: ${action}`);
                document.getElementById('cart-form').submit(); 
            }
        }
    }
</script>
@endsection