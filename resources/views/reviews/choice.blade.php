<x-app-layout>


    <div class="py-12">
        <div class="max-w-2xl mx-auto bg-white/90 p-6 rounded-2xl border border-pink-200 text-center">
            <p class="mb-6 text-pink-700">Would you like to add a full review or add a short pro / con for this ecospace?</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if(!(auth()->check() && auth()->id() == ($target->userID ?? null)))
                    <a href="{{ route('ecospace.reviews.create', $target->ecospaceID) }}" class="block bg-pink-600 text-white px-4 py-3 rounded-md">Add Review</a>
                @endif

                <a href="{{ route('ecospace.proscons.create', $target->ecospaceID) }}" class="block bg-white border border-pink-600 text-pink-600 px-4 py-3 rounded-md">Add Pro / Con</a>
            </div>

            <div class="mt-6">
                <a href="{{ route('ecospace', ['name' => $target->ecospaceName]) }}" class="text-pink-600">Back to ecospace</a>
            </div>
        </div>
    </div>
</x-app-layout>
