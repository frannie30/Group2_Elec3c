<div>
<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-200 transition-all duration-300">
    <!-- Primary Navigation Menu (EcoSpaces style) -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <!-- Left: Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <x-application-mark class="block h-10 w-auto text-dark-green" />
                    <span class="text-2xl font-bold"><span class="text-dark-green">Eco</span><span class="text-pink-logo">Spaces</span></span>
                </a>
            </div>

              <!-- Center: Search (visible on md+)
                  Submitting the search will go to the full events listing -->
              <form method="GET" action="{{ route('events.all') }}" class="hidden md:flex items-center bg-gray-100 rounded-lg overflow-hidden flex-1 max-w-lg mx-4 border border-gray-200">
                <span class="pl-4 pr-2 text-gray-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Search parks, gardens, events, third spaces..." class="flex-grow p-2.5 border-none focus:ring-0 text-gray-700 placeholder-gray-500 bg-gray-100">
            </form>

            <!-- Right: Links & Auth -->
            <div class="hidden md:flex items-center space-x-6">
                <div class="hidden md:flex items-center space-x-6">
                    @if(request()->routeIs('index.index') || request()->routeIs('create.index') || request()->routeIs('edit.index'))
                        <x-nav-link href="{{ route('index.index') }}" :active="request()->routeIs('index.index')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Admin Dashboard') }}</x-nav-link>
                    @else
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Dashboard') }}</x-nav-link>
                        @if(!(Auth::user() && (Auth::user()->userTypeID === 1 || Auth::user()->userTypeID === 3)))
                            <x-nav-link href="{{ route('submitecospace') }}" :active="request()->is('submitecospace')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Submit an EcoSpace') }}</x-nav-link>
                        @endif
                        @if(!(Auth::user() && Auth::user()->userTypeID === 1))
                            <x-nav-link href="{{ route('submitevent') }}" :active="request()->is('submitevent')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Submit an Event') }}</x-nav-link>
                        @endif
                        <x-nav-link href="{{ route('events.index') }}" :active="request()->routeIs('events.index')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Events') }}</x-nav-link>
                        <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Users') }}</x-nav-link>
                    @endif
                </div>

                @auth
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <button type="button" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <!-- keep original content -->
                                    <div class="w-60">
                                        <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Manage Team') }}</div>
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">{{ __('Team Settings') }}</x-dropdown-link>
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">{{ __('Create New Team') }}</x-dropdown-link>
                                        @endcan
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t"></div>
                                            <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Switch Teams') }}</div>
                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm rounded-full focus:outline-none">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <button type="button" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                    </button>
                                @endif
                            </x-slot>
                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Manage Account') }}</div>
                                <x-dropdown-link href="{{ route('users.show', Auth::user()->id) }}">{{ __('My Profile') }}</x-dropdown-link>
                                <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Settings') }}</x-dropdown-link>
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</x-dropdown-link>
                                @endif
                                @if(Auth::user() && Auth::user()->userTypeID === 1)
                                    @if(request()->routeIs('index.index') || request()->routeIs('create.index') || request()->routeIs('edit.index'))
                                        <x-dropdown-link href="{{ route('dashboard') }}">{{ __('Dashboard') }}</x-dropdown-link>
                                    @else
                                        <x-dropdown-link href="{{ route('index.index') }}">{{ __('Admin Dashboard') }}</x-dropdown-link>
                                    @endif
                                @endif
                                <div class="border-t mt-2"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Log Out') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="#login" class="text-gray-600 hover:text-green-600 font-medium">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="#register" class="text-gray-600 bg-gray-100 px-3 py-1 rounded-md">{{ __('Register') }}</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = ! open" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (kept original responsive links and settings) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="pt-4 pb-3 space-y-2 px-4">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            @if(!(Auth::user() && (Auth::user()->userTypeID === 1 || Auth::user()->userTypeID === 3)))
                <x-responsive-nav-link href="{{ route('submitecospace') }}" :active="request()->is('submitecospace')">{{ __('Submit an EcoSpace') }}</x-responsive-nav-link>
            @endif
            @if(!(Auth::user() && Auth::user()->userTypeID === 1))
                <x-responsive-nav-link href="{{ route('submitevent') }}" :active="request()->is('submitevent')">{{ __('Submit an Event') }}</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link href="{{ route('events.index') }}" :active="request()->routeIs('events.index')">{{ __('Events') }}</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">{{ __('Users') }}</x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-base text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1 px-2">
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">{{ __('Profile') }}</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('users.show', Auth::user()->id) }}">{{ __('Profile') }}</x-responsive-nav-link>
                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">{{ __('API Tokens') }}</x-responsive-nav-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                    </form>
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t mt-2"></div>
                        <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Manage Team') }}</div>
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">{{ __('Team Settings') }}</x-responsive-nav-link>
                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">{{ __('Create New Team') }}</x-responsive-nav-link>
                        @endcan
                        @if (Auth::user()->allTeams()->count() > 1)
                            <div class="border-t mt-2"></div>
                            <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Switch Teams') }}</div>
                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 px-4 border-t">
                <div class="flex items-center gap-3">
                    <a href="#login" class="text-gray-600 hover:text-dark-green font-medium">{{ __('Log in') }}</a>
                    @if (Route::has('register'))
                        <a href="#register" class="text-white bg-magenta-secondary px-3 py-1 rounded-md hover:opacity-90 transition-opacity">{{ __('Register') }}</a>
                    @endif
                </div>
            </div>
        @endauth
    </div>
</nav>

<!-- CSS-only modals for Login and Register (no JavaScript) -->
<style>
    .modal-overlay { display: none; }
    .modal-overlay:target, .modal-overlay.show { display: flex; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 60; }
    .modal { background: white; border-radius: 0.5rem; max-width: 28rem; width: 95%; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.15); position: relative; }
    .modal .close { position: absolute; right: 0.5rem; top: 0.5rem; }
    .modal input:focus, .modal button:focus, .modal a:focus { outline: 3px solid rgba(236,72,153,0.25); outline-offset: 2px; }
</style>

<!-- Login Modal -->
@php
    $showLogin = old('form') === 'login' || ($errors->any() && old('form') === 'login');
    $showRegister = old('form') === 'register' || ($errors->any() && old('form') === 'register');
@endphp

<div id="login" class="modal-overlay {{ $showLogin ? 'show' : '' }}" role="dialog" aria-modal="true" aria-labelledby="login-title">
    <div class="modal" tabindex="-1">
        <a href="#" class="close text-gray-500">✕</a>
        <h3 id="login-title" class="text-lg font-semibold text-pink-800 mb-4">{{ __('Log in') }}</h3>

        @if(session('status'))
            <div class="mb-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        @if($errors->any() && $showLogin)
            <div class="mb-3 text-sm text-red-700" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="mb-3 text-sm text-pink-700">{{ __('No account yet?') }} <a href="#register" class="text-pink-600 hover:underline">{{ __('Register') }}</a></p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="form" value="login">
            <div class="mb-3">
                <label for="login_email" class="block text-sm text-pink-700">{{ __('Email') }}</label>
                <input id="login_email" name="email" type="email" required class="w-full border rounded px-3 py-2" value="{{ old('email') }}" />
            </div>
            <div class="mb-3">
                <label for="login_password" class="block text-sm text-pink-700">{{ __('Password') }}</label>
                <input id="login_password" name="password" type="password" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="flex items-center justify-between mb-4">
                <label class="inline-flex items-center text-sm text-pink-700"><input type="checkbox" name="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}</label>
                <a href="{{ route('password.request') ?? '#' }}" class="text-sm text-pink-600 hover:underline">{{ __('Forgot your password?') }}</a>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded">{{ __('Log in') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div id="register" class="modal-overlay {{ $showRegister ? 'show' : '' }}" role="dialog" aria-modal="true" aria-labelledby="register-title">
    <div class="modal" tabindex="-1">
        <a href="#" class="close text-gray-500">✕</a>
        <h3 id="register-title" class="text-lg font-semibold text-pink-800 mb-4">{{ __('Register') }}</h3>

        @if($errors->any() && $showRegister)
            <div class="mb-3 text-sm text-red-700" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="form" value="register">
            <div class="mb-3">
                <label for="reg_name" class="block text-sm text-pink-700">{{ __('Name') }}</label>
                <input id="reg_name" name="name" type="text" required class="w-full border rounded px-3 py-2" value="{{ old('name') }}" />
            </div>
            <div class="mb-3">
                <label for="reg_email" class="block text-sm text-pink-700">{{ __('Email') }}</label>
                <input id="reg_email" name="email" type="email" required class="w-full border rounded px-3 py-2" value="{{ old('email') }}" />
            </div>
            <div class="mb-3">
                <label for="reg_password" class="block text-sm text-pink-700">{{ __('Password') }}</label>
                <input id="reg_password" name="password" type="password" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-3">
                <label for="reg_password_confirmation" class="block text-sm text-pink-700">{{ __('Confirm Password') }}</label>
                <input id="reg_password_confirmation" name="password_confirmation" type="password" required class="w-full border rounded px-3 py-2" />
            </div>
            <p class="mt-2 mb-4 text-sm text-pink-700">{{ __('Have an account?') }} <a href="#login" class="text-pink-600 hover:underline">{{ __('Log in') }}</a></p>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded">{{ __('Register') }}</button>
            </div>
        </form>
    </div>
</div>
