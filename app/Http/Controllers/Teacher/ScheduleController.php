<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        // For now, assume logged in user is authorized and has a teacher profile
        // In real app, check Auth::user()->teacher
        $user = Auth::user();

        // 1. Get Teacher Profile
        $teacher = $user->teacher; // Assuming User hasOne Teacher
        
        if(!$teacher) {
            $schedules = collect([]);
        } else {
            // 2. Fetch schedules filtered by Teacher NIP
            $schedules = ScheduleSession::with(['subject', 'classroom'])
                ->where('teacher_nip', $teacher->nip) // Strict filter
                ->where('is_active', true)
                ->orderBy('start_time') // Order by time within the day
                ->get();
        }

        // Define expected weekdays order (Indonesian)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Group by weekday and Ensure all days exist
        $scheduleByDay = collect($days)->mapWithKeys(function($day) use ($schedules) {
            return [$day => $schedules->where('weekday', $day)->values()];
        });

        if(request()->wantsJson()) {
            return response()->json($scheduleByDay);
        }

        return view('pages.teacher.schedule', compact('scheduleByDay'));
    }
}
