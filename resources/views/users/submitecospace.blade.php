<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-pink-700 leading-tight text-center py-4">
            {{ __('Submit a New Ecospace') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-pink-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700">Add an Ecospace</h2>
                <p class="text-pink-600 text-center mb-8">Fill out the form below. Fields marked with <span class="text-pink-700 font-bold">*</span> are required.</p>

                <form method="POST" action="{{ route('ecospaces.store') }}" enctype="multipart/form-data"
                      onsubmit="return confirm('Are you sure you want to submit this ecospace?');">
                    @csrf

                    <!-- Ecospace Name -->
                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-pink-800 font-semibold mb-2">
                            <span class="text-pink-700 font-bold">*</span> Ecospace Name
                        </label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName') }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400"
                               placeholder="e.g. Riverside Community Garden">
                        @error('ecospaceName')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label for="ecospaceAdd" class="block text-pink-800 font-semibold mb-2">
                            <span class="text-pink-700 font-bold">*</span> Address
                        </label>
                        <input type="text" id="ecospaceAdd" name="ecospaceAdd" value="{{ old('ecospaceAdd') }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400"
                               placeholder="Street, City, ZIP">
                        @error('ecospaceAdd')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-pink-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4"
                                  class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400"
                                  placeholder="Describe the ecospace (optional)">{{ old('ecospaceDesc') }}</textarea>
                        @error('ecospaceDesc')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Opening / Closing Hours -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="openingHours" class="block text-pink-800 font-semibold mb-2">Opening Hours</label>
                            <input type="time" id="openingHours" name="openingHours" value="{{ old('openingHours') }}"
                                   class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                            @error('openingHours')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="closingHours" class="block text-pink-800 font-semibold mb-2">Closing Hours</label>
                            <input type="time" id="closingHours" name="closingHours" value="{{ old('closingHours') }}"
                                   class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                            @error('closingHours')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Days Opened -->
                    <div class="mb-6">
                        <label for="daysOpened" class="block text-pink-800 font-semibold mb-2">Days Opened</label>
                        <input type="text" id="daysOpened" name="daysOpened" value="{{ old('daysOpened') }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400"
                               placeholder="e.g. Mon-Fri">
                        @error('daysOpened')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Tier select (status is forced to pending via controller) -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <input type="hidden" name="statusID" value="1">
                        <div>
                            <label for="priceTierID" class="block text-pink-800 font-semibold mb-2">Price Tier</label>
                            <select id="priceTierID" name="priceTierID" required class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
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

                    <!-- Images upload -->
                    <div class="mb-6">
                        <label for="images" class="block text-pink-800 font-semibold mb-2">Upload Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
                        <p class="text-sm text-pink-500 mt-2">You may upload multiple images (jpg, png, gif, webp). Max size per image 5MB.</p>
                        @error('images')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center gap-4 mt-8">
                        <button type="submit" class="bg-pink-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-pink-700 hover:scale-105 transition">
                            Submit Ecospace
                        </button>
                        <a href="{{ route('index.index') }}"
                           class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-400 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-pink-400 text-center"
                           onclick="return confirm('Are you sure you want to cancel? Any unsaved changes will be lost.');">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
