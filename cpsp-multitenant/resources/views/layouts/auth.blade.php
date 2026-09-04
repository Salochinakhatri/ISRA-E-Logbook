<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name ?? 'ePortal' }} – e-Log Book | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="page-login">
    @yield('content')

    <div class="modal" id="forgotModal" role="dialog" aria-modal="true" aria-labelledby="forgotModalTitle" hidden>
        <div class="modal__backdrop" data-close-modal></div>
        <div class="modal__panel">
            <h2 class="modal__title" id="forgotModalTitle">Password help</h2>
            <p class="modal__text">Please contact your programme office or system administrator to reset your portal password.</p>
            <button type="button" class="btn btn-login" data-close-modal>Close</button>
        </div>
    </div>

    <button type="button" class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script src="{{ asset('script.js') }}"></script>
</body>
</html>
