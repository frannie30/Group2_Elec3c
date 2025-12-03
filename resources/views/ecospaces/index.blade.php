<x-app-layout>

    @push('styles')
        <style>
            :root{--brand-green:#4D9A51;--brand-maroon:#642D45}
        </style>
    @endpush

    <main class="bg-[color:var(--brand-bg-light)]">
        <section class="py-12 md:py-16 bg-[color:var(--brand-bg-dark)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-[color:var(--brand-maroon)]">All EcoSpaces</h2>

                    {{-- Sort & Filters (styled like events) --}}
                    <div class="mb-6">
                        <form method="GET" action="{{ route('ecospaces.index') }}" class="flex flex-wrap items-center gap-3">
                            <input type="hidden" name="search" value="{{ $search ?? request('search', '') }}">

                            <label class="text-sm text-gray-600">Sort</label>
                            <select name="sort" class="border rounded px-3 py-2 bg-white">
                                <option value="date_desc" {{ (isset($sort) && $sort==='date_desc') ? 'selected' : '' }}>Date (newest)</option>
                                <option value="date_asc" {{ (isset($sort) && $sort==='date_asc') ? 'selected' : '' }}>Date (oldest)</option>
                                <option value="name_asc" {{ (isset($sort) && $sort==='name_asc') ? 'selected' : '' }}>Name A → Z</option>
                                <option value="name_desc" {{ (isset($sort) && $sort==='name_desc') ? 'selected' : '' }}>Name Z → A</option>
                                <option value="highest" {{ (isset($sort) && $sort==='highest') ? 'selected' : '' }}>Highest rating</option>
                                <option value="lowest" {{ (isset($sort) && $sort==='lowest') ? 'selected' : '' }}>Lowest rating</option>
                            </select>

                            <label class="text-sm text-gray-600">Price</label>
                            <select name="price_tier" class="border rounded px-3 py-2 bg-white">
                                <option value="">All prices</option>
                                @foreach(($priceTiers ?? collect()) as $pt)
                                    <option value="{{ $pt->priceTierID }}" {{ (isset($priceTier) && $priceTier == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                                @endforeach
                            </select>

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

                            <button type="submit" class="px-4 py-2 bg-magenta-secondary text-white rounded">Apply</button>
                        </form>
                    </div>
                    {{-- Back to preview removed per UI update request --}}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @if(isset($ecospaces) && $ecospaces->count())
                        @foreach($ecospaces as $ecospace)
                                @php
                                // Prefer the most recently uploaded ecospace image; if none, fall back to the newest review image
                                $firstImg = $ecospace->images()->orderByDesc('esImageID')->first();
                                $reviewImg = null;
                                if (!$firstImg) {
                                    $latestReviewWithImg = $ecospace->reviews()->whereHas('images')->with('images')->orderByDesc('dateCreated')->first();
                                    if ($latestReviewWithImg && $latestReviewWithImg->images->count()) {
                                        $reviewImg = $latestReviewWithImg->images->first();
                                    }
                                }

                                if ($firstImg && Storage::disk('public')->exists($firstImg->path)) {
                                    $ts = Storage::disk('public')->lastModified($firstImg->path);
                                    $imgUrl = Storage::url($firstImg->path) . '?t=' . $ts;
                                    $imgSource = 'ecospace';
                                    $imgPath = $firstImg->path;
                                } elseif ($firstImg) {
                                    $imgUrl = Storage::url($firstImg->path);
                                    $imgSource = 'ecospace';
                                    $imgPath = $firstImg->path;
                                } elseif ($reviewImg) {
                                    $imgUrl = Storage::url($reviewImg->revImgName) . (Storage::disk('public')->exists($reviewImg->revImgName) ? ('?t=' . Storage::disk('public')->lastModified($reviewImg->revImgName)) : '');
                                    $imgSource = 'review';
                                    $imgPath = $reviewImg->revImgName;
                                } else {
                                    $imgUrl = 'https://placehold.co/400x300/A8C6B7/FFFFFF?text=No+Image';
                                    $imgSource = null;
                                    $imgPath = null;
                                }
                                $isPaid = $ecospace->priceTier && $ecospace->priceTier->pricetier ? true : false;
                                $esBookmarked = isset($bookmarkedEcospaces) ? in_array($ecospace->ecospaceID, $bookmarkedEcospaces) : false;
                            @endphp
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300 ease-in-out">
                                <div class="relative">
                                    <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="block absolute inset-0 z-10" aria-label="View {{ $ecospace->ecospaceName }}"></a>
                                    <img src="{{ $imgUrl }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-48 object-cover" />
                                    @php
                                        // Show debug overlay only to the ecospace owner (remove APP_DEBUG gating)
                                        $debugShow = (auth()->check() && auth()->id() == ($ecospace->userID ?? null));
                                    @endphp
                                    @if($debugShow)
                                        <div class="absolute left-2 top-2 bg-white/80 text-xs text-gray-800 p-2 rounded border shadow-sm z-30">
                                            <div><strong>DB path:</strong> {{ $firstImg?->path ?? 'none' }}</div>
                                            <div><strong>Exists:</strong> {{ $firstImg ? (Storage::disk('public')->exists($firstImg->path) ? 'yes' : 'no') : 'n/a' }}</div>
                                            <div><strong>URL:</strong> {{ $firstImg ? Storage::url($firstImg->path) : 'n/a' }}</div>
                                        </div>
                                    @endif
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
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-sm text-gray-600">{{ $ecospace->priceTier->pricetier ?? 'Free' }}</span>
                                            <span class="text-gray-400 text-sm">•</span>
                                            <span class="text-sm text-gray-600">{{ $ecospace->reviews_count ?? 0 }} reviews</span>
                                        </div>
                                        <p class="text-gray-600 text-sm leading-relaxed mt-auto">{{ Str::limit($ecospace->ecospaceDesc ?? 'No description provided.', 120) }}</p>
                                    </a>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="inline-flex items-center px-3 py-2 bg-[color:var(--brand-maroon)] text-white rounded-md text-sm font-medium">View</a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $ecospace->dateCreated ? \Illuminate\Support\Carbon::parse($ecospace->dateCreated)->format('M d, Y') : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center text-gray-600">No ecospaces found.</div>
                    @endif
                </div>

                <div class="mt-8">
                    @if(isset($ecospaces) && method_exists($ecospaces, 'links'))
                        {{ $ecospaces->links() }}
                    @endif
                </div>
            </div>
        </section>
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
