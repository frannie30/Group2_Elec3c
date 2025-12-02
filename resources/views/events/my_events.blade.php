<x-app-layout>

    @push('styles')
        <style>
            /* Keep dashboard-compatible toggle behavior for compact views */
            #dashboard-main.cards-only > *:not(#events-section) { display: none !important; }
        </style>
    @endpush

    <main id="dashboard-main" class="bg-seiun-sky">
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-emerald-700">My Events</h1>
                    <form method="GET" action="{{ route('my.events') }}" class="flex items-center">
                        <input type="search" name="search" value="{{ request('search', '') }}" placeholder="Search my events..." class="border rounded-l px-3 py-2" />
                        <button type="submit" class="bg-magenta-secondary text-white px-4 py-2 rounded-r">Search</button>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @if(isset($events) && $events->count())
                        @foreach($events as $event)
                            @php
                                $firstImg = $event->images->first();
                                $imgUrl = $firstImg ? Storage::url($firstImg->path) : 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                $isPaid = $event->priceTier && $event->priceTier->pricetier ? true : false;
                            @endphp

                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300 ease-in-out">
                                <div class="relative">
                                    <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $event->eventName }} details"></a>
                                    <img src="{{ $imgUrl }}" alt="{{ $event->eventName }}" class="w-full h-48 object-cover" />
                                </div>

                                <div class="p-5 flex-grow flex flex-col">
                                    <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="block relative z-30" aria-label="View {{ $event->eventName }} details">
                                        <h3 class="font-bold text-xl text-gray-900 mb-1">{{ $event->eventName }}</h3>
                                        <p class="text-gray-500 text-sm mb-2">{{ $event->eventAdd ?? 'Address unavailable' }}</p>

                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-sm text-gray-600">{{ $event->priceTier->pricetier ?? 'Free' }}</span>
                                            <span class="text-gray-400 text-sm">•</span>
                                            <span class="text-sm text-gray-600">{{ $event->attendees_count ?? 0 }} going</span>
                                        </div>

                                        <p class="text-gray-600 text-sm leading-relaxed mt-auto">{{ Str::limit($event->eventDesc ?? 'No description provided.', 120) }}</p>
                                    </a>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="inline-flex items-center px-3 py-2 bg-[color:var(--brand-pink-accent)] text-white rounded-md text-sm font-medium">View</a>
                                            <a href="{{ route('user.events.edit', $event->eventID) }}" class="inline-flex items-center px-3 py-2 bg-amber-500 text-white rounded-md text-sm font-medium">Edit</a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $event->eventDate ? \Illuminate\Support\Carbon::parse($event->eventDate)->format('M d, Y H:i') : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-span-full">
                            <div class="mt-6">{{ $events->links() }}</div>
                        </div>

                    @else
                        <div class="col-span-full text-center text-gray-600">You have not created any events yet.</div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
