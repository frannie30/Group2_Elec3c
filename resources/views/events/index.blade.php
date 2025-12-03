<x-app-layout>

    @push('styles')
        <style>
            /* Keep dashboard-compatible toggle behavior for compact views */
            #dashboard-main.cards-only > *:not(#events-section) { display: none !important; }
        </style>
    @endpush

    <!-- Use the pale mint background for the whole page -->
    <main id="dashboard-main" class="bg-seiun-sky">


        <!-- Hero (white card so content stays readable on the pale mint page) -->
        <section id="hero-section" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                        <span class="text-dark-green">Discover Local</span>
                        <br>
                        <span class="text-light-green">Events Near You</span>
                    </h1>

                    <p class="text-2xl text-dark-green font-extralight mb-8 max-w-lg">Find community events, workshops, and gatherings. RSVP, share, and join your neighbors for greener initiatives.</p>

                    <div class="max-w-xl bg-white rounded-lg p-4">
                        <div class="border-2 border-magenta-secondary rounded-lg p-2">
                            <form method="GET" action="{{ route('events.index') }}" class="flex items-center bg-white shadow-md rounded-lg overflow-hidden w-full">
                                <span class="pl-4 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                                <input id="search" name="search" type="search" placeholder="Search events, locations, hosts..." value="{{ request('search', '') }}" class="flex-grow p-4 border-none focus:ring-0 text-gray-700 placeholder-gray-500" />
                                <button type="submit" class="bg-magenta-secondary text-white px-6 py-4 font-semibold hover:opacity-95 transition-colors">Search</button>
                            </form>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mt-4">Browse upcoming and past events across the platform.</p>
                </div>

                {{-- Slideshow / placeholder: show images from first several events, fall back to asset images --}}
                @php
                    // Collect up to 5 slide images from events (first image of each event) or fallbacks
                    $slideImages = [];
                    if(isset($events) && $events->count()){
                        foreach($events->take(5) as $ev){
                            $first = $ev->images->first();
                            if($first){
                                try {
                                    if (Storage::disk('public')->exists($first->path)) {
                                        $slideImages[] = Storage::url($first->path) . '?t=' . Storage::disk('public')->lastModified($first->path);
                                    } else {
                                        $slideImages[] = asset('images/EcoSpace5.jpg');
                                    }
                                } catch (\Exception $e) {
                                    $slideImages[] = Storage::url($first->path);
                                }
                            } else {
                                $slideImages[] = asset('images/EcoSpace5.jpg');
                            }
                        }
                    } else {
                        // default slideshow images
                        $slideImages = [
                            asset('images/EcoSpace1.jpg'),
                            asset('images/EcoSpace2.jpg'),
                            asset('images/EcoSpace3.jpg')
                        ];
                    }
                @endphp

                <div class="hidden md:block">
                    <img id="hero-slide-image" src="{{ $slideImages[0] ?? asset('images/EcoSpace5.jpg') }}" alt="Events slideshow" class="rounded-lg shadow-lg w-full h-auto object-contain transition-opacity duration-1000 bg-gray-100" style="aspect-ratio:4/3;" />
                </div>
            </div>
        </section>

        <!-- Events Grid -->
        <section id="events-section" class="py-16 md:py-24 bg-cinderella-gray">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-magenta-secondary">Events</h2>
                    <a id="view-all-events" data-requires-login="true" href="{{ route('events.all') }}" class="text-magenta-secondary font-normal hover:underline text-lg">View all &gt;</a>
                </div>

                <!-- Filters moved to the full listing page -->

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @if(isset($events) && $events->count())
                        {{-- Show only first 3 cards on this preview page --}}
                        @foreach($events->take(3) as $event)
                            @php
                                $firstImg = $event->images->first();
                                $imgUrl = 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                if ($firstImg) {
                                    try {
                                        if (Storage::disk('public')->exists($firstImg->path)) {
                                            $imgUrl = Storage::url($firstImg->path) . '?t=' . Storage::disk('public')->lastModified($firstImg->path);
                                        } else {
                                            $imgUrl = 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                        }
                                    } catch (\Exception $e) {
                                        $imgUrl = Storage::url($firstImg->path);
                                    }
                                }
                                $isPaid = $event->priceTier && $event->priceTier->pricetier ? true : false;
                                $evBookmarked = auth()->check() ? auth()->user()->evBookmarks()->where('eventID', $event->eventID)->exists() : false;
                            @endphp

                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300 ease-in-out">
                                <div class="relative">
                                    <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $event->eventName }} details"></a>
                                    <img src="{{ $imgUrl }}" alt="{{ $event->eventName }}" class="w-full h-48 object-contain bg-gray-100" />

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
                                            <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="inline-flex items-center px-3 py-2 bg-[color:var(--brand-pink-accent)] text-white rounded-md text-sm font-medium">View</a>
                                        </div>

                                        {{-- event date removed per design --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center text-gray-600">No events found.</div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageElement = document.getElementById('hero-slide-image');
            if(!imageElement) return;

            const slideImages = {!! json_encode($slideImages ?? []) !!};
            if(!slideImages || !slideImages.length) return;

            let currentImageIndex = 0;
            const cycleInterval = 4000; // 4s
            const fadeDuration = 1000; // match CSS duration

            function cycleImage(){
                // fade out
                imageElement.style.opacity = '0';
                setTimeout(() => {
                    currentImageIndex = (currentImageIndex + 1) % slideImages.length;
                    imageElement.src = slideImages[currentImageIndex];
                    imageElement.style.opacity = '1';
                }, fadeDuration);
            }

            // start with first image already set in DOM; begin cycling
            setInterval(cycleImage, cycleInterval);
        });
    </script>
@endpush
