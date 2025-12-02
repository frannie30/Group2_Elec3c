<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoSpaces') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @php
                // Hide the navigation menu on admin routes or when explicitly requested.
                $isAdminRoute = request()->route() ? request()->route()->getName() && str_starts_with(request()->route()->getName(), 'admin.') : false;
            @endphp
            @if (empty($hideNavbar) && !($isAdminRoute || request()->routeIs('index.index') || request()->routeIs('create.index') || request()->routeIs('edit.index')))
                @livewire('navigation-menu')
            @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
            // Global handler: intercept clicks/submits on elements with `data-confirm`.
            (function(){
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                function handleConfirm(event, message, proceed) {
                    event.preventDefault();
                    Swal.fire({
                        title: message || 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) proceed();
                    });
                }

                // Intercept form submissions that have data-confirm
                document.addEventListener('submit', function(e){
                    const form = e.target;
                    if (!form || !form.hasAttribute) return;
                    const msg = form.getAttribute('data-confirm');
                    if (msg) {
                        handleConfirm(e, msg, () => form.submit());
                    }
                }, true);

                // Intercept link clicks with data-confirm
                document.addEventListener('click', function(e){
                    const el = e.target.closest && e.target.closest('[data-confirm]');
                    if (!el) return;

                    // If it's a form control inside a form (button with data-confirm), let the form handler deal with it
                    if (el.tagName === 'BUTTON' && el.type === 'submit' && el.form) return;

                    const msg = el.getAttribute('data-confirm');
                    if (!msg) return;

                    // For anchors, confirm then navigate
                    if (el.tagName === 'A' && el.href) {
                        handleConfirm(e, msg, () => { window.location.href = el.href; });
                        return;
                    }

                    // For buttons not in a form, if data-action is present, try to POST using fetch
                    if (el.tagName === 'BUTTON' && el.dataset.action) {
                        handleConfirm(e, msg, () => {
                            const action = el.dataset.action;
                            const method = (el.dataset.method || 'POST').toUpperCase();
                            const headers = { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' };
                            fetch(action, { method, headers }).then(()=> location.reload()).catch(()=> location.reload());
                        });
                        return;
                    }
                });
            })();
        </script>
        {{-- Session flash toasts (SweetAlert2) --}}
        @if(session('success') || session('error'))
            <script>
                (function(){
                    const success = @json(session('success'));
                    const error = @json(session('error'));
                    if (success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: success,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    } else if (error) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: error,
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                        });
                    }
                })();
            </script>
        @endif
        <script>
            // Combine address parts into a single hidden input for forms that opt-in
            (function(){
                function trim(s){ return (s||'').toString().trim(); }

                document.addEventListener('submit', function(e){
                    const form = e.target;
                    if (!form || !form.hasAttribute) return;
                    if (!form.hasAttribute('data-composite-address')) return;

                    const targetName = form.getAttribute('data-address-target') || 'address';
                    const lineEl = form.querySelector('[data-address-line]');
                    const barangayEl = form.querySelector('[data-barangay]');

                    const line = lineEl ? trim(lineEl.value) : '';
                    const barangay = barangayEl ? trim(barangayEl.value) : '';
                    const city = 'Makati';
                    const region = 'Metro Manila';

                    // Compose: "{line}, {barangay}, Makati, Metro Manila"
                    const parts = [];
                    if (line) parts.push(line);
                    if (barangay) parts.push(barangay);
                    parts.push(city);
                    parts.push(region);

                    const combined = parts.join(', ');

                    // Set or create the hidden input with the server-expected name
                    let hidden = form.querySelector('input[name="' + targetName + '"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = targetName;
                        form.appendChild(hidden);
                    }
                    hidden.value = combined;
                    // allow submit to continue
                }, true);
            })();
        </script>
    </body>
</html>
