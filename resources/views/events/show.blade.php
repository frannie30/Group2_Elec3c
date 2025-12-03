<x-app-layout>


    @if(empty($event))
        <div class="py-12">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100 text-center text-gray-700">
                    <h3 class="text-xl font-semibold">No event selected</h3>
                    <p class="mt-2">We couldn't find the event you requested. Try selecting one from the list.</p>
                    <div class="mt-4"><a href="{{ route('events.index') }}" class="text-green-600 hover:underline">Back to events</a></div>
                </div>
            </div>
        </div>
    @else
    <div class="py-12 bg-green-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4">
                    <div class="flash-message bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-md sm:rounded-2xl p-6 border border-gray-100">
                @php
                    $imgs = $event->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                @endphp

                <div class="mb-6">
                    @if(count($imgs))
                        <div id="detail-carousel" class="relative" data-images='@json($imgs)' data-index="0">
                            <img id="detail-img" src="{{ $imgs[0] }}" alt="{{ $event->eventName }}" class="w-full h-72 lg:h-96 object-contain rounded-lg bg-gray-100" />
                            <button onclick="detailPrev()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">‹</button>
                            <button onclick="detailNext()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">›</button>

                            {{-- Thumbnails --}}
                            <div id="detail-thumbs" class="mt-3 flex gap-2 overflow-x-auto">
                                @foreach($imgs as $i => $thumb)
                                    <button type="button" class="thumb-btn rounded-md border-2" data-index="{{ $i }}" aria-label="View image {{ $i + 1 }}">
                                        <img src="{{ $thumb }}" alt="thumb-{{ $i }}" class="w-20 h-14 object-contain rounded-md bg-gray-100" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-400">No images available</div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <h3 class="text-4xl font-bold text-gray-900">{{ $event->eventName }}</h3>
                        <p class="text-base text-gray-700 mt-3">{{ $event->eventDesc ?? 'No description provided.' }}</p>

                        <div class="mt-6 text-sm text-gray-700">
                            <div><strong>Date & Time:</strong> {{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</div>
                            <div><strong>Address:</strong> {{ $event->eventAdd ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm">
                        <div class="mb-4"><strong class="text-gray-800">Price Tier</strong>
                            <div class="text-gray-600">{{ $event->priceTier->pricetier ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-4"><strong class="text-gray-800">Status</strong>
                            <div class="text-gray-600">{{ $event->status->status ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-4"><strong class="text-gray-800">Organizer</strong>
                            <div class="text-gray-600">{{ $event->user->name ?? 'Unknown' }}</div>
                        </div>

                        <div class="mt-4">
                            <div class="space-y-2">
                                @auth
                                    @if(auth()->id() != ($event->userID ?? null))
                                        <form method="POST" action="{{ route('events.attendance.toggle', $event->eventID) }}">
                                            @csrf
                                            <button type="submit" class="w-full block text-center bg-green-600 text-white px-4 py-2 rounded-md">
                                                {{ $isGoing ? 'Attending' : 'Attend' }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('user.events.edit', $event->eventID) }}" class="block text-center bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full block text-center bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-md">Sign in to attend</a>
                                @endauth
                            </div>

                            <div class="mt-2">
                                <a href="{{ url()->previous() }}" class="block text-center bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Guestlist (organizer only) --}}
        @auth
                @if(auth()->id() == $event->userID)
                <div class="w-full px-4 sm:px-6 lg:px-8 mt-8">
                    <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                        <h4 class="text-2xl font-bold text-gray-900 mb-3">Guestlist</h4>
                        @if(!empty($guestlist) && $guestlist->count())
                            <ul class="space-y-3">
                                @foreach($guestlist as $g)
                                    <li class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $g->user->name ?? ('User #' . $g->userID) }}</p>
                                            @if(!empty($g->dateCreated))
                                                <p class="text-xs text-gray-500">Joined: {{ \Illuminate\Support\Carbon::parse($g->dateCreated)->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                        <span class="text-sm px-2 py-1 rounded-full {{ $g->isGoing ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $g->isGoing ? 'Going' : 'Not going' }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-4">
                                {{ $guestlist->links() }}
                            </div>
                        @else
                            <div class="text-gray-600">No attendees yet.</div>
                        @endif
                    </div>
                </div>
            @endif
        @endauth

        {{-- carousel & attendance scripts --}}
        <script>
            (function(){
                const container = document.getElementById('detail-carousel');
                const imgs = container ? JSON.parse(container.getAttribute('data-images') || '[]') : [];
                let idx = parseInt(container?.getAttribute('data-index') || '0', 10) || 0;

                function updateMain(index) {
                    const el = document.getElementById('detail-img');
                    if (!el || !imgs.length) return;
                    idx = (index + imgs.length) % imgs.length;
                    el.src = imgs[idx];
                    if (container) container.setAttribute('data-index', idx);

                    // Update thumbnail active state
                    const thumbs = document.querySelectorAll('#detail-thumbs .thumb-btn');
                    thumbs.forEach(t => t.classList.remove('ring-2','ring-green-600'));
                    const active = document.querySelector('#detail-thumbs .thumb-btn[data-index="' + idx + '"]');
                    if (active) active.classList.add('ring-2','ring-green-600');
                }

                window.detailNext = function() {
                    if (!imgs.length) return;
                    updateMain(idx + 1);
                }

                window.detailPrev = function() {
                    if (!imgs.length) return;
                    updateMain(idx - 1);
                }

                // Thumbnails click handlers
                document.addEventListener('DOMContentLoaded', function() {
                    const thumbs = document.querySelectorAll('#detail-thumbs .thumb-btn');
                    thumbs.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const i = parseInt(btn.getAttribute('data-index'), 10);
                            updateMain(i);
                        });
                    });

                    // mark initial thumb
                    updateMain(idx);
                });
            })();

            // Attendance handled server-side via standard POST form; no JS required here.
        </script>
    </div>
    @endif
</x-app-layout>
