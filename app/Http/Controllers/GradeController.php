<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\OfflineAssessment;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function gradeAssignment(Request $request, Assignment $assignment): JsonResponse
    {
        $data = $request->validate([
            'student_nis'=>'required|exists:students,nis',
            'score'=>'required|numeric|min:0|max:100',
            'feedback'=>'nullable|string'
        ]);

        $sub = Submission::firstOrCreate([
            'assignment_id'=>$assignment->id,
            'student_nis'=>$data['student_nis']
        ]);

        $sub->update([
            'score'=>$data['score'],
            'feedback'=>$data['feedback'] ?? null,
            'status'=>'Sudah Dinilai'
        ]);

        return response()->json($sub);
    }

    public function createOfflineItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_nis'=>'required|exists:students,nis',
            'subject_code'=>'required|exists:subjects,code',
            'semester_id'=>'required|exists:semesters,id',
            'type'=>'required|in:UH,Praktikum,UTS,UAS,"Nilai Akhir Semester",Lainnya',
            'score'=>'required|numeric|min:0|max:100',
            'graded_on'=>'required|date'
        ]);

        $row = OfflineAssessment::create(array_merge($data, [
            'graded_by_nip'=>$request->user()->id ?? 't-19800101'
        ]));

        return response()->json($row, 201);
    }
}
