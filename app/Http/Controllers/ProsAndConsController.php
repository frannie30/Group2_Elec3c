<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProAndCon;
use App\Models\Ecospace;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;

class ProsAndConsController extends Controller
{
    /**
     * Show the form to create a pro or con for an ecospace.
     */
    public function create($ecospaceId)
    {
        $ecospace = Ecospace::findOrFail($ecospaceId);
        return view('proscons.create', ['ecospace' => $ecospace]);
    }

    /**
     * Store a pro/con entry.
     */
    public function store(Request $request, $ecospaceId)
    {
        $request->validate([
            'isPro' => 'required|in:0,1',
            'description' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $ecospace = Ecospace::findOrFail($ecospaceId);

        $pc = ProAndCon::create([
            'isPro' => (int) $request->input('isPro'),
            'description' => $request->input('description'),
            'userID' => $user->id,
            'ecospaceID' => $ecospace->ecospaceID,
            'dateCreated' => now(),
            'dateUpdated' => now(),
        ]);

        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Pro/Con submitted successfully.');
    }

    /**
     * Show edit form for a pro/con entry.
     */
    public function edit($ecospaceId, $pcId)
    {
        $pc = ProAndCon::findOrFail($pcId);
        $ecospace = Ecospace::findOrFail($ecospaceId);

        if (auth()->id() !== $pc->userID) {
            abort(403);
        }

        return view('proscons.edit', ['ecospace' => $ecospace, 'pc' => $pc]);
    }

    /**
     * Update a pro/con entry.
     */
    public function update(Request $request, $ecospaceId, $pcId)
    {
        $pc = ProAndCon::findOrFail($pcId);

        if (auth()->id() !== $pc->userID) {
            abort(403);
        }

        $request->validate([
            'isPro' => 'required|in:0,1',
            'description' => 'required|string|max:255',
        ]);

        $pc->isPro = (int) $request->input('isPro');
        $pc->description = $request->input('description');
        $pc->dateUpdated = now();
        $pc->save();

        $ecospace = Ecospace::findOrFail($ecospaceId);
        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Pro/Con updated successfully.');
    }

    /**
     * Delete a pro/con entry.
     */
    public function destroy($ecospaceId, $pcId)
    {
        $pc = ProAndCon::findOrFail($pcId);

        if (auth()->id() !== $pc->userID) {
            abort(403);
        }

        $pc->delete();

        $ecospace = Ecospace::findOrFail($ecospaceId);
        return redirect()->route('ecospace', ['name' => $ecospace->ecospaceName])->with('success', 'Pro/Con deleted successfully.');
    }
}
