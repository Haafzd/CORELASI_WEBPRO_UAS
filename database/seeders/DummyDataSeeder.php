<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
   
    public function run(): void
    {
        $sessions = \App\Models\ScheduleSession::with(['classroom.students', 'subject', 'teacher'])->get();

        foreach($sessions as $session) {
            for($m=1; $m<=3; $m++) {
                \App\Models\LearningMaterial::create([
                    'schedule_session_id' => $session->id,
                    'title' => "Materi {$session->subject->name} - Pertemuan {$m}",
                    'description' => "Ini adalah materi pembahasan bab {$m} untuk mata pelajaran {$session->subject->name}. Silakan dipelajari.",
                    'external_link' => 'https://example.com/materi-pdf',
                    'publish_status' => 'Published',
                    'created_at' => now()->subDays(rand(1, 40))
                ]);
            }

          
            $assignments = [];
            for($t=1; $t<=2; $t++) {
                $deadline = now()->addDays(rand(-5, 5));
                $assignments[] = \App\Models\Assignment::create([
                    'schedule_session_id' => $session->id,
                    'title' => "Tugas {$session->subject->name} - Latihan {$t}",
                    'instruction' => "Kerjakan soal-soal latihan bab {$t}. Kumpulkan tepat waktu. Jangan lupa format PDF.",
                    'external_problem_link' => 'https://example.com/soal-latihan',
                    'deadline_at' => $deadline, // Some past deadlines, some future
                    'publish_status' => 'Published',
                    'created_at' => now()->subDays(rand(10, 20))
                ]);
            }

           
            $weekdayMap = [
                'Senin' => 0, 'Selasa' => 1, 'Rabu' => 2, 
                'Kamis' => 3, 'Jumat' => 4, 'Sabtu' => 5, 'Minggu' => 6
            ];
            
            $addDays = $weekdayMap[$session->weekday] ?? 0;

            for($h=1; $h<=4; $h++) {
                $date = now()->subWeeks($h)->startOfWeek()->addDays($addDays);
                
                $journal = \App\Models\TeachingJournal::create([
                    'schedule_session_id' => $session->id,
                    'journal_date' => $date,
                    'topic' => "Pembahasan Bab {$h}: " . ($h % 2 == 0 ? 'Latihan Soal' : 'Teori Dasar'),
                    'observation_notes' => "Kegiatan belajar mengajar berjalan lancar. Siswa aktif bertanya. (Metode: Ceramah & Diskusi)",
                    'location' => $session->classroom->name,
                    'created_at' => $date
                ]);

              
                foreach($session->classroom->students as $student) {
                    $status = ['hadir','hadir','hadir','sakit','izin','alpa'][rand(0,5)]; 
                    \App\Models\AttendanceRecord::create([
                        'teaching_journal_id' => $journal->id,
                        'student_nis' => $student->nis,
                        'status' => $status,
                        'notes' => $status == 'sakit' ? 'Sakit Demam' : '-'
                    ]);
                }
            }

            foreach($assignments as $assign) {
                foreach($session->classroom->students as $student) {
                    if(rand(0, 10) > 3) {
                        $isLate = rand(0, 1) == 1;
                        $submittedAt = \Carbon\Carbon::parse($assign->deadline_at)->subHours(rand(1, 48));
                        if($isLate) $submittedAt = \Carbon\Carbon::parse($assign->deadline_at)->addHours(rand(1, 24));
                        
                        $isGraded = rand(0, 1) == 1;
                        $score = $isGraded ? rand(70, 100) : null;
                        $status = $isGraded ? 'Sudah Dinilai' : ($isLate ? 'Terlambat' : 'Tepat Waktu');

                        $notes = [
                            "Bu, soal nomor 3 agak membingungkan tapi saya coba.",
                            "Sudah saya kerjakan semampu saya pak.",
                            "Tugas selesai.",
                            "-",
                            "Mohon koreksinya.",
                            "Gampang banget bu soalnya hehe.",
                            "Maaf telat bu."
                        ];

                        \App\Models\Submission::create([
                            'assignment_id' => $assign->id,
                            'student_nis' => $student->nis,
                            'file_path' => 'dummy/path/file.pdf',
                            'original_name' => 'tugas_' . $student->nis . '.pdf',
                            'submitted_at' => $submittedAt,
                            'student_note' => $notes[rand(0, 6)],
                            'status' => $status,
                            'score' => $score,
                            'feedback' => $isGraded ? "Bagus, tingkatkan!" : null
                        ]);
                    }
                }
            }
        }
    }
}
