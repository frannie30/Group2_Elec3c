<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Ecospace;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Show review creation form for an ecospace.
     */
    public function createEcospace($ecospaceId)
    {
        $ecospace = Ecospace::with('reviews.images')->findOrFail($ecospaceId);
        return view('reviews.create', ['targetType' => 'ecospace', 'target' => $ecospace]);
    }

    /**
     * Show a choice page where the user picks between adding a review or a pro/con.
     */
    public function chooseEcospace($ecospaceId)
    {
        $ecospace = Ecospace::with('reviews.images')->findOrFail($ecospaceId);
        return view('reviews.choice', ['targetType' => 'ecospace', 'target' => $ecospace]);
    }


    /**
     * Store a review for an ecospace
     */
    public function storeEcospace(Request $request, $ecospaceId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $ecospace = Ecospace::findOrFail($ecospaceId);
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        $review = Review::create([
            'userID' => $user->id,
            'ecospaceID' => $ecospace->ecospaceID,
            'rating' => $request->input('rating'),
            'review' => $request->input('review'),
            'dateCreated' => now(),
            'dateUpdated' => now(),
        ]);

        // Handle review images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('review_images/' . $review->reviewID, $filename, 'public');

                ReviewImage::create([
                    'revImgName' => $path,
                    'reviewID' => $review->reviewID,
                    'dateCreated' => now(),
                ]);
            }
        }

        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Review submitted.');
    }

    /**
     * Store a review for an event
     */
    public function storeEvent(Request $request, $eventId)
    {
        // Event reviews are disabled.
        abort(404);
    }

    /**
     * Show the edit form for an ecospace review.
     */
    public function edit($ecospaceId, $reviewId)
    {
        $ecospace = Ecospace::findOrFail($ecospaceId);
        $review = Review::with('images')->findOrFail($reviewId);
        if ($review->ecospaceID != $ecospace->ecospaceID) abort(404);
        if (!auth()->check() || auth()->id() != $review->userID) abort(403);

        return view('reviews.edit', ['targetType' => 'ecospace', 'target' => $ecospace, 'review' => $review]);
    }

    /**
     * Update an ecospace review.
     */
    public function update(Request $request, $ecospaceId, $reviewId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer',
        ]);

        $ecospace = Ecospace::findOrFail($ecospaceId);
        $review = Review::with('images')->findOrFail($reviewId);
        if ($review->ecospaceID != $ecospace->ecospaceID) abort(404);
        if (!auth()->check() || auth()->id() != $review->userID) abort(403);

        $review->rating = $request->input('rating');
        $review->review = $request->input('review');
        $review->dateUpdated = now();
        $review->save();

        // Remove selected images
        if ($request->filled('images_to_remove')) {
            foreach ($request->images_to_remove as $imgId) {
                $ri = ReviewImage::find($imgId);
                if ($ri && $ri->reviewID == $review->reviewID) {
                    if ($ri->revImgName && Storage::disk('public')->exists($ri->revImgName)) {
                        Storage::disk('public')->delete($ri->revImgName);
                    }
                    $ri->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('review_images/' . $review->reviewID, $filename, 'public');

                ReviewImage::create([
                    'revImgName' => $path,
                    'reviewID' => $review->reviewID,
                    'dateCreated' => now(),
                ]);
            }
        }

        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Review updated.');
    }

    /**
     * Delete an ecospace review.
     */
    public function destroy(Request $request, $ecospaceId, $reviewId)
    {
        $ecospace = Ecospace::findOrFail($ecospaceId);
        $review = Review::with('images')->findOrFail($reviewId);
        if ($review->ecospaceID != $ecospace->ecospaceID) abort(404);
        if (!auth()->check() || auth()->id() != $review->userID) abort(403);

        // delete images from storage
        foreach ($review->images as $ri) {
            if ($ri->revImgName && Storage::disk('public')->exists($ri->revImgName)) {
                Storage::disk('public')->delete($ri->revImgName);
            }
            $ri->delete();
        }

        $review->delete();

        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Review deleted.');
    }
}
