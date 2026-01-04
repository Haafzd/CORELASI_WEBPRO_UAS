<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ScheduleSession;
use Carbon\Carbon;

class MobileApiController extends Controller
{
    public function login(Request $request)
    {
        //  Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek Credential
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Cek apakah dia Guru
            // Role di database bisa 'Guru' atau 'teacher'
            if (!in_array(strtolower($user->role), ['teacher', 'guru'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun ini bukan akun Guru. (' . $user->role . ')'
                ], 403);
            }

            // 3. Return Data User
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name ?? $user->name,
                    'email' => $user->email,
                    'nip' => $user->teacher->nip ?? null, 
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah.'
        ], 401);
    }

    
    /* API GET SCHEDULE (JADWAL HARI INI)*/
    public function getTodaySchedule(Request $request) 
    {
        $nip = $request->nip; 
        
        if(!$nip) {
            return response()->json(['success'=>false, 'message'=>'NIP user diperlukan'], 400);
        }
        Carbon::setLocale('id');
     
        $todayName = Carbon::now()->isoFormat('dddd'); 
        \Illuminate\Support\Facades\Log::info("Mobile Schedule Req: NIP={$nip}, Day={$todayName}");
        $schedules = ScheduleSession::with(['subject', 'classroom'])
            ->where('teacher_nip', $nip)
            ->where('weekday', $todayName)
            ->where('is_active', true) 
            ->orderBy('start_time')
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'subject_name' => $s->subject->name,
                    'class_name' => $s->classroom->name,
                    'time_start' => Carbon::parse($s->start_time)->format('H:i'),
                    'time_end' => Carbon::parse($s->end_time)->format('H:i'),
                    'room' => $s->classroom->name, 
                    'is_active' => true 
                ];
            });

        \Illuminate\Support\Facades\Log::info("Found: " . count($schedules));

        return response()->json([
            'success' => true,
            'day' => $todayName,
            'date' => Carbon::now()->isoFormat('D MMMM Y'),
            'schedules' => $schedules
        ]);
    }
}
