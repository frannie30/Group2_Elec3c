@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-12">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Admin: Edit Event</h1>

            <form action="{{ route('admin.events.update', ['id' => $event->eventID ?? $event['eventID']]) }}" method="POST" enctype="multipart/form-data" data-composite-address data-address-target="eventAdd">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Event Name</label>
                        <input type="text" name="eventName" value="{{ old('eventName', $event->eventName ?? $event['eventName']) }}" class="w-full border px-4 py-3 rounded-lg" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Event Date</label>
                        <input type="datetime-local" id="eventDate" name="eventDate" value="{{ old('eventDate', optional($event)->eventDate ?? ($event['eventDate'] ?? '')) }}" class="w-full border px-4 py-3 rounded-lg" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Address</label>
                        @php
                            $__full_addr = old('eventAdd', $event->eventAdd ?? $event['eventAdd'] ?? '');
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

                        <input type="text" data-address-line name="event_address_line" value="{{ $__address_line }}" class="w-full border px-4 py-3 rounded-lg" placeholder="Street, building, number" />
                        <select data-barangay name="event_barangay" class="w-full border px-4 py-3 rounded-lg mt-2">
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

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Price Tier</label>
                        <select name="priceTierID" class="w-full border px-4 py-3 rounded-lg">
                            @foreach($pricetiers as $pt)
                                <option value="{{ $pt->priceTierID ?? $pt['priceTierID'] }}" {{ (old('priceTierID', $event->priceTierID ?? $event['priceTierID'] ?? '') == ($pt->priceTierID ?? $pt['priceTierID'])) ? 'selected' : '' }}>{{ $pt->pricetier ?? $pt['pricetier'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status</label>
                        <select name="statusID" class="w-full border px-4 py-3 rounded-lg">
                            @foreach($statuses as $s)
                                <option value="{{ $s->statusID ?? $s['statusID'] }}" {{ (old('statusID', $event->statusID ?? $event['statusID'] ?? '') == ($s->statusID ?? $s['statusID'])) ? 'selected' : '' }}>{{ $s->statusName ?? $s['statusName'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Event Description</label>
                        <textarea name="eventDesc" rows="5" class="w-full border px-4 py-3 rounded-lg">{{ old('eventDesc', $event->eventDesc ?? $event['eventDesc'] ?? '') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.events.index') }}" class="px-6 py-3 rounded-lg bg-gray-200 text-gray-800">Cancel</a>
                        <button type="submit" class="px-6 py-3 rounded-lg bg-blue-600 text-white">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
@endsection
