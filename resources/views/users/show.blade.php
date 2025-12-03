<x-app-layout>

    <style>
        /* Custom styles to complement Tailwind (from user's UI) */
        .logo-text { color: #3ca841; font-weight: 700; font-size: 1.75rem; display: flex; align-items: center; }
        .avatar-placeholder { width: 200px; height: 200px; background-color: #d1d5db; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
        .avatar-placeholder-icon { width: 120px; height: 120px; border-radius: 50%; background-color: #e5e7eb; }
    </style>

    <main id="dashboard-main" class="bg-seiun-sky">
        @php
            // Attending events paginator (6 per page)
            $attendingPage = (int) request()->get('attending_page', 1);
            $attendingPerPage = 6;
            if (isset($attendingEvents) && !method_exists($attendingEvents, 'links')) {
                $attColl = collect($attendingEvents);
                $attItems = $attColl->forPage($attendingPage, $attendingPerPage);
                $attendingPag = new \Illuminate\Pagination\LengthAwarePaginator(
                    $attItems,
                    $attColl->count(),
                    $attendingPerPage,
                    $attendingPage,
                    ['path' => request()->url(), 'query' => array_merge(request()->query(), ['attending_page' => $attendingPage])]
                );
            } else {
                $attendingPag = $attendingEvents ?? null;
            }

            // Reviews paginator (3 per page)
            $reviewsPage = (int) request()->get('reviews_page', 1);
            $reviewsPerPage = 3;
            if (isset($userReviews) && !method_exists($userReviews, 'links')) {
                $revColl = collect($userReviews);
                $revItems = $revColl->forPage($reviewsPage, $reviewsPerPage);
                $userReviewsPag = new \Illuminate\Pagination\LengthAwarePaginator(
                    $revItems,
                    $revColl->count(),
                    $reviewsPerPage,
                    $reviewsPage,
                    ['path' => request()->url(), 'query' => array_merge(request()->query(), ['reviews_page' => $reviewsPage])]
                );
            } else {
                $userReviewsPag = $userReviews ?? null;
            }
        @endphp

        <section id="profile-section" class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Profile Header Section -->
        <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-12 mb-10">
            <!-- Avatar -->
            <div class="relative">
                <div class="avatar-placeholder">
                    @if(!empty($user->profile_photo_path))
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                    @elseif(!empty($user->profile_image))
                        <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                    @else
                        <div class="avatar-placeholder-icon"></div>
                    @endif
                </div>
                
            </div>

            <!-- Profile Info Box -->
            <div class="mt-6 md:mt-4">
                <div class="border border-gray-300 bg-white rounded-lg shadow-sm p-6 w-80 text-center">
                    <h1 class="text-2xl font-bold mb-6 text-dark-green">{{ $user->name }}</h1>
                    @auth
                        @if(auth()->id() == $user->id)
                            {{-- Edit profile removed per request --}}
                        @endif
                    @endauth
                    <div class="flex justify-around">
                        <div class="text-center">
                            <span class="block text-3xl font-bold text-dark-green">{{ isset($userReviewsPag) && method_exists($userReviewsPag,'total') ? $userReviewsPag->total() : (is_countable($user->reviews ?? []) ? count($user->reviews) : 0) }}</span>
                            <span class="text-gray-600">Reviews</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-3xl font-bold text-dark-green">{{ $user->ecospaces->count() ?? 0 }}</span>
                            <span class="text-gray-600">Listings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content Tabs -->
        <div>
            <div class="border-b border-gray-300">
                <nav class="profile-tabs flex space-x-4" aria-label="Tabs">
                    <a href="#" class="tab px-4 py-3 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 cursor-pointer" data-tab="reviews">Photos & Reviews</a>
                    @if(auth()->check() && auth()->id() == $user->id)
                        <a href="#" class="tab px-4 py-3 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 cursor-pointer" data-tab="favorites">Favorites</a>
                    @endif
                    <a href="#" class="tab px-4 py-3 font-medium text-dark-green border-b-2 border-dark-green font-semibold cursor-pointer active" data-tab="events">Events</a>
                    <a href="#" class="tab px-4 py-3 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 cursor-pointer" data-tab="ecospaces">EcoSpaces</a>
                    @if(auth()->check() && auth()->id() == $user->id)
                        <a href="#" class="tab px-4 py-3 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-900 cursor-pointer" data-tab="to-attend">To Attend</a>
                    @endif
                </nav>
            </div>

            <div class="py-10">
                <!-- Events Tab Panel -->
                <div id="events" class="tab-panel">
                    <h3 class="text-2xl font-extrabold text-magenta-secondary mb-6">Events</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @if($events->count())
                            @foreach($events as $event)
                                    @php
                                    $firstImg = $event->images->first();
                                    $imgUrl = null;
                                    if ($firstImg) {
                                        try {
                                            if (Storage::disk('public')->exists($firstImg->path)) {
                                                $imgUrl = Storage::url($firstImg->path) . '?t=' . Storage::disk('public')->lastModified($firstImg->path);
                                            }
                                        } catch (\Exception $e) {
                                            $imgUrl = Storage::url($firstImg->path);
                                        }
                                    }
                                @endphp
                                <div class="bg-white/90 border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $event->eventName }}" class="w-full h-40 object-cover" />
                                    @else
                                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="text-lg font-bold text-dark-green">{{ $event->eventName }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $event->eventAdd ?? 'Address unavailable' }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($event->eventDesc ?? 'No description provided.', 100) }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-dark-green font-semibold">{{ $event->priceTier->pricetier ?? 'N/A' }}</span>
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">{{ $event->attendees_count ?? 0 }} going</span>
                                                <span class="text-sm text-gray-600">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</span>
                                                <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="bg-magenta-secondary text-white px-3 py-1 rounded-xl text-sm font-semibold">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center text-magenta-secondary">No events yet.</div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Tab Panel -->
                <div id="reviews" class="tab-panel hidden">
                    <h3 class="text-2xl font-extrabold text-magenta-secondary mb-6">Reviews</h3>
                    @if(isset($userReviews) && $userReviews->count())
                        <div class="space-y-3">
                            @foreach($userReviewsPag as $review)
                                <div class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm flex items-center justify-between">
                                    <div class="text-sm text-dark-green">
                                        @if($review->ecospace)
                                            <a href="{{ route('ecospace', ['name' => $review->ecospace->ecospaceName]) }}" class="font-semibold text-dark-green hover:underline">{{ Str::limit($review->ecospace->ecospaceName, 80) }}</a>
                                            <span class="text-gray-500">&middot; EcoSpace</span>
                                        @elseif($review->event)
                                            <a href="{{ route('events.show', ['id' => $review->event->eventID]) }}" class="font-semibold text-dark-green hover:underline">{{ Str::limit($review->event->eventName, 80) }}</a>
                                            <span class="text-gray-500">&middot; Event</span>
                                        @else
                                            <span class="text-gray-500">No linked resource</span>
                                        @endif

                                        @if(!empty($review->review))
                                            <div class="mt-2 text-gray-700">{{ $review->review }}</div>
                                        @endif
                                    </div>

                                    <div class="text-right text-xs text-gray-500">
                                        <div>{{ optional($review->dateCreated)->format('M d, Y') ?? '' }}</div>
                                        @php $filled = (int) floor($review->rating); @endphp
                                        <div class="text-magenta-secondary font-semibold">
                                            <span class="flex items-center text-sm text-gray-700 font-semibold">
                                                @for($i=1;$i<=5;$i++)
                                                    @if($i <= $filled)
                                                        <span class="text-yellow-500">★</span>
                                                    @else
                                                        <span class="text-gray-300">☆</span>
                                                    @endif
                                                @endfor
                                                <span class="ml-2">{{ number_format($review->rating,1) }}/5</span>
                                            </span>
                                        </div>

                                        @auth
                                            @if(auth()->id() == $review->userID)
                                                <div class="mt-2">
                                                    @if($review->ecospace)
                                                        <a href="{{ route('ecospace.reviews.edit', [$review->ecospace->ecospaceID, $review->reviewID]) }}" class="text-sm text-magenta-secondary mr-3">Edit</a>
                                                        <form action="{{ route('ecospace.reviews.destroy', [$review->ecospace->ecospaceID, $review->reviewID]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            @if(isset($userReviewsPag) && method_exists($userReviewsPag, 'links'))
                                {{ $userReviewsPag->links() }}
                            @endif
                        </div>
                    @else
                        <div class="text-magenta-secondary">No reviews yet.</div>
                    @endif
                </div>

                <!-- Favorites (Bookmarks) Tab Panel -->
                @if(auth()->check() && auth()->id() == $user->id)
                    <div id="favorites" class="tab-panel hidden">
                    <h3 class="text-2xl font-extrabold text-magenta-secondary mb-6">Bookmarks</h3>
                        @php
                            $bmCollection = collect($bookmarks ?? []);
                            $eventBms = $bmCollection->filter(fn($b) => isset($b['type']) && $b['type'] === 'event')->values();
                            $ecoBms = $bmCollection->filter(fn($b) => isset($b['type']) && $b['type'] === 'ecospace')->values();
                            $otherBms = $bmCollection->filter(fn($b) => !isset($b['type']) || !in_array($b['type'], ['event', 'ecospace']))->values();

                            // Bookmarks paginators (3 per page for events and ecospaces)
                            $eventPage = (int) request()->get('event_page', 1);
                            $ecoPage = (int) request()->get('eco_page', 1);
                            $eventPerPage = 3;
                            $ecoPerPage = 3;

                            if (!method_exists($eventBms, 'links')) {
                                $eventItems = $eventBms->forPage($eventPage, $eventPerPage);
                                $eventBmsPag = new \Illuminate\Pagination\LengthAwarePaginator(
                                    $eventItems,
                                    $eventBms->count(),
                                    $eventPerPage,
                                    $eventPage,
                                    ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'event_page']
                                );
                            } else {
                                $eventBmsPag = $eventBms;
                            }

                            if (!method_exists($ecoBms, 'links')) {
                                $ecoItems = $ecoBms->forPage($ecoPage, $ecoPerPage);
                                $ecoBmsPag = new \Illuminate\Pagination\LengthAwarePaginator(
                                    $ecoItems,
                                    $ecoBms->count(),
                                    $ecoPerPage,
                                    $ecoPage,
                                    ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'eco_page']
                                );
                            } else {
                                $ecoBmsPag = $ecoBms;
                            }

                            $hasAnyBookmarks = ($eventBms->count() ?? 0) + ($ecoBms->count() ?? 0) + ($otherBms->count() ?? 0);
                        @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-magenta-secondary mb-4">Event Bookmarks</h4>
                                    <div class="space-y-4">
                                        @if($eventBms->count())
                                            @foreach($eventBmsPag as $bm)
                                                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                                                    <div>
                                                        <p class="font-semibold text-dark-green">{{ $bm['title'] }}</p>
                                                        @if(!empty($bm['note']))
                                                            <p class="text-sm text-gray-600">{{ $bm['note'] }}</p>
                                                        @endif
                                                    </div>
                                                    <a href="{{ $bm['link'] }}" class="text-magenta-secondary font-semibold">Open</a>
                                                </div>
                                            @endforeach
                                            @if(isset($eventBmsPag) && method_exists($eventBmsPag, 'links'))
                                                <div class="mt-3">{{ $eventBmsPag->links() }}</div>
                                            @endif
                                        @else
                                            <div class="text-magenta-secondary">No event bookmarks yet.</div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-magenta-secondary mb-4">EcoSpace Bookmarks</h4>
                                    <div class="space-y-4">
                                        @if($ecoBms->count())
                                            @foreach($ecoBmsPag as $bm)
                                                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                                                    <div>
                                                        <p class="font-semibold text-dark-green">{{ $bm['title'] }}</p>
                                                        @if(!empty($bm['note']))
                                                            <p class="text-sm text-gray-600">{{ $bm['note'] }}</p>
                                                        @endif
                                                    </div>
                                                    <a href="{{ $bm['link'] }}" class="text-magenta-secondary font-semibold">Open</a>
                                                </div>
                                            @endforeach
                                            @if(isset($ecoBmsPag) && method_exists($ecoBmsPag, 'links'))
                                                <div class="mt-3">{{ $ecoBmsPag->links() }}</div>
                                            @endif
                                        @else
                                            <div class="text-magenta-secondary">No ecospace bookmarks yet.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($otherBms->count())
                                <div class="mt-6">
                                    <h4 class="text-lg font-semibold text-magenta-secondary mb-4">Other Bookmarks</h4>
                                    <div class="space-y-4">
                                        @foreach($otherBms as $bm)
                                            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-dark-green">{{ $bm['title'] }}</p>
                                                </div>
                                                <a href="{{ $bm['link'] }}" class="text-magenta-secondary font-semibold">Open</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                    </div>
                @endif

                <!-- EcoSpaces Tab Panel -->
                <div id="ecospaces" class="tab-panel hidden">
                    <h3 class="text-2xl font-extrabold text-magenta-secondary mb-6">EcoSpaces</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @if($user->ecospaces->count())
                            @foreach($user->ecospaces as $ecospace)
                                @php
                                    // Show the most recently uploaded image so profile cards update when new images are added
                                    $firstImg = $ecospace->images()->orderByDesc('esImageID')->first();
                                    $imgUrl = null;
                                    if ($firstImg) {
                                        try {
                                            if (Storage::disk('public')->exists($firstImg->path)) {
                                                $imgUrl = Storage::url($firstImg->path) . '?t=' . Storage::disk('public')->lastModified($firstImg->path);
                                            } else {
                                                $imgUrl = Storage::url($firstImg->path);
                                            }
                                        } catch (\Exception $e) {
                                            $imgUrl = Storage::url($firstImg->path);
                                        }
                                    }
                                @endphp
                                <div class="bg-white/90 border border-gray-200 rounded-2xl shadow-lg overflow-hidden relative">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-40 object-cover" />
                                    @else
                                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                                    @endif
                                    @php
                                        // If no ecospace image, try fallback to the newest review image (like the detail page)
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
                                            $imgUrl = null;
                                            $imgSource = null;
                                            $imgPath = null;
                                        }

                                        // Show debug overlay only to the ecospace owner (remove APP_DEBUG gating)
                                        $debugShow = (auth()->check() && auth()->id() == ($ecospace->userID ?? null));
                                    @endphp
                                    @if($debugShow)
                                        <div class="absolute left-2 top-2 bg-white/80 text-xs text-gray-800 p-2 rounded border shadow-sm z-30">
                                            <div><strong>Source:</strong> {{ $imgSource ?? 'none' }}</div>
                                            <div><strong>DB path:</strong> {{ $imgPath ?? 'none' }}</div>
                                            <div><strong>Exists:</strong> {{ $imgPath ? (Storage::disk('public')->exists($imgPath) ? 'yes' : 'no') : 'n/a' }}</div>
                                            <div class="break-words"><strong>URL:</strong> {{ $imgPath ? Storage::url($imgPath) : 'n/a' }}</div>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="text-lg font-bold text-dark-green">{{ $ecospace->ecospaceName }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $ecospace->ecospaceAdd ?? 'Address unavailable' }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($ecospace->ecospaceDesc ?? 'No description provided.', 100) }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-dark-green font-semibold">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</span>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="bg-magenta-secondary text-white px-3 py-1 rounded-xl text-sm font-semibold">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center text-magenta-secondary">No ecospaces yet.</div>
                        @endif
                    </div>
                </div>

                <!-- To-Attend Tab Panel -->
                @if(auth()->check() && auth()->id() == $user->id)
                    <div id="to-attend" class="tab-panel hidden">
                    <h3 class="text-2xl font-extrabold text-magenta-secondary mb-6">Attending</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @if(!empty($attendingEvents) && $attendingEvents->count())
                            @php $attIter = $attendingPag ?? $attendingEvents; @endphp
                            @foreach($attIter as $event)
                                @php
                                    $firstImg = $event->images->first();
                                    $imgUrl = null;
                                    if ($firstImg) {
                                        try {
                                            if (Storage::disk('public')->exists($firstImg->path)) {
                                                $imgUrl = Storage::url($firstImg->path) . '?t=' . Storage::disk('public')->lastModified($firstImg->path);
                                            }
                                        } catch (\Exception $e) {
                                            $imgUrl = Storage::url($firstImg->path);
                                        }
                                    }
                                @endphp
                                <div class="bg-white/90 border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $event->eventName }}" class="w-full h-40 object-cover" />
                                    @else
                                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="text-lg font-bold text-dark-green">{{ $event->eventName }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $event->eventAdd ?? 'Address unavailable' }}</p>
                                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($event->eventDesc ?? 'No description provided.', 100) }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-dark-green font-semibold">{{ $event->priceTier->pricetier ?? 'N/A' }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">{{ $event->attendees_count ?? 0 }} going</span>
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm text-gray-600">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</span>
                                                <a href="{{ route('events.show', ['id' => $event->eventID]) }}" class="bg-magenta-secondary text-white px-3 py-1 rounded-xl text-sm font-semibold">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(isset($attendingPag) && method_exists($attendingPag, 'links'))
                                <div class="col-span-full mt-4">{{ $attendingPag->links() }}</div>
                            @endif
                        @else
                            <div class="col-span-full text-center text-magenta-secondary">Not attending any events.</div>
                        @endif
                    </div>
                @endif
                </div>
            </div>
        </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.profile-tabs .tab');
            const panels = document.querySelectorAll('.tab-panel');

            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = tab.getAttribute('data-tab');
                    const targetPanel = document.getElementById(targetId);

                    // remove active styling from all tabs and add inactive classes
                    tabs.forEach(t => {
                        t.classList.remove('text-dark-green','border-dark-green','font-semibold','active');
                        t.classList.add('text-gray-600','border-transparent');
                    });

                    // hide all panels
                    panels.forEach(p => p.classList.add('hidden'));

                    // apply active styling to clicked tab
                    tab.classList.remove('text-gray-600','border-transparent');
                    tab.classList.add('text-dark-green','border-dark-green','font-semibold','active');

                    // show the target panel
                    if (targetPanel) targetPanel.classList.remove('hidden');
                });
            });

            // On load: if the URL has pagination query params, activate the related tab
            const params = new URLSearchParams(window.location.search);
            const tabFromParams = (() => {
                if (params.has('event_page') || params.has('eco_page')) return 'favorites';
                if (params.has('attending_page')) return 'to-attend';
                if (params.has('reviews_page') || params.has('user_reviews_page')) return 'reviews';
                return null;
            })();

            if (tabFromParams) {
                const tabToActivate = document.querySelector(`.profile-tabs .tab[data-tab="${tabFromParams}"]`);
                const panelToShow = document.getElementById(tabFromParams);
                if (tabToActivate && panelToShow) {
                    // reset all tabs/panels then activate
                    tabs.forEach(t => {
                        t.classList.remove('text-dark-green','border-dark-green','font-semibold','active');
                        t.classList.add('text-gray-600','border-transparent');
                    });
                    panels.forEach(p => p.classList.add('hidden'));

                    tabToActivate.classList.remove('text-gray-600','border-transparent');
                    tabToActivate.classList.add('text-dark-green','border-dark-green','font-semibold','active');
                    panelToShow.classList.remove('hidden');
                }
            }

            

            // Edit profile UI removed — no inline edit handlers needed
        });
    </script>

</x-app-layout>
