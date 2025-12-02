<x-app-layout>
    @php $hideNavbar = true; @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            {{ __('Edit Recipe') }}
        </h2>
    </x-slot>

    <style>
        :root{
            --brand-green: #2F9E4A;
            --brand-maroon: #166534;
            --brand-bg-admin-main: #E6F7EA;
            --brand-bg-sidebar: #FFFFFF;
        }
    </style>

    <div class="py-12 bg-brand-bg-admin-main min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-md shadow-2xl sm:rounded-2xl p-10 border border-green-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-brand-maroon">Edit EcoSpace</h2>
<form method="POST" action="{{ route('ecospaces.update', $ecospace->ecospaceID) }}" enctype="multipart/form-data"
    data-confirm="Are you sure you want to update this ecospace?" data-composite-address data-address-target="ecospaceAdd">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-brand-maroon font-semibold mb-2">EcoSpace Name</label>
        <input type="text" name="ecospaceName" value="{{ old('ecospaceName', $ecospace->ecospaceName) }}" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white" />
    </div>

    <div class="mt-4">
        <label class="block text-brand-maroon font-semibold mb-2">Address</label>
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

        <input type="text" data-address-line name="ecospace_address_line" value="{{ $__address_line }}" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white" placeholder="Street, building, number" />
        <select data-barangay name="ecospace_barangay" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white mt-2">
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
        <input type="hidden" name="ecospaceAdd" value="{{ $__full_addr }}" />
    </div>

    <div class="mt-4">
        <label for="ecospaceDesc" class="block text-brand-maroon font-semibold mb-2">Description</label>
        <textarea id="ecospaceDesc" name="ecospaceDesc" rows="4" class="w-full border-2 border-green-200 rounded-xl px-4 py-3 bg-white">{{ old('ecospaceDesc', $ecospace->ecospaceDesc) }}</textarea>
    </div>

    <!-- Opening hours / closing hours / days opened fields removed from admin edit -->

    <div class="mt-4">
        <label class="block text-brand-maroon font-semibold mb-2">Price Tier</label>
        <select name="priceTierID" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white">
            <option value="">-- Keep current --</option>
            @foreach($pricetiers as $pt)
                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $ecospace->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
            @endforeach
        </select>
    </div>

    {{-- Existing images: carousel + remove checkboxes --}}
    <div class="mt-4">
        <label class="block text-brand-maroon font-semibold mb-2">Existing Images</label>
        @if($ecospace->images && $ecospace->images->count())
            <div class="flex items-center gap-4 mb-2">
                @foreach($ecospace->images as $img)
                    <div class="flex flex-col items-center">
                        <img src="{{ Storage::url($img->path) }}" alt="image" class="w-32 h-20 object-cover rounded-md border mb-2" />
                        <label class="text-sm text-brand-maroon">
                            <input type="checkbox" name="images_to_remove[]" value="{{ $img->esImageID }}"> Remove
                        </label>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm text-brand-maroon">No images uploaded.</div>
        @endif
    </div>

    <div class="mt-4">
        <label class="block text-brand-maroon font-semibold mb-2">Add Images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white" />
        <p class="text-sm text-brand-maroon mt-2">You can upload additional images (max 5MB each).</p>
    </div>

    <div class="flex justify-center gap-4 mt-6">
        <button type="submit" class="admin-btn-positive px-8 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-green-400">
            Update EcoSpace
        </button>
        <a href="{{ route('index.index') }}" class="admin-btn-neutral px-8 py-3 rounded-xl font-bold shadow-lg hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-green-400 text-center"
           data-confirm="Are you sure you want to cancel editing? Any unsaved changes will be lost.">
            Cancel
        </a>
    </div>
</form>
            </div>
        </div>
    </div>
</x-app-layout>