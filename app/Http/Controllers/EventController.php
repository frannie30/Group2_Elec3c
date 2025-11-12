<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\PriceTier;
use App\Models\EventType;
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

        return view('users.submitevent', compact('eventtypes', 'pricetiers'));
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
        $event = Event::with(['images', 'priceTier', 'eventType', 'user', 'status'])->findOrFail($id);

        return view('users.event', compact('event'));
    }
}
