<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's weekday for "Today Schedule"
        $today = Carbon::now()->locale('id')->isoFormat('dddd');
        // Fallback for dev if day names mismatch (e.g. English system vs Indo seeder)
        // Seeder uses: Senin, Selasa, etc.

        $user = Auth::user();
        // Assuming relationship: User -> Teacher
        // We need the teacher model to get NIP/ID
        $teacher = $user->teacher; // Make sure this relationship exists in User model

        if (!$teacher) {
            // Handle case if user is not a teacher (e.g. admin or student logged in by mistake)
             $todaySchedule = collect([]);
             $courses = collect([]);
        } else {
             $todaySchedule = ScheduleSession::with(['subject', 'classroom'])
                ->where('teacher_nip', $teacher->nip)
                ->where('weekday', $today)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get();

            $courses = ScheduleSession::with('subject')
                ->where('teacher_nip', $teacher->nip)
                ->where('weekday', $today)
                ->get()
                ->unique(function ($session) {
                     return $session->subject_code . '-' . $session->classroom_id;
                })
                ->map(function ($session) {
                    return [
                        'code' => $session->subject_code,
                        'name' => $session->subject->name ?? 'Unknown',
                        'session_id' => $session->id,
                        'class' => $session->classroom->name ?? '-',
                        'room' => $session->classroom->name ?? '-', 
                        'color' => '#EEF2FF'
                    ];
                });

            // Get Assignments needing grading
            $ungradedAssignments = \App\Models\Assignment::whereHas('session', function($q) use ($teacher) {
                $q->where('teacher_nip', $teacher->nip);
            })
            ->with(['session.subject', 'session.classroom'])
            ->withCount(['submissions' => function($q) {
                $q->whereNull('score')->whereIn('status', ['Tepat Waktu', 'Terlambat']);
            }])
            ->having('submissions_count', '>', 0)
            ->take(5)
            ->get();
        }

        if(request()->wantsJson()) {
            return response()->json([
                'todaySchedule' => $todaySchedule,
                'courses' => $courses,
                'ungradedAssignments' => $ungradedAssignments ?? [],
                'user' => Auth::user(),
            ]);
        }


        // Greeting Logic
        $hour = Carbon::now('Asia/Jakarta')->hour;
        if ($hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        return view('pages.teacher.dashboard', compact('todaySchedule', 'courses', 'ungradedAssignments', 'today', 'greeting', 'user'));
    }

    public function courses()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            $courses = collect([]);
        } else {
            $courses = ScheduleSession::with('subject')
                ->where('teacher_nip', $teacher->nip)
                ->get()
                ->unique(function ($session) {
                    return $session->subject_code . '-' . $session->classroom_id;
                })
                ->map(function ($session) {
                    return [
                        'code' => $session->subject_code,
                        'name' => $session->subject->name ?? 'Unknown',
                        'session_id' => $session->id,
                        'class' => $session->classroom->name ?? '-',
                        'color' => '#EEF2FF'
                    ];
                });
        }

        return view('pages.teacher.courses', compact('courses'));
    }
}
