<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="flex items-center justify-between h-20 sm:h-24 lg:h-20">
            
            <div class="flex items-center flex-1">
                <a href="{{ route('buyer.home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/coco-hub.png') }}" alt="CocoHub Logo" class="h-10 w-10 sm:h-12 sm:w-12 lg:h-10 lg:w-10 object-contain transition-transform group-hover:scale-105">
                    <div class="hidden sm:flex flex-col"> {{-- Visible on Tablet+ --}}
                        <h1 class="text-xl sm:text-2xl lg:text-xl font-extrabold leading-none tracking-tight">
                            <span class="text-[#6D4C41]">Coco</span><span class="text-[#738D56]">Hub</span>
                        </h1>
                        <span class="text-[10px] sm:text-[11px] lg:text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-0.5">Lumiere</span>
                    </div>
                </a>
            </div>

            <div class="hidden lg:flex items-center justify-center space-x-8">
                <x-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')" class="text-sm font-bold">
                    {{ __('Home') }}
                </x-nav-link>

                <x-nav-link :href="route('buyer.about')" :active="request()->routeIs('buyer.about')" class="text-sm font-bold">
                    {{ __('About') }}
                </x-nav-link>

                <x-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')" class="text-sm font-bold">
                    {{ __('Products') }}
                </x-nav-link>

                @auth
                    <x-nav-link :href="route('buyer.history')" :active="request()->routeIs('buyer.history')" class="text-sm font-bold">
                        {{ __('History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*') || request()->routeIs('buyer.profile')" class="text-sm font-bold">
                        {{ __('Profile') }}
                    </x-nav-link>
                @endauth
            </div>

            {{-- Right Side Actions --}}
            <div class="flex items-center justify-end flex-1 gap-4 sm:gap-6 lg:gap-8">
                @auth
                    {{-- Cart Icon --}}
                    <a href="{{ route('buyer.cart') }}" class="relative p-2.5 text-gray-400 hover:text-[#738D56] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 lg:h-6 lg:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-[#738D56] rounded-full shadow-sm">
                            {{ auth()->user()->cartItems()->distinct('product_id')->count() }}
                        </span>
                    </a>

                    {{-- Logout: Only visible on Desktop (lg) --}}
                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:block ml-2">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-100 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition duration-300 text-sm font-bold group">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:flex items-center gap-2 px-8 py-2.5 bg-[#738D56] text-white rounded-2xl hover:bg-[#5f7547] transition duration-300 text-sm font-bold shadow-lg shadow-[#738D56]/20">
                        Login
                    </a>
                @endauth

                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-gray-400 hover:text-[#738D56] hover:bg-gray-50 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-8 w-8 sm:h-9 sm:w-9 lg:h-6 lg:w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tablet/Mobile Menu Dropdown --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden bg-white border-t border-gray-100 shadow-xl" x-cloak>
        <div class="pt-4 pb-8 space-y-2 px-6 sm:px-12"> {{-- Increased padding for tablets --}}
            <x-responsive-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('buyer.about')" :active="request()->routeIs('buyer.about')">
                {{ __('About') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')">
                {{ __('Products') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('buyer.cart')" :active="request()->routeIs('buyer.cart')" class="flex justify-between items-center">
                    {{ __('Cart') }}
                    <span class="bg-[#738D56] text-white text-xs px-3 py-1 rounded-full">
                        {{ auth()->user()->cartItems()->distinct('product_id')->count() }}
                    </span>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('buyer.history')" :active="request()->routeIs('buyer.history')">
                    {{ __('History') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
            @endauth
            
            <div class="border-t border-gray-100 mt-6 pt-6">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-4 py-4 text-base font-bold text-red-500 hover:bg-red-50 rounded-2xl transition-colors px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full flex items-center gap-4 py-4 text-base font-bold text-[#738D56] hover:bg-gray-50 rounded-2xl transition-colors px-4">
                        {{ __('Log In') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>