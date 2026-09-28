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
                <x-dropdown align="right" width="w-80 sm:w-96">
                    <x-slot name="trigger">
                        <button class="relative p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full focus:outline-none transition-all duration-200 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div x-data="{ activeTab: 'unread' }" class="w-full">
                            
                            <div class="px-4 pt-3 border-b border-gray-100 flex justify-between items-end bg-gray-50/80 backdrop-blur-sm">
                                <div class="flex gap-4">
                                    <button @click.prevent.stop="activeTab = 'unread'" 
                                            :class="activeTab === 'unread' ? 'text-blue-600 border-b-2 border-blue-600 font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" 
                                            class="text-sm pb-2 transition-all flex items-center gap-1">
                                        Belum Dibaca
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </button>
                                    
                                    <button @click.prevent.stop="activeTab = 'read'" 
                                            :class="activeTab === 'read' ? 'text-blue-600 border-b-2 border-blue-600 font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" 
                                            class="text-sm pb-2 transition-all">
                                        Sudah Dibaca
                                    </button>
                                </div>
                                
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.markAllRead') }}" x-show="activeTab === 'unread'" class="pb-2">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">Tandai Semua</button>
                                    </form>
                                @endif
                            </div>
                            
                            <div x-show="activeTab === 'unread'" class="max-h-80 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="flex items-start justify-between px-4 py-3 border-b border-gray-50 bg-blue-50/30 hover:bg-blue-50/60 transition-colors">
                                        <a href="{{ $notification->data['url'] ?? '#' }}" class="flex-1 flex items-start gap-3 overflow-hidden">
                                            <div class="mt-0.5 text-blue-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-sm font-bold text-gray-900 truncate">{{ $notification->data['title'] }}</p>
                                                <p class="text-xs text-gray-600 mt-0.5 line-clamp-2 leading-relaxed">{{ $notification->data['message'] }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </a>
                                        <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" class="ml-2 shrink-0">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-full transition-colors tooltip" title="Tandai sudah dibaca">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="px-4 py-10 text-center flex flex-col items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-sm font-medium text-gray-500">Belum ada notifikasi baru.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div x-show="activeTab === 'read'" class="max-h-80 overflow-y-auto" style="display: none;">
                                @forelse(auth()->user()->readNotifications()->take(10)->get() as $notification)
                                    <div class="flex items-start justify-between px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors opacity-75">
                                        <div class="flex-1 flex items-start gap-3 overflow-hidden grayscale">
                                            <div class="mt-0.5 text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-sm font-semibold text-gray-600 truncate">{{ $notification->data['title'] }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-2 leading-relaxed">{{ $notification->data['message'] }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">Dibaca {{ $notification->read_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="ml-2 shrink-0" onsubmit="return confirm('Hapus notifikasi ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors" title="Hapus Permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    </div>
                                @empty
                                    <div class="px-4 py-10 text-center flex flex-col items-center justify-center">
                                        <p class="text-sm font-medium text-gray-400">Riwayat notifikasi kosong.</p>
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