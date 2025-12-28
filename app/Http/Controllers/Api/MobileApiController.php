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
    /**
     * API LOGIN
     * Mobile mengirim: email, password
     * Server membalas: data user & token (simple)
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek Credential
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
            // Di production sebaiknya pakai Sanctum/Passport token.
            // Untuk simpel saat ini kita return data user saja.
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name ?? $user->name,
                    'email' => $user->email,
                    'nip' => $user->teacher->nip ?? null, // Ambil NIP dari relasi teacher
                ]
            ]);
        }

        // Jika salah
        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah.'
        ], 401);
    }

    /**
     * API GET SCHEDULE (JADWAL HARI INI)
     * Mobile butuh list kelas yang harus diajar HARI INI.
     */
    public function getTodaySchedule(Request $request) 
    {
        // Ambil user yang sedang login (bisa dari params atau auth)
        // Karena mobile kita simple, kita bisa kirim user_id atau nip via query param sementara,
        // atau jika nanti pakai token, ambil dari Auth::user().
        
        // Asumsi: Mobile mengirim ?nip=... atau kita cari user berdasarkan email param. 
        // Best practice: Pakai Auth Token. Tapi kita buat fleksibel dulu.
        
        $nip = $request->nip; // Mobile kirim NIP setelah login berhasil
        
        if(!$nip) {
            return response()->json(['success'=>false, 'message'=>'NIP user diperlukan'], 400);
        }

        // Cari Hari ini (Senin, Selasa, dll) dalam bhs Indonesia
        Carbon::setLocale('id');
        // Force Day to Senin for Testing
        // $todayName = 'Senin'; 
        $todayName = Carbon::now()->isoFormat('dddd'); // "Senin", "Selasa"...

        // Query Jadwal
        \Illuminate\Support\Facades\Log::info("Mobile Schedule Req: NIP={$nip}, Day={$todayName}");
        
        $schedules = ScheduleSession::with(['subject', 'classroom'])
            ->where('teacher_nip', $nip)
            ->where('weekday', $todayName)
            ->where('is_active', true) // Re-enable active check
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
