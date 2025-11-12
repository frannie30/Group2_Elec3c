<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between py-6 px-4 bg-gradient-to-r from-pink-100 via-white to-pink-200 rounded-b-3xl shadow-lg">
            <h2 class="font-extrabold text-4xl text-pink-700 tracking-tight drop-shadow-lg">
                {{ __('Dashboard') }}
            </h2>
            <span class="text-pink-500 font-semibold text-lg hidden md:block">Discover Filipino Provinces & Dishes</span>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-300 text-green-800 font-semibold shadow">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-300 text-red-800 font-semibold shadow">
            {{ session('error') }}
        </div>
    @endif

   
            <!-- Search & Filter (now above Province Grid) -->

            <!-- EcoSpace Grid -->
            <div id="ecospace-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
                @if(isset($ecospaces) && $ecospaces->count())
                    @foreach($ecospaces as $ecospace)
                        @php
                            $firstImg = $ecospace->images->first();
                            $imgUrl = $firstImg ? Storage::url($firstImg->path) : null;
                        @endphp
                        <div class="bg-white/90 border border-pink-200 rounded-2xl shadow-lg overflow-hidden">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $ecospace->ecospaceName }}" class="w-full h-44 object-cover" />
                            @else
                                <div class="w-full h-44 bg-pink-50 flex items-center justify-center text-pink-400">No image</div>
                            @endif
                            <div class="p-4">
                                <h4 class="text-xl font-bold text-pink-800">{{ $ecospace->ecospaceName }}</h4>
                                <p class="text-sm text-pink-600 mb-2">{{ $ecospace->ecospaceAdd ?? 'Address unavailable' }}</p>
                                <p class="text-sm text-pink-500 mb-3">{{ Str::limit($ecospace->ecospaceDesc ?? 'No description provided.', 120) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-pink-700 font-semibold">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</span>
                                    <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="bg-pink-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-pink-600">No ecospaces found.</div>
                @endif
            </div>
            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                @if(isset($ecospaces))
                    {{ $ecospaces->links() }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
