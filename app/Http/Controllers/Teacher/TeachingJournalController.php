<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Models\TeachingJournal;
use App\Models\AttendanceRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeachingJournalController extends Controller
{
    // API to fetch BAP data & students for a modal
    public function getData(ScheduleSession $session)
    {
        // 1. Get Existing Journal for "Today" (or specific date logic if passed)
        // User requested "realtime realife", so we assume we are managing "Today's" session.
        // Or if editing past session, we should use that date. 
        // For now, let's use the session's intended date derived from weekday vs today? 
        // Or simply: check if a journal exists for this session_id.
        // Since schedule_sessions are "weekly templates", a journal attaches to a specific DATE.
        // We will assume "Today" for now as per dashboard flow.
        
        $today = Carbon::today();
        
        $journal = TeachingJournal::where('schedule_session_id', $session->id)
                    ->whereDate('journal_date', $today) 
                    ->with('attendanceRecords')
                    ->first();

        // 2. Get Students
        $students = Student::with('user')
                    ->where('classroom_id', $session->classroom_id)
                    ->orderBy('nis') // Order by NIS or Name
                    ->get()
                    ->map(function($student) use ($journal) {
                        // Attach current attendance status if journal exists
                        $status = 'alpa'; // Default as requested
                        if ($journal) {
                            $record = $journal->attendanceRecords->firstWhere('student_nis', $student->nis);
                            $status = $record ? $record->status : 'alpa';
                        }
                        
                        return [
                            'nis' => $student->nis,
                            'name' => $student->user->full_name,
                            'status' => $status
                        ];
                    });

        \Illuminate\Support\Facades\Log::info("getData BAP: Session ID " . $session->id);
        
        // Ensure relations are loaded
        $session->load(['subject', 'classroom']);

        return response()->json([
            'session' => $session, // Keep for backward compat if needed
            'subject_name' => $session->subject ? $session->subject->name : 'Mata Pelajaran ???',
            'classroom_name' => $session->classroom ? $session->classroom->name : 'Kelas ???',
            'journal' => $journal,
            'students' => $students,
            'date_formatted' => $today->locale('id')->isoFormat('D MMMM Y')
        ]);
    }

    public function store(Request $request, ScheduleSession $session)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'observation_notes' => 'nullable|string',
            'location' => 'nullable|string',
            'attendance' => 'nullable|array', 
            'attendance.*.nis' => 'required_with:attendance|exists:students,nis',
            'attendance.*.status' => 'required_with:attendance|in:hadir,sakit,izin,alpa',
        ]);

        DB::beginTransaction();
        try {
            $today = Carbon::today();

            // 1. Create/Update Journal
            $journal = TeachingJournal::updateOrCreate(
                [
                    'schedule_session_id' => $session->id,
                    'journal_date' => $today,
                ],
                [
                    'topic' => $validated['topic'],
                    'observation_notes' => $validated['observation_notes'] ?? null,
                    'location' => $validated['location'] ?? null,
                ]
            );

            // 2. Process Attendance
            if (!empty($validated['attendance'])) {
                foreach ($validated['attendance'] as $item) {
                    AttendanceRecord::updateOrCreate(
                        [
                            'teaching_journal_id' => $journal->id,
                            'student_nis' => $item['nis'],
                        ],
                        [
                            'status' => $item['status'] 
                        ]
                    );
                }
            } else {
                // FALLBACK: "Forward current data"
                // If records exist (from QR), keep them.
                // If not, create as 'alpa' (Absent).
                $students = Student::where('classroom_id', $session->classroom_id)->pluck('nis');
                foreach($students as $nis) {
                    AttendanceRecord::firstOrCreate(
                        [
                            'teaching_journal_id' => $journal->id,
                            'student_nis' => $nis,
                        ],
                        [
                            'status' => 'alpa' // Default to ALPA as requested
                        ]
                    );
                }
            }

            DB::commit();



            // Send Notification
            // Handle Mobile request where Auth::user() might be null but user_id is sent
            $user = $request->user();
            if (!$user && $request->has('user_id')) {
                $user = \App\Models\User::find($request->user_id);
            }

            if ($user) {
                $user->notify(new \App\Notifications\BapSaved($journal));
            }

            return response()->json(['message' => 'BAP berhasil disimpan', 'journal_id' => $journal->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan BAP: ' . $e->getMessage()], 500);
        }
    }
    public function history(ScheduleSession $session)
    {
        // 1. Get all session IDs for this Class + Subject + Teacher
        // ... (existing logic)
        $sessionIds = ScheduleSession::where('teacher_nip', $session->teacher_nip)
                        ->where('classroom_id', $session->classroom_id)
                        ->where('subject_code', $session->subject_code)
                        ->pluck('id');

        // 2. Fetch Journals
        $journals = TeachingJournal::whereIn('schedule_session_id', $sessionIds)
                        ->with(['attendanceRecords'])
                        ->orderBy('journal_date', 'desc')
                        ->get()
                        ->map(function ($j) {
                            return [
                                'id' => $j->id,
                                'date' => Carbon::parse($j->journal_date)->locale('id')->isoFormat('dddd, D MMMM Y'),
                                'topic' => $j->topic,
                                'hadir' => $j->attendanceRecords->filter(fn($r) => strcasecmp($r->status, 'hadir') === 0)->count(),
                                'sakit' => $j->attendanceRecords->filter(fn($r) => strcasecmp($r->status, 'sakit') === 0)->count(),
                                'izin'  => $j->attendanceRecords->filter(fn($r) => strcasecmp($r->status, 'izin') === 0)->count(),
                                'alpa'  => $j->attendanceRecords->filter(fn($r) => strcasecmp($r->status, 'alpa') === 0)->count(),
                            ];
                        });

        return view('pages.teacher.history', compact('session', 'journals'));
    }

    public function getJournalDetail(TeachingJournal $journal)
    {
        $journal->load(['attendanceRecords.student.user']);

        $attendance = $journal->attendanceRecords->map(function ($record) {
            return [
                'nis' => $record->student_nis,
                'name' => $record->student->user->full_name ?? 'Unknown',
                'status' => $record->status,
                'notes' => $record->notes
            ];
        });

        return response()->json([
            'date' => Carbon::parse($journal->journal_date)->locale('id')->isoFormat('dddd, D MMMM Y'),
            'topic' => $journal->topic,
            'notes' => $journal->observation_notes,
            'location' => $journal->location,
            'attendance' => $attendance
        ]);
    }
}
