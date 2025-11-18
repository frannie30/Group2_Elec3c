@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-12">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Admin: Edit Event</h1>

            <form action="{{ route('admin.events.update', ['id' => $event->eventID ?? $event['eventID']]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Event Name</label>
                        <input type="text" name="eventName" value="{{ old('eventName', $event->eventName ?? $event['eventName']) }}" class="w-full border px-4 py-3 rounded-lg" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Event Date</label>
                        <input type="datetime-local" name="eventDate" value="{{ old('eventDate', optional($event)->eventDate ?? ($event['eventDate'] ?? '')) }}" class="w-full border px-4 py-3 rounded-lg" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Address</label>
                        <input type="text" name="eventAdd" value="{{ old('eventAdd', $event->eventAdd ?? $event['eventAdd']) }}" class="w-full border px-4 py-3 rounded-lg" />
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
@endsection
