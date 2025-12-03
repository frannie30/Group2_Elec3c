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
                        <h1 class="text-3xl font-bold text-gray-900">Pending Events (Create)</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600">Sort:</span>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="px-3 py-1 rounded-md text-sm {{ (isset($sort) && $sort === 'newest') || !isset($sort) ? 'bg-gray-200' : 'bg-white' }}">Newest</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" class="px-3 py-1 rounded-md text-sm {{ isset($sort) && $sort === 'oldest' ? 'bg-gray-200' : 'bg-white' }}">Oldest</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User</th>
                                <!-- User ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Event Type</th>
                                <!-- EventType ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Event</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Address</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Date</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Images</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold min-w-[16rem]">Description</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Price Tier</th>
                                <!-- PriceTier ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Status</th>
                                <!-- Status ID column removed -->
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Is Done</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($events) && $events->count())
                                @foreach($events as $event)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 border-b align-top">{{ $event->user->name ?? 'Unknown' }}</td>
                                        <!-- userID cell removed -->
                                        <td class="px-6 py-4 border-b align-top">{{ $event->eventType->eventTypeName ?? 'N/A' }}</td>
                                        <!-- eventTypeID cell removed -->
                                        <td class="px-6 py-4 border-b align-top">{{ $event->eventName }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ $event->eventAdd ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ optional($event->eventDate)->format('M d, Y H:i') ?? $event->eventDate }}</td>
                                        <td class="px-6 py-4 border-b align-top">
                                            @php
                                                $imgs = $event->images->pluck('path')->map(fn($p) => Storage::url($p))->toArray();
                                            @endphp
                                            @if(count($imgs))
                                                <img src="{{ $imgs[0] }}" alt="event image" class="w-24 h-16 object-cover rounded-md border" />
                                            @else
                                                <span class="text-sm text-gray-400">No images</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b align-top min-w-[16rem]">{{ $event->eventDesc }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ $event->priceTier->pricetier ?? 'N/A' }}</td>
                                        <!-- priceTierID cell removed -->
                                        <td class="px-6 py-4 border-b align-top">{{ $event->status->status ?? 'N/A' }}</td>
                                        <!-- statusID cell removed -->
                                        <td class="px-6 py-4 border-b align-top">{{ $event->isDone ? 'Yes' : 'No' }}</td>
                                        <td class="px-6 py-4 border-b align-top">
                                            <div class="flex flex-col items-stretch gap-3">
                                                <form method="POST" action="{{ route('admin.event.approve', $event->eventID) }}">@csrf
                                                    <button class="w-full inline-flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-green-700 transition focus:outline-none">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.event.remove', $event->eventID) }}" data-confirm="Archive this event? This will remove it from the public listing." class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition focus:outline-none">Decline</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="px-6 py-4 border-b text-center text-gray-500">No pending events.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ isset($events) ? $events->links() : '' }}</div>
            </div>
        </main>
    </div>

</x-app-layout>
