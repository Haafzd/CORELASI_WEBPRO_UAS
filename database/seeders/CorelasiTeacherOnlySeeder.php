<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ScheduleSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CorelasiTeacherOnlySeeder extends Seeder
{
    public function run(): void
    {
        $teacherUser = User::create([
            'username' => 't-19800101',
            'password' => Hash::make('Corelasi#123'),
            'full_name' => 'Ibu Guru',
            'role' => 'Guru',
            'account_status' => 'Aktif'
        ]);

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'nip' => 'T1980',
            'phone' => '',
            'is_duty_teacher' => true
        ]);

        $studentUser = User::create([
            'username' => 's-20230001',
            'password' => Hash::make('Corelasi#123'),
            'full_name' => 'Andi Saputra',
            'role' => 'Siswa',
            'account_status' => 'Aktif'
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'nis' => 's-20230001',
            'entry_cohort' => 2023,
            'short_bio' => null
        ]);

        $subjectFisika = Subject::create([
            'code' => 'FISIK', 
            'name' => 'Fisika'
        ]);

        $year = AcademicYear::create([
            'label' => '2025/2026',
            'start_date' => '2025-07-15',
            'end_date' => '2026-06-30'
        ]);
        
        $sem = Semester::create([
            'academic_year_id' => $year->id,
            'name' => 'Ganjil',
            'start_date' => '2025-07-15',
            'end_date' => '2025-12-31',
            'is_active' => true
        ]);

        $cls = Classroom::create([
            'name' => 'X IPA 1',
            'cohort_year' => 2025,
            'major' => 'IPA',
            'homeroom_teacher_nip' => $teacher->nip
        ]);

 
        $session = ScheduleSession::create([
            'semester_id'  => $sem->id,
            'classroom_id' => $cls->id,
            'subject_code' => $subjectFisika->code,
            'teacher_nip'  => $teacher->nip,
            'weekday'      => 'Senin', 
            'start_time'   => '08:00:00',
            'end_time'     => '09:30:00',
            'is_active'    => true,
            'remark'       => 'Sesi perdana'
        ]);

        Assignment::create([
            'schedule_session_id'   => $session->id,
            'title'                 => 'Essay Hukum Newton',
            'instruction'           => 'Tulis esai 500 kata.',
            'external_problem_link' => null,
            'deadline_at'           => now()->addDays(2),
            'publish_status'        => 'Published'
        ]);
    }
}