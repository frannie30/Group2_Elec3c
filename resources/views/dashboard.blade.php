<x-app-layout>

<!--p.s. Tinanggal ko na yung font import dito since I've set up the proper one sa tailwind.config.js -Arkin -->
    @push('styles')
        <style>
            /* When toggled, show only the spaces card list and hide everything else inside the main container */
            #dashboard-main.cards-only > *:not(#spaces-section) { display: none !important; }
        </style>
    @endpush

    <main id="dashboard-main" class="bg-seiun-sky">
        <!-- Hero -->
        <section id="hero-section" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                        <span class="text-dark-green">Discover Makati's</span>
                        <br>
                        <span class="text-light-green">Green Spaces</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-lg">Find parks, gardens, and eco-friendly spaces near you. Join events, share reviews, and help build sustainable communities.</p>

                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center bg-white shadow-md rounded-lg overflow-hidden max-w-xl">
                        <span class="pl-4 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input id="search" name="search" type="search" placeholder="Search parks, gardens, events..." value="{{ $search ?? '' }}" class="flex-grow p-4 border-none focus:ring-0 text-gray-700 placeholder-gray-500" />
                        <button type="submit" class="bg-[color:var(--brand-maroon)] text-white px-6 py-4 font-semibold hover:opacity-95 transition-colors">Search</button>
                    </form>
                    <p class="text-gray-500 text-sm mt-4">@auth <a href="{{ route('users.show', Auth::user()->id) }}" class="font-medium hover:underline">View your profile</a> @else <a href="#login" class="font-medium hover:underline">Log in</a> to see saved listings @endauth</p>
                </div>

                <div class="hidden md:block">
                    <img src="https://placehold.co/600x450/96B8A0/FFFFFF?text=Park+View" alt="Park view" class="rounded-lg shadow-lg w-full h-auto object-cover" style="aspect-ratio:4/3;" />
                </div>
            </div>
        </section>
    </main>

    <!-- Spaces Near Me -->
    <section id="spaces-section" class="py-16 md:py-24 bg-cinderella-gray">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-3xl md:text-4xl font-extrabold text-magenta-secondary">Spaces Near Me</h2>
                            <a href="{{ route('ecospaces.index') }}" class="text-magenta-secondary font-normal hover:underline text-lg">View all &gt;</a>
                        </div>

                <div id="spaces-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    @if(isset($ecospaces) && $ecospaces->count())
                        @foreach($ecospaces as $ecospace)
                            @php
                                $firstImg = $ecospace->images->first();
                                $imgUrl = $firstImg ? Storage::url($firstImg->path) : 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                $esBookmarked = auth()->check() ? auth()->user()->esBookmarks()->where('ecospaceID', $ecospace->ecospaceID)->exists() : false;
                            @endphp
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300 ease-in-out">
                                <div class="relative">
                                    <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $ecospace->ecospaceName }} details"></a>
                                    <img src="{{ $imgUrl }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-48 object-cover" />
                                    @auth
                                        <form method="POST" action="{{ route('bookmark.ecospace.toggle', $ecospace->ecospaceID) }}" class="absolute top-4 right-4 z-20">
                                            @csrf
                                            <button type="submit" class="bg-white p-2.5 rounded-full shadow-md hover:bg-red-50 transition-colors">
                                                @if($esBookmarked)
                                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.13 2.44h1.74C14.09 5 15.76 4 17.5 4 20 4 22 6 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                @else
                                                    <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <div class="p-5 flex-grow flex flex-col">
                                    <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="block relative z-30" aria-label="View {{ $ecospace->ecospaceName }} details">
                                        <h3 class="font-bold text-xl text-gray-900 mb-1">{{ $ecospace->ecospaceName }}</h3>
                                        <p class="text-gray-500 text-sm mb-2">{{ $ecospace->ecospaceAdd ?? 'Address unavailable' }}</p>
                                        @php
                                            $avg = $ecospace->reviews()->avg('rating');
                                            $avgFormatted = $avg ? number_format($avg, 1) : null;
                                            $filled = $avg ? (int) round($avg) : 0;
                                        @endphp
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-sm text-gray-600">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</span>
                                            <span class="text-gray-400 text-sm">•</span>
                                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $filled)
                                                            <svg class="h-4 w-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"/></svg>
                                                        @else
                                                            <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"/></svg>
                                                        @endif
                                                    @endfor
                                                </span>
                                                <span>{{ $avgFormatted ?? '-' }}/5</span>
                                                <span class="text-gray-400">•</span>
                                                <span>{{ $ecospace->reviews()->count() }} reviews</span>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm leading-relaxed mt-auto">{{ Str::limit($ecospace->ecospaceDesc ?? 'No description provided.', 120) }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center text-magenta-secondary">No ecospaces found.</div>
                    @endif
                </div>

                {{-- Dashboard shows only a small preview; pagination handled on the all-ecospaces page --}}
            </div>
        </section>
    </main>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                var link = document.getElementById('view-all-spaces');
                if(!link) return;
                var originalText = link.getAttribute('data-original') || link.textContent.trim();
                var main = document.getElementById('dashboard-main');
                var spaces = document.getElementById('spaces-section');

                // If server rendered without <main>, show "Show less" and ensure link goes back to dashboard
                if(!main){
                    link.setAttribute('aria-expanded','true');
                    link.textContent = 'Show less';
                    try{ link.href = '{{ route('dashboard') }}'; }catch(e){}
                }

                link.addEventListener('click', function(e){
                    // Preserve native navigation if user uses modifier keys
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    e.preventDefault();

                    main = document.getElementById('dashboard-main');
                    spaces = document.getElementById('spaces-section');

                    // If <main> exists, detach the spaces section and remove <main>
                    if(main && spaces){
                        // Move spaces-section out of main (insert after main)
                        if(main.parentNode){
                            main.parentNode.insertBefore(spaces, main.nextSibling);
                            main.parentNode.removeChild(main);
                        } else {
                            // fallback: append to body
                            document.body.appendChild(spaces);
                        }

                        link.setAttribute('aria-expanded','true');
                        link.textContent = 'Show less';
                        try{ link.href = '{{ route('dashboard') }}'; }catch(e){}

                        var grid = document.getElementById('spaces-grid');
                        if(grid && grid.scrollIntoView) grid.scrollIntoView({behavior:'smooth'});
                        return;
                    }

                    // If <main> is already removed (cards-only), navigate back to dashboard to restore full layout
                    window.location.href = link.href || '{{ route('dashboard') }}';
                });
            });
        </script>
    @endpush
</x-app-layout>
