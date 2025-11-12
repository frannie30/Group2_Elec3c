<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-md shadow-2xl sm:rounded-2xl p-10 border border-pink-200">
                <a href="{{ route('index.index') }}"
                   class="self-start inline-flex items-center justify-center w-10 h-10 text-pink-600 hover:text-pink-800 font-bold rounded-full transition duration-200 mb-4 text-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2 bg-pink-100 hover:bg-pink-200 shadow"
                   title="Back to Dashboard"
                   onclick="return confirm('Are you sure you want to cancel editing? Any unsaved changes will be lost.');"
                   aria-label="Back to Dashboard">
                    &larr;
                </a>
                <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700">Edit Event</h2>

                <form method="POST" action="{{ route('events.update', $event->eventID) }}" enctype="multipart/form-data"
                      onsubmit="return confirm('Are you sure you want to update this event?');">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-pink-800 font-semibold mb-2">Event Name</label>
                        <input type="text" name="eventName" value="{{ old('eventName', $event->eventName) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-pink-800 font-semibold mb-2">Event Type</label>
                        <select name="eventTypeID" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50">
                            <option value="">-- Keep current --</option>
                            @foreach($eventtypes as $et)
                                <option value="{{ $et->eventTypeID }}" {{ (old('eventTypeID', $event->eventTypeID) == $et->eventTypeID) ? 'selected' : '' }}>{{ $et->eventTypeName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-pink-800 font-semibold mb-2">Event Date & Time</label>
                            <input type="datetime-local" name="eventDate" value="{{ old('eventDate', 
                                optional(
                                    \Carbon\Carbon::parse($event->eventDate ?? null)
                                )->format('Y-m-d\TH:i')
                            ) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50" />
                        </div>
                        <div>
                            <label class="block text-pink-800 font-semibold mb-2">Address</label>
                            <input type="text" name="eventAdd" value="{{ old('eventAdd', $event->eventAdd) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-pink-800 font-semibold mb-2">Price Tier</label>
                        <select name="priceTierID" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50">
                            <option value="">-- Keep current --</option>
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $event->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="eventDesc" class="block text-pink-800 font-semibold mb-2">Description</label>
                        <textarea id="eventDesc" name="eventDesc" rows="4" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">{{ old('eventDesc', $event->eventDesc) }}</textarea>
                    </div>

                    {{-- Existing images: thumbnails + remove checkboxes --}}
                    <div class="mt-4">
                        <label class="block text-pink-800 font-semibold mb-2">Existing Images</label>
                        @if($event->images && $event->images->count())
                            <div class="flex items-center gap-4 mb-2">
                                @foreach($event->images as $img)
                                    <div class="flex flex-col items-center">
                                        <img src="{{ Storage::url($img->path) }}" alt="image" class="w-32 h-20 object-cover rounded-md border mb-2" />
                                        <label class="text-sm text-pink-700">
                                            <input type="checkbox" name="images_to_remove[]" value="{{ $img->eventImageID }}"> Remove
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-pink-500">No images uploaded.</div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <label class="block text-pink-800 font-semibold mb-2">Add Images</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50" />
                        <p class="text-sm text-pink-500 mt-2">You can upload additional images (max 5MB each).</p>
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                        <button type="submit" class="bg-pink-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-pink-700 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-pink-400">
                            Update Event
                        </button>
                        <a href="{{ route('index.index') }}" class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-400 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-pink-400 text-center"
                           onclick="return confirm('Are you sure you want to cancel editing? Any unsaved changes will be lost.');">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
