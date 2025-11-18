<x-app-layout>


    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white/90 p-6 rounded-2xl border border-pink-200">
            <form action="{{ $targetType === 'ecospace' ? route('ecospace.reviews.store', $target->ecospaceID) : route('events.reviews.store', $target->eventID) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Rating (1–5)</label>
                    <input type="number" name="rating" step="1" min="1" max="5" required class="w-full border rounded-md px-3 py-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Review</label>
                    <textarea name="review" rows="5" class="w-full border rounded-md px-3 py-2"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Images (optional)</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="w-full" />
                    <p class="text-xs text-pink-500 mt-2">You can upload multiple images. Max 5MB each.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-xl">Submit Review</button>
                    <a href="{{ $targetType === 'ecospace' ? route('ecospace', ['name' => $target->ecospaceName]) : route('events.show', ['id' => $target->eventID]) }}" class="text-pink-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>