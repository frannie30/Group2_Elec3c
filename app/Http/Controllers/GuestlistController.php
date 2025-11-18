<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guestlist;
use Illuminate\Support\Facades\Auth;

class GuestlistController extends Controller
{
    /**
     * Toggle attendance for the authenticated user on the given event.
     * Creates a guestlist row with isGoing = 1 if none exists.
     * Toggles isGoing between 1 and 0 when a record exists.
     */
    public function toggle(Request $request, $eventId)
    {
        $user = Auth::user();
        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            return redirect()->route('login');
        }

        $guest = Guestlist::where('eventID', $eventId)->where('userID', $user->id)->first();

        if ($guest) {
            // Toggle isGoing flag
            $guest->isGoing = ! (bool) $guest->isGoing;
            $guest->dateUpdated = now();
            $guest->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'isGoing' => (bool) $guest->isGoing, 'guestID' => $guest->guestID]);
            }

            return redirect()->back()->with('success', $guest->isGoing ? 'Marked as attending' : 'Marked as not attending');
        }

        // Create attendance record
        $guest = Guestlist::create([
            'eventID' => $eventId,
            'userID' => $user->id,
            'isGoing' => true,
            'dateCreated' => now(),
            'dateUpdated' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'isGoing' => true, 'guestID' => $guest->guestID]);
        }

        return redirect()->back()->with('success', 'Marked as attending');
    }
}
