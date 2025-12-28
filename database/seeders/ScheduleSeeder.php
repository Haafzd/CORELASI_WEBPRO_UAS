<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\Student; // Changed from User to explicit Models
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ScheduleSession;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Subjects
        // Guru 1: MTK Minat, MTK Wajib, Fisika
        // Guru 2: Sejarah Minat, Ekonomi, Geografi
        $subjects = [
            ['code' => 'MTK-M', 'name' => 'Matematika Peminatan', 'description' => 'Matematika Peminatan IPA'],
            ['code' => 'MTK-W', 'name' => 'Matematika Wajib', 'description' => 'Matematika Umum'],
            ['code' => 'FIS',   'name' => 'Fisika', 'description' => 'Fisika Dasar & Terapan'],
            ['code' => 'SEJ-M', 'name' => 'Sejarah Peminatan', 'description' => 'Sejarah Dunia'],
            ['code' => 'EKO',   'name' => 'Ekonomi', 'description' => 'Prinsip Ekonomi'],
            ['code' => 'GEO',   'name' => 'Geografi', 'description' => 'Geografi & Lingkungan'],
        ];

        foreach ($subjects as $sub) {
            Subject::updateOrCreate(['code' => $sub['code']], $sub);
        }

        // 2. Academic Year & Semester
        $year = AcademicYear::firstOrCreate(
            ['label' => '2024/2025'],
            ['start_date' => '2024-07-01', 'end_date' => '2025-06-30']
        );

        $semester = Semester::firstOrCreate(
            ['name' => 'Ganjil', 'academic_year_id' => $year->id],
            ['is_active' => true, 'start_date' => '2024-07-01', 'end_date' => '2024-12-31']
        );

        // 3. Classrooms (X, XI, XII - IPA 1-3, IPS 1-2)
        $classes = [];
        $levels = ['X', 'XI', 'XII'];
        
        foreach ($levels as $lvl) {
            // IPA 1-3
            for ($i = 1; $i <= 3; $i++) {
                $name = "{$lvl} IPA {$i}";
                $classes[] = Classroom::firstOrCreate(
                    ['name' => $name],
                    ['cohort_year' => '2024', 'major' => 'IPA']
                );
            }
            // IPS 1-2
            for ($i = 1; $i <= 2; $i++) {
                $name = "{$lvl} IPS {$i}";
                $classes[] = Classroom::firstOrCreate(
                    ['name' => $name],
                    ['cohort_year' => '2024', 'major' => 'IPS']
                );
            }
        }

        // 4. Create Students (Dummy 5 per class)
        foreach ($classes as $cls) {
            for ($k = 1; $k <= 5; $k++) {
                $nis = rand(10000, 99999);
                // Create User for Student
                $sUser = User::create([
                    'username' => 'siswa_' . str_replace(' ', '', $cls->name) . '_' . $k,
                    'password' => Hash::make('password'),
                    'full_name' => "Siswa {$k} {$cls->name}",
                    'role' => 'Siswa',
                    'account_status' => 'Aktif',
                    'email' => "siswa{$nis}@school.com"
                ]);
                
                Student::create([
                    'nis' => (string)$nis,
                    'user_id' => $sUser->id,
                    'classroom_id' => $cls->id,
                    'entry_cohort' => '2024',
                    'short_bio' => 'Student of ' . $cls->name
                ]);
            }
        }


        // 5. Create Teachers
        // Guru 1 (Science)
        $userG1 = User::firstOrCreate(['email' => 'guru1@corelasi.com'], [
            'username' => 'guru_ipa',
            'password' => Hash::make('password'),
            'full_name' => 'Bapak Sains S.Si',
            'role' => 'Guru',
            'account_status' => 'Aktif'
        ]);
        $teacher1 = Teacher::firstOrCreate(
            ['nip' => 'GURU001'],
            ['user_id' => $userG1->id, 'phone' => '08111111111', 'is_duty_teacher' => false]
        );

        // Guru 2 (Social)
        $userG2 = User::firstOrCreate(['email' => 'guru2@corelasi.com'], [
            'username' => 'guru_ips',
            'password' => Hash::make('password'),
            'full_name' => 'Ibu Sosio S.Pd',
            'role' => 'Guru',
            'account_status' => 'Aktif'
        ]);
        $teacher2 = Teacher::firstOrCreate(
            ['nip' => 'GURU002'],
            ['user_id' => $userG2->id, 'phone' => '08222222222', 'is_duty_teacher' => false]
        );

        // 6. Create Daily Schedule
        // Helper to add session
        $addSession = function($teacher, $subjectCode, $className, $day, $start, $end) use ($semester, $classes, $subjects) {
            // Find Classroom
            $cls = collect($classes)->firstWhere('name', $className);
            if (!$cls) return;

            // Find Subject (from array)
            // Note: $subjects is array of arrays ['code' => ...]
            $sub = collect($subjects)->firstWhere('code', $subjectCode);
            
            // If not in array, maybe check DB (since we used updateOrCreate)
            if (!$sub) {
                $subModel = Subject::where('code', $subjectCode)->first();
                if (!$subModel) return;
                $code = $subModel->code;
            } else {
                $code = $sub['code'];
            }

            ScheduleSession::create([
                'teacher_nip' => $teacher->nip,
                'subject_code' => $code,
                'classroom_id' => $cls->id,
                'semester_id' => $semester->id,
                'weekday' => $day,
                'start_time' => $start,
                'end_time' => $end,
                'is_active' => true
            ]);
        };

        // GURU 1: MTK Minat, MTK Wajib, Fisika (Science Classes)
        // Senin
        $addSession($teacher1, 'MTK-W', 'X IPA 1', 'Senin', '07:00', '08:30');
        $addSession($teacher1, 'FIS',   'X IPA 2', 'Senin', '08:45', '10:15');
        $addSession($teacher1, 'MTK-M', 'XI IPA 1', 'Senin', '10:30', '12:00');
        // Selasa
        $addSession($teacher1, 'FIS',   'XI IPA 2', 'Selasa', '07:00', '08:30');
        $addSession($teacher1, 'MTK-W', 'XII IPA 1', 'Selasa', '08:45', '10:15');
        // Rabu
        $addSession($teacher1, 'MTK-M', 'XII IPA 2', 'Rabu', '10:30', '12:00');
        // Kamis
        $addSession($teacher1, 'FIS',   'X IPA 3', 'Kamis', '07:00', '08:30');
        // Jumat
        $addSession($teacher1, 'MTK-W', 'XI IPA 3', 'Jumat', '08:00', '09:30');


        // GURU 2: Sejarah Minat, Ekonomi, Geografi (Social Classes)
        // Senin
        $addSession($teacher2, 'EKO',   'X IPS 1', 'Senin', '07:00', '08:30');
        $addSession($teacher2, 'GEO',   'X IPS 2', 'Senin', '08:45', '10:15');
        // Selasa
        $addSession($teacher2, 'SEJ-M', 'XI IPS 1', 'Selasa', '10:30', '12:00');
        // Rabu
        $addSession($teacher2, 'EKO',   'XI IPS 2', 'Rabu', '07:00', '08:30');
        $addSession($teacher2, 'GEO',   'XII IPS 1', 'Rabu', '08:45', '10:15');
        // Kamis
        $addSession($teacher2, 'SEJ-M', 'XII IPS 2', 'Kamis', '13:00', '14:30');
        // Jumat
        $addSession($teacher2, 'EKO',   'X IPS 1', 'Jumat', '08:00', '09:30');

    }
}
