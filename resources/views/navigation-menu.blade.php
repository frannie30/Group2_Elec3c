<nav x-data="{ open: false }" class="bg-white border-b border-pink-400 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-12 w-auto" />
                    </a>
                </div>
                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:flex">
                    @if(request()->routeIs('index.index') || request()->routeIs('create.index') || request()->routeIs('edit.index'))
                        <x-nav-link href="{{ route('index.index') }}" :active="request()->routeIs('index.index')" class="text-pink-900  hover:bg-pink-200 border-pink-400">
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>
                    @else
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-pink-900 hover:bg-pink-200 border-pink-400">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('submitecospace') }}" :active="request()->is('submitecospace')" class="text-pink-900 hover:bg-pink-200 border-pink-400">
                            {{ __('Submit an EcoSpace') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-pink-400 text-sm leading-4 font-medium rounded-xl text-pink-900 hover:bg-pink-200 hover:text-pink-900 focus:outline-none transition">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>
                            <x-slot name="content">
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs text-pink-500">
                                        {{ __('Manage Team') }}
                                    </div>
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="text-pink-900 hover:bg-pink-100">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>
                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}" class="text-pink-900 hover:bg-pink-100">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-pink-400"></div>
                                        <div class="block px-4 py-2 text-xs text-pink-500">
                                            {{ __('Switch Teams') }}
                                        </div>
                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                <!-- Settings Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-pink-400 rounded-full focus:outline-none transition">
                                    <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-pink-400 text-sm leading-4 font-medium rounded-xl text-pink-900 bg-pink-100 hover:bg-pink-200 hover:text-pink-900 focus:outline-none transition">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-pink-500">
                                {{ __('Manage Account') }}
                            </div>
                            <x-dropdown-link href="{{ route('profile.show') }}" class="text-pink-900 hover:bg-pink-100">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}" class="text-pink-900 hover:bg-pink-100">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif
                            @if(Auth::user() && Auth::user()->userTypeID === 1)
                                @if(request()->routeIs('index.index') || request()->routeIs('create.index') || request()->routeIs('edit.index'))
                                    <x-dropdown-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-pink-900 hover:bg-pink-100">
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>
                                @else
                                    <x-dropdown-link href="{{ route('index.index') }}" :active="request()->routeIs('index.index')" class="text-pink-900 hover:bg-pink-100">
                                        {{ __('Admin Dashboard') }}
                                    </x-dropdown-link>
                                @endif
                            @endif
                            <div class="border-t border-pink-400"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();" class="text-pink-900 hover:bg-pink-100">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-pink-500 hover:text-pink-900 hover:bg-pink-100 focus:outline-none transition duration-150 ease-in-out bg-white">
                    <svg class="size-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-pink-400 rounded-b-2xl shadow-lg">
        <div class="pt-4 pb-3 space-y-2 px-4">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-pink-900 bg-pink-100 hover:bg-pink-200 border-pink-400">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('submitecospace') }}" :active="request()->is('submitecospace')" class="text-pink-900 bg-pink-100 hover:bg-pink-200 border-pink-400">
                {{ __('Submit an EcoSpace') }}
            </x-responsive-nav-link>
        </div>
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-pink-400">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover border-2 border-pink-400" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base text-pink-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-pink-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-pink-900 hover:bg-pink-100">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="text-pink-900 hover:bg-pink-100">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();" class="text-pink-900 hover:bg-pink-100">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-pink-400"></div>
                    <div class="block px-4 py-2 text-xs text-pink-500">
                        {{ __('Manage Team') }}
                    </div>
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')" class="text-pink-900 hover:bg-pink-100">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>
                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')" class="text-pink-900 hover:bg-pink-100">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-pink-400"></div>
                        <div class="block px-4 py-2 text-xs text-pink-500">
                            {{ __('Switch Teams') }}
                        </div>
                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
