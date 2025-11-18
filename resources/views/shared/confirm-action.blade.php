@php
    // $title, $message, $actionUrl, $cancelUrl, $inputs
    // Ensure inputs is an array
    $inputs = $inputs ?? [];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">{{ $title ?? 'Confirm Action' }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title ?? 'Please confirm' }}</h3>
                <p class="text-gray-700 mb-6">{{ $message ?? 'Please confirm you want to perform this action. Review the data below and click "Yes, proceed" to continue.' }}</p>

                <div class="mb-6">
                    <table class="w-full text-sm table-auto border-collapse">
                        <tbody>
                        @forelse($inputs as $key => $value)
                            <tr class="border-t">
                                <td class="py-2 font-medium text-gray-700 align-top w-1/3">{{ $key }}</td>
                                <td class="py-2 text-gray-800">@if(is_array($value) || is_object($value))<pre class="whitespace-pre-wrap">{{ print_r($value, true) }}</pre>@else{{ $value }}@endif</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-2 text-gray-600">No data to show.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-3">
                    <form method="POST" action="{{ $actionUrl }}">
                        @csrf
                        <input type="hidden" name="confirmed" value="1">
                        @foreach($inputs as $name => $val)
                            @if(is_array($val))
                                @foreach($val as $k => $v)
                                    <input type="hidden" name="{{ $name }}[{{ $k }}]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $name }}" value="{{ $val }}">
                            @endif
                        @endforeach

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Yes, proceed
                        </button>
                    </form>

                    <a href="{{ $cancelUrl ?? route('index.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-800 hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
