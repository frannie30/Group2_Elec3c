<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Ecospace;
use App\Models\Guestlist;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the specified user profile with ecospaces, events,
     * and static reviews/bookmarks.
     */
    public function show($id)
    {
        $user = User::with(['ecospaces.images', 'ecospaces.priceTier'])->findOrFail($id);

        $events = Event::where('userID', $id)->withCount('attendees')->get();

        // Only load owner-only data when the viewer is the profile owner
        $isOwner = auth()->check() && auth()->id() == $id;

        $attendingEvents = collect();
        if ($isOwner) {
            // Events the user is attending (guestlist.isGoing = 1)
            $attendingEventIds = Guestlist::where('userID', $id)->where('isGoing', true)->pluck('eventID');
            $attendingEvents = Event::whereIn('eventID', $attendingEventIds)
                ->with(['images', 'priceTier', 'eventType', 'user', 'status'])
                ->withCount('attendees')
                ->get();
        }

        // Load reviews made by this user (paginated). Eager-load related ecospace or event.
        $userReviews = \App\Models\Review::where('userID', $id)
            ->with(['ecospace', 'event'])
            ->orderByDesc('dateCreated')
            ->paginate(10, ['*'], 'user_reviews_page')
            ->withQueryString();

        // Only load bookmarks for the owner (private bookmarks list)
        $bookmarks = collect();
        if ($isOwner) {
            $user->loadMissing(['esBookmarks.ecospace', 'evBookmarks.event']);

            // Ecospace bookmarks
            if ($user->esBookmarks && $user->esBookmarks->count()) {
                foreach ($user->esBookmarks as $bm) {
                    if ($bm->ecospace) {
                        $bookmarks->push([
                            'title' => $bm->ecospace->ecospaceName ?? 'Untitled EcoSpace',
                            'link' => route('ecospace', ['name' => $bm->ecospace->ecospaceName ?? '#']),
                            'type' => 'ecospace',
                        ]);
                    }
                }
            }

            // Event bookmarks
            if ($user->evBookmarks && $user->evBookmarks->count()) {
                foreach ($user->evBookmarks as $bm) {
                    if ($bm->event) {
                        $bookmarks->push([
                            'title' => $bm->event->eventName ?? 'Untitled Event',
                            'link' => route('events.show', ['id' => $bm->event->eventID ?? '#']),
                            'type' => 'event',
                        ]);
                    }
                }
            }

            // Fallback static bookmarks if none found
            if ($bookmarks->isEmpty()) {
                $bookmarks = collect([
                    ['title' => 'Community Garden', 'link' => '#', 'type' => 'ecospace'],
                    ['title' => 'Outdoor Lab', 'link' => '#', 'type' => 'event']
                ]);
            }

            $bookmarks = $bookmarks->values()->all();
        }

        return view('users.show', compact('user', 'events', 'userReviews', 'bookmarks', 'attendingEvents'));
    }

    /**
     * Update the authenticated user's profile (image, name, email).
     */
    public function updateProfile(Request $request, $id)
    {
        // Only the profile owner may update their profile
        if (! auth()->check() || auth()->id() != (int) $id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        $rules = [];
        if ($request->hasFile('profile_image')) {
            $rules['profile_image'] = ['image', 'max:2048'];
        }
        if ($request->filled('name')) {
            $rules['name'] = ['string', 'max:255'];
        }
        if ($request->filled('email')) {
            $rules['email'] = ['email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if (!empty($rules)) {
            $request->validate($rules);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return redirect()->route('users.show', $user->id)->with('success', 'Profile updated successfully.');
    }

    /**
     * Display a paginated list of users as profile cards.
     */
    public function index(HttpRequest $request)
    {
        // If the user is not authenticated and the request expects JSON (AJAX/fetch),
        // return a JSON response signalling that login is required so the frontend
        // can show the login modal without redirecting the browser.
        if (! auth()->check() && $request->expectsJson()) {
            return response()->json(['requires_login' => true], 401);
        }

        $search = $request->input('search');
        $sort = $request->input('sort', 'name_asc');
        // filter: all | has | none
        $hasEcospaces = $request->input('has_ecospaces', 'all');

        $query = User::withCount('ecospaces')
            ->when($search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            })
            ->when($hasEcospaces === 'has', function ($q) {
                $q->has('ecospaces');
            })
            ->when($hasEcospaces === 'none', function ($q) {
                $q->doesntHave('ecospaces');
            });

        // apply sorting
        switch ($sort) {
            case 'name_desc':
                $query = $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query = $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query = $query->orderBy('created_at', 'asc');
                break;
            case 'most_ecospaces':
                $query = $query->orderBy('ecospaces_count', 'desc');
                break;
            case 'least_ecospaces':
                $query = $query->orderBy('ecospaces_count', 'asc');
                break;
            case 'name_asc':
            default:
                $query = $query->orderBy('name', 'asc');
                break;
        }

        $users = $query->paginate(12)->withQueryString();

        return view('users.index', compact('users', 'search', 'sort', 'hasEcospaces'));
    }

    /**
     * Admin: display current (non-archived) users in a table for admin management.
     */
    public function adminIndex(HttpRequest $request)
    {
        $users = User::with('userType')
            ->orderBy('name')
            ->paginate(5);

        return view('admin.accounts', compact('users'));
    }

    /**
     * Archive (soft-delete) a user account. Admin-only action.
     */
    public function archive($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Prevent archiving yourself
        if (auth()->check() && auth()->id() == $user->id) {
            return redirect()->route('admin.users')->with('error', 'You cannot archive your own account.');
        }

        // Prevent archiving other admins
        if (isset($user->userTypeID) && (int)$user->userTypeID === 1) {
            return redirect()->route('admin.users')->with('error', 'Cannot archive an admin account.');
        }

        try {
            DB::beginTransaction();

            // Soft-delete user's ecospace and mark as archived (statusID = 3)
            $userEcospaces = Ecospace::where('userID', $user->id)->whereNull('deleted_at')->get();
            foreach ($userEcospaces as $ecospace) {
                $ecospace->update(['statusID' => 3]);
                $ecospace->delete();
            }

            // Soft-delete user's events and mark as archived (statusID = 3)
            $userEvents = Event::where('userID', $user->id)->whereNull('deleted_at')->get();
            foreach ($userEvents as $event) {
                $event->update(['statusID' => 3]);
                $event->delete();
            }

            // Soft delete the user (sets deleted_at)
            if (!$user->trashed()) {
                $user->delete();
            }

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User account archived successfully. Related EcoSpaces and events were archived.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('admin.users')->with('error', 'Failed to archive user account.');
        }
    }

    /**
     * Restore (unarchive) a soft-deleted user account. Admin-only action.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        try {
            DB::beginTransaction();

            if ($user->trashed()) {
                $user->restore();
            }

            // Restore user's ecospaces and set them to approved (statusID = 2)
            $trashedEcospaces = Ecospace::onlyTrashed()->where('userID', $user->id)->get();
            foreach ($trashedEcospaces as $ecospace) {
                $ecospace->restore();
                $ecospace->update(['statusID' => 2]);
            }

            // Restore user's events and set them to approved (statusID = 2)
            $trashedEvents = Event::onlyTrashed()->where('userID', $user->id)->get();
            foreach ($trashedEvents as $event) {
                $event->restore();
                $event->update(['statusID' => 2]);
            }

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User account restored successfully. Related EcoSpaces and events were restored.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('admin.users')->with('error', 'Failed to restore user account.');
        }
    }

    /**
     * Display a paginated list of archived (soft-deleted) users for admin.
     */
    public function archives(HttpRequest $request)
    {
        $users = User::onlyTrashed()->with('userType')->paginate(5);
        return view('admin.users_archives', compact('users'));
    }
}
