<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\Auth\LoginController;

// Auth Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes untuk teacher (Protected)
Route::middleware(['auth'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/courses', [App\Http\Controllers\Teacher\DashboardController::class, 'courses'])->name('teacher.courses');
    Route::get('/teaching', [App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('teacher.schedule');
    // Materi per session related
    Route::get('/materi/{session}', [MateriController::class, 'index'])
        ->name('pages.teacher.materi');
    Route::post('/materi', [MateriController::class, 'store'])->name('teacher.materi.store');
    
    // BAP & Attendance API
    Route::get('/schedule/{session}/bap-data', [App\Http\Controllers\Teacher\TeachingJournalController::class, 'getData']);
    Route::get('/schedule/{session}/bap-data', [App\Http\Controllers\Teacher\TeachingJournalController::class, 'getData']);
    Route::post('/schedule/{session}/bap', [App\Http\Controllers\Teacher\TeachingJournalController::class, 'store']);
    Route::get('/schedule/{session}/history', [App\Http\Controllers\Teacher\TeachingJournalController::class, 'history'])->name('teacher.schedule.history');
    Route::get('/journal/{journal}/detail', [App\Http\Controllers\Teacher\TeachingJournalController::class, 'getJournalDetail'])->name('teacher.journal.detail');

    // Materi CRUD
    Route::put('/materials/{material}', [MateriController::class, 'updateMaterial'])->name('teacher.material.update');
    Route::delete('/materials/{material}', [MateriController::class, 'destroyMaterial'])->name('teacher.material.destroy');
    Route::put('/assignments/{assignment}', [MateriController::class, 'updateAssignment'])->name('teacher.assignment.update');
    Route::delete('/assignments/{assignment}', [MateriController::class, 'destroyAssignment'])->name('teacher.assignment.destroy');
    Route::get('/assignments/{assignment}/submissions', [MateriController::class, 'getSubmissions']);
    Route::post('/submissions/grade', [MateriController::class, 'gradeSubmission']);
});

// Notification Routes
Route::middleware(['auth'])->group(function() {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // TEST ROUTE 
    Route::get('/test-notif', function() {
        $user = \Illuminate\Support\Facades\Auth::user();
        $fakeAssignment = new \App\Models\Assignment();
        $fakeAssignment->id = 999;
        $fakeAssignment->title = "Tugas Percobaan (Dummy)";
        
        $user->notify(new \App\Notifications\GradeReleased($fakeAssignment, 85)); 
        
        return back()->with('success', 'Notifikasi dummy berhasil dikirim! Silakan cek lonceng.');
    });
    // Test TugasMTK
    Route::get('/kirim-notif', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $tugasPalsu = (object) [
            'title' => 'Latihan Soal Aljabar',
            'class_id' => 1,
            'schedule_session_id' => 1 // ID sesi dummy 
        ];
        $user->notify(new \App\Notifications\TugasMTK($tugasPalsu));
        return back()->with('success', 'Notifikasi TugasMTK terkirim! Cek lonceng.');
    });

    // Clear All Notifications 
    Route::get('/clear-notif', function() {
        \Illuminate\Support\Facades\Auth::user()->notifications()->delete();
        return back()->with('success', 'Semua notifikasi berhasil dihapus bersih!');
    });
});


Route::view('/touch', 'pages.touch.index')->name('touch');
