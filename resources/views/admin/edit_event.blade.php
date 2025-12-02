<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            {{ __('Edit Event') }}
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
                <h2 class="text-3xl font-extrabold mb-8 text-center text-brand-maroon">Edit Event</h2>

                    <form method="POST" action="{{ route('events.update', $event->eventID) }}" enctype="multipart/form-data"
                        data-confirm="Are you sure you want to update this event?" data-composite-address data-address-target="eventAdd">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-brand-maroon font-semibold mb-2">Event Name</label>
                        <input type="text" name="eventName" value="{{ old('eventName', $event->eventName) }}" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-brand-maroon font-semibold mb-2">Event Type</label>
                        <select name="eventTypeID" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white">
                            <option value="">-- Keep current --</option>
                            @foreach($eventtypes as $et)
                                <option value="{{ $et->eventTypeID }}" {{ (old('eventTypeID', $event->eventTypeID) == $et->eventTypeID) ? 'selected' : '' }}>{{ $et->eventTypeName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-brand-maroon font-semibold mb-2">Event Date & Time</label>
                            <input type="datetime-local" id="eventDate" name="eventDate" value="{{ old('eventDate', 
                                optional(
                                    \Carbon\Carbon::parse($event->eventDate ?? null)
                                )->format('Y-m-d\TH:i')
                            ) }}" class="w-full border-2 border-pink-200 rounded-xl px-4 py-2 bg-pink-50" />
                        </div>
                        <div>
                            <label class="block text-brand-maroon font-semibold mb-2">Address</label>
                            @php
                                $__full_addr = old('eventAdd', $event->eventAdd ?? '');
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

                            <input type="text" data-address-line name="event_address_line" value="{{ $__address_line }}" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white" placeholder="Street, building, number" />
                            <select data-barangay name="event_barangay" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white mt-2">
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
                            <input type="hidden" name="eventAdd" value="{{ $__full_addr }}" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-brand-maroon font-semibold mb-2">Price Tier</label>
                        <select name="priceTierID" class="w-full border-2 border-green-200 rounded-xl px-4 py-2 bg-white">
                            <option value="">-- Keep current --</option>
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID }}" {{ (old('priceTierID', $event->priceTierID) == $pt->priceTierID) ? 'selected' : '' }}>{{ $pt->pricetier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="eventDesc" class="block text-brand-maroon font-semibold mb-2">Description</label>
                        <textarea id="eventDesc" name="eventDesc" rows="4" class="w-full border-2 border-green-200 rounded-xl px-4 py-3 bg-white">{{ old('eventDesc', $event->eventDesc) }}</textarea>
                    </div>

                    {{-- Existing images: thumbnails + remove checkboxes --}}
                    <div class="mt-4">
                        <label class="block text-brand-maroon font-semibold mb-2">Existing Images</label>
                        @if($event->images && $event->images->count())
                            <div class="flex items-center gap-4 mb-2">
                                @foreach($event->images as $img)
                                    <div class="flex flex-col items-center">
                                        <img src="{{ Storage::url($img->path) }}" alt="image" class="w-32 h-20 object-cover rounded-md border mb-2" />
                                        <label class="text-sm text-brand-maroon">
                                            <input type="checkbox" name="images_to_remove[]" value="{{ $img->eventImageID }}"> Remove
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
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-green-700 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-green-400">
                            Update Event
                        </button>
                        <a href="{{ route('index.index') }}" class="bg-gray-300 text-brand-maroon px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-400 hover:scale-105 transition focus:outline-none focus:ring-2 focus:ring-green-400 text-center"
                           data-confirm="Are you sure you want to cancel editing? Any unsaved changes will be lost.">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const dt = document.getElementById('eventDate');
    if (!dt) return;
    function setMin(){
        const now = new Date(); now.setSeconds(0,0);
        const pad = (n)=>String(n).padStart(2,'0');
        const local = now.getFullYear() + '-' + pad(now.getMonth()+1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
        dt.min = local;
    }
    setMin(); setInterval(setMin, 60000);
    const form = dt.closest('form'); if (!form) return;
    form.addEventListener('submit', function(e){ if (!dt.value) return; const selected = new Date(dt.value); if (selected < new Date()) { e.preventDefault(); alert('Please choose a date and time that is not in the past.'); } });
});
</script>
