<?php

namespace App\Http\Controllers;

use App\Models\Ecospace;
use App\Models\Status;
use App\Models\PriceTier;
use App\Models\Image;
use Intervention\Image\ImageManagerStatic as Img;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EvBookmark;
use App\Models\EsBookmark;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest;
use App\Models\ProAndCon;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class EcospaceController extends Controller
{
    // User-facing: show the form to submit an ecospace
    public function submitEcospace()
    {
    // Only provide price tiers to the form; status is forced to pending (1)
    $pricetiers = PriceTier::all();
    return view('ecospaces.submitecospace', compact('pricetiers'));
    }

    /**
     * Public dashboard showing approved ecospaces (statusID = 2)
     */
    public function dashboard(HttpRequest $request)
    {
        $search = $request->input('search');


        // Build base query for approved ecospaces
        $baseQuery = Ecospace::with(['images', 'priceTier'])
            ->where('statusID', 2)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                // Only search by ecospace name from global navigation search
                return $query->where('ecospaceName', 'like', "%{$search}%");
            });

        // For the dashboard we only want to display a small preview (4 cards)
        $ecospaces = (clone $baseQuery)->take(4)->get();

        // Events removed from dashboard: moved to dedicated Events page

        $bookmarkedEvents = [];
        $bookmarkedEcospaces = [];
        if (auth()->check()) {
            // Defer to bookmark models (tbl_evbookmarks, tbl_esbookmarks)
            $bookmarkedEvents = EvBookmark::where('userID', auth()->id())->pluck('eventID')->toArray();
            $bookmarkedEcospaces = EsBookmark::where('userID', auth()->id())->pluck('ecospaceID')->toArray();
        }

        return view('dashboard', [
            'ecospaces' => $ecospaces,
            'search' => $search,
            'bookmarkedEvents' => $bookmarkedEvents,
            'bookmarkedEcospaces' => $bookmarkedEcospaces,
        ]);
    }

    /**
     * Public page listing all approved ecospaces (paginated).
     */
    public function all(HttpRequest $request)
    {
        // Debug: log incoming query parameters to help troubleshoot sorting/filtering
        Log::debug('EcospaceController@all query', $request->query());

        $search = $request->input('search');
        // Use same sort keys as events: date_desc (default), date_asc, name_asc, name_desc
        $sort = $request->input('sort', 'date_desc');
        $hasReviews = $request->input('has_reviews', 'all');
        // support multiple star filters (e.g. stars[]=5&stars[]=4) and '0' for no reviews
        $stars = $request->input('stars', []);
        if (!is_array($stars)) {
            $stars = [$stars];
        }
        // whether to only show currently open ecospaces
        $openNow = $request->boolean('open_now', false);

        // Build base query for approved ecospaces
        // Include review aggregates to support sorting by review averages
        $baseQuery = Ecospace::with(['images', 'priceTier'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('statusID', 2)
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                // Only search by ecospace name from global navigation search
                return $query->where('ecospaceName', 'like', "%{$search}%");
            });

        // Filtering by presence/absence of reviews
        if ($hasReviews === 'with') {
            $baseQuery = $baseQuery->has('reviews');
        } elseif ($hasReviews === 'without') {
            $baseQuery = $baseQuery->doesntHave('reviews');
        }

        // Note: support for multiple star selections and "0" (no reviews) is handled after fetching
        // because the rounded average and absence-of-reviews are easier to evaluate in PHP
        // given the flexible set of requested values.

        // Sorting options (match `events.all` keys):
        // - date_desc / date_asc => dateCreated desc/asc
        // - name_asc / name_desc => ecospaceName asc/desc
        // - highest / lowest => average review rating desc/asc
        switch ($sort) {
            case 'date_asc':
                $baseQuery = $baseQuery->orderBy('dateCreated', 'asc');
                break;
            case 'name_asc':
                $baseQuery = $baseQuery->orderBy('ecospaceName', 'asc');
                break;
            case 'name_desc':
                $baseQuery = $baseQuery->orderBy('ecospaceName', 'desc');
                break;
            case 'highest':
                $baseQuery = $baseQuery->orderByDesc('reviews_avg_rating');
                break;
            case 'lowest':
                $baseQuery = $baseQuery->orderBy('reviews_avg_rating');
                break;
            default:
                $baseQuery = $baseQuery->orderByDesc('dateCreated');
                break;
        }

        // If no special post-filters (multiple stars) are requested,
        // we can paginate directly using the query builder.
        $needsPostFilter = (is_array($stars) && count($stars) > 0 && !(count($stars) === 1 && in_array('', $stars, true)));

        if (!$needsPostFilter) {
            $ecospaces = $baseQuery->paginate(12)->withQueryString();
        } else {
            // Fetch all matching rows (reasonable for moderate datasets) and apply PHP-side filters:
            $all = $baseQuery->get();

            // Normalize star filters to a set of integers (allowed 0..5)
            $starSet = collect($stars)->filter(fn($s) => in_array((string)$s, ['0','1','2','3','4','5'], true))->map(fn($s) => (int)$s)->unique()->values()->all();

            $filtered = $all->filter(function ($ecospace) use ($starSet) {
                // Star filtering only (rounded average or no reviews)
                if (!empty($starSet)) {
                    $rounded = null;
                    if (isset($ecospace->reviews_avg_rating) && $ecospace->reviews_avg_rating !== null) {
                        $rounded = (int) round($ecospace->reviews_avg_rating);
                    }

                    $hasReviewsCount = isset($ecospace->reviews_count) ? (int) $ecospace->reviews_count : ($ecospace->reviews()->count() ?? 0);

                    $matchStars = false;
                    foreach ($starSet as $star) {
                        if ($star === 0) {
                            if ($hasReviewsCount === 0) { $matchStars = true; break; }
                        } else {
                            if ($rounded !== null && $rounded === $star) { $matchStars = true; break; }
                        }
                    }

                    if (!$matchStars) return false;
                }

                return true;
            })->values();

            // Manual pagination for filtered collection
            $perPage = 12;
            $page = (int) ($request->query('page', 1));
            $offset = ($page - 1) * $perPage;
            $paginatedItems = $filtered->slice($offset, $perPage)->values();

            $ecospaces = new LengthAwarePaginator($paginatedItems, $filtered->count(), $perPage, $page, [
                'path' => url()->current(),
                'pageName' => 'page',
            ]);
            $ecospaces->appends($request->query());
        }

        $bookmarkedEvents = [];
        $bookmarkedEcospaces = [];
        if (auth()->check()) {
            $bookmarkedEvents = EvBookmark::where('userID', auth()->id())->pluck('eventID')->toArray();
            $bookmarkedEcospaces = EsBookmark::where('userID', auth()->id())->pluck('ecospaceID')->toArray();
        }

        $priceTiers = PriceTier::orderBy('pricetier')->get();

        return view('ecospaces.index', [
            'ecospaces' => $ecospaces,
            'search' => $search,
            'sort' => $sort,
            'stars' => $stars,
            'priceTier' => $request->input('price_tier'),
            'priceTiers' => $priceTiers,
            'bookmarkedEvents' => $bookmarkedEvents,
            'bookmarkedEcospaces' => $bookmarkedEcospaces,
        ]);
    }

    // User-facing: show an ecospace by name (query string: name)
    public function showEcospace(Request $request)
    {
        // Debug: log incoming query parameters for reviews sorting/filtering
        Log::debug('EcospaceController@showEcospace query', $request->query());

        $name = $request->input('name');

        $ecospace = null;
        if ($name) {
            $ecospace = Ecospace::with(['images', 'priceTier', 'status', 'user'])->where('ecospaceName', $name)->first();

            if ($ecospace) {
                // Allow filtering of the pros/cons panel via `pc` query param: 'both' (default), 'pros', 'cons'
                $pcFilter = request()->query('pc', 'both');

                // Load only the requested type to avoid unnecessary queries when user filters
                if ($pcFilter === 'pros') {
                    $pros = ProAndCon::where('ecospaceID', $ecospace->ecospaceID)
                        ->where('isPro', 1)
                        ->with('user')
                        ->orderByDesc('dateCreated')
                        ->paginate(4, ['*'], 'pros_page')
                        ->withQueryString();

                    $cons = collect();
                } elseif ($pcFilter === 'cons') {
                    $cons = ProAndCon::where('ecospaceID', $ecospace->ecospaceID)
                        ->where('isPro', 0)
                        ->with('user')
                        ->orderByDesc('dateCreated')
                        ->paginate(4, ['*'], 'cons_page')
                        ->withQueryString();

                    $pros = collect();
                } else {
                    // both
                    $pros = ProAndCon::where('ecospaceID', $ecospace->ecospaceID)
                        ->where('isPro', 1)
                        ->with('user')
                        ->orderByDesc('dateCreated')
                        ->paginate(4, ['*'], 'pros_page')
                        ->withQueryString();

                    $cons = ProAndCon::where('ecospaceID', $ecospace->ecospaceID)
                        ->where('isPro', 0)
                        ->with('user')
                        ->orderByDesc('dateCreated')
                        ->paginate(4, ['*'], 'cons_page')
                        ->withQueryString();
                }

                // Paginate reviews 5 per page; use a named page param to avoid conflicts
                // Apply optional rating filter and sort from query params for server-side handling
                $ratingFilter = request()->query('rating'); // 1..5
                $sort = request()->query('sort', 'newest'); // newest, oldest, highest, lowest

                $reviewsQuery = $ecospace->reviews()->with(['images', 'user']);

                if ($ratingFilter && in_array((string)$ratingFilter, ['1','2','3','4','5'])) {
                    // Filter by rounded rating value for compatibility with fractional ratings
                    $reviewsQuery = $reviewsQuery->whereRaw('ROUND(rating) = ?', [(int)$ratingFilter]);
                }

                if ($sort === 'highest') {
                    $reviewsQuery = $reviewsQuery->orderByDesc('rating');
                } elseif ($sort === 'lowest') {
                    $reviewsQuery = $reviewsQuery->orderBy('rating');
                } elseif ($sort === 'oldest') {
                    $reviewsQuery = $reviewsQuery->orderBy('dateCreated');
                } else {
                    $reviewsQuery = $reviewsQuery->orderByDesc('dateCreated');
                }

                $reviews = $reviewsQuery->paginate(4, ['*'], 'reviews_page')->withQueryString();

                // Debug: log paginator class and count to help diagnose pagination issues
                try {
                    Log::debug('Ecospace show - reviews paginator', [
                        'class' => is_object($reviews) ? get_class($reviews) : gettype($reviews),
                        'count' => is_countable($reviews) ? count($reviews) : null,
                        'total' => method_exists($reviews, 'total') ? $reviews->total() : null,
                        'pageName' => method_exists($reviews, 'getPageName') ? $reviews->getPageName() : 'reviews_page',
                    ]);
                } catch (\Throwable $e) {
                    Log::debug('Ecospace show - reviews debug failed: ' . $e->getMessage());
                }
            } else {
                $pros = collect();
                $cons = collect();
                $reviews = collect();
            }
        }

        // Overall review stats and images (used for carousel and summary)
        $reviewCount = 0;
        $avgRating = null;
        $reviewImgs = [];
        $reviewStarsTotal = 0;
        $latestReviewDate = null;
        if (!empty($ecospace)) {
            $reviewCount = (int) $ecospace->reviews()->count();
            $avgRating = $reviewCount ? (float) $ecospace->reviews()->avg('rating') : null;
            // Sum of all rating values (total stars across all reviews)
            $reviewStarsTotal = $ecospace->reviews()->sum('rating');
            $reviewImgs = $ecospace->reviews()->with('images')->get()->flatMap(function($r){
                return $r->images->pluck('revImgName')->map(fn($p) => Storage::url($p));
            })->toArray();
            // Latest review date (short format, e.g., "Nov 16")
            $latest = $ecospace->reviews()->orderByDesc('dateCreated')->value('dateCreated');
            if ($latest) {
                $latestReviewDate = Carbon::parse($latest)->format('M d');
            }
            // compute open/closed for the view as well
            $openResult = $this->computeOpenStatus($ecospace);
            $isOpenNow = $openResult['isOpenNow'] ?? null;
            $openUntil = $openResult['openUntil'] ?? null;
        }

        return view('ecospaces.show', compact('name', 'ecospace', 'pros', 'cons', 'reviews', 'reviewCount', 'avgRating', 'reviewImgs', 'reviewStarsTotal', 'latestReviewDate', 'isOpenNow', 'openUntil'));
    }

    /**
     * API endpoint: return JSON indicating whether an ecospace is open now.
     * Accepts query params: `id` (ecospaceID) or `name` (ecospaceName).
     */
    public function openStatusApi(Request $request): JsonResponse
    {
        $id = $request->query('id');
        $name = $request->query('name');

        $ecospace = null;
        if ($id) {
            $ecospace = Ecospace::find($id);
        } elseif ($name) {
            $ecospace = Ecospace::where('ecospaceName', $name)->first();
        }

        if (!$ecospace) {
            return response()->json(['error' => 'Ecospace not found'], 404);
        }

        $result = $this->computeOpenStatus($ecospace);

        return response()->json($result);
    }

    /**
     * Compute open/closed status for an ecospace.
     * Returns array: ['isOpenNow' => true|false|null, 'openUntil' => string|null, 'reason' => string|null]
     */
    protected function computeOpenStatus(Ecospace $ecospace): array
    {
        $isOpenNow = null;
        $openUntil = null;
        $reason = null;

        try {
            $tz = config('app.timezone') ?: date_default_timezone_get();
            $now = Carbon::now($tz);

            // parse daysOpened
            $allowedDays = range(0,6);
            $daysText = trim(strtolower($ecospace->daysOpened ?? ''));
            $matchedSpecificDate = null;
            if ($daysText !== '') {
                if (str_contains($daysText, 'every') || str_contains($daysText, 'daily')) {
                    $allowedDays = range(0,6);
                } elseif (str_contains($daysText, 'weekday')) {
                    $allowedDays = [1,2,3,4,5];
                } elseif (str_contains($daysText, 'weekend')) {
                    $allowedDays = [0,6];
                } else {
                    // check for explicit month/day like "nov 15" or "november 15"
                    if (preg_match('/(jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)[a-z]*\s*(\d{1,2})/i', $daysText, $m)) {
                        $month = $m[1];
                        $day = (int) $m[2];
                        // assume current year
                        $candidate = Carbon::createFromFormat('M j Y', ucfirst(strtolower($month)) . ' ' . $day . ' ' . $now->year, $tz);
                        if ($candidate) {
                            $matchedSpecificDate = $candidate->startOfDay();
                        }
                    }

                    // parse day ranges or lists
                    if (!$matchedSpecificDate) {
                        $dayMap = [
                            'sun' => 0, 'sunday' => 0,
                            'mon' => 1, 'monday' => 1,
                            'tue' => 2, 'tues' => 2, 'tuesday' => 2,
                            'wed' => 3, 'wednesday' => 3,
                            'thu' => 4, 'thursday' => 4, 'thur' => 4, 'thurs' => 4,
                            'fri' => 5, 'friday' => 5,
                            'sat' => 6, 'saturday' => 6,
                        ];

                        $txt = preg_replace('/\s+/', ' ', $daysText);
                        if (preg_match('/(mon|tue|wed|thu|fri|sat|sun)[\w]*\s*[-to]+\s*(mon|tue|wed|thu|fri|sat|sun)/', $txt, $mm)) {
                            $start = $mm[1]; $end = $mm[2];
                            $s = $dayMap[$start] ?? null; $e = $dayMap[$end] ?? null;
                            if ($s !== null && $e !== null) {
                                $allowedDays = [];
                                $i = $s;
                                while (true) {
                                    $allowedDays[] = $i;
                                    if ($i === $e) break;
                                    $i = ($i + 1) % 7;
                                }
                            }
                        } else {
                            $parts = preg_split('/[,;]|\s+/', $txt);
                            $found = [];
                            foreach ($parts as $p) {
                                $p = trim($p);
                                if ($p === '') continue;
                                foreach ($dayMap as $k => $v) {
                                    if (str_starts_with($p, $k)) { $found[] = $v; break; }
                                }
                            }
                            if (!empty($found)) {
                                $allowedDays = array_values(array_unique($found));
                            }
                        }
                    }
                }
            }

            // if daysText included a specific date like Nov 15, check it
            if ($matchedSpecificDate) {
                if ($now->between($matchedSpecificDate, $matchedSpecificDate->copy()->endOfDay())) {
                    $todayAllowed = true;
                } else {
                    $todayAllowed = false;
                }
            } else {
                $todayAllowed = in_array($now->dayOfWeek, $allowedDays, true);
            }

            $oh = $ecospace->openingHours;
            $ch = $ecospace->closingHours;

            if ($todayAllowed) {
                if ($oh && $ch) {
                    // try parse times with seconds or without
                    $formats = ['H:i:s', 'H:i'];
                    $openTime = null; $closeTime = null;
                    foreach ($formats as $f) {
                        try {
                            if (!$openTime) $openTime = Carbon::createFromFormat($f, $oh, $tz)->setDate($now->year, $now->month, $now->day);
                        } catch (\Throwable $e) { /* ignore */ }
                        try {
                            if (!$closeTime) $closeTime = Carbon::createFromFormat($f, $ch, $tz)->setDate($now->year, $now->month, $now->day);
                        } catch (\Throwable $e) { /* ignore */ }
                    }

                    if (!$openTime || !$closeTime) {
                        $reason = 'Unable to parse opening/closing times';
                        $isOpenNow = null;
                    } else {
                        if ($closeTime->lessThanOrEqualTo($openTime)) {
                            $closeTime->addDay();
                        }
                        if ($now->between($openTime, $closeTime)) {
                            $isOpenNow = true;
                            $openUntil = $closeTime->format('g:i A');
                        } else {
                            $isOpenNow = false;
                        }
                    }
                } else {
                    $isOpenNow = true; // no specific hours, open for allowed day
                }
            } else {
                $isOpenNow = false;
            }
        } catch (\Throwable $e) {
            report($e);
            $isOpenNow = null;
            $reason = 'Error computing status';
        }

        return [
            'isOpenNow' => $isOpenNow,
            'openUntil' => $openUntil,
            'reason' => $reason,
            'openingHours' => $ecospace->openingHours,
            'closingHours' => $ecospace->closingHours,
            'daysOpened' => $ecospace->daysOpened,
        ];
    }

    // Store a new ecospace (user-submitted)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ecospaceName' => 'required|string|min:5|max:191',
            'ecospaceAdd' => 'nullable|string|min:5|max:255',
            'ecospaceDesc' => 'nullable|string|min:5|max:1000',
            // Price tier must exist in tbl_pricetiers
            'priceTierID' => 'required|integer|exists:tbl_pricetiers,priceTierID',
            // Images are optional; limit to 7 files, each must be a valid image under 5MB
            'images' => 'nullable|array|max:7',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'images.max' => 'You may only upload up to 7 images.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.mimes' => 'Images must be jpeg, png, jpg, gif, or webp.',
            'images.*.max' => 'Each image must be at most 5MB.',
        ]);
           // Removed openingHours, closingHours, and daysOpened from validation
           // 'openingHours' => 'nullable|string|max:50',
           // 'closingHours' => 'nullable|string|max:50',
           // 'daysOpened' => 'nullable|string|max:255',

        $ecospace = Ecospace::create([
            'ecospaceName' => $request->ecospaceName,
            'ecospaceAdd' => $request->ecospaceAdd,
            'ecospaceDesc' => $request->ecospaceDesc,
            'userID' => auth()->user()->id,
            // Force newly-submitted ecospaces to pending
            'statusID' => 1,
            'priceTierID' => $request->priceTierID,
               // Removed openingHours, closingHours, and daysOpened from creation
               // 'openingHours' => $request->openingHours,
               // 'closingHours' => $request->closingHours,
               // 'daysOpened' => $request->daysOpened,
            'dateCreated' => Carbon::now(),
        ]);

        // If images were uploaded, store them and create image records
        if ($request->hasFile('images')) {
            $index = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                    // process image (resize + watermark) and store under storage/app/public/ecospace_images
                    $path = $this->processEcospaceImageUpload($file);

                Image::create([
                    'ecospaceID' => $ecospace->ecospaceID,
                    'path' => $path,
                    'order' => $index,
                    'caption' => null,
                ]);

                $index++;
            }
        }

        // Promote the user who created an ecospace to userTypeID = 3
        try {
            $user = auth()->user();
            if ($user) {
                $user->userTypeID = 3;
                $user->save();
            }
        } catch (\Throwable $e) {
            // Don't break ecospace creation if updating the user fails; log the error
            report($e);
        }

        return redirect()->route('dashboard')->with('success', 'EcoSpace created successfully.');
    }

    // Admin-facing: list pending ecospaces
    public function create()
    {
        // Pending ecospaces use statusID = 1
        $ecospaces = Ecospace::where('statusID', 1)
            ->with(['user', 'status', 'priceTier'])
            ->orderByDesc('dateCreated')
            ->paginate(5);
        // Also fetch pending events for the admin create page
        $events = Event::where('statusID', 1)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->orderByDesc('dateCreated')
            ->paginate(5, ['*'], 'events_page');

        return view('admin.create', compact('ecospaces', 'events'));
    }

    // Admin-facing: list approved ecospaces
    public function index()
    {
        // Approved ecospaces use statusID = 2
        $ecospaces = Ecospace::where('statusID', 2)
            ->with(['user', 'status', 'priceTier'])
            ->orderByDesc('dateCreated')
            ->paginate(5);
        // Also load approved events for display on admin dashboard
        $events = Event::where('statusID', 2)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->orderByDesc('dateCreated')
            ->paginate(5, ['*'], 'events_page');

        // Include users listing for admin index
        $users = User::orderBy('name')->paginate(10, ['*'], 'users_page');

        // The `admin.index` view was removed — return the admin ecospaces listing instead
        return view('admin.ecospaces', compact('ecospaces'));
    }

    public function archives()
    {
        // Show ecospaces that were declined/archived (statusID = 3) and are soft-deleted
        $ecospaces = Ecospace::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'status', 'priceTier'])
            ->orderByDesc('dateCreated')
            ->paginate(5);
        // Also fetch trashed events (statusID = 3) for display in archives
        $events = Event::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'images', 'priceTier', 'eventType'])
            ->orderByDesc('dateCreated')
            ->paginate(5, ['*'], 'events_page');

        // Also include trashed users for admin archives (unarchive option)
        $users = \App\Models\User::onlyTrashed()->orderBy('name')->paginate(10, ['*'], 'users_page');

        return view('admin.archives', compact('ecospaces', 'events', 'users'));
    }

    /**
     * Admin preview: standalone ecospaces admin page (for UI preview)
     */
    public function adminEcospaces(HttpRequest $request)
    {
        $sort = $request->input('sort', 'newest');

        $query = Ecospace::query();

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            // default to newest first
            $query->orderByDesc('dateCreated');
        }

        $ecospaces = $query->paginate(5)->withQueryString();
        return view('admin.ecospaces', compact('ecospaces', 'sort'));
    }

    /**
     * Admin-facing: show pending ecospaces only (create page split)
     */
    public function adminEcospacesCreate()
    {
        // support sort query (newest|oldest)
        $sort = request()->input('sort', 'newest');

        $query = Ecospace::where('statusID', 1)
            ->with(['user', 'status', 'priceTier']);

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            $query->orderByDesc('dateCreated');
        }

        $ecospaces = $query->paginate(5)->withQueryString();
        return view('admin.ecospaces_create', compact('ecospaces', 'sort'));
    }

    /**
     * Admin-facing: show archived ecospaces only (archives split)
     */
    public function adminEcospacesArchives()
    {
        $sort = request()->input('sort', 'newest');

        $query = Ecospace::onlyTrashed()
            ->where('statusID', 3)
            ->with(['user', 'status', 'priceTier']);

        if ($sort === 'oldest') {
            $query->orderBy('dateCreated', 'asc');
        } else {
            $query->orderByDesc('dateCreated');
        }

        $ecospaces = $query->paginate(5)->withQueryString();

        return view('admin.ecospaces_archives', compact('ecospaces', 'sort'));
    }

    public function approve($id)
    {
        // Set to approved
        Ecospace::where('ecospaceID', $id)->update(['statusID' => 2]);
        return redirect()->route('admin.ecospaces')->with('success', 'EcoSpace approved successfully.');
    }

    public function remove($id)
    {
        // Mark as removed/declined by setting statusID = 3 and soft-delete
        $ecospace = Ecospace::findOrFail($id);
        $ecospace->update(['statusID' => 3]);
        $ecospace->delete(); // soft delete (sets deleted_at)
        // Revert the owner's userTypeID back to 2 (regular user) when their ecospace is declined
        try {
            $owner = User::find($ecospace->userID);
            if ($owner) {
                $owner->userTypeID = 2;
                $owner->save();
            }
        } catch (\Throwable $e) {
            // Log but don't interrupt the flow
            report($e);
        }

        return redirect()->route('admin.ecospaces')->with('success', 'EcoSpace archived successfully.');
    }

    /**
     * Show a confirmation page (no JS) before soft-removing an ecospace.
     */
    public function confirmRemove($id)
    {
        $ecospace = Ecospace::findOrFail($id);
        $title = 'Confirm Remove EcoSpace';
        $message = 'Are you sure you want to remove the EcoSpace "' . $ecospace->ecospaceName . '"? This will archive the EcoSpace (soft-delete).';
        $actionRoute = route('admin.ecospace.remove', $id);
        $cancelUrl = url()->previous() ?: route('index.index');

        return view('shared.confirm-delete', compact('title', 'message', 'actionRoute', 'cancelUrl'));
    }

    public function restore($id)
    {
        $ecospace = Ecospace::withTrashed()->findOrFail($id);
        // Restore soft-deleted record
        $ecospace->restore();
        // Mark as approved when restored
        $ecospace->update(['statusID' => 2]);

        return redirect()->route('admin.ecospaces')
                 ->with('success', 'EcoSpace restored successfully.');
    }

    public function delete($id)
    {
        $ecospace = Ecospace::withTrashed()->findOrFail($id);
        $ecospace->forceDelete();

        return redirect()->route('admin.ecospaces.archives')
                 ->with('success', 'EcoSpace permanently deleted.');
    }

    /**
     * Show a confirmation page (no JS) before permanently deleting an ecospace.
     */
    public function confirmDelete($id)
    {
        $ecospace = Ecospace::withTrashed()->findOrFail($id);
        $title = 'Confirm Permanent Delete';
        $message = 'Are you sure you want to permanently delete the EcoSpace "' . $ecospace->ecospaceName . '"? This cannot be undone.';
        $actionRoute = route('admin.ecospaces.delete', $id);
        $cancelUrl = url()->previous() ?: route('archives.index');

        return view('shared.confirm-delete', compact('title', 'message', 'actionRoute', 'cancelUrl'));
    }

    public function edit($id)
    {
        $ecospace = Ecospace::with('images')->findOrFail($id);
        $pricetiers = PriceTier::all();
        return view('admin.edit', compact('ecospace', 'pricetiers'));
    }

    /**
     * Owner-facing edit form. Only the owner (userID) may access.
     */
    public function editOwner($id)
    {
        $ecospace = Ecospace::with('images')->findOrFail($id);
        if (!auth()->check() || auth()->id() != $ecospace->userID) {
            abort(403);
        }
        $pricetiers = PriceTier::all();
        return view('ecospaces.edit-ecospace', compact('ecospace', 'pricetiers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ecospaceName' => 'required|string|max:191',
            'ecospaceAdd' => 'nullable|string|max:255',
            'ecospaceDesc' => 'nullable|string|max:1000',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            // removed opening/closing/days from update validation
            'images' => 'nullable|array|max:7',
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
            // openingHours/closingHours/daysOpened removed from update
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
                $path = $this->processEcospaceImageUpload($file);
                Image::create([
                    'ecospaceID' => $ecospace->ecospaceID,
                    'path' => $path,
                    'order' => $maxOrder + 1 + $index,
                    'caption' => null,
                ]);
                $index++;
            }
        }

        // After admin edit, redirect back to the admin ecospaces listing
        return redirect()->route('admin.ecospaces')->with('success', 'EcoSpace updated successfully.');
    }

    /**
     * Owner-facing update. Similar to admin update but checks ownership and redirects to user's profile.
     */
    public function updateOwner(Request $request, $id)
    {
        $validated = $request->validate([
            'ecospaceName' => 'required|string|max:191',
            'ecospaceAdd' => 'nullable|string|max:255',
            'ecospaceDesc' => 'nullable|string|max:1000',
            'priceTierID' => 'nullable|integer|exists:tbl_pricetiers,priceTierID',
            // removed opening/closing/days from owner update validation
            'images' => 'nullable|array|max:7',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images_to_remove' => 'nullable|array',
            'images_to_remove.*' => 'integer|exists:tbl_esImages,esImageID',
        ]);

        $ecospace = Ecospace::findOrFail($id);
        if (!auth()->check() || auth()->id() != $ecospace->userID) {
            abort(403);
        }

        // Update basic fields
        $ecospace->update([
            'ecospaceName' => $request->ecospaceName,
            'ecospaceAdd' => $request->ecospaceAdd,
            'ecospaceDesc' => $request->ecospaceDesc,
            'priceTierID' => $request->priceTierID,
            // openingHours/closingHours/daysOpened removed from owner update
        ]);

        // Remove selected images
        if ($request->filled('images_to_remove')) {
            foreach ($request->images_to_remove as $imgId) {
                $img = Image::find($imgId);
                if ($img && $img->ecospaceID == $ecospace->ecospaceID) {
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
                $path = $this->processEcospaceImageUpload($file);
                Image::create([
                    'ecospaceID' => $ecospace->ecospaceID,
                    'path' => $path,
                    'order' => $maxOrder + 1 + $index,
                    'caption' => null,
                ]);
                $index++;
            }
        }

        // Redirect owner to their profile page after updating their ecospace
        return redirect()->route('users.show', auth()->id())->with('success', 'EcoSpace updated successfully.');
    }

    /**
     * Process an uploaded ecospace image using Intervention Image:
     * - Resize to fit within max dimensions while preserving aspect ratio
     * - Add a watermark text 'ecospaces' at the bottom-right
     * - Encode as JPEG and store on the public disk under ecospace_images/
     *
     * Returns the storage path (relative to disk root) on success.
     */
    protected function processEcospaceImageUpload($file)
    {
        try {
            // Create Intervention image instance
            $img = Img::make($file->getRealPath());

            // Determine a sensible max size relative to the uploaded image
            // If the image is small, keep its size; otherwise cap to these max values.
            $maxWidth = 1600;
            $maxHeight = 1200;

            if ($img->width() > $maxWidth || $img->height() > $maxHeight) {
                $img->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Add watermark text 'ecospaces' in bottom-right corner
            $fontPath = '/Library/Fonts/Arial.ttf';
            if (!file_exists($fontPath)) {
                $fontPath = null; // Intervention will fall back if possible
            }

            $fontSize = max(12, (int) round($img->width() / 20));

            $img->text('ecospaces', $img->width() - 12, $img->height() - 12, function ($font) use ($fontPath, $fontSize) {
                if ($fontPath) $font->file($fontPath);
                $font->size($fontSize);
                $font->color('rgba(128,128,128,0.6)');
                $font->align('right');
                $font->valign('bottom');
                $font->angle(0);
            });

            // Encode and save as JPEG to public disk
            $encoded = $img->encode('jpg', 85);
            $filename = 'ecospace_' . time() . '_' . Str::random(8) . '.jpg';
            $path = 'ecospace_images/' . $filename;
            Storage::disk('public')->put($path, (string) $encoded);

            return $path;
        } catch (\Throwable $e) {
            report($e);
            // fallback: store original uploaded file
            return $file->store('ecospace_images', 'public');
        }
    }
}