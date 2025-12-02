<x-app-layout>


    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="flex flex-wrap items-center gap-4 mb-8">
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-3">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <label class="text-sm text-gray-600">Sort</label>
                <select name="sort" class="border rounded px-3 py-2 bg-white">
                    <option value="name_asc" {{ (isset($sort) && $sort==='name_asc') ? 'selected' : '' }}>Name (A → Z)</option>
                    <option value="name_desc" {{ (isset($sort) && $sort==='name_desc') ? 'selected' : '' }}>Name (Z → A)</option>
                    <option value="newest" {{ (isset($sort) && $sort==='newest') ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ (isset($sort) && $sort==='oldest') ? 'selected' : '' }}>Oldest</option>
                    <option value="most_ecospaces" {{ (isset($sort) && $sort==='most_ecospaces') ? 'selected' : '' }}>Most EcoSpaces</option>
                    <option value="least_ecospaces" {{ (isset($sort) && $sort==='least_ecospaces') ? 'selected' : '' }}>Least EcoSpaces</option>
                </select>

                <label class="text-sm text-gray-600">Filter</label>
                <select name="has_ecospaces" class="border rounded px-3 py-2 bg-white">
                    <option value="all" {{ (isset($hasEcospaces) && $hasEcospaces==='all') ? 'selected' : '' }}>All users</option>
                    <option value="has" {{ (isset($hasEcospaces) && $hasEcospaces==='has') ? 'selected' : '' }}>Has EcoSpaces</option>
                    <option value="none" {{ (isset($hasEcospaces) && $hasEcospaces==='none') ? 'selected' : '' }}>No EcoSpaces</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Apply</button>
            </form>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @if($users->count())
                @foreach($users as $user)
                    <a href="{{ route('users.show', $user->id) }}" class="group">
                        <div class="bg-gray-300 aspect-square rounded-lg flex items-center justify-center overflow-hidden shadow-sm transition-shadow group-hover:shadow-md">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                            @else
                                <svg class="h-2/3 w-2/3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="bg-gray-100 p-3 text-center rounded-b-lg -mt-2 relative z-10 shadow-sm transition-shadow group-hover:shadow-md">
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="text-center text-gray-600 col-span-full">No users found.</div>
            @endif
        </div>

        <div class="mt-6 flex justify-center">
            {{ $users->links() }}
        </div>
    </main>
</x-app-layout>