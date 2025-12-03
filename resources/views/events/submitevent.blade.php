<x-app-layout>


    <main id="dashboard-main" class="bg-seiun-sky min-h-screen py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg border border-gray-100 shadow-md">
                <h2 class="text-3xl font-extrabold mb-4 text-center text-dark-green">Add an Event</h2>
                <p class="text-gray-600 text-center mb-6">Fill out the form below. Fields marked with <span class="text-dark-green font-bold">*</span> are required.</p>

                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data"
                        data-confirm="Are you sure you want to submit this event?" data-composite-address data-address-target="eventAdd">
                    @csrf

                    <!-- Event Name -->
                    <div class="mb-6">
                        <label for="eventName" class="block text-sm font-medium text-dark-green mb-2">
                            <span class="text-dark-green font-bold">*</span> Event Name
                        </label>
                        <input type="text" id="eventName" name="eventName" value="{{ old('eventName') }}"
                               class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20"
                               placeholder="e.g. Community Cleanup">
                        @error('eventName')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div class="mb-6">
                        <label for="eventTypeID" class="block text-emerald-800 font-semibold mb-2">Event Type</label>
                        <select id="eventTypeID" name="eventTypeID" required class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20">
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
                                class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20">
                            @error('eventDate')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                                <label class="block text-emerald-800 font-semibold mb-2">Address</label>
                                <div class="grid grid-cols-1 gap-3">
                                    <input type="text" data-address-line name="event_address_line" id="event_address_line" class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20" placeholder="Street, building, number" />

                                    <select data-barangay name="event_barangay" id="event_barangay" class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20">
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
                                <input type="hidden" name="eventAdd" value="{{ old('eventAdd') }}" />
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
                            <select id="priceTierID" name="priceTierID" required class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20">
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
                        <label for="eventDesc" class="block text-sm font-medium text-dark-green mb-2">Description</label>
                        <textarea id="eventDesc" name="eventDesc" rows="4"
                                  class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-magenta-secondary/20"
                                  placeholder="Describe the event (optional)">{{ old('eventDesc') }}</textarea>
                        @error('eventDesc')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images upload -->
                    <div class="mb-6">
                           <label for="images" class="block text-sm font-medium text-dark-green mb-2">Upload Images</label>
                           <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="w-full border border-gray-200 rounded-md px-3 py-2 bg-white">
                           <p class="text-sm text-gray-500 mt-2">You may upload multiple images (jpg, png, gif, webp). Max size per image 5MB. Up to 7 files.</p>
                           <div id="images-error" class="text-red-600 text-sm mt-2 hidden"></div>
                        @error('images')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center gap-4 mt-8">
                        <button id="images-submit-btn" type="submit" class="bg-magenta-secondary text-white px-6 py-2 rounded-md font-semibold shadow-sm hover:bg-magenta-secondary/90 transition">
                            Submit Event
                        </button>
                        <a href="{{ route('index.index') }}"
                           class="text-magenta-secondary px-4 py-2 rounded-md font-medium"
                           data-confirm="Are you sure you want to cancel? Any unsaved changes will be lost.">
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

    function setMin() {
        const now = new Date();
        now.setSeconds(0,0);
        const pad = (n)=>String(n).padStart(2,'0');
        const local = now.getFullYear() + '-' + pad(now.getMonth()+1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
        dt.min = local;
    }

    setMin();
    // update min periodically
    setInterval(setMin, 60000);

    const form = dt.closest('form');
    if (!form) return;

    form.addEventListener('submit', function(e){
        if (!dt.value) return;
        const selected = new Date(dt.value);
        const now = new Date();
        if (selected < now) {
            e.preventDefault();
            alert('Please choose a date and time that is not in the past.');
        }
    });

    // Images client-side validation (max 7 files)
    const input = document.getElementById('images');
    const errorEl = document.getElementById('images-error');
    const submitBtn = document.getElementById('images-submit-btn');

    function validateImages() {
        if (!input || !input.files) return true;
        const files = Array.from(input.files);
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
        submitBtn.disabled = false;

        if (files.length > 7) {
            errorEl.textContent = 'You may only upload up to 7 images.';
            errorEl.classList.remove('hidden');
            submitBtn.disabled = true;
            return false;
        }
        return true;
    }

    if (input) input.addEventListener('change', validateImages);
    if (form) {
        form.addEventListener('submit', function(e){
            if (!validateImages()) e.preventDefault();
        });
    }
});
</script>
