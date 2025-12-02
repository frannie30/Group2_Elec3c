<x-app-layout>


    <div class="py-12 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg shadow-2xl sm:rounded-2xl p-10 border border-pink-200">
                <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700">Edit Event</h2>

                <form method="POST" action="{{ route('user.events.update', $event->eventID) }}" enctype="multipart/form-data" data-confirm="Save changes to this event?" data-composite-address data-address-target="eventAdd">
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

                            <input type="text" data-address-line id="event_address_line" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50" placeholder="Street, building, number" value="{{ $__address_line }}" />

                            <select data-barangay id="event_barangay" class="w-full border-2 border-pink-200 rounded-xl px-4 py-3 bg-pink-50">
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
                            <input type="hidden" name="eventAdd" value="{{ $__full_addr }}" />
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
                        <a href="{{ route('users.show', auth()->id()) }}" class="bg-gray-300 text-pink-800 px-8 py-3 rounded-xl font-bold" data-confirm="Are you sure you want to cancel? Any unsaved changes will be lost.">Cancel</a>
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
