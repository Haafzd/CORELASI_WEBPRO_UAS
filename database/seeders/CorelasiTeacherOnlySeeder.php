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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CorelasiTeacherOnlySeeder extends Seeder
{
    public function run(): void
    {
        // Teacher
        User::create([
            'id'=>'t-19800101','username'=>'t-19800101','password'=>Hash::make('Corelasi#123'),
            'full_name'=>'Ibu Guru','role'=>'Guru','account_status'=>'Aktif'
        ]);
        Teacher::create(['nip'=>'t-19800101','phone'=>'','is_duty_teacher'=>true]);

        // Student
        User::create([
            'id'=>'s-20230001','username'=>'s-20230001','password'=>Hash::make('Corelasi#123'),
            'full_name'=>'Andi Saputra','role'=>'Siswa','account_status'=>'Aktif'
        ]);
        Student::create(['nis'=>'s-20230001','entry_cohort'=>2023,'short_bio'=>null]);

        // Subjects
        Subject::insert([
            ['code'=>'MATEM','name'=>'Matematika','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'FISIK','name'=>'Fisika','created_at'=>now(),'updated_at'=>now()]
        ]);

        // Year & Semester
        $year = AcademicYear::create([
            'label'=>'2025/2026','start_date'=>'2025-07-15','end_date'=>'2026-06-30'
        ]);
        $sem = Semester::create([
            'academic_year_id'=>$year->id,'name'=>'Ganjil','start_date'=>'2025-07-15','end_date'=>'2025-12-31','is_active'=>true
        ]);

        // Classroom
        $cls = Classroom::create([
            'name'=>'X IPA 1','cohort_year'=>2025,'major'=>'IPA','homeroom_teacher_nip'=>'t-19800101'
        ]);

        Assignment::create([
            'schedule_session_id'=>1,
            'title'=>'Essay Hukum Newton',
            'instruction'=>'Tulis esai 500 kata.',
            'external_problem_link'=>null,
            'deadline_at'=>now()->addDays(2),
            'publish_status'=>'Published'
        ]);
    }
}
