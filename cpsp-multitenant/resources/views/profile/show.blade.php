@extends('layouts.app')

@section('title', 'Supervisor Profile | CPSP e-Logbook')

@section('content')
<div class="sup-profile-page">
    {{-- Breadcrumb --}}
    <nav class="supervisor-dash__breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item active" aria-current="page">My Profile</li>
        </ol>
    </nav>

    {{-- Profile Hero Card --}}
    <div class="profile-hero-card">
        <div class="profile-hero-card__avatar">
            <div class="avatar-circle">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>
        <div class="profile-hero-card__info">
            <div class="profile-hero-card__role-tag">
                <span class="badge badge--ok">{{ session('user_type') ?: 'Supervisor' }}</span>
                <span class="badge badge--teal">{{ $user->tenant?->name ?: 'Isra University' }}</span>
            </div>
            <h1 class="profile-hero-card__name">
                {{ $user->profile?->full_name ?: $user->username }}
            </h1>
            <div class="profile-hero-card__meta">
                <span><i class="fa-solid fa-id-badge"></i> CPSP ID: <strong>{{ $user->username }}</strong></span>
                <span><i class="fa-solid fa-envelope"></i> {{ $user->email }}</span>
                @if($user->profile?->phone)
                    <span><i class="fa-solid fa-phone"></i> {{ $user->profile->phone }}</span>
                @endif
            </div>
        </div>
        <div class="profile-hero-card__stats">
            <div class="p-stat">
                <div class="p-stat__num">{{ $stats['trainees_count'] }}</div>
                <div class="p-stat__label">Trainees</div>
            </div>
            <div class="p-stat">
                <div class="p-stat__num" style="color: #28a745;">{{ $stats['approved_count'] }}</div>
                <div class="p-stat__label">Approved</div>
            </div>
            <div class="p-stat">
                <div class="p-stat__num" style="color: #ff9800;">{{ $stats['pending_count'] }}</div>
                <div class="p-stat__label">Pending</div>
            </div>
        </div>
    </div>

    {{-- Error summary if any --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Grid: Information Form & Password Change --}}
    <div class="profile-grid">
        {{-- Card 1: Edit Profile Details --}}
        <div class="profile-card">
            <div class="profile-card__header">
                <h2><i class="fa-solid fa-user-pen"></i> Profile Information</h2>
                <span class="text-muted" style="font-size: 13px;">Update your personal details</span>
            </div>
            <div class="profile-card__body">
                <form action="{{ route('profile.update') }}" method="post">
                    @csrf
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="full_name" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Full Name <span class="vd_red">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" class="field__control" 
                               value="{{ old('full_name', $user->profile?->full_name) }}" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="username_disabled" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            CPSP ID / Username
                        </label>
                        <input type="text" id="username_disabled" class="field__control" 
                               value="{{ $user->username }}" disabled style="background: #e9ecef; cursor: not-allowed;">
                        <small class="text-muted">Username is system-managed and cannot be altered.</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="email" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Email Address <span class="vd_red">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="field__control" 
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="phone" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Contact Phone / Mobile
                        </label>
                        <input type="text" name="phone" id="phone" class="field__control" 
                               placeholder="+92-300-0000000"
                               value="{{ old('phone', $user->profile?->phone) }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="bio" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Professional Summary / Bio
                        </label>
                        <textarea name="bio" id="bio" rows="4" class="field__control" 
                                  placeholder="Department, specialty, hospital affiliations..."
                                  style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; font-family: inherit;">{{ old('bio', $user->profile?->bio) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn--submit" style="min-width: 150px;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Profile
                    </button>
                </form>
            </div>
        </div>

        {{-- Card 2: Change Password --}}
        <div class="profile-card" id="password-section">
            <div class="profile-card__header">
                <h2><i class="fa-solid fa-shield-halved"></i> Change Password</h2>
                <span class="text-muted" style="font-size: 13px;">Manage your account security</span>
            </div>
            <div class="profile-card__body">
                <form action="{{ route('profile.password') }}" method="post">
                    @csrf

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="current_password" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Current Password <span class="vd_red">*</span>
                        </label>
                        <input type="password" name="current_password" id="current_password" class="field__control" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="password" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            New Password <span class="vd_red">*</span>
                        </label>
                        <input type="password" name="password" id="password" class="field__control" required minlength="6">
                        <small class="text-muted">Minimum 6 characters.</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="password_confirmation" class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">
                            Confirm New Password <span class="vd_red">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="field__control" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn--submit" style="min-width: 170px; background: #0b6040;">
                        <i class="fa-solid fa-key"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
