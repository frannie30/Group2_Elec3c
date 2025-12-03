<x-app-layout>


    @if(!empty($ecospace))
        @php
            $__reviewCount = $reviewCount ?? 0;
            $__avgRating = $avgRating ?? null;
            // show full stars by floor so decimal averages still display the appropriate
            // number of full stars (e.g. 4.3 -> 4 full stars). Keep numeric average as 1-decimal.
            $__filledAvg = $__avgRating ? (int) floor($__avgRating) : 0;
            $__totalStars = $reviewStarsTotal ?? 0;
            $__latestReviewDate = $latestReviewDate ?? null;
        @endphp
    @endif

    @if(empty($ecospace))
        <div class="py-12">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 text-center text-gray-700">
                    <h3 class="text-xl font-semibold">No ecospace selected</h3>
                    <p class="mt-2">We couldn't find the ecospace you requested. Try selecting one from the list.</p>
                    <div class="mt-4"><a href="{{ route('ecospace') }}" class="text-green-600 hover:underline">Back to ecospaces</a></div>
                </div>
            </div>
        </div>
    @else
    <main id="dashboard-main" class="bg-seiun-sky min-h-screen py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4">
                    <div class="flash-message bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="lg:col-span-1">
                        <h3 class="text-4xl font-bold text-dark-green">{{ $ecospace->ecospaceName }}</h3>
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

                            @auth
                                @if(auth()->id() == ($ecospace->userID ?? null))
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded">Open now</span>
                                        <span class="ml-2 text-sm text-gray-600">until 5:00 PM</span>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4">
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="mb-2"><strong class="text-gray-800">Price Tier</strong>
                                    <div class="text-gray-600">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</div>
                                </div>
                                <div class="mb-2"><strong class="text-gray-800">Status</strong>
                                    <div class="text-gray-600">{{ $ecospace->status->status ?? 'N/A' }}</div>
                                </div>
                                <div class="mb-2"><strong class="text-gray-800">Owner</strong>
                                    <div class="text-gray-600">{{ $ecospace->user->name ?? 'Unknown' }}</div>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-lg border">
                                <div class="mt-2">
                                    @auth
                                        @if(auth()->id() == $ecospace->userID)
                                            <a href="{{ route('user.ecospaces.edit', $ecospace->ecospaceID) }}" class="block text-center bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
                                        @endif
                                    @endauth

                                    <a href="{{ url()->previous() }}" class="block text-center bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-lg border lg:col-span-1">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-dark-green">Reviews</h3>
                                        @if($__reviewCount)
                                            <div class="mt-2">
                                                <div class="text-lg font-semibold text-dark-green">{{ $__avgRating ? number_format($__avgRating,1) : '-' }}/5</div>
                                                <div class="text-sm text-gray-600">{{ $__reviewCount }} review{{ $__reviewCount > 1 ? 's' : '' }}</div>
                                            </div>
                                        @else
                                            <div class="text-gray-600 mt-2">No reviews yet. Be the first to review this ecospace.</div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col items-end gap-3">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $selectedRating = request()->query('rating', '');
                                                $selectedSort = request()->query('sort', 'newest');
                                                $queryWithoutRating = request()->except(['rating','reviews_page']);
                                                $allRatingsUrl = url()->current() . (count($queryWithoutRating) ? ('?' . http_build_query($queryWithoutRating)) : '');
                                            @endphp
                                            <div class="flex items-center gap-2 text-sm">
                                                <form id="reviews-filter-form" action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                                                    @foreach(request()->except(['rating','reviews_page']) as $k => $v)
                                                        @if(is_array($v))
                                                            @foreach($v as $vv)
                                                                <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}" />
                                                            @endforeach
                                                        @else
                                                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                                        @endif
                                                    @endforeach
                                                    <label class="text-gray-600">Filter:</label>
                                                    <select name="rating" onchange="this.form.submit()" class="rounded px-2 py-1 text-sm border">
                                                        <option value="" {{ $selectedRating === '' ? 'selected' : '' }}>All</option>
                                                        @for($s=5;$s>=1;$s--)
                                                            <option value="{{ $s }}" {{ (string)$selectedRating === (string)$s ? 'selected' : '' }}>{{ $s }}</option>
                                                        @endfor
                                                    </select>
                                                </form>
                                            </div>

                                            <div class="flex items-center gap-2 text-sm">
                                                @php $sortOptions = ['newest' => 'Newest', 'oldest' => 'Oldest', 'highest' => 'Highest', 'lowest' => 'Lowest']; @endphp
                                                <form id="reviews-sort-form" action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                                                    @foreach(request()->except(['sort','reviews_page']) as $k => $v)
                                                        @if(is_array($v))
                                                            @foreach($v as $vv)
                                                                <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}" />
                                                            @endforeach
                                                        @else
                                                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                                        @endif
                                                    @endforeach
                                                    <label class="text-gray-600">Sort:</label>
                                                    <select name="sort" onchange="this.form.submit()" class="rounded px-2 py-1 text-sm border">
                                                        @foreach($sortOptions as $k => $label)
                                                            <option value="{{ $k }}" {{ $selectedSort === $k ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </div>
                                        </div>

                                        <div>
                                            @auth
                                                    @if(auth()->id() != ($ecospace->userID ?? null))
                                                    <a href="{{ route('ecospace.reviews.create', $ecospace->ecospaceID) }}" class="inline-flex items-center gap-2 bg-magenta-secondary hover:bg-magenta-secondary/90 text-white px-4 py-2 rounded-md text-sm">Add Review</a>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm">Sign in</a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>

                                {{-- Reviews list --}}
                                @if(isset($reviews) && $reviews->count())
                                    <div class="space-y-4">
                                        @foreach($reviews as $r)
                                            <div class="border rounded-md p-3">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-start gap-3">
                                                        <div>
                                                            <div class="font-semibold text-gray-900 flex items-center gap-3">
                                                                <span>{{ $r->user->name ?? ('User #' . $r->userID) }}</span>
                                                                @php $filled = (int) floor($r->rating); @endphp
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
                                                                <a href="{{ route('ecospace.reviews.edit', [$ecospace->ecospaceID, $r->reviewID]) }}" class="text-sm text-magenta-secondary">Edit</a>
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

                            {{-- Pros & Cons panel --}}
                            <div class="bg-white p-4 rounded-lg border lg:col-span-1">
                                @php
                                    $prosTotal = method_exists($pros, 'total') ? $pros->total() : (is_countable($pros) ? count($pros) : ($pros->count() ?? 0));
                                    $consTotal = method_exists($cons, 'total') ? $cons->total() : (is_countable($cons) ? count($cons) : ($cons->count() ?? 0));
                                    $selectedPc = request()->query('pc', 'both');
                                @endphp
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Pros &amp; Cons</h3>
                                    </div>

                                    <div class="flex flex-col items-end gap-3">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $selectedPc = request()->query('pc', $selectedPc ?? 'both');
                                                $selectedPcSort = request()->query('pc_sort', 'newest');
                                            @endphp

                                            <form id="pc-filter-form" action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                                                @foreach(request()->except(['pc','pros_page','cons_page']) as $k => $v)
                                                    @if(is_array($v))
                                                        @foreach($v as $vv)
                                                            <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}" />
                                                        @endforeach
                                                    @else
                                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                                    @endif
                                                @endforeach
                                                <label class="text-gray-600">Filter:</label>
                                                <select name="pc" onchange="this.form.submit()" class="rounded px-2 py-1 text-sm border">
                                                    <option value="both" {{ (string)$selectedPc === 'both' ? 'selected' : '' }}>All</option>
                                                    <option value="pros" {{ (string)$selectedPc === 'pros' ? 'selected' : '' }}>Pros</option>
                                                    <option value="cons" {{ (string)$selectedPc === 'cons' ? 'selected' : '' }}>Cons</option>
                                                </select>
                                            </form>

                                            <div class="flex items-center gap-2 text-sm">
                                                @php $pcSortOptions = ['newest' => 'Newest', 'oldest' => 'Oldest']; @endphp
                                                <form id="pc-sort-form" action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                                                    @foreach(request()->except(['pc_sort','pros_page','cons_page']) as $k => $v)
                                                        @if(is_array($v))
                                                            @foreach($v as $vv)
                                                                <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}" />
                                                            @endforeach
                                                        @else
                                                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                                        @endif
                                                    @endforeach
                                                    <label class="text-gray-600">Sort:</label>
                                                    <select name="pc_sort" onchange="this.form.submit()" class="rounded px-2 py-1 text-sm border">
                                                        @foreach($pcSortOptions as $k => $label)
                                                            <option value="{{ $k }}" {{ $selectedPcSort === $k ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </div>
                                        </div>

                                        <div>
                                            @auth
                                                <a href="{{ route('ecospace.proscons.create', $ecospace->ecospaceID) }}" class="inline-flex items-center gap-2 bg-magenta-secondary hover:bg-magenta-secondary/90 text-white px-4 py-2 rounded-md text-sm">Add Pro / Con</a>
                                            @else
                                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm">Sign in</a>
                                            @endauth
                                        </div>

                                        <div class="text-sm text-gray-600">{{ $prosTotal + $consTotal }} total</div>
                                    </div>
                                </div>

                                @if($prosTotal === 0)
                                    <div class="text-gray-600">No pros yet.</div>
                                @else
                                    <div class="mb-3">
                                        <h5 class="text-sm font-semibold text-dark-green mb-2">Pros</h5>
                                        <div class="space-y-2">
                                            @foreach($pros as $pc)
                                                <div class="text-sm border-b pb-2">
                                                    <div class="font-semibold text-gray-800">{{ $pc->description }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">— {{ $pc->user->name ?? ('User #' . $pc->userID) }} · {{ optional($pc->dateCreated)->format('M d, Y') ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(method_exists($pros, 'links'))
                                            <div class="mt-3">{{ $pros->links() }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if($consTotal === 0)
                                    <div class="text-gray-600">No cons yet.</div>
                                @else
                                    <div class="mt-2">
                                        <h5 class="text-sm font-semibold text-red-700 mb-2">Cons</h5>
                                        <div class="space-y-2">
                                            @foreach($cons as $pc)
                                                <div class="text-sm border-b pb-2">
                                                    <div class="font-semibold text-gray-800">{{ $pc->description }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">— {{ $pc->user->name ?? ('User #' . $pc->userID) }} · {{ optional($pc->dateCreated)->format('M d, Y') ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(method_exists($cons, 'links'))
                                            <div class="mt-3">{{ $cons->links() }}</div>
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
</main>
    @endif
</x-app-layout>
