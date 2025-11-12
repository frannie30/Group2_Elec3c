<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-pink-700 leading-tight text-center py-4">
            {{ $ecospace->ecospaceName ?? 'Ecospace' }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 shadow-2xl sm:rounded-2xl p-6 border border-pink-200">
                {{-- Images carousel --}}
                @php
                    $imgs = $ecospace->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                @endphp
                <div class="mb-6">
                    @if(count($imgs))
                        <div id="detail-carousel" class="relative">
                            <img id="detail-img" src="{{ $imgs[0] }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-72 object-cover rounded-md" />
                            <button onclick="detailPrev()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">‹</button>
                            <button onclick="detailNext()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2">›</button>
                        </div>
                    @else
                        <div class="w-full h-72 bg-pink-50 flex items-center justify-center text-pink-400">No images available</div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-2xl font-bold text-pink-800">{{ $ecospace->ecospaceName }}</h3>
                        <p class="text-sm text-pink-600 mt-2">{{ $ecospace->ecospaceDesc ?? 'No description provided.' }}</p>

                        <div class="mt-4 text-sm text-pink-700">
                            <div><strong>Address:</strong> {{ $ecospace->ecospaceAdd ?? 'N/A' }}</div>
                            <div><strong>Opening Hours:</strong> {{ $ecospace->openingHours ?? 'N/A' }} - {{ $ecospace->closingHours ?? 'N/A' }}</div>
                            <div><strong>Days Opened:</strong> {{ $ecospace->daysOpened ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="md:col-span-1 bg-pink-50 p-4 rounded-md">
                        <div class="mb-3"><strong class="text-pink-700">Price Tier</strong>
                            <div class="text-pink-600">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3"><strong class="text-pink-700">Status</strong>
                            <div class="text-pink-600">{{ $ecospace->status->status ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3"><strong class="text-pink-700">Owner</strong>
                            <div class="text-pink-600">{{ $ecospace->user->name ?? 'Unknown' }}</div>
                        </div>

                        <div class="mt-4">
                            <div class="space-y-2">
                                <button type="button" onclick="alert('Add review is not implemented yet.')" class="w-full block text-center bg-white border border-pink-600 text-pink-600 px-4 py-2 rounded-md">Add Review</button>
                                <button type="button" onclick="alert('Add photo is not implemented yet.')" class="w-full block text-center bg-pink-600 text-white px-4 py-2 rounded-md">Add Photo</button>
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

                                        @if($isOwnerFirst)
                                            <button type="button" class="w-full block text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-md cursor-default" disabled>Edit</button>
                                        @else
                                            <a href="{{ route('edit.index', $ecospace->ecospaceID) }}" class="block text-center bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
                                        @endif
                                    @endif
                                @endauth

                                <a href="{{ url()->previous() }}" class="block text-center bg-pink-600 text-white px-4 py-2 rounded-md">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- carousel script --}}
        <script>
            const imgs = @json($imgs);
            let idx = 0;
            function detailNext() {
                if (!imgs.length) return;
                idx = (idx + 1) % imgs.length;
                document.getElementById('detail-img').src = imgs[idx];
            }
            function detailPrev() {
                if (!imgs.length) return;
                idx = (idx - 1 + imgs.length) % imgs.length;
                document.getElementById('detail-img').src = imgs[idx];
            }
        </script>
    </div>
</x-app-layout>
