<x-app-layout>


    <main id="dashboard-main" class="bg-seiun-sky min-h-screen py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg border border-gray-100 shadow-md">
                <h2 class="text-2xl font-semibold text-dark-green mb-4">Write a review</h2>
                <form action="{{ $targetType === 'ecospace' ? route('ecospace.reviews.store', $target->ecospaceID) : route('events.reviews.store', $target->eventID) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-dark-green">Rating (1–5)</label>
                    <input type="number" name="rating" step="1" min="1" max="5" required class="w-full border border-gray-200 rounded-md px-3 py-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-dark-green">Review</label>
                    <textarea name="review" rows="5" class="w-full border border-gray-200 rounded-md px-3 py-2"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-dark-green">Images (optional)</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="w-full" />
                    <p class="text-xs text-gray-500 mt-2">You can upload multiple images. Max 5MB each.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-magenta-secondary text-white px-4 py-2 rounded-md font-semibold">Submit Review</button>
                    <a href="{{ $targetType === 'ecospace' ? route('ecospace', ['name' => $target->ecospaceName]) : route('events.show', ['id' => $target->eventID]) }}" class="text-magenta-secondary">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>