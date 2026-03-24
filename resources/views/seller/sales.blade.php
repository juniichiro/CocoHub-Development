@extends('layouts.seller')

@section('title', 'Sales Report')

@section('content')

<div class="flex flex-col min-h-screen" x-data="salesCharts(@js($hourlySales), @js($monthlySales))">
    <div class="flex-grow">

        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-10 gap-6 text-center sm:text-left">
            <div>
                <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Seller Performance</p>
                <h2 class="text-3xl font-bold text-gray-900">Sales Report</h2>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('seller.sales.export') }}" class="px-6 py-3 bg-[#738D56] text-white rounded-2xl text-sm font-bold hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20">
                    Export Report
                </a>
            </div>
        </div>

        {{-- Performance Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales Today</p>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">₱{{ number_format($totalSalesToday ?? 0, 2) }}</h4>
                <p class="text-[10px] font-bold text-[#738D56] uppercase tracking-tight">▲ Real-time update</p>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales This Month</p>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">₱{{ number_format($totalSalesMonth ?? 0, 2) }}</h4>
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">Steady monthly growth</p>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Completed Orders</p>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">{{ $completedOrdersCount }}</h4>
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">Total successful deliveries</p>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Pending Revenue</p>
                <h4 class="text-3xl font-bold text-amber-500 mb-2">₱{{ number_format($pendingRevenue ?? 0, 2) }}</h4>
                <p class="text-[10px] font-bold text-amber-300 uppercase tracking-tight">In transit / Awaiting</p>
            </div>
        </div>

        {{-- Revenue Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 text-center sm:text-left">
            <h2 class="text-2xl font-bold text-gray-900">Revenue Analysis</h2>
            <a href="{{ route('seller.inventory') }}" class="px-8 py-3 bg-[#738D56] text-white font-bold rounded-xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition-all">Manage Inventory</a>
        </div>

        {{-- Sales Graph --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- Daily Distribution Chart --}}
            <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-50 shadow-sm flex flex-col">
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 italic">Sold Today</h3>
                    <p class="text-xs text-gray-400">Hourly revenue distribution (Asia/Manila).</p>
                </div>
                <div class="h-64 mb-8">
                    <canvas id="todaySalesChart"></canvas>
                </div>
                <div class="mt-auto pt-6 border-t border-gray-50">
                    <h4 class="text-4xl font-bold text-gray-900">₱{{ number_format($totalSalesToday ?? 0, 2) }}</h4>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1">Total sales recorded for the day</p>
                </div>
            </div>

            {{-- Monthly Growth Chart --}}
            <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-50 shadow-sm flex flex-col">
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 italic">Monthly Performance</h3>
                    <p class="text-xs text-gray-400">Revenue distribution (Last 6 Months).</p>
                </div>
                <div class="h-64 mb-8">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
                <div class="mt-auto pt-6 border-t border-gray-50">
                    <h4 class="text-4xl font-bold text-gray-900">₱{{ number_format($totalSalesMonth ?? 0, 2) }}</h4>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1">Total sales for the current month</p>
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