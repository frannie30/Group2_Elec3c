<x-app-layout>


    <div class="py-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-emerald-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-emerald-700">Add an Event</h2>
                <p class="text-emerald-600 text-center mb-8">Fill out the form below. Fields marked with <span class="text-emerald-700 font-bold">*</span> are required.</p>

                <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data"
                      onsubmit="return confirm('Are you sure you want to submit this event?');">
                    @csrf

                    <!-- Event Name -->
                    <div class="mb-6">
                        <label for="eventName" class="block text-emerald-800 font-semibold mb-2">
                            <span class="text-emerald-700 font-bold">*</span> Event Name
                        </label>
                        <input type="text" id="eventName" name="eventName" value="{{ old('eventName') }}"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400"
                               placeholder="e.g. Community Cleanup">
                        @error('eventName')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div class="mb-6">
                        <label for="eventTypeID" class="block text-emerald-800 font-semibold mb-2">Event Type</label>
                        <select id="eventTypeID" name="eventTypeID" required class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                            <option value="" disabled selected>-- Select event type --</option>
                            @if(isset($eventtypes))
                                @foreach($eventtypes as $et)
                                    <option value="{{ $et->eventTypeID ?? $et['eventTypeID'] }}" {{ old('eventTypeID') == ($et->eventTypeID ?? $et['eventTypeID']) ? 'selected' : '' }}>
                                        {{ $et->eventTypeName ?? $et['eventTypeName'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('eventTypeID')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Event Date and Time -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="eventDate" class="block text-emerald-800 font-semibold mb-2">Event Date & Time</label>
                            <input type="datetime-local" id="eventDate" name="eventDate" value="{{ old('eventDate') }}"
                                class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                            @error('eventDate')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="eventAdd" class="block text-emerald-800 font-semibold mb-2">Address</label>
                            <input type="text" id="eventAdd" name="eventAdd" value="{{ old('eventAdd') }}"
                                   class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400"
                                   placeholder="Street, City, ZIP">
                            @error('eventAdd')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Price Tier select (status is forced to pending via controller) -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <input type="hidden" name="statusID" value="1">
                        <div>
                            <label for="priceTierID" class="block text-emerald-800 font-semibold mb-2">Price Tier</label>
                            <select id="priceTierID" name="priceTierID" required class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                                <option value="" disabled selected>-- Select price tier --</option>
                                @if(isset($pricetiers))
                                    @foreach($pricetiers as $pt)
                                        <option value="{{ $pt->priceTierID ?? $pt['priceTierID'] }}" {{ old('priceTierID') == ($pt->priceTierID ?? $pt['priceTierID']) ? 'selected' : '' }}>
                                            {{ $pt->pricetier ?? $pt['pricetier'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('priceTierID')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="eventDesc" class="block text-emerald-800 font-semibold mb-2">Description</label>
                        <textarea id="eventDesc" name="eventDesc" rows="4"
                                  class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400"
                                  placeholder="Describe the event (optional)">{{ old('eventDesc') }}</textarea>
                        @error('eventDesc')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images upload -->
                    <div class="mb-6">
                           <label for="images" class="block text-emerald-800 font-semibold mb-2">Upload Images</label>
                           <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50">
                           <p class="text-sm text-emerald-500 mt-2">You may upload multiple images (jpg, png, gif, webp). Max size per image 5MB.</p>
                        @error('images')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center gap-4 mt-8">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-emerald-700 hover:scale-105 transition">
                            Submit Event
                        </button>
                        <a href="{{ route('index.index') }}"
                           class="bg-gray-300 text-emerald-800 px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-400 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-emerald-400 text-center"
                           onclick="return confirm('Are you sure you want to cancel? Any unsaved changes will be lost.');">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
