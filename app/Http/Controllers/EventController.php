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

        return redirect()->back()->with('success', 'Event approved!');
    }

    /**
     * Decline (remove) an event: set statusID = 3 and soft-delete.
     */
    public function remove($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['statusID' => 3]);
        $event->delete(); // soft delete

        return redirect()->back()->with('success', 'Event declined and archived.');
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
            'eventDate' => 'required|date',
            'eventAdd' => 'nullable|string|max:255',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_eventImages,eventImageID',
        ]);

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

        return redirect()->route('index.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Owner-facing update for events.
     */
    public function updateOwner(Request $request, $id)
    {
        $validated = $request->validate([
            'eventName' => 'required|string|max:255',
            'eventTypeID' => 'nullable|integer|exists:tbl_eventtypes,eventTypeID',
            'eventDate' => 'required|date',
            'eventAdd' => 'nullable|string|max:255',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_eventImages,eventImageID',
        ]);

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

        return redirect()->route('events.show', auth()->id())->with('success', 'Event updated successfully.');
    }

    /**
     * Restore a soft-deleted event and set status to approved (2).
     */
    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();
        $event->update(['statusID' => 2]);

        return redirect()->route('archives.index')->with('success', 'Event restored and approved successfully!');
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

        return redirect()->route('archives.index')->with('success', 'Event permanently deleted.');
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
            'eventName' => 'required|string|max:255',
            'eventTypeID' => 'required|integer|exists:tbl_eventtypes,eventTypeID',
            'eventDate' => 'required|date',
            'eventAdd' => 'required|string|max:255',
            'priceTierID' => 'required|integer|exists:tbl_pricetiers,priceTierID',
            'eventDesc' => 'nullable|string',
            'images.*' => 'image|max:5120', // 5MB per image
        ]);

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

        return redirect()->route('dashboard')->with('success', 'Event submitted successfully.');
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
        $search = $request->input('search');

        // Only show approved events (statusID = 2)
        $query = Event::with(['images', 'priceTier', 'eventType', 'user', 'status'])
            ->where('statusID', 2)
            ->withCount('attendees')
            ->orderBy('eventDate', 'desc');

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

        $events = $query->paginate(12)->appends(['search' => $search]);

        return view('events.all', compact('events'));
    }
}
