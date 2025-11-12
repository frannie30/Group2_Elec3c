<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            {{ __('Admin - Manage Recipes') }}
        </h2>
    </x-slot>

    @if (session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-300 text-green-800 font-semibold shadow">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-300 text-red-800 font-semibold shadow">
        {{ session('error') }}
    </div>
@endif

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-full mx-auto sm:px-12 lg:px-24">
            <div class="bg-white/90 shadow-2xl rounded-2xl p-16 border border-pink-200">
                <a href="{{ route('index.index') }}"
                   class="self-start inline-flex items-center justify-center w-10 h-10 text-pink-600 hover:text-pink-800 font-bold rounded-full transition duration-200 mb-4 text-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2 bg-pink-100 hover:bg-pink-200 shadow"
                   title="Back to Dashboard"
                   aria-label="Back to Dashboard">
                    &larr;
                </a>  
                <h2 class="text-3xl font-extrabold mb-6 text-center text-pink-700">Add New EcoSpace</h2>

                <!-- Static Table of Posts -->
                <h2 class="text-xl font-bold mb-4 text-pink-800">Here are the EcoSpaces submitted by the users</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-pink-200 rounded-xl shadow">
                        <thead class="bg-pink-100">
    <tr>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">User</th>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Address</th>
    <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Name</th>
    <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Images</th>
    <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Description</th>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Opening Hours</th>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Days Opened</th>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Submitted At</th>
        <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Actions</th>
    </tr>
</thead>
<tbody>
@forelse($ecospaces as $ecospace)
    <tr class="hover:bg-pink-200 transition">
        <td class="px-6 py-4 border-b align-top">{{ $ecospace->user->name ?? 'Unknown' }}</td>
        <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceAdd ?? 'Unknown' }}</td>

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
                    <span id="carousel-count-{{ $ecospace->ecospaceID }}" class="text-sm text-pink-600">1/{{ count($imgs) }}</span>
                </div>
            @else
                <span class="text-sm text-pink-500">No images</span>
            @endif
        </td>
        <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceDesc }}</td>
        <td class="px-6 py-4 border-b align-top">{{ $ecospace->openingHours }}</td>
        <td class="px-6 py-4 border-b align-top">{{ $ecospace->daysOpened }}</td>
        <td class="px-6 py-4 border-b align-top">{{ optional($ecospace->dateCreated)->format('M d, Y') }}</td>
        <td class="px-6 py-4 border-b align-top">
            <div class="flex flex-col items-stretch gap-3">
                <form method="POST" action="{{ route('admin.ecospace.approve', $ecospace->ecospaceID) }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-pink-700 transition focus:outline-none focus:ring-2 focus:ring-pink-400">
                        Approve
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.ecospace.remove', $ecospace->ecospaceID) }}"
                      onsubmit="return confirm('Are you sure you want to decline this ecospace? This action cannot be undone.');">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none focus:ring-2 focus:ring-red-400">
                        Decline
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-4 border-b text-center text-pink-600">No ecospaces found.</td>
    </tr>
@endforelse
</tbody>
                    </table>
                </div>
</br>
                {{ $ecospaces->links() }}
                
                <!-- Pending Events Table -->
                <h2 class="text-xl font-bold mt-12 mb-4 text-pink-800">Pending Events submitted by users</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-pink-200 rounded-xl shadow">
                        <thead class="bg-pink-100">
                        <tr>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">User</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Event Name</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Address</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Date</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Images</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Description</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Price Tier</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Submitted At</th>
                            <th class="px-6 py-3 border-b text-left text-pink-800 font-semibold">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($events) && $events->count())
                            @foreach($events as $event)
                                <tr class="hover:bg-pink-200 transition">
                                    <td class="px-6 py-4 border-b align-top">{{ $event->user->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 border-b align-top">{{ $event->eventName }}</td>
                                    <td class="px-6 py-4 border-b align-top">{{ $event->eventAdd ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 border-b align-top">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</td>
                                    <td class="px-6 py-4 border-b align-top">
                                        @php
                                            $imgs = $event->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                                        @endphp
                                        @if(count($imgs))
                                            <div id="e-carousel-{{ $event->eventID }}" class="flex items-center gap-2" data-images='@json($imgs)' data-index="0">
                                                <button type="button" onclick="eCarouselPrev({{ $event->eventID }})" class="px-2 py-1 bg-white border rounded-md">‹</button>
                                                <img id="e-carousel-img-{{ $event->eventID }}" src="{{ $imgs[0] }}" alt="event image" class="w-24 h-16 object-cover rounded-md border" />
                                                <button type="button" onclick="eCarouselNext({{ $event->eventID }})" class="px-2 py-1 bg-white border rounded-md">›</button>
                                                <span id="e-carousel-count-{{ $event->eventID }}" class="text-sm text-pink-600">1/{{ count($imgs) }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-pink-500">No images</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 border-b align-top">{{ $event->eventDesc }}</td>
                                    <td class="px-6 py-4 border-b align-top">{{ $event->priceTier->pricetier ?? '—' }}</td>
                                    <td class="px-6 py-4 border-b align-top">{{ optional($event->dateCreated)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 border-b align-top">
                                        <div class="flex flex-col items-stretch gap-3">
                                            <form method="POST" action="{{ route('admin.event.approve', $event->eventID) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full flex items-center justify-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-pink-700 transition focus:outline-none focus:ring-2 focus:ring-pink-400">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.event.remove', $event->eventID) }}"
                                                  onsubmit="return confirm('Are you sure you want to decline this event? This action cannot be undone.');">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none focus:ring-2 focus:ring-red-400">
                                                    Decline
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.event.edit', $event->eventID) }}" class="w-full flex items-center justify-center gap-2 bg-gray-300 text-pink-800 px-4 py-2 rounded-lg font-semibold shadow hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="px-6 py-4 border-b text-center text-pink-600">No pending events found.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                {{ isset($events) ? $events->links() : '' }}

                <script>
                    function eCarouselNext(id) {
                        const container = document.getElementById('e-carousel-' + id);
                        if (!container) return;
                        const images = JSON.parse(container.getAttribute('data-images') || '[]');
                        if (!images.length) return;
                        let idx = parseInt(container.getAttribute('data-index') || '0', 10);
                        idx = (idx + 1) % images.length;
                        container.setAttribute('data-index', idx);
                        const img = document.getElementById('e-carousel-img-' + id);
                        if (img) img.src = images[idx];
                        const count = document.getElementById('e-carousel-count-' + id);
                        if (count) count.textContent = (idx + 1) + '/' + images.length;
                    }
                    function eCarouselPrev(id) {
                        const container = document.getElementById('e-carousel-' + id);
                        if (!container) return;
                        const images = JSON.parse(container.getAttribute('data-images') || '[]');
                        if (!images.length) return;
                        let idx = parseInt(container.getAttribute('data-index') || '0', 10);
                        idx = (idx - 1 + images.length) % images.length;
                        container.setAttribute('data-index', idx);
                        const img = document.getElementById('e-carousel-img-' + id);
                        if (img) img.src = images[idx];
                        const count = document.getElementById('e-carousel-count-' + id);
                        if (count) count.textContent = (idx + 1) + '/' + images.length;
                    }
                </script>
                {{-- Carousel script: handles prev/next per ecospace by id --}}
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
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
