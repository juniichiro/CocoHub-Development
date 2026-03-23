@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-6 pe-5 py-5 sm:py-6 bg-[#738D56]/10 border-l-4 border-[#738D56] text-start text-sm sm:text-base font-extrabold text-[#738D56] rounded-r-3xl shadow-sm shadow-[#738D56]/5 focus:outline-none transition-all duration-200 ease-in-out'
            : 'block w-full ps-6 pe-5 py-5 sm:py-6 border-l-4 border-transparent text-start text-sm sm:text-base font-bold text-gray-500 hover:text-[#738D56] hover:bg-gray-50/80 hover:border-[#738D56]/30 active:bg-gray-100 rounded-r-3xl focus:outline-none transition-all duration-200 ease-in-out mb-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center justify-between">
        <span class="truncate">{{ $slot }}</span>
        
        {{-- Show arrow for both active state and tablet-sized hover for better UX --}}
        <div class="flex items-center gap-2">
            @if($active)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-[#738D56] animate-pulse-slow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            @else
                {{-- Subtle indicator for tablet users that this is clickable --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-0 group-hover:opacity-30 transition-opacity hidden sm:block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
    </div>
</a>

<style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 0.8; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>