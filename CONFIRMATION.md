# Server-side confirmation (no JavaScript)

This project includes a small helper to present a server-side confirmation step for create/update actions without using JavaScript.

Files added:

- `app/Http/Controllers/Traits/Confirmable.php` — trait with `confirmStep()` helper.
- `resources/views/shared/confirm-action.blade.php` — Blade that shows the summary and reposts inputs with a `confirmed` hidden field.

How it works

1. The initial form posts to your controller `store` or `update` method as usual.
2. At the top of the controller method call `confirmStep($request, $title, $message)`.
   - If the request is not yet confirmed, `confirmStep` returns a View (`shared.confirm-action`) and the controller should `return` that view.
   - If the request includes `confirmed` (the user already pressed Yes), `confirmStep` returns `null` and the controller proceeds.
3. The confirm view reposts the same inputs (as hidden fields) back to the same URL with `confirmed=1` so your controller can validate and persist.

Example (controller `store` method)

```php
use App\Http\Controllers\Traits\Confirmable;

class EcoSpaceController extends Controller
{
    use Confirmable;

    public function store(Request $request)
    {
        // show confirmation first
        $confirm = $this->confirmStep($request, 'Confirm Create EcoSpace', 'Are you sure you want to create this EcoSpace?');
        if ($confirm) return $confirm;

        // now proceed with normal validation and saving
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // ... other rules
        ]);

        \App\Models\EcoSpace::create($validated);

        return redirect()->route('ecospaces.index')->with('success', 'Created successfully.');
    }
}
```

Notes
- The confirm page renders all inputs passed in the original POST (except `_token`) as hidden fields. If you upload files, you'll need to handle them specially (store temporarily or re-upload after confirmation).
- This approach does not use JavaScript and uses Eloquent/regular controller flow. It's compatible with Laravel 12.

If you want, I can adapt one or more existing controllers (e.g. `EcoSpaceController`) to use this trait across the app.
