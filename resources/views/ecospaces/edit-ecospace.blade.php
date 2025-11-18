<x-app-layout>


    <div class="py-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-emerald-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-emerald-700">Edit Ecospace</h2>

                <form method="POST" action="{{ route('user.ecospaces.update', $ecospace->ecospaceID) }}" enctype="multipart/form-data"
                      onsubmit="return confirm('Save changes to this ecospace?');">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-emerald-800 font-semibold mb-2">Ecospace Name</label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName', $ecospace->ecospaceName) }}"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                        @error('ecospaceName')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceAdd" class="block text-emerald-800 font-semibold mb-2">Address</label>
                        <input type="text" id="ecospaceAdd" name="ecospaceAdd" value="{{ old('ecospaceAdd', $ecospace->ecospaceAdd) }}"
                               class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                        @error('ecospaceAdd')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-emerald-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">{{ old('ecospaceDesc', $ecospace->ecospaceDesc) }}</textarea>
                        @error('ecospaceDesc')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="openingHours" class="block text-emerald-800 font-semibold mb-2">Opening Hours</label>
                            <input type="time" id="openingHours" name="openingHours" value="{{ old('openingHours', $ecospace->openingHours) }}" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                            @error('openingHours')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="closingHours" class="block text-emerald-800 font-semibold mb-2">Closing Hours</label>
                            <input type="time" id="closingHours" name="closingHours" value="{{ old('closingHours', $ecospace->closingHours) }}" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                            @error('closingHours')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="daysOpened" class="block text-emerald-800 font-semibold mb-2">Days Opened</label>
                        <input type="text" id="daysOpened" name="daysOpened" value="{{ old('daysOpened', $ecospace->daysOpened) }}" class="w-full border-2 border-emerald-200 rounded-xl px-4 py-3 bg-emerald-50 focus:ring-2 focus:ring-emerald-400">
                        @error('daysOpened')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

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
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-emerald-800 px-8 py-3 rounded-xl font-bold">Cancel</a>
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
                      onsubmit="return confirm('Save changes to this ecospace?');">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="ecospaceName" class="block text-pink-800 font-semibold mb-2">Ecospace Name</label>
                        <input type="text" id="ecospaceName" name="ecospaceName" value="{{ old('ecospaceName', $ecospace->ecospaceName) }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                        @error('ecospaceName')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceAdd" class="block text-pink-800 font-semibold mb-2">Address</label>
                        <input type="text" id="ecospaceAdd" name="ecospaceAdd" value="{{ old('ecospaceAdd', $ecospace->ecospaceAdd) }}"
                               class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                        @error('ecospaceAdd')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label for="ecospaceDesc" class="block text-pink-800 font-semibold mb-2">Description</label>
                        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">{{ old('ecospaceDesc', $ecospace->ecospaceDesc) }}</textarea>
                        @error('ecospaceDesc')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="openingHours" class="block text-pink-800 font-semibold mb-2">Opening Hours</label>
                            <input type="time" id="openingHours" name="openingHours" value="{{ old('openingHours', $ecospace->openingHours) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                            @error('openingHours')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="closingHours" class="block text-pink-800 font-semibold mb-2">Closing Hours</label>
                            <input type="time" id="closingHours" name="closingHours" value="{{ old('closingHours', $ecospace->closingHours) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                            @error('closingHours')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="daysOpened" class="block text-pink-800 font-semibold mb-2">Days Opened</label>
                        <input type="text" id="daysOpened" name="daysOpened" value="{{ old('daysOpened', $ecospace->daysOpened) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50 focus:ring-2 focus:ring-pink-400">
                        @error('daysOpened')<div class="text-red-500 mt-2 text-sm">{{ $message }}</div>@enderror
                    </div>

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
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
