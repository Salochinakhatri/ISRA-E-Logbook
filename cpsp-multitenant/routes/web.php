<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PresentedController;
use App\Http\Controllers\PublishedController;
use App\Http\Controllers\RotationalController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CPSP Multi-Tenant Web Routes
|--------------------------------------------------------------------------
| All routes are wrapped in the 'tenant' middleware group so every request
| first resolves the active Tenant from the HTTP Host header.
|--------------------------------------------------------------------------
*/

// ─── Dynamic Tenant Entry Points (e.g. /tenant/{domain}/{any?} or /cpsp1, /cpsp2) ───
$switchTenant = function (string $domain, ?string $any = null) {
    $matched = \App\Models\Tenant::where('domain', $domain)
        ->orWhere('domain', $domain . '.test')
        ->first();

    if ($matched) {
        session(['dev_tenant' => $matched->domain]);
        if (session('tenant_id') && (int) session('tenant_id') !== (int) $matched->id) {
            session()->forget(['user_id', 'user_type_id', 'username', 'email', 'user_type', 'tenant_id']);
        }
    }

    if ($any) {
        return redirect('/' . ltrim($any, '/'));
    }
    return redirect(session()->has('user_id') ? '/dashboard' : '/');
};

Route::get('/tenant/{domain}/{any?}', $switchTenant)->where('any', '.*');
Route::get('/cpsp1/{any?}', fn(?string $any = null) => $switchTenant('cpsp1.test', $any))->where('any', '.*');
Route::get('/cpsp2/{any?}', fn(?string $any = null) => $switchTenant('cpsp2.test', $any))->where('any', '.*');

Route::middleware(['tenant'])->group(function () {

    // ─── Auth ──────────────────────────────────────────────────────────────
    Route::get('/',       [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // ─── Authenticated Routes ─────────────────────────────────────────────
    Route::middleware(['auth.session'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Training entries
        Route::get('/training',          [TrainingController::class, 'index'])->name('training.index');
        Route::get('/training/create',   [TrainingController::class, 'create'])->name('training.create');
        Route::post('/training',         [TrainingController::class, 'store'])->name('training.store');
        Route::get('/training/{id}',     [TrainingController::class, 'show'])->name('training.show')->where('id', '[0-9]+');

        // Rotational entries
        Route::get('/rotational',        [RotationalController::class, 'index'])->name('rotational.index');
        Route::get('/rotational/create', [RotationalController::class, 'create'])->name('rotational.create');
        Route::post('/rotational',       [RotationalController::class, 'store'])->name('rotational.store');

        // Journal entries
        Route::get('/journal',           [JournalController::class, 'index'])->name('journal.index');
        Route::get('/journal/create',    [JournalController::class, 'create'])->name('journal.create');
        Route::post('/journal',          [JournalController::class, 'store'])->name('journal.store');

        // Presented entries
        Route::get('/presented',         [PresentedController::class, 'index'])->name('presented.index');
        Route::get('/presented/create',  [PresentedController::class, 'create'])->name('presented.create');
        Route::post('/presented',        [PresentedController::class, 'store'])->name('presented.store');

        // Published entries
        Route::get('/published',         [PublishedController::class, 'index'])->name('published.index');
        Route::get('/published/create',  [PublishedController::class, 'create'])->name('published.create');
        Route::post('/published',        [PublishedController::class, 'store'])->name('published.store');

        // Suggestions
        Route::get('/suggestions',       [SuggestionController::class, 'index'])->name('suggestions.index');
        Route::post('/suggestions',      [SuggestionController::class, 'store'])->name('suggestions.store');

        // Supervisor review and approval
        Route::get('/supervisor/entries',                  [\App\Http\Controllers\SupervisorApprovalController::class, 'index'])->name('supervisor.entries');
        Route::get('/supervisor/entries/{type}/{id}',      [\App\Http\Controllers\SupervisorApprovalController::class, 'show'])->name('supervisor.entry.show')->where('id', '[0-9]+');
        Route::post('/supervisor/entries/{type}/{id}/status', [\App\Http\Controllers\SupervisorApprovalController::class, 'updateStatus'])->name('supervisor.entry.status')->where('id', '[0-9]+');
        Route::post('/supervisor/entries/bulk-status',     [\App\Http\Controllers\SupervisorApprovalController::class, 'bulkUpdateStatus'])->name('supervisor.entries.bulk');
        Route::get('/supervisor/trainees/{id}/academic-review', [\App\Http\Controllers\SupervisorApprovalController::class, 'academicReview'])->name('supervisor.academic-review')->where('id', '[0-9]+');
        Route::get('/supervisor/academic-review',                [\App\Http\Controllers\SupervisorApprovalController::class, 'academicReview'])->name('supervisor.academic-review.direct');

        // Dynamic Academic Progression Management (Workshops, Synopsis, Dissertation)
        Route::get('/academic-progression',                                   [\App\Http\Controllers\AcademicProgressionController::class, 'traineeIndex'])->name('academic.trainee');
        Route::post('/supervisor/trainees/{id}/workshops',                    [\App\Http\Controllers\AcademicProgressionController::class, 'storeWorkshop'])->name('academic.workshop.store')->where('id', '[0-9]+');
        Route::delete('/supervisor/trainees/{id}/workshops/{workshopId}',     [\App\Http\Controllers\AcademicProgressionController::class, 'deleteWorkshop'])->name('academic.workshop.delete')->where(['id' => '[0-9]+', 'workshopId' => '[0-9]+']);
        Route::post('/supervisor/trainees/{id}/synopses',                     [\App\Http\Controllers\AcademicProgressionController::class, 'storeSynopsis'])->name('academic.synopsis.store')->where('id', '[0-9]+');
        Route::delete('/supervisor/trainees/{id}/synopses/{synopsisId}',      [\App\Http\Controllers\AcademicProgressionController::class, 'deleteSynopsis'])->name('academic.synopsis.delete')->where(['id' => '[0-9]+', 'synopsisId' => '[0-9]+']);
        Route::post('/supervisor/trainees/{id}/dissertations',                [\App\Http\Controllers\AcademicProgressionController::class, 'storeDissertation'])->name('academic.dissertation.store')->where('id', '[0-9]+');
        Route::delete('/supervisor/trainees/{id}/dissertations/{dissId}',     [\App\Http\Controllers\AcademicProgressionController::class, 'deleteDissertation'])->name('academic.dissertation.delete')->where(['id' => '[0-9]+', 'dissId' => '[0-9]+']);

        // Profile & Password Management
        Route::get('/profile',           [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::post('/profile',          [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
