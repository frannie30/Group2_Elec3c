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
                    <input id="review-images-input" type="file" name="images[]" accept=".jpg,.jpeg,.png" multiple class="w-full" />
                    <p class="text-xs text-gray-500 mt-2">You can upload multiple images (JPG, PNG). Max 5MB each. Up to 3 files per upload.</p>
                    <div id="review-images-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>

                <div class="flex items-center gap-3">
                    <button id="review-submit-btn" type="submit" class="bg-magenta-secondary text-white px-4 py-2 rounded-md font-semibold">Submit Review</button>
                    <a href="{{ $targetType === 'ecospace' ? route('ecospace', ['name' => $target->ecospaceName]) : route('events.show', ['id' => $target->eventID]) }}" class="text-magenta-secondary">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        (function(){
            const input = document.getElementById('review-images-input');
            const errorEl = document.getElementById('review-images-error');
            const submitBtn = document.getElementById('review-submit-btn');

            function validateFiles() {
                errorEl.classList.add('hidden');
                errorEl.textContent = '';
                submitBtn.disabled = false;

                if (!input || !input.files) return true;
                const files = Array.from(input.files);
                if (files.length > 3) {
                    errorEl.textContent = 'You may only upload up to 3 images at a time.';
                    errorEl.classList.remove('hidden');
                    submitBtn.disabled = true;
                    return false;
                }

                // check file types
                for (const f of files) {
                    const ext = (f.name.split('.').pop() || '').toLowerCase();
                    if (!['jpg','jpeg','png'].includes(ext)) {
                        errorEl.textContent = 'Only JPG, JPEG or PNG images are allowed.';
                        errorEl.classList.remove('hidden');
                        submitBtn.disabled = true;
                        return false;
                    }
                    if (f.size > 5 * 1024 * 1024) {
                        errorEl.textContent = 'Each image must be 5MB or smaller.';
                        errorEl.classList.remove('hidden');
                        submitBtn.disabled = true;
                        return false;
                    }
                }

                return true;
            }

            if (input) {
                input.addEventListener('change', validateFiles);
            }
            // Prevent submit if invalid
            const form = input ? input.closest('form') : null;
            if (form) {
                form.addEventListener('submit', function(e){
                    if (!validateFiles()) {
                        e.preventDefault();
                    }
                });
            }
        })();
    </script>
</x-app-layout>