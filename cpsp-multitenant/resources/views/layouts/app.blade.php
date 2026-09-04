<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($tenant->name ?? 'Isra e-Logbook'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @stack('styles')
</head>
<body class="page-elogbook">
    <div class="elog-layout">
        <aside class="elog-sidebar" id="elogSidebar">
            <div class="elog-sidebar__brand">
                <img src="{{ $tenant->logo_url ?? asset('assets/images/logo.png') }}" alt="{{ $tenant->name ?? 'Logo' }}" class="elog-sidebar__logo" width="36" height="36">
                <span class="elog-sidebar__brand-text">{{ $tenant->name ?? 'Isra e-Logbook' }}</span>
            </div>
            <nav class="elog-sidebar__nav" aria-label="Main">
                <a class="elog-sidebar__link @yield('nav_home', '')" href="{{ route('dashboard', ['program' => request('program')]) }}">
                    <i class="fa-solid fa-house"></i><span>Home</span>
                </a>

                @if(session('user_type') === 'Trainee' || (isset($user) && $user->isTrainee()))
                {{-- Training --}}
                <div class="elog-sidebar__group @yield('group_training', '')">
                    <button type="button" class="elog-sidebar__parent" id="trainingToggle" aria-expanded="true">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Training</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="trainingSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_training_list', '')" href="{{ route('training.index', ['program' => request('program')]) }}">List all entries</a>
                        <a class="elog-sidebar__sublink @yield('nav_training_add', '')" href="{{ route('training.create', ['program' => request('program')]) }}">Add new</a>
                    </div>
                </div>

                {{-- Rotational Training --}}
                <div class="elog-sidebar__group @yield('group_rotational', '')">
                    <button type="button" class="elog-sidebar__parent" id="rotationalToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Rotational Training</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="rotationalSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_rotational_list', '')" href="{{ route('rotational.index', ['program' => request('program')]) }}">List all entries</a>
                        <a class="elog-sidebar__sublink @yield('nav_rotational_add', '')" href="{{ route('rotational.create', ['program' => request('program')]) }}">Add new</a>
                    </div>
                </div>

                {{-- Journal Club --}}
                <div class="elog-sidebar__group @yield('group_journal', '')">
                    <button type="button" class="elog-sidebar__parent" id="journalToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Journal Club</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="journalSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_journal_list', '')" href="{{ route('journal.index', ['program' => request('program')]) }}">List all entries</a>
                        <a class="elog-sidebar__sublink @yield('nav_journal_add', '')" href="{{ route('journal.create', ['program' => request('program')]) }}">Add new</a>
                    </div>
                </div>

                {{-- Paper/Poster Presented --}}
                <div class="elog-sidebar__group @yield('group_presented', '')">
                    <button type="button" class="elog-sidebar__parent" id="presentedToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Paper/Poster Presented</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="presentedSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_presented_list', '')" href="{{ route('presented.index', ['program' => request('program')]) }}">List all entries</a>
                        <a class="elog-sidebar__sublink @yield('nav_presented_add', '')" href="{{ route('presented.create', ['program' => request('program')]) }}">Add new</a>
                    </div>
                </div>

                {{-- Paper Published --}}
                <div class="elog-sidebar__group @yield('group_published', '')">
                    <button type="button" class="elog-sidebar__parent" id="publishedToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Paper Published</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="publishedSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_published_list', '')" href="{{ route('published.index', ['program' => request('program')]) }}">List all entries</a>
                        <a class="elog-sidebar__sublink @yield('nav_published_add', '')" href="{{ route('published.create', ['program' => request('program')]) }}">Add new</a>
                    </div>
                </div>

                {{-- Suggestions --}}
                <div class="elog-sidebar__group @yield('group_suggestions', '')">
                    <button type="button" class="elog-sidebar__parent" id="suggestionsToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-lightbulb"></i><span>Suggestions</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="suggestionsSubnav">
                        <a class="elog-sidebar__sublink @yield('nav_suggestions_list', '')" href="{{ route('suggestions.index', ['program' => request('program')]) }}">Suggestions / Feedback</a>
                    </div>
                </div>
                @else
                {{-- Supervisor / Fellow Navigation --}}
                <a class="elog-sidebar__link @yield('nav_supervisor_entries', '')" href="{{ route('supervisor.entries') }}">
                    <i class="fa-solid fa-clipboard-check"></i><span>Awaiting Approvals</span>
                </a>
                <div class="elog-sidebar__group @yield('group_reports', '')">
                    <button type="button" class="elog-sidebar__parent" id="reportsToggle" aria-expanded="false">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Reports</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="reportsSubnav">
                        <a class="elog-sidebar__sublink" href="{{ route('dashboard') }}">Summary Reports</a>
                        <a class="elog-sidebar__sublink" href="{{ route('supervisor.entries', ['status' => 'Approved']) }}">Approved Entries</a>
                    </div>
                </div>
                <a class="elog-sidebar__link @yield('nav_suggestions_list', '')" href="{{ route('suggestions.index') }}">
                    <i class="fa-solid fa-lightbulb"></i><span>Suggestions</span>
                </a>
                @endif
            </nav>

            {{-- Pinned to bottom like in CPSP reference --}}
            <div class="elog-sidebar__bottom">
                <a class="elog-sidebar__link" href="{{ route('profile.show') }}#password-section">
                    <i class="fa-solid fa-lock"></i><span>Change Password</span>
                </a>
                <a class="elog-sidebar__link js-logout-confirm" href="{{ route('logout') }}">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="elog-shell">
            <header class="elog-topbar">
                <button type="button" class="elog-menu-btn" id="elogMenuOpen" aria-controls="elogSidebar" aria-expanded="false" aria-label="Toggle sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="elog-topbar__brand">
                    <span class="elog-topbar__title">{{ $tenant ? $tenant->getSpecialtyTitle(request('program')) : 'INTERNAL MEDICINE' }}</span>
                </div>
                <button type="button" class="elog-menu-btn" id="elogTopbarToggle" aria-controls="elogTopbarNav" aria-expanded="false" aria-label="Open account menu">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <nav class="elog-topbar__nav" aria-label="Account">
                    <a class="elog-topbar__link" href="{{ route('profile.show') }}" title="View Profile">
                        <i class="fa-solid fa-user"></i> <span>{{ session('username') }}</span>
                    </a>
                    <a class="elog-topbar__link elog-topbar__link--out js-logout-confirm" href="{{ route('logout') }}">
                        <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                    </a>
                </nav>
            </header>

            <main class="elog-main @yield('main_class', '')">
                @if(session('flash_ok'))
                    <div class="alert alert-success elog-flash" role="status">{{ session('flash_ok') }}</div>
                @endif
                @if(session('login_error'))
                    <div class="alert alert-danger elog-flash" role="alert">{{ session('login_error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="elog-backdrop" id="elogBackdrop" hidden></div>

    {{-- Exact original logout confirmation modal from cpsp1/cpsp2 --}}
    <div class="modal" id="logoutModal" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle" hidden>
        <div class="modal__backdrop" data-close-modal></div>
        <div class="modal__panel">
            <h2 class="modal__title" id="logoutModalTitle" style="color: #0b6040; margin-bottom: 10px;">Confirm Logout</h2>
            <p class="modal__text" style="font-size: 16px; margin-bottom: 25px;">Are you sure you want to logout from {{ $tenant->name ?? 'Isra e-Logbook' }}?</p>
            <div style="display: flex; gap: 15px;">
                <form id="logoutForm" action="{{ route('logout') }}" method="post" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-login" style="width: 100%; text-align: center; text-decoration: none;">OK</button>
                </form>
                <button type="button" class="btn btn-forgot" data-close-modal style="flex: 1; margin: 0;">Cancel</button>
            </div>
        </div>
    </div>

    <button type="button" class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('elogbook.js') }}"></script>
    @stack('scripts')
</body>
</html>
