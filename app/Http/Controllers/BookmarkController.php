<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EsBookmark;
use App\Models\EvBookmark;
use Illuminate\Support\Facades\Redirect;

class BookmarkController extends Controller
{
    public function toggleEcospace(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'You must be logged in to bookmark.');
        }

        $bookmark = EsBookmark::where('userID', $user->id)
            ->where('ecospaceID', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return Redirect::back()->with('success', 'Ecospace removed from bookmarks successfully.');
        }

        EsBookmark::create([
            'userID' => $user->id,
            'ecospaceID' => $id,
            'dateCreated' => now(),
        ]);

        return Redirect::back()->with('success', 'Ecospace added to bookmarks successfully.');
    }

    public function toggleEvent(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'You must be logged in to bookmark.');
        }

        $bookmark = EvBookmark::where('userID', $user->id)
            ->where('eventID', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return Redirect::back()->with('success', 'Event removed from bookmarks successfully.');
        }

        EvBookmark::create([
            'userID' => $user->id,
            'eventID' => $id,
            'dateCreated' => now(),
        ]);

        return Redirect::back()->with('success', 'Event added to bookmarks successfully.');
    }
}
