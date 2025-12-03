<aside class="w-64 bg-brand-bg-sidebar flex flex-col p-6 border-r border-gray-100 shadow-sm">
    @include('admin._button-styles')
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 mb-6 p-1">


    <x-application-mark class="block h-8 w-auto" />


    <span class="text-2xl font-extrabold"><span class="text-dark-green">Eco</span><span class="text-pink-logo">Spaces</span></span>

    </a>
        <span class="text-2xl font-bold text-dark-green">Admin</span>
    </a>

    <div class="mb-6">
        <h2 class="text-lg font-bold text-dark-green">Hello</h2>
        <p class="text-sm text-dark-green opacity-60">{{ Auth::user()->name }}</p>
    </div>

    @php
        use App\Models\Ecospace;
        use App\Models\Event;
        use App\Models\User;

        $ecospaceCount = Ecospace::count();
        $ecospaceArchived = Ecospace::onlyTrashed()->count();
        $eventCount = Event::count();
        $eventArchived = Event::onlyTrashed()->count();
        $userCount = User::count();
        $userArchived = User::onlyTrashed()->count();
    @endphp

    <nav class="flex-grow">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-dark-green hover:text-brand-green p-2 rounded-lg transition-colors">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Manage Ecospaces dropdown -->
            <li x-data="{ open: false }" class="relative">
                <button type="button" onclick="(function(el){el.nextElementSibling.classList.toggle('hidden');})(this)" class="flex items-center justify-between w-full text-dark-green p-2 rounded-lg hover:bg-gray-50 transition">
                    <span class="flex items-center space-x-3">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span class="font-medium">Manage Content</span>
                    </span>
                    <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <ul class="hidden mt-2 space-y-1 pl-3">
                    <li>
                        <a href="{{ route('admin.ecospaces') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>Current</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 text-dark-green">{{ $ecospaceCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ecospaces.create') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>Add New</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ecospaces.archives') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>View Archives</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 text-dark-green">{{ $ecospaceArchived }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Manage Events dropdown -->
            <li class="relative">
                <button type="button" onclick="(function(el){el.nextElementSibling.classList.toggle('hidden');})(this)" class="flex items-center justify-between w-full text-dark-green p-2 rounded-lg hover:bg-gray-50 transition">
                    <span class="flex items-center space-x-3">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="font-medium">Manage Events</span>
                    </span>
                    <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <ul class="hidden mt-2 space-y-1 pl-3">
                    <li>
                        <a href="{{ route('admin.events') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>Current</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 text-dark-green">{{ $eventCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.create') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>Add New</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.archives') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>View Archives</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 text-dark-green">{{ $eventArchived }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Manage Users dropdown -->
            <li class="relative">
                <button type="button" onclick="(function(el){el.nextElementSibling.classList.toggle('hidden');})(this)" class="flex items-center justify-between w-full text-dark-green p-2 rounded-lg hover:bg-gray-50 transition">
                    <span class="flex items-center space-x-3">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                            <path d="M6 20v-1c0-1.657 3.582-3 6-3s6 1.343 6 3v1"></path>
                        </svg>
                        <span class="font-medium">Manage Accounts</span>
                    </span>
                    <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <ul class="hidden mt-2 space-y-1 pl-3">
                    <li>
                        <a href="{{ route('admin.users') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>Current</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800">{{ $userCount }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.archives') }}" class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-dark-green">
                            <span>View Archives</span>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 text-dark-green">{{ $userArchived }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <hr class="my-4 border-dark-green">

    <div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="flex items-center space-x-3 text-dark-green hover:text-dark-green p-2 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
                    <span class="font-medium text-dark-green">Log out</span>
                </button>
            </form>
        </div>
</aside>
