<?php

namespace App\Http\Controllers;

use App\Models\Ecospace;
use App\Models\Status;
use App\Models\PriceTier;
use App\Models\Image;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request as HttpRequest;

class EcospaceController extends Controller
{
    // User-facing: show the form to submit an ecospace
    public function submitEcospace()
    {
    // Only provide price tiers to the form; status is forced to pending (1)
    $pricetiers = PriceTier::all();
    return view('users.submitecospace', compact('pricetiers'));
    }

    /**
     * Public dashboard showing approved ecospaces (statusID = 2)
     */
    public function dashboard(HttpRequest $request)
    {
        $search = $request->input('search');

        $ecospaces = Ecospace::with(['images', 'priceTier'])
            ->where('statusID', 2)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('ecospaceName', 'like', "%{$search}%")
                      ->orWhere('ecospaceAdd', 'like', "%{$search}%");
                });
            })
            ->paginate(12);

        // Also load approved events for the public dashboard
        $events = Event::with(['images', 'priceTier'])
            ->where('statusID', 2)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('eventName', 'like', "%{$search}%")
                      ->orWhere('eventAdd', 'like', "%{$search}%");
                });
            })
            ->paginate(12, ['*'], 'events_page');

        return view('dashboard', [
            'ecospaces' => $ecospaces,
            'events' => $events,
            'search' => $search,
        ]);
    }

    // User-facing: show an ecospace by name (query string: name)
    public function showEcospace(Request $request)
    {
        $name = $request->input('name');

        $ecospace = $name ? Ecospace::where('ecospaceName', $name)->first() : null;

        return view('users.ecospace', compact('name', 'ecospace'));
    }

    // Store a new ecospace (user-submitted)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ecospaceName' => 'required|string|max:191',
            'ecospaceAdd' => 'nullable|string|max:255',
            'ecospaceDesc' => 'nullable|string|max:1000',
            // Price tier must exist in tbl_pricetiers
            'priceTierID' => 'required|integer|exists:tbl_pricetiers,priceTierID',
            // Images are optional; each must be a valid image under 5MB
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'openingHours' => 'nullable|string|max:50',
            'closingHours' => 'nullable|string|max:50',
            'daysOpened' => 'nullable|string|max:255',
        ]);

        $ecospace = Ecospace::create([
            'ecospaceName' => $request->ecospaceName,
            'ecospaceAdd' => $request->ecospaceAdd,
            'ecospaceDesc' => $request->ecospaceDesc,
            'userID' => auth()->user()->id,
            // Force newly-submitted ecospaces to pending
            'statusID' => 1,
            'priceTierID' => $request->priceTierID,
            'openingHours' => $request->openingHours,
            'closingHours' => $request->closingHours,
            'daysOpened' => $request->daysOpened,
            'dateCreated' => Carbon::now(),
        ]);

        // If images were uploaded, store them and create image records
        if ($request->hasFile('images')) {
            $index = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                // store under storage/app/public/ecospace_images
                $path = $file->store('ecospace_images', 'public');

                Image::create([
                    'ecospaceID' => $ecospace->ecospaceID,
                    'path' => $path,
                    'order' => $index,
                    'caption' => null,
                ]);

                $index++;
            }
        }

        return redirect()->route('dashboard')->with('success', 'Ecospace submitted successfully.');
    }

    // Admin-facing: list pending ecospaces
    public function create()
    {
        // Pending ecospaces use statusID = 1
        $ecospaces = Ecospace::where('statusID', 1)
            ->with(['user', 'status', 'priceTier'])
            ->paginate(5);
        // Also fetch pending events for the admin create page
        $events = Event::where('statusID', 1)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->paginate(5, ['*'], 'events_page');

        return view('admin.create', compact('ecospaces', 'events'));
    }

    // Admin-facing: list approved ecospaces
    public function index()
    {
        // Approved ecospaces use statusID = 2
        $ecospaces = Ecospace::where('statusID', 2)
            ->with(['user', 'status', 'priceTier'])
            ->paginate(5);
        // Also load approved events for display on admin dashboard
        $events = Event::where('statusID', 2)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->paginate(5, ['*'], 'events_page');

        return view('admin.index', compact('ecospaces', 'events'));
    }

    public function archives()
    {
        // Show ecospaces that were declined/archived (statusID = 3) and are soft-deleted
        $ecospaces = Ecospace::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'status', 'priceTier'])
            ->paginate(5);
        // Also fetch trashed events (statusID = 3) for display in archives
        $events = Event::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->paginate(5, ['*'], 'events_page');

        return view('admin.archives', compact('ecospaces', 'events'));
    }

    public function approve($id)
    {
        // Set to approved
        Ecospace::where('ecospaceID', $id)->update(['statusID' => 2]);
        return redirect()->back()->with('success', 'Ecospace approved!');
    }

    public function remove($id)
    {
        // Mark as removed/declined by setting statusID = 3 and soft-delete
        $ecospace = Ecospace::findOrFail($id);
        $ecospace->update(['statusID' => 3]);
        $ecospace->delete(); // soft delete (sets deleted_at)
        return redirect()->back()->with('success', 'Ecospace declined and archived.');
    }

    public function restore($id)
    {
        $ecospace = Ecospace::withTrashed()->findOrFail($id);
        // Restore soft-deleted record
        $ecospace->restore();
        // Mark as approved when restored
        $ecospace->update(['statusID' => 2]);

        return redirect()->route('index.index')
                         ->with('success', 'Ecospace restored and approved successfully!');
    }

    public function delete($id)
    {
        $ecospace = Ecospace::withTrashed()->findOrFail($id);
        $ecospace->forceDelete();

        return redirect()->route('archives.index')
                         ->with('success', 'Ecospace permanently deleted.');
    }

    public function edit($id)
    {
        $ecospace = Ecospace::with('images')->findOrFail($id);
        $pricetiers = PriceTier::all();
        return view('admin.edit', compact('ecospace', 'pricetiers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ecospaceName' => 'required|string|max:191',
            'ecospaceAdd' => 'nullable|string|max:255',
            'ecospaceDesc' => 'nullable|string|max:1000',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            'openingHours' => 'nullable|string|max:50',
            'closingHours' => 'nullable|string|max:50',
            'daysOpened' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_esImages,esImageID',
        ]);

        $ecospace = Ecospace::findOrFail($id);

        // Update basic fields
        $ecospace->update([
            'ecospaceName' => $request->ecospaceName,
            'ecospaceAdd' => $request->ecospaceAdd,
            'ecospaceDesc' => $request->ecospaceDesc,
            'priceTierID' => $request->priceTierID,
            'openingHours' => $request->openingHours,
            'closingHours' => $request->closingHours,
            'daysOpened' => $request->daysOpened,
        ]);

        // Remove selected images
        if ($request->filled('images_to_remove')) {
            foreach ($request->images_to_remove as $imgId) {
                $img = Image::find($imgId);
                if ($img && $img->ecospaceID == $ecospace->ecospaceID) {
                    // delete file from storage
                    if ($img->path && Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }
        }

        // Add uploaded images
        if ($request->hasFile('images')) {
            $maxOrder = Image::where('ecospaceID', $ecospace->ecospaceID)->max('order');
            if (!is_numeric($maxOrder)) $maxOrder = -1;
            $index = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) continue;
                $path = $file->store('ecospace_images', 'public');
                Image::create([
                    'ecospaceID' => $ecospace->ecospaceID,
                    'path' => $path,
                    'order' => $maxOrder + 1 + $index,
                    'caption' => null,
                ]);
                $index++;
            }
        }

        return redirect()->route('index.index')->with('success', 'Ecospace updated successfully.');
    }
}