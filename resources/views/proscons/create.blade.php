<x-app-layout>


    <div class="py-12">
        <div class="max-w-3xl mx-auto bg-white/90 p-6 rounded-2xl border border-pink-200">
            <form action="{{ route('ecospace.proscons.store', $ecospace->ecospaceID) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Type</label>
                    <div class="flex gap-4 mt-2">
                        <label class="inline-flex items-center"><input type="radio" name="isPro" value="1" checked class="mr-2">Pro</label>
                        <label class="inline-flex items-center"><input type="radio" name="isPro" value="0" class="mr-2">Con</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-pink-700">Description</label>
                    <textarea name="description" rows="4" required class="w-full border rounded-md px-3 py-2"></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-xl">Submit</button>
                    <a href="{{ route('ecospace', ['name' => $ecospace->ecospaceName]) }}" class="text-pink-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

