<x-app-layout>


    <div class="py-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-emerald-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-emerald-700">Add an Ecospace</h2>
                <p class="text-emerald-600 text-center mb-8">Fill out the form below. Fields marked with <span class="text-emerald-700 font-bold">*</span> are required.</p>

                    <form method="POST" action="{{ route('ecospaces.store') }}" enctype="multipart/form-data"
                        data-confirm="Are you sure you want to submit this ecospace?" data-composite-address data-address-target="ecospaceAdd">
                    @csrf

                    <!-- Ecospace Name -->
                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-emerald-800 font-semibold mb-2">
                            <span class="text-emerald-700 font-bold">*</span> Ecospace Name
                        </label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName') }}"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400"
                               placeholder="e.g. Maker Space">
                        @error('ecospaceName')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address (street + barangay + static city/region) -->
                    <div class="mb-6">
                        <label class="block text-emerald-800 font-semibold mb-2">Address</label>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" data-address-line name="ecospace_address_line" id="ecospace_address_line" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400" placeholder="Street, building, number" />

                            <select data-barangay name="ecospace_barangay" id="ecospace_barangay" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                                <option value="" disabled selected>-- Select Barangay (Makati) --</option>
                                <option>Bangkal</option>
                                <option>Bel-Air</option>
                                <option>Carmona</option>
                                <option>Dasmariñas</option>
                                <option>Forbes Park</option>
                                <option>Guadalupe Nuevo</option>
                                <option>Guadalupe Viejo</option>
                                <option>Palanan</option>
                                <option>Pembo</option>
                                <option>Pitogo</option>
                                <option>Poblacion</option>
                                <option>San Antonio</option>
                                <option>San Isidro</option>
                                <option>San Lorenzo</option>
                                <option>San Miguel</option>
                                <option>Valenzuela</option>
                                <option>Tejeros</option>
                                <option>Urdaneta</option>
                            </select>

                            <div class="text-sm text-emerald-600">City: <strong>Makati</strong>, Region: <strong>Metro Manila</strong></div>
                        </div>
                        {{-- Hidden combined field submitted to server --}}
                        <input type="hidden" name="ecospaceAdd" value="{{ old('ecospaceAdd') }}" />
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-emerald-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4"
                                  class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400"
                                  placeholder="Describe the ecospace (optional)">{{ old('ecospaceDesc') }}</textarea>
                        @error('ecospaceDesc')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Tier (required) -->
                    <div class="mb-6">
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
                            Submit Ecospace
                        </button>
                                <a href="{{ route('index.index') }}"
                                    class="bg-gray-300 text-emerald-800 px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-400 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-emerald-400 text-center"
                                    data-confirm="Are you sure you want to cancel? Any unsaved changes will be lost.">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
