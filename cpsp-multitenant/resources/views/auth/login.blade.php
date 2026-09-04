@extends('layouts.auth')

@section('content')
<main class="login-shell">
    <div class="login-stack">
        <header class="login-header">
            <h1 class="title-green">{{ $tenant->name ?? 'CPSP ePortal' }}</h1>
            <span class="visually-hidden">CPSP ePortal</span>
            <p class="subtitle">LOGIN TO YOUR ACCOUNT</p>
        </header>

        <div class="logo-block">
            <img src="{{ $tenant->logo_url ?? asset('assets/images/logo.png') }}" alt="{{ $tenant->name ?? 'e-Log Book' }}" class="crest-logo" width="120" height="120">
            <p class="elogbook-curve" aria-label="e-Log Book">e-Log Book</p>
        </div>

        <div class="form-wrap">
            @if(session('login_error'))
                <div class="alert alert-error" role="alert">{{ session('login_error') }}</div>
            @endif

            <form id="loginForm" class="login-form" action="{{ route('login.submit') }}" method="post" novalidate>
                @csrf

                <div class="form-group">
                    <label class="visually-hidden" for="user_type_id">User type</label>
                    <div class="select-wrap">
                        <select name="user_type_id" id="user_type_id" class="form-control form-select" required>
                            <option value="">- Select User Type -</option>
                            @foreach($userTypes as $t)
                                <option value="{{ $t->id }}" {{ old('user_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="visually-hidden" for="username">Username</label>
                    <div class="input-icon">
                        <span class="input-icon__i" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="username" value="{{ old('username') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="visually-hidden" for="password">Password</label>
                    <div class="input-icon">
                        <span class="input-icon__i" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="current-password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">Login</button>

                <div class="remember-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember_me" id="remember_me" value="1">
                        <span>Remember me</span>
                    </label>
                </div>
            </form>

            <section class="forgot-block" aria-labelledby="forgot-heading">
                <p id="forgot-heading" class="forgot-text">
                    If you have forgot or don't know your password then click on the following button.
                </p>
                <button type="button" class="btn btn-forgot" id="btnForgot">Reset / Forgot Password</button>
            </section>
        </div>

        <footer class="site-footer">
            <hr class="footer-rule">
            <p class="copyright">Copyright &copy; {{ date('Y') }} {{ $tenant->name ?? 'CPSP' }}. All Rights Reserved.</p>
        </footer>
    </div>
</main>
@endsection
