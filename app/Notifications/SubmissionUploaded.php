<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Submission;

class SubmissionUploaded extends Notification
{
    use Queueable;

    public $submission;
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if (!$this->submission->relationLoaded('student'))
            $this->submission->load('student.user');
        if (!$this->submission->relationLoaded('assignment'))
            $this->submission->load('assignment.session');

        $studentName = $this->submission->student->user->full_name ?? 'Siswa';
        $assignmentTitle = $this->submission->assignment->title ?? 'Tugas';
        $sessionId = $this->submission->assignment->schedule_session_id;

        return [
            'title' => 'Tugas Dikumpulkan',
            'message' => "{$studentName} telah mengumpulkan tugas \"{$assignmentTitle}\".",
            'link' => route('pages.teacher.materi', $sessionId), 
            'type' => 'success'
        ];
    }
}
