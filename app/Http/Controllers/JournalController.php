<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeachingJournal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'schedule_session_id' => 'required|exists:schedule_sessions,id',
            'activity_desc' => 'required|string'
        ]);

        $row = TeachingJournal::updateOrCreate(
            ['schedule_session_id' => $data['schedule_session_id']],
            [
                'activity_desc' => $data['activity_desc'],
                'filled_by_nip' => $request->user()->id ?? 't-19800101'
            ]
        );

        return response()->json($row, 201);
    }
}
