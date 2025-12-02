<x-app-layout>
    @php $hideNavbar = true; @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --brand-green: #2F9E4A;
            --brand-maroon: #166534;
            --brand-bg-admin-main: #E6F7EA;
            --brand-bg-sidebar: #FFFFFF;
        }
        .font-inter{ font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .bg-brand-bg-admin-main{ background-color: var(--brand-bg-admin-main); }
        .bg-brand-bg-sidebar{ background-color: var(--brand-bg-sidebar); }
        .brand-accent{ color: var(--brand-maroon); }
        .min-h-screen > nav { display: none !important; }
    </style>

    <div class="flex min-h-screen font-inter bg-brand-bg-admin-main">
        @include('admin._sidebar')

        <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
                <div class="bg-white rounded-xl shadow-lg p-8 w-full mx-auto">
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h1 class="text-3xl font-bold text-gray-900">Current Accounts</h1>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-100 rounded-xl shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Name</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Email</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">User Type</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Registered</th>
                                <th class="px-6 py-3 border-b text-left text-gray-600 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($users) && $users->count())
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 border-b align-top">{{ $user->name }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ $user->email }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ $user->userType->userTypeName ?? ($user->userTypeID ?? 'N/A') }}</td>
                                        <td class="px-6 py-4 border-b align-top">{{ optional($user->created_at)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 border-b align-top">
                                            <div class="flex gap-2">
                                                <a href="{{ route('users.show', $user->id) }}" class="px-3 py-1 rounded-md bg-gray-100 text-gray-800">View</a>
                                                @if(auth()->check() && auth()->user()->userTypeID === 1 && auth()->id() !== $user->id && ($user->userTypeID ?? null) !== 1)
                                                    <form method="POST" action="{{ route('admin.users.archive', $user->id) }}" data-confirm="Archive this user account? This will disable their access.">
                                                        @csrf
                                                        <button type="submit" class="admin-btn-negative px-3 py-1 rounded-md">Archive</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 border-b text-center text-gray-500">No users found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ isset($users) ? $users->links() : '' }}</div>
            </div>
        </main>
    </div>
</x-app-layout>
