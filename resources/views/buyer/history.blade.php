@extends('layouts.app')

@section('title', 'Order History')

@section('content')
{{-- Wrap everything in an Alpine data component to manage modal state --}}
<div x-data="{ ratingModalOpen: false, activeOrderId: null }" class="min-h-screen bg-[#F9F7F2] flex flex-col">
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-6 sm:px-12 lg:px-20 py-8 lg:py-12 w-full">
        <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 text-center md:text-left">
            <div>
                <span class="text-[#738D56] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-3 py-1 rounded-full">Account Activity</span>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-3 leading-tight">History<br class="hidden md:block"> Transactions</h1>
            </div>
            
            <div class="w-full md:w-auto">
                <select 
                    onchange="window.location.href = '{{ route('buyer.history') }}' + (this.value ? '?status=' + encodeURIComponent(this.value) : '')"
                    class="w-full md:w-64 bg-white border border-gray-100 rounded-xl px-5 py-3.5 text-xs font-bold uppercase tracking-widest text-gray-500 shadow-sm focus:ring-2 focus:ring-[#738D56] focus:border-[#738D56] cursor-pointer appearance-none">
                    
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Orders</option>
                    <option value="Awaiting Shipping" {{ request('status') == 'Awaiting Shipping' ? 'selected' : '' }}>Pending (Awaiting Shipping)</option>
                    <option value="On Delivery" {{ request('status') == 'On Delivery' ? 'selected' : '' }}>Shipped (On Delivery)</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </header>

        @if(session('status'))
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 animate-fade-in">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
            @forelse($orders as $order)
                <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-gray-50 flex flex-col gap-6 relative group hover:shadow-md transition-all duration-300">
                    
                    <div class="flex justify-between items-start border-b border-gray-50 pb-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Order Reference</p>
                            <h3 class="text-lg font-black text-gray-900">#{{ $order->id }}</h3>
                        </div>
                        
                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border inline-flex items-center justify-center min-h-[24px]
                            {{ $order->status == 'Awaiting Shipping' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                            {{ $order->status == 'On Delivery' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                            {{ $order->status == 'Completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                            {{ $order->status == 'Cancelled' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-grow">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 bg-[#F9F7F2]/40 p-3 rounded-2xl border border-gray-50/50">
                            <div class="w-14 h-14 shrink-0 rounded-xl overflow-hidden shadow-sm bg-white border border-gray-50">
                                <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.png')) }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-black text-gray-800 truncate">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                                    {{ $item->quantity }} Units @ ₱{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-gray-50 mt-auto flex justify-between items-end gap-4">
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Transaction Date</p>
                            <p class="text-xs font-bold text-gray-700">{{ $order->created_at->format('M d, Y') }}</p>
                            <div class="pt-2">
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Paid</p>
                                <p class="text-xl font-black text-[#738D56]">₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 w-1/2">
                            @if($order->status == 'Awaiting Shipping')
                                <form action="{{ route('buyer.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-red-50 text-red-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all border border-red-100 transform active:scale-95">
                                        Cancel Order
                                    </button>
                                </form>
                            @elseif($order->status == 'On Delivery')
                                <form action="{{ route('buyer.order.receive', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-[#738D56] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20 transform active:scale-95">
                                        Order Received
                                    </button>
                                </form>
                            @elseif($order->status == 'Completed')
                                @if(!($order->is_rated ?? false))
                                    <button type="button" 
                                        @click="activeOrderId = {{ $order->id }}; ratingModalOpen = true" 
                                        class="w-full px-6 py-3 bg-amber-400 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-500 transition-all shadow-lg shadow-amber-400/20 transform active:scale-95">
                                        Rate Products
                                    </button>
                                @else
                                    <button disabled class="w-full px-6 py-3 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl cursor-not-allowed border border-gray-200">
                                        Already Rated
                                    </button>
                                @endif
                            @endif

                            {{-- High Contrast Action Buttons --}}
                            <div class="grid grid-cols-2 gap-3">
                                @if($order->items->first())
                                <form action="{{ route('buyer.cart.add', $order->items->first()->product_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-white text-gray-700 text-[9px] font-black uppercase tracking-widest rounded-xl border-2 border-gray-100 hover:text-[#738D56] hover:border-[#738D56]/20 transition-all">
                                        Reorder
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('buyer.order.receipt', $order->id) }}" 
                                   class="w-full py-2.5 bg-white text-gray-700 text-[9px] font-black uppercase tracking-widest rounded-xl border-2 border-gray-100 hover:bg-gray-50 transition-all text-center flex items-center justify-center">
                                    Receipt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-white rounded-[2rem] shadow-sm flex items-center justify-center mb-6 text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="text-gray-400 font-black uppercase tracking-widest text-xs">No transactions found</p>
                    @if(request('status'))
                        <a href="{{ route('buyer.history') }}" class="mt-4 text-[#738D56] text-xs font-bold uppercase underline">Clear Filters</a>
                    @endif
                </div>
            @endforelse
        </div>
    </main>

    <x-buyer-footer />

    {{-- MODAL POWERED BY ALPINE --}}
    <div 
        x-show="ratingModalOpen" 
        x-cloak 
        class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-6"
    >
        <div 
            @click.away="ratingModalOpen = false" 
            x-show="ratingModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl relative"
        >
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Rate Products</h2>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Order #<span x-text="activeOrderId"></span></p>
                </div>
                <button type="button" @click="ratingModalOpen = false" class="text-gray-300 hover:text-gray-500 transition p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('buyer.reviews.store') }}" method="POST" class="space-y-8"> 
                @csrf
                <input type="hidden" name="order_id" :value="activeOrderId">
                
                <div class="text-center space-y-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Your Overall Rating</p>
                    <div class="flex flex-row-reverse justify-center gap-2 text-4xl star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                            <label for="star{{ $i }}" class="cursor-pointer text-gray-200 transition-colors duration-200 hover:text-yellow-300 peer-checked:text-yellow-400">★</label>
                        @endfor
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Write a Review</label>
                    <textarea name="comment" rows="4" placeholder="How was your coconut product experience?" 
                        class="w-full px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-[2rem] focus:ring-2 focus:ring-[#738D56] text-sm transition-all outline-none resize-none placeholder-gray-300 font-medium text-gray-700"></textarea>
                </div>

                <button type="submit" class="w-full py-5 bg-[#738D56] text-white font-black uppercase tracking-widest text-[11px] rounded-2xl shadow-xl shadow-[#738D56]/20 transform active:scale-95 transition-all">
                    Submit Feedback
                </button>
            </form>
        </div>
    </div>
</div>
@endsection