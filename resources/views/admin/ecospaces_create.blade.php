<x-app-layout>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --brand-green: #2F9E4A;
            --brand-maroon: #166534;
            --brand-bg-admin-main: #E6F7EA;
            --brand-bg-sidebar: #FFFFFF;
        }
        .font-inter{ font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .bg-brand-bg-admin-main{ background-color: var(--brand-bg-admin-main); }
        .bg-brand-bg-sidebar{ background-color: var(--brand-bg-sidebar); }
        .brand-accent{ color: var(--brand-maroon); }
        .min-h-screen > nav { display: none !important; }
    </style>

    <div class="flex min-h-screen font-inter bg-brand-bg-admin-main">
        @include('admin._sidebar')

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-lg p-8 w-full mx-auto">
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h1 class="text-3xl font-bold text-gray-900">Pending EcoSpaces (Create)</h1>
                    </div>
                    
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User</th>
                                <!-- User ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Address</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Price Tier</th>
                                <!-- PriceTier ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status</th>
                                <!-- Status ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Name</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Images</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold min-w-[16rem]">Description</th>
                                <!-- Opening/closing hours and days opened columns removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Created</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Updated</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ecospaces as $ecospace)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 border-b align-top">{{ $ecospace->user->name ?? 'Unknown' }}</td>
                                <!-- userID cell removed -->
                                <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceAdd ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 border-b align-top">{{ $ecospace->priceTier->pricetier ?? 'N/A' }}</td>
                                <!-- priceTierID cell removed -->
                                <td class="px-6 py-4 border-b align-top">{{ $ecospace->status->status ?? 'N/A' }}</td>
                                <!-- statusID cell removed -->
                                <td class="px-6 py-4 border-b align-top">{{ $ecospace->ecospaceName }}</td>
                                <td class="px-6 py-4 border-b align-top">
                                    @php
                                        $imgs = $ecospace->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                                    @endphp
                                    @if(count($imgs))
                                        <div id="carousel-{{ $ecospace->ecospaceID }}" class="flex items-center gap-2" data-images='@json($imgs)' data-index="0">
                                            <button type="button" onclick="carouselPrev({{ $ecospace->ecospaceID }})" class="px-2 py-1 bg-white border rounded-md">‹</button>
                                            <img id="carousel-img-{{ $ecospace->ecospaceID }}" src="{{ $imgs[0] }}" alt="ecospace image" class="w-24 h-16 object-cover rounded-md border" />
                                            <button type="button" onclick="carouselNext({{ $ecospace->ecospaceID }})" class="px-2 py-1 bg-white border rounded-md">›</button>
                                            <span id="carousel-count-{{ $ecospace->ecospaceID }}" class="text-sm text-gray-500">1/{{ count($imgs) }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No images</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b align-top min-w-[16rem]">{{ $ecospace->ecospaceDesc }}</td>
                                <!-- Opening/closing hours and days opened removed from table -->
                                <td class="px-6 py-4 border-b align-top">
                                    @if($ecospace->dateCreated)
                                        {{ \Carbon\Carbon::parse($ecospace->dateCreated)->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b align-top">
                                    @if($ecospace->dateUpdated)
                                        {{ \Carbon\Carbon::parse($ecospace->dateUpdated)->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b align-top">
                                    <div class="flex flex-col items-stretch gap-3">
                                        <form method="POST" action="{{ route('admin.ecospace.approve', $ecospace->ecospaceID) }}">@csrf
                                            <button class="w-full flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-green-700 transition focus:outline-none">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.ecospace.remove', $ecospace->ecospaceID) }}" data-confirm="Archive this EcoSpace? This will remove it from the public listing." class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none">Decline</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 border-b text-center text-gray-500">No pending ecospaces.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $ecospaces->links() }}</div>
            </div>
        </main>
    </div>

</x-app-layout>
