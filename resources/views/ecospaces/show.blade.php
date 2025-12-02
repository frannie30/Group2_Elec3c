<x-app-layout>


    @if(!empty($ecospace))
        @php
            $__reviewCount = $reviewCount ?? 0;
            $__avgRating = $avgRating ?? null;
            $__filledAvg = $__avgRating ? (int) round($__avgRating) : 0;
            $__totalStars = $reviewStarsTotal ?? 0;
            $__latestReviewDate = $latestReviewDate ?? null;
        @endphp
    @endif

    @if(empty($ecospace))
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 text-center text-gray-700">
                    <h3 class="text-xl font-semibold">No ecospace selected</h3>
                    <p class="mt-2">We couldn't find the ecospace you requested. Try selecting one from the list.</p>
                    <div class="mt-4"><a href="{{ route('ecospace') }}" class="text-green-600 hover:underline">Back to ecospaces</a></div>
                </div>
            </div>
        </div>
    @else
    <div class="py-12 bg-green-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4">
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            <div class="bg-white shadow-md sm:rounded-2xl p-6 border border-gray-100">
                {{-- Images carousel --}}
                @php
                    $imgs = $ecospace->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                    // Include review images (if any) into the displayed images
                    if (!empty($reviewImgs)) {
                        $imgs = array_merge($imgs, $reviewImgs);
                    }
                @endphp
                <div class="mb-6">
                    @if(count($imgs))
                        <div id="detail-carousel" class="relative" data-images='@json($imgs)' data-index="0">
                            <img id="detail-img" src="{{ $imgs[0] }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-72 lg:h-96 object-cover rounded-lg" />
                            <button onclick="detailPrev()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">‹</button>
                            <button onclick="detailNext()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">›</button>
                        </div>
                    @else
                        <div class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-400">No images available</div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <h3 class="text-4xl font-bold text-gray-900">{{ $ecospace->ecospaceName }}</h3>
                        <p class="text-base text-gray-700 mt-3">{{ $ecospace->ecospaceDesc ?? 'No description provided.' }}</p>

                        @if($__reviewCount)
                            <div class="mt-6">
                                <div class="inline-flex items-center gap-3 bg-white px-3 py-2 rounded-xl border border-gray-100">
                                    @for($i=1;$i<=5;$i++)
                                        @if($i <= $__filledAvg)
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"/></svg>
                                        @else
                                            <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"/></svg>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm font-semibold">{{ number_format($__avgRating,1) }}/5</span>
                                    <span class="ml-2 text-gray-600">({{ $__reviewCount }})</span>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 text-sm text-gray-700">
                            <div><strong>Address:</strong> {{ $ecospace->ecospaceAdd ?? 'N/A' }}</div>

                            @isset($isOpenNow)
                                <div class="mt-2">
                                        @if($isOpenNow === true)
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded">Open now</span>
                                        @if(!empty($openUntil))
                                            <span class="ml-2 text-sm text-gray-600">until {{ $openUntil }}</span>
                                        @endif
                                    @elseif($isOpenNow === false)
                                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded">Closed now</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded">Hours unavailable</span>
                                    @endif
                                </div>
                            @endisset
                        </div>
                    </div>
                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm">
                        <div class="mb-4"><strong class="text-gray-800">Price Tier</strong>
                            <div class="text-gray-600">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-4"><strong class="text-gray-800">Status</strong>
                            <div class="text-gray-600">{{ $ecospace->status->status ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-4"><strong class="text-gray-800">Owner</strong>
                            <div class="text-gray-600">{{ $ecospace->user->name ?? 'Unknown' }}</div>
                        </div>

                        <div class="mt-4">
                            <div class="space-y-2">
                                @auth
                                                @if(auth()->id() != ($ecospace->userID ?? null))
                                                <a href="{{ route('ecospace.reviews.choose', $ecospace->ecospaceID) }}" class="w-full block text-center bg-green-600 text-white px-4 py-2 rounded-md">Add Review</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="w-full block text-center bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-md">Sign in to add a review</a>
                                        @endauth
                            </div>

                            <div class="mt-2">
                                {{-- Edit button visible only to the owner; if this is the owner's first ecospace show a static (disabled) button --}}
                                @auth
                                    @if(auth()->id() == $ecospace->userID)
                                        @php
                                            // value('ecospaceID') returns the earliest ecospaceID for this user (ordered by dateCreated)
                                            $firstEcospaceId = \App\Models\Ecospace::where('userID', $ecospace->userID)
                                                ->orderBy('dateCreated', 'asc')
                                                ->value('ecospaceID');
                                            $isOwnerFirst = $firstEcospaceId && $firstEcospaceId == $ecospace->ecospaceID;
                                        @endphp

                                                <a href="{{ route('user.ecospaces.edit', $ecospace->ecospaceID) }}" class="block text-center bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
                                    @endif
                                @endauth

                                <a href="{{ url()->previous() }}" class="block text-center bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Combined Reviews + Pros & Cons --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Left: Reviews list (spans 2 cols on large screens) --}}
                    <div class="lg:col-span-2">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 inline">Reviews</h3>
                                @if($__reviewCount)
                                    <span class="ml-3 text-sm text-gray-700 font-semibold">{{ $__avgRating ? (int) round($__avgRating) : '-' }}/5</span>
                                    <span class="ml-2 text-sm text-gray-600">({{ $__reviewCount }} review{{ $__reviewCount > 1 ? 's' : '' }})</span>
                                @else
                                    <div class="text-gray-600 mt-2">No reviews yet. Be the first to review this ecospace.</div>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                @php
                                    $selectedRating = request()->query('rating', '');
                                    $selectedSort = request()->query('sort', 'newest');
                                    $queryWithoutRating = request()->except(['rating','reviews_page']);
                                    $allRatingsUrl = url()->current() . (count($queryWithoutRating) ? ('?' . http_build_query($queryWithoutRating)) : '');
                                @endphp
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-600">Filter:</span>
                                    <a href="{{ $allRatingsUrl }}" class="px-2 py-1 rounded text-sm {{ $selectedRating === '' ? 'bg-green-600 text-white' : 'bg-white border' }}">All</a>
                                    @for($s=5;$s>=1;$s--)
                                        @php $url = request()->fullUrlWithQuery(['rating' => $s, 'reviews_page' => 1]); @endphp
                                        <a href="{{ $url }}" class="px-2 py-1 rounded text-sm {{ (string)$selectedRating === (string)$s ? 'bg-green-600 text-white' : 'bg-white border' }}">{{ $s }}</a>
                                    @endfor
                                </div>

                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-600">Sort:</span>
                                    @php $sortOptions = ['newest' => 'Newest', 'oldest' => 'Oldest', 'highest' => 'Highest', 'lowest' => 'Lowest']; @endphp
                                    @foreach($sortOptions as $k => $label)
                                        @php $url = request()->fullUrlWithQuery(['sort' => $k, 'reviews_page' => 1]); @endphp
                                        <a href="{{ $url }}" class="px-2 py-1 rounded text-sm {{ $selectedSort === $k ? 'bg-green-600 text-white' : 'bg-white border' }}">{{ $label }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Reviews list --}}
                        @if(isset($reviews) && $reviews->count())
                            <div class="space-y-6">
                                @foreach($reviews as $r)
                                    <div class="border rounded-md p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3">
                                                <div>
                                                    <div class="font-semibold text-gray-900 flex items-center gap-3">
                                                        <span>{{ $r->user->name ?? ('User #' . $r->userID) }}</span>
                                                        @php $filled = (int) round($r->rating); @endphp
                                                        <span class="flex items-center text-sm text-gray-700 font-semibold">
                                                            @for($i=1;$i<=5;$i++)
                                                                @if($i <= $filled)
                                                                    <span class="text-yellow-500">★</span>
                                                                @else
                                                                    <span class="text-gray-300">☆</span>
                                                                @endif
                                                            @endfor
                                                            <span class="ml-2">{{ number_format($r->rating,1) }}/5</span>
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ optional($r->dateCreated)->format('M d, Y') ?? '' }}</div>

                                                    @if(!empty($r->review))
                                                        <div class="mt-2 text-gray-700">{{ $r->review }}</div>
                                                    @endif

                                                    @if(!empty($r->images) && $r->images->count())
                                                        <div class="mt-3 flex gap-3 overflow-x-auto">
                                                            @foreach($r->images as $ri)
                                                                <img src="{{ Storage::url($ri->revImgName) }}" alt="review image" class="w-28 h-20 object-cover rounded-md border" />
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="ml-4 flex flex-col items-end gap-2">
                                                @auth
                                                    @if(auth()->id() == $r->userID)
                                                        <a href="{{ route('ecospace.reviews.edit', [$ecospace->ecospaceID, $r->reviewID]) }}" class="text-sm text-yellow-600">Edit</a>
                                                        <form action="{{ route('ecospace.reviews.destroy', [$ecospace->ecospaceID, $r->reviewID]) }}" method="POST" data-confirm="Delete this review?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600">Delete</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                @if(method_exists($reviews, 'links'))
                                    {{ $reviews->links() }}
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Right: Pros & Cons compact panel --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-4">
                                @php
                                    $prosTotal = method_exists($pros, 'total') ? $pros->total() : (is_countable($pros) ? count($pros) : ($pros->count() ?? 0));
                                    $consTotal = method_exists($cons, 'total') ? $cons->total() : (is_countable($cons) ? count($cons) : ($cons->count() ?? 0));
                                    $selectedPc = request()->query('pc', 'both');
                                @endphp
                                <div class="bg-green-50 p-4 rounded-md">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Pros &amp; Cons</h4>
                                            <div class="mt-2 flex items-center gap-2 text-sm">
                                                <a href="{{ request()->fullUrlWithQuery(['pc' => 'both', 'pros_page' => 1, 'cons_page' => 1]) }}" class="px-2 py-1 rounded text-sm {{ $selectedPc === 'both' ? 'bg-green-600 text-white' : 'bg-white border' }}">All</a>
                                                <a href="{{ request()->fullUrlWithQuery(['pc' => 'pros', 'pros_page' => 1, 'cons_page' => 1]) }}" class="px-2 py-1 rounded text-sm {{ $selectedPc === 'pros' ? 'bg-green-600 text-white' : 'bg-white border' }}">Pros</a>
                                                <a href="{{ request()->fullUrlWithQuery(['pc' => 'cons', 'pros_page' => 1, 'cons_page' => 1]) }}" class="px-2 py-1 rounded text-sm {{ $selectedPc === 'cons' ? 'bg-green-600 text-white' : 'bg-white border' }}">Cons</a>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $prosTotal + $consTotal }} total</div>
                                    </div>
                                </div>

                                <div class="bg-white border rounded-md p-4">
                                        @php
                                            // Support both Collection (legacy) and Paginator instances
                                            $prosTotal = method_exists($pros, 'total') ? $pros->total() : (is_countable($pros) ? count($pros) : ($pros->count() ?? 0));
                                            $consTotal = method_exists($cons, 'total') ? $cons->total() : (is_countable($cons) ? count($cons) : ($cons->count() ?? 0));
                                        @endphp

                                        {{-- Pros --}}
                                        @if($prosTotal === 0)
                                            <div class="text-gray-600">No pros yet.</div>
                                        @else
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-green-700 mb-2">Pros</h5>
                                                <div class="space-y-3">
                                                    @foreach($pros as $pc)
                                                        <div class="text-sm border-b pb-2">
                                                            <div class="font-semibold text-gray-800">{{ $pc->description }}</div>
                                                            <div class="text-xs text-gray-500 mt-1">— {{ $pc->user->name ?? ('User #' . $pc->userID) }} · {{ optional($pc->dateCreated)->format('M d, Y') ?? '' }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if(method_exists($pros, 'links'))
                                                    <div class="mt-3">
                                                        {{ $pros->links() }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Cons --}}
                                        @if($consTotal === 0)
                                            <div class="text-gray-600">No cons yet.</div>
                                        @else
                                            <div class="mt-2">
                                                <h5 class="text-sm font-semibold text-red-700 mb-2">Cons</h5>
                                                <div class="space-y-3">
                                                    @foreach($cons as $pc)
                                                        <div class="text-sm border-b pb-2">
                                                            <div class="font-semibold text-gray-800">{{ $pc->description }}</div>
                                                            <div class="text-xs text-gray-500 mt-1">— {{ $pc->user->name ?? ('User #' . $pc->userID) }} · {{ optional($pc->dateCreated)->format('M d, Y') ?? '' }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if(method_exists($cons, 'links'))
                                                    <div class="mt-3">
                                                        {{ $cons->links() }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- carousel script --}}
        <script>
            (function(){
                const container = document.getElementById('detail-carousel');
                const imgs = container ? JSON.parse(container.getAttribute('data-images') || '[]') : [];
                let idx = parseInt(container?.getAttribute('data-index') || '0', 10) || 0;

                window.detailNext = function() {
                    if (!imgs.length) return;
                    idx = (idx + 1) % imgs.length;
                    if (container) container.setAttribute('data-index', idx);
                    const el = document.getElementById('detail-img');
                    if (el) el.src = imgs[idx];
                }

                window.detailPrev = function() {
                    if (!imgs.length) return;
                    idx = (idx - 1 + imgs.length) % imgs.length;
                    if (container) container.setAttribute('data-index', idx);
                    const el = document.getElementById('detail-img');
                    if (el) el.src = imgs[idx];
                }
            })();
        </script>
        <script>
            // Review form toggle
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('toggle-review-form');
                const form = document.getElementById('review-form');
                const cancel = document.getElementById('cancel-review');
                if (toggle && form) {
                    toggle.addEventListener('click', function() {
                        form.classList.toggle('hidden');
                    });
                }
                if (cancel && form) {
                    cancel.addEventListener('click', function() {
                        form.classList.add('hidden');
                    });
                }
            });
        </script>
    </div>
    @endif
</x-app-layout>
