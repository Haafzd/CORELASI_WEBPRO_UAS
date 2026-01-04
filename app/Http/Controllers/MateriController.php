<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(\App\Models\ScheduleSession $session)
    {
        $subject = $session->subject;
        $materi = \App\Models\LearningMaterial::where('schedule_session_id', $session->id)->get();
        $tugas = \App\Models\Assignment::where('schedule_session_id', $session->id)->get();

        if (request()->wantsJson()) {
            return response()->json([
                'session' => $session->load('subject', 'classroom'),
                'materi' => $materi,
                'tugas' => $tugas
            ]);
        }

        return view('pages.teacher.materi', compact('session', 'subject', 'materi', 'tugas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_session_id' => 'required|exists:schedule_sessions,id',
            'type' => 'required|in:materi,tugas',
            'title' => 'required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'instruction' => 'sometimes|nullable|string',
            'link' => 'nullable|url',
            'deadline_at' => 'nullable|required_if:type,tugas|date|after_or_equal:today',
        ]);

        if ($validated['type'] === 'materi') {
            \App\Models\LearningMaterial::create([
                'schedule_session_id' => $validated['schedule_session_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'external_link' => $validated['link'],
                'publish_status' => 'Published'
            ]);
        } else {
            $assignment = \App\Models\Assignment::create([
                'schedule_session_id' => $validated['schedule_session_id'],
                'title' => $validated['title'],
                'instruction' => $validated['description'],
                'external_problem_link' => $validated['link'],
                'deadline_at' => $validated['deadline_at'],
                'publish_status' => 'Published'
            ]);

            $session = \App\Models\ScheduleSession::with('classroom.students.user')->find($validated['schedule_session_id']);
            if ($session && $session->classroom) {
                foreach ($session->classroom->students as $student) {
                    if ($student->user) {
                        $student->user->notify(new \App\Notifications\AssignmentCreated($assignment->load('session.subject')));
                    }
                }
            }

            $request->user()->notify(new \App\Notifications\AssignmentCreated($assignment));
        }

        return redirect()->back()->with('success', ucfirst($validated['type']) . ' berhasil ditambahkan!');
    }

    public function updateMaterial(Request $request, \App\Models\LearningMaterial $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $material->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'external_link' => $validated['link'],
        ]);

        return back()->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroyMaterial(\App\Models\LearningMaterial $material)
    {
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }

    public function updateAssignment(Request $request, \App\Models\Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'deadline_at' => 'required|date|after_or_equal:today',
        ]);

        $assignment->update([
            'title' => $validated['title'],
            'instruction' => $validated['description'],
            'external_problem_link' => $validated['link'],
            'deadline_at' => $validated['deadline_at'],
        ]);

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroyAssignment(\App\Models\Assignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }
    public function getSubmissions(\App\Models\Assignment $assignment)
    {

        if (!$assignment->relationLoaded('session')) {
            $assignment->load('session');
        }

        $classroomId = $assignment->session->classroom_id;

        $students = \App\Models\Student::with('user')
            ->where('classroom_id', $classroomId)
            ->get();

        $submissions = \App\Models\Submission::where('assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_nis');

        $data = $students->map(function ($student) use ($submissions) {
            $sub = $submissions->get($student->nis);
            return [
                'nis' => $student->nis,
                'name' => $student->user->full_name,
                'note' => $sub ? $sub->student_note : '-',
                'score' => $sub ? $sub->score : null,
                'status' => $sub ? $sub->status : 'Belum Mengumpulkan',
                'submission_id' => $sub ? $sub->id : null
            ];
        });

        return response()->json([
            'assignment' => $assignment->only(['id', 'title']),
            'students' => $data
        ]);
    }

    public function gradeSubmission(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'student_nis' => 'required|exists:students,nis',
            'score' => 'required|numeric|min:0|max:100'
        ]);

        $submission = \App\Models\Submission::firstOrNew([
            'assignment_id' => $validated['assignment_id'],
            'student_nis' => $validated['student_nis']
        ]);

        $submission->score = $validated['score'];
        $submission->status = 'Sudah Dinilai';

        if (!$submission->exists) {
            $submission->file_path = '-';
            $submission->original_name = '-';
        }

        $submission->save();

        $student = \App\Models\Student::where('nis', $validated['student_nis'])->with('user')->first();
        if ($student && $student->user) {
            $assignment = \App\Models\Assignment::find($validated['assignment_id']);
            $student->user->notify(new \App\Notifications\GradeReleased($assignment, $validated['score']));
        }

        return response()->json(['message' => 'Nilai berhasil disimpan', 'submission' => $submission]);
    }
}
