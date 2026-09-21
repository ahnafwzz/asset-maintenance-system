<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition-transform hover:scale-105">
                        <x-application-logo class="w-10 h-10 object-contain drop-shadow-sm" />
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                        {{ __('Maintenance') }}
                    </x-nav-link>

                    <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                        {{ __('Departments') }}
                    </x-nav-link>

                    <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                        {{ __('Locations') }}
                    </x-nav-link>

                    <x-nav-link :href="route('asset-categories.index')" :active="request()->routeIs('asset-categories.*')">
                        {{ __('Asset Categories') }}
                    </x-nav-link>

                    <x-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">
                        {{ __('Assets') }}
                    </x-nav-link>

                    @role('Super Admin')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('User Approvals') }}
                    </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- KANAN: Lonceng Notif & Dropdown Profil -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                
                <!-- Dropdown Notifikasi -->
                <x-dropdown align="right" width="w-80">
                    <x-slot name="trigger">
                        <button class="relative p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full focus:outline-none transition-all duration-200 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            
                            <!-- Titik Merah Ping jika ada notif -->
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div>
                            <!-- Header Notif -->
                            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 backdrop-blur-sm">
                                <span class="text-sm font-bold text-gray-900">Notifikasi</span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">Tandai dibaca</button>
                                    </form>
                                @endif
                            </div>
                            
                            <!-- List Notif -->
                            <div class="max-h-80 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="text-xl mt-0.5">{{ $notification->data['icon'] ?? '🔔' }}</div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-sm font-bold text-gray-800 truncate">{{ $notification->data['title'] }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">{{ $notification->data['message'] }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-sm font-medium text-gray-500">Belum ada notifikasi baru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- Dropdown Profil -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-semibold rounded-full text-gray-600 bg-gray-50 hover:bg-gray-100 hover:text-gray-900 focus:outline-none transition-all duration-200 shadow-sm">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs mr-2 border border-blue-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-600 hover:bg-red-50">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- NOTIFIKASI MOBILE -->
            <div class="px-4 py-2 flex justify-between items-center border-l-4 border-transparent">
                <span class="text-base font-medium text-gray-600">Notifikasi Baru</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <div class="flex items-center gap-3">
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        <form method="POST" action="{{ route('notifications.markAllRead') }}">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800">Tandai Dibaca</button>
                        </form>
                    </div>
                @else
                    <span class="text-xs text-gray-400">Kosong</span>
                @endif
            </div>

            <!-- ... Menu navigasi lainnya ... -->
            <x-responsive-nav-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                {{ __('Maintenance') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                {{ __('Departments') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                {{ __('Locations') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('asset-categories.index')" :active="request()->routeIs('asset-categories.*')">
                {{ __('Asset Categories') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">
                {{ __('Assets') }}
            </x-responsive-nav-link>

            @role('Super Admin')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                {{ __('User Approvals') }}
            </x-responsive-nav-link>
            @endrole
        </div>

        <div class="pt-4 pb-1 border-t border-gray-100 bg-gray-50">
            <div class="px-4 flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>