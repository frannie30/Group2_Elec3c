<x-app-layout>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --brand-green: #F89EA4;
            --brand-maroon: #C33F64;
            --brand-bg-admin-main: #FFF5F7;
            --brand-bg-sidebar: #FFF8FA;
        }
        .font-inter{ font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .text-brand-green{ color: var(--brand-green); }
        .bg-brand-bg-admin-main{ background-color: var(--brand-bg-admin-main); }
        .bg-brand-bg-sidebar{ background-color: var(--brand-bg-sidebar); }
        .brand-accent{ color: var(--brand-maroon); }
        /* hide the top navigation (from layouts.app) on admin pages */
        .min-h-screen > nav { display: none !important; }
    </style>

    <div class="flex min-h-screen font-inter bg-brand-bg-admin-main">
        <!-- Sidebar -->
        <aside class="w-64 bg-brand-bg-sidebar flex flex-col p-6 border-r border-gray-100 shadow-sm">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 mb-8">
                <svg class="h-8 w-8 text-brand-maroon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.965 2.214a1 1 0 0 0-1.93 0l-1.33 4.116a1 1 0 0 1-.956.69h-4.33a1 1 0 0 0-.97 1.258l2.25 6.953a1 1 0 0 1-.162.903l-3.32 4.103a1 1 0 0 0 .78 1.637h5.45a1 1 0 0 1 .957-.69l1.33-4.116a1 1 0 0 0-.797-1.37l-3.064-.998 1.41-4.357h2.29a1 1 0 0 1 .956.69l1.33 4.116a1 1 0 0 0 .957.69h5.45a1 1 0 0 0 .78-1.637l-3.32-4.103a1 1 0 0 1-.162-.903l2.25-6.953a1 1 0 0 0-.97-1.258h-4.33a1 1 0 0 1-.956-.69L12.965 2.214z" />
                </svg>
                <span class="text-2xl font-bold text-brand-maroon">EcoSpaces</span>
            </a>

            <div class="mb-8">
                <h2 class="text-lg font-bold text-brand-maroon">Admin Dashboard</h2>
                <p class="text-sm text-gray-500">Hi, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
            </div>

            <nav class="flex-grow">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-brand-maroon hover:text-brand-green p-2 rounded-lg hover:bg-pink-50 transition-colors">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <button id="btn-ecospaces" class="flex items-center justify-between w-full text-brand-maroon hover:text-brand-green p-2 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="flex items-center space-x-3">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                                <span class="font-medium">Manage Content</span>
                            </span>
                        </button>
                    </li>
                    <li>
                        <button id="btn-events" class="flex items-center justify-between w-full text-brand-maroon hover:text-brand-green p-2 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="flex items-center space-x-3">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span class="font-medium">Manage Events</span>
                            </span>
                        </button>
                    </li>
                    <li>
                        <button id="btn-users" class="flex items-center justify-between w-full text-brand-maroon hover:text-brand-green p-2 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="flex items-center space-x-3">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                                    <path d="M6 20v-1c0-1.657 3.582-3 6-3s6 1.343 6 3v1"></path>
                                </svg>
                                <span class="font-medium">Users</span>
                            </span>
                        </button>
                    </li>
                </ul>
            </nav>

            <hr class="my-4 border-gray-200">

            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-gray-700 hover:text-brand-green p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        <span class="font-medium">Log out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-6xl mx-auto">

                {{-- Session messages --}}
                @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-pink-100 border border-pink-300 text-pink-800 font-semibold shadow">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-300 text-red-800 font-semibold shadow">
                    {{ session('error') }}
                </div>
                @endif

                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('index.index') }}" class="inline-flex items-center justify-center w-10 h-10 text-gray-700 hover:text-gray-900 font-bold rounded-full transition duration-200 text-2xl focus:outline-none bg-gray-100">&larr;</a>
                        <h1 id="page-title" class="text-3xl font-bold text-gray-900">Archives</h1>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('create.index') }}" class="bg-brand-maroon text-white px-4 py-2 rounded-lg font-semibold shadow" style="background-color: var(--brand-maroon);">+ Add New EcoSpace</a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div id="section-ecospaces">
                        <h2 class="text-2xl font-bold mb-2 text-gray-900">Ecospace Archives</h2>
                        <p class="text-sm text-gray-500 mb-4">Here are the ecospaces you have removed</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Address</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Price Tier</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">PriceTier ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Name</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Images</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold min-w-[16rem]">Description</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Opening Hours</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Closing Hours</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Days Opened</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Created</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Updated</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ecospaces as $ecospace)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->user->name ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->userID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceAdd ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->priceTierID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->status->status ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->statusID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceName }}</td>
                                            <td class="px-6 py-4 border-b align-top">
                                                @php
                                                    $imgs = $ecospace->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                                                @endphp
                                                @if(count($imgs))
                                                    <div id="carousel-{{ $ecospace->ecospaceID }}" class="flex items-center gap-2" data-images='@json($imgs)' data-index="0">
                                                        <button type="button" onclick="carouselPrev({{ $ecospace->ecospaceID }})" class="px-2 py-1 bg-white border rounded-md">‹</button>
                                                        <img id="carousel-img-{{ $ecospace->ecospaceID }}" src="{{ $imgs[0] }}" alt="ecospace image" class="w-24 h-16 object-cover rounded-md border" />
                                                        <button type="button" onclick="carouselNext({{ $ecospace->ecospaceID }})" class="px-2 py-1 bg-white border rounded-md">›</button>
                                                        <span id="carousel-count-{{ $ecospace->ecospaceID }}" class="text-sm text-gray-500">1/{{ count($imgs) }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">No images</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border-b align-top min-w-[16rem]">{{ $ecospace->ecospaceDesc }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->openingHours ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->closingHours ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $ecospace->daysOpened ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">
                                                @if($ecospace->dateCreated)
                                                    {{ \Carbon\Carbon::parse($ecospace->dateCreated)->format('M d, Y H:i') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border-b align-top">
                                                @if($ecospace->dateUpdated)
                                                    {{ \Carbon\Carbon::parse($ecospace->dateUpdated)->format('M d, Y H:i') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border-b align-top">
                                                <div class="flex flex-col items-stretch gap-3">
                                                    <form method="POST" action="{{ route('admin.ecospaces.restore', $ecospace->ecospaceID) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to restore this ecospace?');">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-pink-700 transition focus:outline-none">Restore</button>
                                                    </form>
                                                    <a href="{{ route('admin.ecospaces.confirm-delete', $ecospace->ecospaceID) }}" class="w-full inline-flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="16" class="px-6 py-4 border-b text-center text-gray-500">No ecospaces found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">{{ $ecospaces->links() }}</div>
                    </div>

                    <div id="section-events" class="hidden">
                        <h2 class="text-2xl font-bold mb-2 text-gray-900">Event Archives</h2>
                        <p class="text-sm text-gray-500 mb-4">Here are the events you have removed</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Event Type</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">EventType ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Event</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Address</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Date</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Images</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold min-w-[16rem]">Description</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Price Tier</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">PriceTier ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status ID</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Is Done</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events ?? collect() as $event)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 border-b align-top">{{ $event->user->name ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->userID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->eventType->eventTypeName ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->eventTypeID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->eventName }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->eventAdd ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</td>
                                            <td class="px-6 py-4 border-b align-top">
                                                @php
                                                    $imgs = $event->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                                                @endphp
                                                @if(count($imgs))
                                                    <img src="{{ $imgs[0] }}" alt="event image" class="w-24 h-16 object-cover rounded-md border" />
                                                @else
                                                    <span class="text-sm text-gray-400">No images</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border-b align-top min-w-[16rem]">{{ $event->eventDesc }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->priceTier->pricetier ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->priceTierID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->status->status ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->statusID ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-b align-top">{{ $event->isDone ? 'Yes' : 'No' }}</td>
                                            <td class="px-6 py-4 border-b align-top">
                                                <div class="flex flex-col items-stretch gap-3">
                                                    <form method="POST" action="{{ route('admin.events.restore', $event->eventID) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-green-700 transition focus:outline-none">Restore</button>
                                                    </form>
                                                    <a href="{{ route('admin.events.confirm-delete', $event->eventID) }}" class="w-full inline-flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="px-6 py-4 border-b text-center text-gray-500">No archived events found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">{{ isset($events) ? $events->links() : '' }}</div>
                    </div>

                    <div id="section-users" class="hidden">
                        <h2 class="text-2xl font-bold mb-2 text-gray-900">User Archives</h2>
                        <p class="text-sm text-gray-500 mb-4">Here are the user accounts you have archived</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Name</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Email</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User Type</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Deleted At</th>
                                        <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($users) && $users->count())
                                        @foreach($users as $user)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 border-b align-top">{{ $user->name }}</td>
                                                <td class="px-6 py-4 border-b align-top">{{ $user->email }}</td>
                                                <td class="px-6 py-4 border-b align-top">{{ $user->userType->userTypeName ?? ($user->userTypeID ?? 'N/A') }}</td>
                                                <td class="px-6 py-4 border-b align-top">{{ optional($user->deleted_at)->format('M d, Y H:i') }}</td>
                                                <td class="px-6 py-4 border-b align-top">
                                                    <div class="flex gap-2">
                                                        @if(auth()->check() && auth()->user()->userTypeID === 1)
                                                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" onsubmit="return confirm('Restore this user account?');">
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 rounded-md bg-pink-100 text-pink-800">Restore</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 border-b text-center text-gray-500">No archived users found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">{{ isset($users) ? $users->links() : '' }}</div>
                    </div>

                </div>

                {{-- Carousel + section toggle script (kept intact) --}}
                <script>
                    function carouselNext(id) {
                        const container = document.getElementById('carousel-' + id);
                        if (!container) return;
                        const images = JSON.parse(container.getAttribute('data-images') || '[]');
                        if (!images.length) return;
                        let idx = parseInt(container.getAttribute('data-index') || '0', 10);
                        idx = (idx + 1) % images.length;
                        container.setAttribute('data-index', idx);
                        const img = document.getElementById('carousel-img-' + id);
                        if (img) img.src = images[idx];
                        const count = document.getElementById('carousel-count-' + id);
                        if (count) count.textContent = (idx + 1) + '/' + images.length;
                    }

                    function carouselPrev(id) {
                        const container = document.getElementById('carousel-' + id);
                        if (!container) return;
                        const images = JSON.parse(container.getAttribute('data-images') || '[]');
                        if (!images.length) return;
                        let idx = parseInt(container.getAttribute('data-index') || '0', 10);
                        idx = (idx - 1 + images.length) % images.length;
                        container.setAttribute('data-index', idx);
                        const img = document.getElementById('carousel-img-' + id);
                        if (img) img.src = images[idx];
                        const count = document.getElementById('carousel-count-' + id);
                        if (count) count.textContent = (idx + 1) + '/' + images.length;
                    }

                        function showSection(name, pushState = true) {
                            const pageTitle = document.getElementById('page-title');
                        const eco = document.getElementById('section-ecospaces');
                        const evt = document.getElementById('section-events');
                        const users = document.getElementById('section-users');
                        const btnEco = document.getElementById('btn-ecospaces');
                        const btnEvt = document.getElementById('btn-events');
                        const btnUsers = document.getElementById('btn-users');
                        eco.classList.add('hidden'); evt.classList.add('hidden'); users.classList.add('hidden');
                        btnEco.classList.remove('bg-pink-50'); btnEvt.classList.remove('bg-pink-50'); btnUsers.classList.remove('bg-pink-50');
                        if (name === 'events') {
                            evt.classList.remove('hidden'); btnEvt.classList.add('bg-pink-50');
                            if (pageTitle) pageTitle.textContent = 'Event Archives';
                        } else if (name === 'users') {
                            users.classList.remove('hidden'); btnUsers.classList.add('bg-pink-50');
                            if (pageTitle) pageTitle.textContent = 'User Archives';
                        } else {
                            eco.classList.remove('hidden'); btnEco.classList.add('bg-pink-50');
                            if (pageTitle) pageTitle.textContent = 'Ecospace Archives';
                        }
                        if (pushState) {
                            const url = new URL(window.location);
                            url.searchParams.set('section', name);
                            window.history.replaceState({}, '', url);
                        }
                    }

                    document.getElementById('btn-ecospaces').addEventListener('click', () => showSection('ecospaces', true));
                    document.getElementById('btn-events').addEventListener('click', () => showSection('events', true));
                    document.getElementById('btn-users').addEventListener('click', () => showSection('users', true));

                    (function(){
                        const params = new URLSearchParams(window.location.search);
                        const section = params.get('section') || 'ecospaces';
                        showSection(section, false);
                    })();
                </script>

            </div>
        </main>
    </div>

</x-app-layout>