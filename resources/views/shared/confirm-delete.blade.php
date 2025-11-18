

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title ?? 'Please confirm' }}</h3>
                <p class="text-gray-700 mb-6">{{ $message ?? 'Are you sure you want to continue?' }}</p>

                <div class="flex gap-3">
                    <form method="POST" action="{{ $actionRoute }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Yes, proceed
                        </button>
                    </form>

                    <a href="{{ $cancelUrl ?? route('index.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-800 hover:bg-gray-300">
                        Cancel
                    </a>
                </div>

                @if(isset($resource) && is_object($resource))
                    <div class="mt-6 border-t pt-4 text-sm text-gray-600">
                        <strong>Details:</strong>
                        <pre class="whitespace-pre-wrap">{{ print_r($resource, true) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
