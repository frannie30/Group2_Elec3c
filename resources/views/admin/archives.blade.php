<x-app-layout>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --brand-green: #2F9E4A;
            --brand-maroon: #166534;
            --brand-bg-admin-main: #E6F7EA;
            --brand-bg-sidebar: #FFFFFF;
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
        @include('admin._sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-lg p-8 w-full mx-auto">

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
                        <!-- Actions moved to sidebar dropdown -->
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
                                                    <form method="POST" action="{{ route('admin.ecospaces.restore', $ecospace->ecospaceID) }}" style="display:inline;" data-confirm="Are you sure you want to restore this ecospace?">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-pink-700 transition focus:outline-none">Restore</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.ecospaces.delete', $ecospace->ecospaceID) }}" data-confirm="Permanently delete this EcoSpace? This cannot be undone.">
                                                        @csrf
                                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 admin-btn-negative px-4 py-2 rounded-lg font-semibold shadow">Delete</button>
                                                    </form>
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
                                                    <form method="POST" action="{{ route('admin.events.delete', $event->eventID) }}" data-confirm="Permanently delete this event? This cannot be undone.">
                                                        @csrf
                                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 admin-btn-negative px-4 py-2 rounded-lg font-semibold shadow">Delete</button>
                                                    </form>
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
                                                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" data-confirm="Restore this user account?">
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

                    // Page now uses the shared sidebar include. Keep only carousel helper functions.
                </script>

            </div>
        </main>
    </div>

</x-app-layout>