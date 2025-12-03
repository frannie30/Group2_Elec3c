<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\PriceTier;
use App\Models\EventType;
use App\Models\Guestlist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Show the submit event form.
     */
    public function submitEvent()
    {
        $eventtypes = \DB::table('tbl_eventtypes')->get();
        $pricetiers = \DB::table('tbl_pricetiers')->get();

        return view('events.submitevent', compact('eventtypes', 'pricetiers'));
    }

    /**
     * Approve an event (set statusID = 2).
     */
    public function approve($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['statusID' => 2]);

        return redirect()->route('admin.events')->with('success', 'Event approved successfully.');
    }

    /**
     * Decline (remove) an event: set statusID = 3 and soft-delete.
     */
    public function remove($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['statusID' => 3]);
        $event->delete(); // soft delete

        return redirect()->route('admin.events')->with('success', 'Event archived successfully.');
    }

    /**
     * Show a confirmation page (no JS) before soft-removing an event.
     */
    public function confirmRemove($id)
    {
        $event = Event::findOrFail($id);
        $title = 'Confirm Remove Event';
        $message = 'Are you sure you want to remove the event "' . $event->eventName . '"? This will archive the event (soft-delete).';
        $actionRoute = route('admin.event.remove', $id);
        $cancelUrl = url()->previous() ?: route('index.index');

        return view('shared.confirm-delete', compact('title', 'message', 'actionRoute', 'cancelUrl'));
    }

    /**
     * Show edit form for an event (admin).
     */
    public function edit($id)
    {
        $event = Event::with('images')->findOrFail($id);
        $pricetiers = PriceTier::all();
        $eventtypes = EventType::all();

        return view('admin.edit_event', compact('event', 'pricetiers', 'eventtypes'));
    }

    /**
     * Owner-facing edit form for events.
     */
    public function editOwner($id)
    {
        $event = Event::with('images')->findOrFail($id);
        if (!auth()->check() || auth()->id() != $event->userID) {
            abort(403);
        }
        $pricetiers = PriceTier::all();
        $eventtypes = EventType::all();

        return view('events.edit-event', compact('event', 'pricetiers', 'eventtypes'));
    }

    /**
     * Update an event (admin).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'eventName' => 'required|string|max:255',
            'eventTypeID' => 'nullable|integer|exists:tbl_eventtypes,eventTypeID',
            'eventDate' => 'required|date|after_or_equal:now',
            'eventAdd' => 'nullable|string|max:255',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_eventImages,eventImageID',
        ]);

        // Server-side safety check: ensure datetime is not in the past
        try {
            $eventDate = \Illuminate\Support\Carbon::parse($request->input('eventDate'));
            if ($eventDate->lt(now())) {
                return back()->withInput()->withErrors(['eventDate' => 'Event date and time must be now or in the future.']);
            }
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['eventDate' => 'Invalid event date and time.']);
        }

        $event = Event::findOrFail($id);

        $event->update([
            'eventName' => $request->input('eventName'),
            'eventTypeID' => $request->input('eventTypeID'),
            'eventAdd' => $request->input('eventAdd'),
            'priceTierID' => $request->input('priceTierID'),
            'eventDate' => $request->input('eventDate'),
            'eventDesc' => $request->input('eventDesc'),
        ]);

        // Remove selected images
        if ($request->filled('images_to_remove')) {
            foreach ($request->images_to_remove as $imgId) {
                $img = EventImage::find($imgId);
                if ($img && $img->eventID == $event->eventID) {
                    if ($img->path && Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }
        }

        // Add uploaded images
        if ($request->hasFile('images')) {
            $maxOrder = EventImage::where('eventID', $event->eventID)->max('order');
            if (!is_numeric($maxOrder)) $maxOrder = -1;
            $index = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $path = $file->store('events/' . $event->eventID, 'public');
                EventImage::create([
                    'eventID' => $event->eventID,
                    'path' => $path,
                    'order' => $maxOrder + 1 + $index,
                    'caption' => null,
                ]);
                $index++;
            }
        }

        // After admin edit, redirect back to the admin events listing
        return redirect()->route('admin.events')->with('success', 'Event updated successfully.');
    }

    /**
     * Owner-facing update for events.
     */
    public function updateOwner(Request $request, $id)
    {
        $validated = $request->validate([
            'eventName' => 'required|string|max:255',
            'eventTypeID' => 'nullable|integer|exists:tbl_eventtypes,eventTypeID',
            'eventDate' => 'required|date|after_or_equal:now',
            'eventAdd' => 'nullable|string|max:255',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_eventImages,eventImageID',
        ]);

        // Server-side safety check: ensure datetime is not in the past
        try {
            $eventDate = \Illuminate\Support\Carbon::parse($request->input('eventDate'));
            if ($eventDate->lt(now())) {
                return back()->withInput()->withErrors(['eventDate' => 'Event date and time must be now or in the future.']);
            }
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['eventDate' => 'Invalid event date and time.']);
        }

        $event = Event::findOrFail($id);
        if (!auth()->check() || auth()->id() != $event->userID) {
            abort(403);
        }

        $event->update([
            'eventName' => $request->input('eventName'),
            'eventTypeID' => $request->input('eventTypeID'),
            'eventAdd' => $request->input('eventAdd'),
            'priceTierID' => $request->input('priceTierID'),
            'eventDate' => $request->input('eventDate'),
            'eventDesc' => $request->input('eventDesc'),
        ]);

        // Remove selected images
        if ($request->filled('images_to_remove')) {
            foreach ($request->images_to_remove as $imgId) {
                $img = EventImage::find($imgId);
                if ($img && $img->eventID == $event->eventID) {
                    if ($img->path && Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }
        }

        // Add uploaded images
        if ($request->hasFile('images')) {
            $maxOrder = EventImage::where('eventID', $event->eventID)->max('order');
            if (!is_numeric($maxOrder)) $maxOrder = -1;
            $index = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $filename = \Illuminate\Support\Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('events/' . $event->eventID, $filename, 'public');

                EventImage::create([
                    'eventID' => $event->eventID,
                    'path' => $path,
                    'order' => $maxOrder + 1 + $index,
                    'caption' => null,
                ]);
                $index++;
            }
        }

        // Owner-facing update still redirects to the owner's events listing page
        return redirect()->route('my.events')->with('success', 'Event updated successfully.');
    }

    /**
     * Restore a soft-deleted event and set status to approved (2).
     */
    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();
        $event->update(['statusID' => 2]);

        return redirect()->route('admin.events')->with('success', 'Event restored successfully.');
    }

    /**
     * Permanently delete a soft-deleted event.
     */
    public function delete($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        // delete associated images from storage
        foreach ($event->images as $img) {
            if ($img->path && Storage::disk('public')->exists($img->path)) {
                Storage::disk('public')->delete($img->path);
            }
            $img->delete();
        }
        $event->forceDelete();

        return redirect()->route('admin.events.archives')->with('success', 'Event permanently deleted.');
    }

    /**
     * Show a confirmation page (no JS) before permanently deleting an event.
     */
    public function confirmDelete($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $title = 'Confirm Permanent Delete';
        $message = 'Are you sure you want to permanently delete the event "' . $event->eventName . '"? This cannot be undone.';
        $actionRoute = route('admin.events.delete', $id);
        $cancelUrl = url()->previous() ?: route('archives.index');

        return view('shared.confirm-delete', compact('title', 'message', 'actionRoute', 'cancelUrl'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'eventName' => 'required|string|min:5|max:255',
            'eventTypeID' => 'required|integer|exists:tbl_eventtypes,eventTypeID',
            'eventDate' => 'required|date|after_or_equal:now',
            'eventAdd' => 'required|string|min:5|max:255',
            'priceTierID' => 'required|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string|min:5',
            'images.*' => 'image|max:5120', // 5MB per image
        ]);

        // Server-side safety check: ensure datetime is not in the past
        try {
            $eventDate = \Illuminate\Support\Carbon::parse($request->input('eventDate'));
            if ($eventDate->lt(now())) {
                return back()->withInput()->withErrors(['eventDate' => 'Event date and time must be now or in the future.']);
            }
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['eventDate' => 'Invalid event date and time.']);
        }

        $user = Auth::user();

        $event = Event::create([
            'eventName' => $request->input('eventName'),
            'eventTypeID' => $request->input('eventTypeID'),
            'userID' => $user->id,
            'eventAdd' => $request->input('eventAdd'),
            // Force status to 1 (pending) regardless of client input
            'statusID' => 1,
            'priceTierID' => $request->input('priceTierID'),
            'eventDate' => $request->input('eventDate'),
            'eventDesc' => $request->input('eventDesc'),
            'isDone' => false,
            'isArchived' => false,
        ]);

        // Handle uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                if (!$file->isValid()) continue;
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('events/' . $event->eventID, $filename, 'public');

                EventImage::create([
                    'eventID' => $event->eventID,
                    'path' => $path,
                    'order' => $i,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event detail page.
     */
    public function show($id)
    {
        // Event reviews were removed; do not eager-load them here.
        $event = Event::with(['images', 'priceTier', 'eventType', 'user', 'status'])->findOrFail($id);

        // If eventDate is in the past and isFinished is not set, mark it finished.
        // Use Carbon to compare dates safely.
        try {
            if (!empty($event->eventDate)) {
                $eventDate = \Illuminate\Support\Carbon::parse($event->eventDate);
                if ($eventDate->lt(now()) && empty($event->isFinished)) {
                    // Update the flag in DB and the model instance
                    $event->update(['isFinished' => true]);
                    $event->isFinished = true;
                }
            }
        } catch (\Throwable $e) {
            // If parsing fails, ignore and continue (don't break the page)
        }

        $isGoing = false;
        $guest = null;
        $guestlist = null;
        if (auth()->check()) {
            $guest = Guestlist::where('eventID', $id)->where('userID', auth()->id())->first();
            $isGoing = $guest ? (bool) $guest->isGoing : false;

            // If the current user is the organizer, load the event's guestlist (attendees)
            if (auth()->id() == $event->userID) {
                // Paginate guestlist for organizers to avoid huge lists on page load
                $guestlist = Guestlist::where('eventID', $id)->where('isGoing', true)->with('user')->paginate(10);
            }
        }

        return view('events.show', compact('event', 'isGoing', 'guest', 'guestlist'));
    }

    /**
     * Show authenticated user's events (owners and regular users).
     */
    public function myEvents(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('dashboard')->with('error', 'Please log in to view your events.');
        }

        $user = auth()->user();

        // Only allow normal users (2) and owners (3) to view their own events list here.
        if (! in_array((int) $user->userTypeID, [2, 3], true)) {
            abort(403, 'Unauthorized.');
        }

        $search = $request->input('search');

        $query = Event::where('userID', $user->id)
            ->with(['images', 'priceTier', 'eventType', 'status'])
            ->withCount('attendees')
            ->orderByDesc('eventDate');

        if (! empty($search)) {
            $query->where('eventName', 'like', "%{$search}%");
        }

        $events = $query->paginate(12)->appends(['search' => $search]);

        return view('events.my_events', compact('events'));
    }

    /**
     * Show a paginated list of events.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Only show approved events (statusID = 2)
        $query = Event::with(['images', 'priceTier', 'eventType', 'user', 'status'])
            ->where('statusID', 2)
            ->withCount('attendees')
            ->orderBy('eventDate', 'desc');

        if (!empty($search)) {
            // Only search by event name when using the global navigation search
            $query->where('eventName', 'like', "%{$search}%");
        }

        $events = $query->paginate(12)->appends(['search' => $search]);

        return view('events.index', compact('events'));
    }

    /**
     * Show the full paginated list of events ("View all").
     */
    public function all(Request $request)
    {
        // If the user is not authenticated and the request expects JSON (AJAX/fetch),
        // return a JSON response signalling that login is required so the frontend
        // can show the login modal without redirecting the browser.
        if (! auth()->check() && $request->expectsJson()) {
            return response()->json(['requires_login' => true], 401);
        }

        // Moveable sorting/filtering logic: apply when viewing the full events listing
        $search = $request->input('search');
        $sort = $request->input('sort', 'date_desc');
        $eventType = $request->input('event_type');
        $priceTier = $request->input('price_tier');
        $hasImages = $request->input('has_images', 'all');

        $query = Event::with(['images', 'priceTier', 'eventType', 'user', 'status'])
            ->where('statusID', 2)
            ->withCount('attendees');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('eventName', 'like', "%{$search}%")
                  ->orWhere('eventAdd', 'like', "%{$search}%")
                  ->orWhere('eventDesc', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('eventType', function ($etq) use ($search) {
                      $etq->where('eventTypeName', 'like', "%{$search}%");
                  });
            });
        }

        // filters
        if (!empty($eventType)) {
            $query->where('eventTypeID', $eventType);
        }
        if (!empty($priceTier)) {
            $query->where('priceTierID', $priceTier);
        }
        if ($hasImages === 'has') {
            $query->has('images');
        } elseif ($hasImages === 'none') {
            $query->doesntHave('images');
        }

        // sorting
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('eventDate', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('eventDate', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('eventName', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('eventName', 'desc');
                break;
            case 'most_attendees':
                $query->orderBy('attendees_count', 'desc');
                break;
            case 'least_attendees':
                $query->orderBy('attendees_count', 'asc');
                break;
            default:
                $query->orderBy('eventDate', 'desc');
                break;
        }

        $events = $query->paginate(12)->withQueryString();

        $eventTypes = \App\Models\EventType::orderBy('eventTypeName')->get();
        $priceTiers = \App\Models\PriceTier::orderBy('pricetier')->get();

        return view('events.all', compact('events', 'search', 'sort', 'eventType', 'priceTier', 'hasImages', 'eventTypes', 'priceTiers'));
    }

    /**
     * Admin preview: standalone events admin page (for UI preview)
     */
    public function adminEvents(Request $request)
    {
        // support sorting: newest (default) or oldest
        $sort = $request->input('sort', 'newest');

        $query = Event::query();

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            $query->orderByDesc('dateCreated');
        }

        $events = $query->paginate(5)->withQueryString();
        return view('admin.events', compact('events', 'sort'));
    }

    /**
     * Admin-facing: show pending events only (create page split)
     */
    public function adminEventsCreate()
    {
        $sort = request()->input('sort', 'newest');

        $query = Event::where('statusID', 1)
            ->with(['user', 'images', 'priceTier', 'eventType']);

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            $query->orderByDesc('dateCreated');
        }

        $events = $query->paginate(5, ['*'], 'events_page')->withQueryString();

        return view('admin.events_create', compact('events', 'sort'));
    }

    /**
     * Admin-facing: show archived events only (archives split)
     */
    public function adminEventsArchives()
    {
        $sort = request()->input('sort', 'newest');

        $query = Event::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'images', 'priceTier', 'eventType']);

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            $query->orderByDesc('dateCreated');
        }

        $events = $query->paginate(5, ['*'], 'events_page')->withQueryString();

        return view('admin.events_archives', compact('events', 'sort'));
    }
}
