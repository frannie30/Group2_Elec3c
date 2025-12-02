
<x-app-layout>
    @php
        $__full_addr = old('ecospaceAdd', $ecospace->ecospaceAdd ?? '');
        $__parts = array_map('trim', explode(',', $__full_addr));
        $__len = count($__parts);
        if ($__len > 2) {
            $__address_line = implode(', ', array_slice($__parts, 0, $__len - 2));
            $__barangay = $__parts[$__len - 2] ?? '';
        } else {
            $__address_line = $__parts[0] ?? '';
            $__barangay = $__parts[1] ?? '';
        }
    @endphp


    <div class="py-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-emerald-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-emerald-700">Edit Ecospace</h2>

                    <form method="POST" action="{{ route('user.ecospaces.update', $ecospace->ecospaceID) }}" enctype="multipart/form-data"
                            data-composite-address data-address-target="ecospaceAdd"
                        data-confirm="Save changes to this ecospace?">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-emerald-800 font-semibold mb-2">Ecospace Name</label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName', $ecospace->ecospaceName) }}"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                        @error('ecospaceName')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-emerald-800 font-semibold mb-2">Address</label>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" data-address-line id="ecospace_address_line_1" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400" placeholder="Street, building, number" value="{{ $__address_line }}" />

                            <select data-barangay id="ecospace_barangay_1" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                                <option value="" disabled {{ $__barangay=='' ? 'selected' : '' }}>-- Select Barangay (Makati) --</option>
                                <option {{ $__barangay=='Bangkal' ? 'selected' : '' }}>Bangkal</option>
                                <option {{ $__barangay=='Bel-Air' ? 'selected' : '' }}>Bel-Air</option>
                                <option {{ $__barangay=='Carmona' ? 'selected' : '' }}>Carmona</option>
                                <option {{ $__barangay=='Dasmariñas' ? 'selected' : '' }}>Dasmariñas</option>
                                <option {{ $__barangay=='Forbes Park' ? 'selected' : '' }}>Forbes Park</option>
                                <option {{ $__barangay=='Guadalupe Nuevo' ? 'selected' : '' }}>Guadalupe Nuevo</option>
                                <option {{ $__barangay=='Guadalupe Viejo' ? 'selected' : '' }}>Guadalupe Viejo</option>
                                <option {{ $__barangay=='Palanan' ? 'selected' : '' }}>Palanan</option>
                                <option {{ $__barangay=='Pembo' ? 'selected' : '' }}>Pembo</option>
                                <option {{ $__barangay=='Pitogo' ? 'selected' : '' }}>Pitogo</option>
                                <option {{ $__barangay=='Poblacion' ? 'selected' : '' }}>Poblacion</option>
                                <option {{ $__barangay=='San Antonio' ? 'selected' : '' }}>San Antonio</option>
                                <option {{ $__barangay=='San Isidro' ? 'selected' : '' }}>San Isidro</option>
                                <option {{ $__barangay=='San Lorenzo' ? 'selected' : '' }}>San Lorenzo</option>
                                <option {{ $__barangay=='San Miguel' ? 'selected' : '' }}>San Miguel</option>
                                <option {{ $__barangay=='Valenzuela' ? 'selected' : '' }}>Valenzuela</option>
                                <option {{ $__barangay=='Tejeros' ? 'selected' : '' }}>Tejeros</option>
                                <option {{ $__barangay=='Urdaneta' ? 'selected' : '' }}>Urdaneta</option>
                            </select>

                            <div class="text-sm text-emerald-600">City: <strong>Makati</strong>, Region: <strong>Metro Manila</strong></div>
                        </div>
                        <input type="hidden" name="ecospaceAdd" value="{{ $__full_addr }}" />
                        @error('ecospaceAdd')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-emerald-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">{{ old('ecospaceDesc', $ecospace->ecospaceDesc) }}</textarea>
                        @error('ecospaceDesc')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <!-- Opening hours / closing hours / days opened removed per spec -->

                    <div class="mb-6">
                        <label for="priceTierID" class="block text-emerald-800 font-semibold mb-2">Price Tier</label>
                        <select id="priceTierID" name="priceTierID" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                            <option value="">-- Select price tier --</option>
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $ecospace->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                            @endforeach
                        </select>
                        @error('priceTierID')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-emerald-800 font-semibold mb-2">Existing Images</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($ecospace->images as $img)
                                <div class="border p-2 rounded">
                                    <img src="{{ Storage::url($img->path) }}" alt="img" class="w-full h-28 object-cover rounded mb-2" />
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="images_to_remove[]" value="{{ $img->esImageID ?? $img->id }}" class="mr-2"> Remove
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="images" class="block text-emerald-800 font-semibold mb-2">Upload New Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50">
                        @error('images')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        @error('images.*')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex justify-center gap-4 mt-8">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-emerald-700 transition">Save Changes</button>
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-emerald-800 px-8 py-3 rounded-xl font-bold" data-confirm="Are you sure you want to cancel? Any unsaved changes will be lost.">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-pink-700 leading-tight text-center py-4">
            {{ __('Edit EcoSpace') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-pink-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700">Edit Ecospace</h2>

                    <form method="POST" action="{{ route('user.ecospaces.update', $ecospace->ecospaceID) }}" enctype="multipart/form-data"
                            data-composite-address data-address-target="ecospaceAdd"
                        data-confirm="Save changes to this ecospace?">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-pink-800 font-semibold mb-2">Ecospace Name</label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName', $ecospace->ecospaceName) }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                        @error('ecospaceName')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-pink-800 font-semibold mb-2">Address</label>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" data-address-line id="ecospace_address_line_2" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400" placeholder="Street, building, number" value="{{ $__address_line }}" />

                            <select data-barangay id="ecospace_barangay_2" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                                <option value="" disabled {{ $__barangay=='' ? 'selected' : '' }}>-- Select Barangay (Makati) --</option>
                                <option {{ $__barangay=='Bangkal' ? 'selected' : '' }}>Bangkal</option>
                                <option {{ $__barangay=='Bel-Air' ? 'selected' : '' }}>Bel-Air</option>
                                <option {{ $__barangay=='Carmona' ? 'selected' : '' }}>Carmona</option>
                                <option {{ $__barangay=='Dasmariñas' ? 'selected' : '' }}>Dasmariñas</option>
                                <option {{ $__barangay=='Forbes Park' ? 'selected' : '' }}>Forbes Park</option>
                                <option {{ $__barangay=='Guadalupe Nuevo' ? 'selected' : '' }}>Guadalupe Nuevo</option>
                                <option {{ $__barangay=='Guadalupe Viejo' ? 'selected' : '' }}>Guadalupe Viejo</option>
                                <option {{ $__barangay=='Palanan' ? 'selected' : '' }}>Palanan</option>
                                <option {{ $__barangay=='Pembo' ? 'selected' : '' }}>Pembo</option>
                                <option {{ $__barangay=='Pitogo' ? 'selected' : '' }}>Pitogo</option>
                                <option {{ $__barangay=='Poblacion' ? 'selected' : '' }}>Poblacion</option>
                                <option {{ $__barangay=='San Antonio' ? 'selected' : '' }}>San Antonio</option>
                                <option {{ $__barangay=='San Isidro' ? 'selected' : '' }}>San Isidro</option>
                                <option {{ $__barangay=='San Lorenzo' ? 'selected' : '' }}>San Lorenzo</option>
                                <option {{ $__barangay=='San Miguel' ? 'selected' : '' }}>San Miguel</option>
                                <option {{ $__barangay=='Valenzuela' ? 'selected' : '' }}>Valenzuela</option>
                                <option {{ $__barangay=='Tejeros' ? 'selected' : '' }}>Tejeros</option>
                                <option {{ $__barangay=='Urdaneta' ? 'selected' : '' }}>Urdaneta</option>
                            </select>

                            <div class="text-sm text-pink-600">City: <strong>Makati</strong>, Region: <strong>Metro Manila</strong></div>
                        </div>
                        <input type="hidden" name="ecospaceAdd" value="{{ $__full_addr }}" />
                        @error('ecospaceAdd')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-pink-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">{{ old('ecospaceDesc', $ecospace->ecospaceDesc) }}</textarea>
                        @error('ecospaceDesc')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <!-- Opening hours / closing hours / days opened removed per spec -->

                    <div class="mb-6">
                        <label for="priceTierID" class="block text-pink-800 font-semibold mb-2">Price Tier</label>
                        <select id="priceTierID" name="priceTierID" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                            <option value="">-- Select price tier --</option>
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $ecospace->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                            @endforeach
                        </select>
                        @error('priceTierID')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-pink-800 font-semibold mb-2">Existing Images</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($ecospace->images as $img)
                                <div class="border p-2 rounded">
                                    <img src="{{ Storage::url($img->path) }}" alt="img" class="w-full h-28 object-cover rounded mb-2" />
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="images_to_remove[]" value="{{ $img->esImageID ?? $img->id }}" class="mr-2"> Remove
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
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold" data-confirm="Are you sure you want to cancel? Any unsaved changes will be lost.">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
