<x-app-layout>


    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white/90 p-6 rounded-2xl border border-pink-200">
            <form action="{{ $targetType === 'ecospace' ? route('ecospace.reviews.update', [$target->ecospaceID, $review->reviewID]) : route('events.reviews.update', [$target->eventID, $review->reviewID]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Rating (1–5)</label>
                    <input type="number" name="rating" step="1" min="1" max="5" required class="w-full border rounded-md px-3 py-2" value="{{ old('rating', $review->rating) }}" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Review</label>
                    <textarea name="review" rows="5" class="w-full border rounded-md px-3 py-2">{{ old('review', $review->review) }}</textarea>
                </div>

                @if($review->images && $review->images->count())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-pink-700">Existing Images (check to remove)</label>
                        <div class="flex gap-3 mt-2">
                            @foreach($review->images as $img)
                                <label class="flex flex-col items-center text-xs">
                                    <img src="{{ Storage::url($img->revImgName) }}" class="w-24 h-20 object-cover rounded border" />
                                    <div>
                                        <input type="checkbox" name="images_to_remove[]" value="{{ $img->revImgID }}" /> Remove
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Add Images (optional)</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="w-full" />
                    <p class="text-xs text-pink-500 mt-2">You can upload multiple images. Max 5MB each.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-xl">Update Review</button>
                    <a href="{{ $targetType === 'ecospace' ? route('ecospace', ['name' => $target->ecospaceName]) : route('events.show', ['id' => $target->eventID]) }}" class="text-pink-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
