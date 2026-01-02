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
        $user = Auth::user();
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:3000/api/schedule', [
                'user_id' => $user->id
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                $schedules = collect($data)->map(function ($item) {
                    return (object) [
                        'id' => $item['id'],
                        'start_time' => $item['start_time'],
                        'end_time' => $item['end_time'],
                        'weekday' => $item['weekday'],
                        'subject' => (object) [
                            'name' => $item['subject_name'],
                            'code' => $item['subject_code'],
                            'description' => $item['subject_description'] ?? ''
                        ],
                        'classroom' => (object) [
                            'name' => $item['classroom_name']
                        ]
                    ];
                });
            } else {
                \Illuminate\Support\Facades\Log::error('Node Service Error: ' . $response->body());
                $schedules = collect([]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Node Service Connectivity Error: ' . $e->getMessage());
            $schedules = collect([]);
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

       
        $scheduleByDay = collect($days)->mapWithKeys(function ($day) use ($schedules) {
            return [$day => $schedules->where('weekday', $day)->values()];
        });

        if (request()->wantsJson()) {
            return response()->json($scheduleByDay);
        }

        return view('pages.teacher.schedule', compact('scheduleByDay'));
    }
}
