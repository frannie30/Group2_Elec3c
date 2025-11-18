<x-app-layout>

    @push('styles')
        <style>
            :root{--brand-green:#4D9A51;--brand-maroon:#642D45}
        </style>
    @endpush

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-start justify-between mb-8 gap-6">
            <h1 class="text-3xl font-bold text-gray-900">EcoSpaces</h1>

            <!-- Filters / Sorting checklist -->
            <form id="ecospace-filters" method="GET" action="{{ route('ecospaces.index') }}" class="w-full max-w-md bg-white p-4 rounded-md shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <label class="font-medium text-gray-700">Sort</label>
                    <button type="submit" class="text-sm text-gray-500">Apply</button>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm text-gray-700 mb-3">
                    <label class="flex items-center gap-2"><input type="radio" name="sort" value="newest" {{ request()->query('sort', 'newest') === 'newest' ? 'checked' : '' }}> Newest</label>
                    <label class="flex items-center gap-2"><input type="radio" name="sort" value="a-z" {{ request()->query('sort') === 'a-z' ? 'checked' : '' }}> A - Z</label>
                    <label class="flex items-center gap-2"><input type="radio" name="sort" value="z-a" {{ request()->query('sort') === 'z-a' ? 'checked' : '' }}> Z - A</label>
                    <label class="flex items-center gap-2"><input type="radio" name="sort" value="highest" {{ request()->query('sort') === 'highest' ? 'checked' : '' }}> Highest rating</label>
                    <label class="flex items-center gap-2"><input type="radio" name="sort" value="lowest" {{ request()->query('sort') === 'lowest' ? 'checked' : '' }}> Lowest rating</label>
                </div>

                <div class="mb-3">
                    <div class="font-medium text-gray-700 text-sm mb-1">Stars (rounded)</div>
                    <div class="flex gap-2 flex-wrap text-sm">
                        @php $selectedStars = request()->query('stars', []); if(!is_array($selectedStars)) $selectedStars = [$selectedStars]; @endphp
                        @foreach([5,4,3,2,1,0] as $s)
                            <label class="flex items-center gap-2 mr-3">
                                <input type="checkbox" name="stars[]" value="{{ $s }}" {{ in_array((string)$s, array_map('strval', $selectedStars)) ? 'checked' : '' }}>
                                @if($s === 0) No reviews @else {{ $s }}★ @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="open_now" value="1" {{ request()->boolean('open_now') ? 'checked' : '' }}> Currently open</label>
                    <a href="{{ route('ecospaces.index') }}" class="text-xs text-gray-400">Reset</a>
                </div>

                <input type="hidden" name="search" value="{{ request()->query('search', '') }}">
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @if(isset($ecospaces) && $ecospaces->count())
                @foreach($ecospaces as $ecospace)
                    @php
                        $firstImg = $ecospace->images->first();
                        $imgUrl = $firstImg ? Storage::url($firstImg->path) : 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                        $esBookmarked = isset($bookmarkedEcospaces) ? in_array($ecospace->ecospaceID, $bookmarkedEcospaces) : false;
                    @endphp

                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                        <div class="relative">
                            <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $ecospace->ecospaceName }}"></a>
                            <img src="{{ $imgUrl }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-48 object-cover" />
                            @auth
                                <form method="POST" action="{{ route('bookmark.ecospace.toggle', $ecospace->ecospaceID) }}" class="absolute top-3 right-3 z-20">
                                    @csrf
                                    <button type="submit" class="bg-white p-2.5 rounded-full shadow-md hover:bg-red-50">
                                        @if($esBookmarked)
                                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1 4.13 2.44h1.74C14.09 5 15.76 4 17.5 4 20 4 22 6 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        @else
                                            <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                        @endif
                                    </button>
                                </form>
                            @endauth
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $ecospace->ecospaceName }}</h3>
                            <p class="text-gray-500 text-sm mb-3">{{ $ecospace->ecospaceAdd ?? 'Address unavailable' }}</p>
                            <div class="mt-auto flex items-center justify-between">
                                <div class="text-sm text-gray-600">{{ Str::limit($ecospace->ecospaceDesc ?? '', 100) }}</div>
                                <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="bg-[color:var(--brand-maroon)] text-white px-3 py-1.5 rounded-md text-sm">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center text-gray-600">No ecospaces found.</div>
            @endif
        </div>

        <div class="mt-8">{{ isset($ecospaces) && method_exists($ecospaces, 'links') ? $ecospaces->links() : '' }}</div>
    </main>

    @push('scripts')
        <script>
            // Auto-submit filters form when inputs change
            (function(){
                const form = document.getElementById('ecospace-filters');
                if (!form) return;
                form.addEventListener('change', function(e){
                    // reset to first page when filters change
                    const pageInput = form.querySelector('input[name="page"]');
                    if (pageInput) pageInput.value = 1;
                    form.submit();
                });
            })();
        </script>
    @endpush

</x-app-layout>
