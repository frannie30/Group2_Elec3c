<x-app-layout>


    @push('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
            :root{
                --brand-green: #4D9A51;
                --brand-maroon: #642D45;
                --brand-bg-light: #F5F4F1;
                --brand-bg-dark: #ECECEC;
            }
            body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        </style>
    @endpush

    <main class="bg-[color:var(--brand-bg-light)]">
        <!-- Hero -->
        <section class="bg-white container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">Discover Local<br><span class="text-[color:var(--brand-green)]">Events Near You</span></h1>
                    <p class="text-lg text-gray-600 mb-6 max-w-lg">Find community events, workshops, and gatherings. RSVP, share, and join your neighbors for greener initiatives.</p>

                    <form method="GET" action="{{ route('events.index') }}" class="flex items-center bg-white shadow-md rounded-lg overflow-hidden max-w-xl">
                        <span class="pl-4 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input id="search" name="search" type="search" placeholder="Search events, locations, hosts..." value="{{ request('search', '') }}" class="flex-grow p-4 border-none focus:ring-0 text-gray-700 placeholder-gray-500" />
                        <button type="submit" class="bg-[color:var(--brand-maroon)] text-white px-6 py-4 font-semibold hover:opacity-95 transition-colors">Search</button>
                    </form>
                    <p class="text-gray-500 text-sm mt-4">Browse upcoming and past events across the platform.</p>
                </div>

                <div class="hidden md:block">
                    <img src="https://placehold.co/600x450/96B8A0/FFFFFF?text=Community+Event" alt="Event hero" class="rounded-lg shadow-lg w-full h-auto object-cover" style="aspect-ratio:4/3;" />
                </div>
            </div>
        </section>

        

        <!-- Events Grid -->
        <section class="py-12 md:py-16 bg-[color:var(--brand-bg-dark)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-[color:var(--brand-maroon)]">Events</h2>
                    <a href="{{ route('events.all') }}" class="text-[color:var(--brand-green)] font-semibold hover:underline text-lg">View all &gt;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @if(isset($events) && $events->count())
                        {{-- Show only first 3 cards on this preview page --}} 
                        @foreach($events->take(3) as $event)
                            @php
                                $firstImg = $event->images->first();
                                $imgUrl = $firstImg ? Storage::url($firstImg->path) : 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                $isPaid = $event->priceTier && $event->priceTier->pricetier ? true : false;
                                $evBookmarked = auth()->check() ? auth()->user()->evBookmarks()->where('eventID', $event->eventID)->exists() : false;
                            @endphp
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300 ease-in-out">
                                <div class="relative">
                                    <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $event->eventName }} details"></a>
                                    <img src="{{ $imgUrl }}" alt="{{ $event->eventName }}" class="w-full h-48 object-cover" />
                                    <div class="absolute top-4 left-4 z-20">
                                        <span class="bg-white/90 text-sm px-3 py-1 rounded-full font-medium text-gray-700">{{ optional($event->eventDate)->format('M d, Y') ?? $event->eventDate }}</span>
                                    </div>
                                    @auth
                                        <form method="POST" action="{{ route('bookmark.event.toggle', $event->eventID) }}" class="absolute top-4 right-4 z-20">
                                            @csrf
                                            <button type="submit" class="bg-white p-2.5 rounded-full shadow-md hover:bg-red-50 transition-colors">
                                                @if($evBookmarked)
                                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.13 2.44h1.74C14.09 5 15.76 4 17.5 4 20 4 22 6 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                @else
                                                    <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth
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
                                            <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="inline-flex items-center px-3 py-2 bg-[color:var(--brand-maroon)] text-white rounded-md text-sm font-medium">View</a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center text-gray-600">No events found.</div>
                    @endif
                </div>

                {{-- Preview: no pagination shown here; use "View all" for full listing --}}
            </div>
        </section>
    </main>
</x-app-layout>
