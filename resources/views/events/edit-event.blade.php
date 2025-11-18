<x-app-layout>


    <div class="py-12 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-pink-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700">Edit Event</h2>

                <form method="POST" action="{{ route('user.events.update', $event->eventID) }}" enctype="multipart/form-data" onsubmit="return confirm('Save changes to this event?');">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="eventName" class="block text-pink-800 font-semibold mb-2">Event Name</label>
                        <input type="text" id="eventName" name="eventName" value="{{ old('eventName', $event->eventName) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                        @error('eventName')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="eventTypeID" class="block text-pink-800 font-semibold mb-2">Event Type</label>
                        <select id="eventTypeID" name="eventTypeID" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                            <option value="">-- Select event type --</option>
                            @foreach($eventtypes as $et)
                                <option value="{{ $et->eventTypeID }}" {{ (old('eventTypeID', $event->eventTypeID) == $et->eventTypeID) ? 'selected' : '' }}>{{ $et->eventTypeName }}</option>
                            @endforeach
                        </select>
                        @error('eventTypeID')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="eventDate" class="block text-pink-800 font-semibold mb-2">Event Date & Time</label>
                            <input type="datetime-local" id="eventDate" name="eventDate" value="{{ old('eventDate', optional($event->eventDate)->format('Y-m-d\TH:i') ?? $event->eventDate) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                            @error('eventDate')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="eventAdd" class="block text-pink-800 font-semibold mb-2">Address</label>
                            <input type="text" id="eventAdd" name="eventAdd" value="{{ old('eventAdd', $event->eventAdd) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                            @error('eventAdd')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="priceTierID" class="block text-pink-800 font-semibold mb-2">Price Tier</label>
                        <select id="priceTierID" name="priceTierID" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                            <option value="">-- Select price tier --</option>
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $event->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                            @endforeach
                        </select>
                        @error('priceTierID')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="eventDesc" class="block text-pink-800 font-semibold mb-2">Description</label>
                        <textarea id="eventDesc" name="eventDesc" rows="4" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">{{ old('eventDesc', $event->eventDesc) }}</textarea>
                        @error('eventDesc')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-pink-800 font-semibold mb-2">Existing Images</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($event->images as $img)
                                <div class="border p-2 rounded">
                                    <img src="{{ Storage::url($img->path) }}" alt="img" class="w-full h-28 object-cover rounded mb-2" />
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="images_to_remove[]" value="{{ $img->eventImageID ?? $img->id }}" class="mr-2"> Remove
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="images" class="block text-pink-800 font-semibold mb-2">Upload New Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                        @error('images')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        @error('images.*')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex justify-center gap-4 mt-8">
                        <button type="submit" class="bg-pink-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-pink-700 transition">Save Changes</button>
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
