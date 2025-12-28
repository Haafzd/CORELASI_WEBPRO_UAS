<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Teacher\TeachingJournalController;
use App\Http\Controllers\Teacher\GradeController;

// Add ->middleware('auth:sanctum') when enabling auth
Route::group([], function () {
    // Attendance
    Route::post('attendance/scan',   [AttendanceController::class,'scan']);
    Route::post('attendance/manual', [AttendanceController::class,'manual']);
    Route::post('attendance/status', [AttendanceController::class,'status']);
    Route::get('attendance/recap',   [AttendanceController::class,'recap']);

    // Teacher modules
    // Route must match controller signature: store(Request $request, ScheduleSession $session)
    Route::get('teacher/sessions/{session}/data', [TeachingJournalController::class,'getData']);
    Route::post('teacher/sessions/{session}/journals', [TeachingJournalController::class,'store']);

    // Mobile API specific
    Route::post('mobile/login', [\App\Http\Controllers\Api\MobileApiController::class, 'login']);
    Route::get('mobile/schedule', [\App\Http\Controllers\Api\MobileApiController::class, 'getTodaySchedule']);
    Route::post('mobile/teacher/sessions/{session}/journals', [TeachingJournalController::class, 'store']);
    Route::get('mobile/teacher/sessions/{session}/data', [TeachingJournalController::class, 'getData']);
    
    // Fix for Mobile Scan
    Route::post('mobile/attendance/scan', [AttendanceController::class, 'scan']);
    
    // Notifications
    Route::get('mobile/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('mobile/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead']);
});
