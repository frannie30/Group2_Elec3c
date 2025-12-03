<x-app-layout>


    <main id="dashboard-main" class="bg-seiun-sky min-h-screen py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg border border-gray-100 text-center">
                <p class="mb-6 text-dark-green font-semibold">Would you like to add a full review or add a short pro / con for this ecospace?</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if(!(auth()->check() && auth()->id() == ($target->userID ?? null)))
                        <a href="{{ route('ecospace.reviews.create', $target->ecospaceID) }}" class="block bg-magenta-secondary text-white px-4 py-3 rounded-md">Add Review</a>
                    @endif

                    <a href="{{ route('ecospace.proscons.create', $target->ecospaceID) }}" class="block bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-md">Add Pro / Con</a>
                </div>

                <div class="mt-6">
                    <a href="{{ route('ecospace', ['name' => $target->ecospaceName]) }}" class="text-magenta-secondary">Back to ecospace</a>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
