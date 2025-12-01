<x-app-layout>


    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-screen">
        <div class="flex justify-start items-center space-x-4 mb-8">
            <div class="relative">
                <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 font-medium hover:bg-gray-50">
                    <span>Sort By</span>
                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="relative">
                <button class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 font-medium hover:bg-gray-50">
                    <span>Filters</span>
                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
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