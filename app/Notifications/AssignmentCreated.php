<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Assignment;

class AssignmentCreated extends Notification
{
    use Queueable;

    public $assignment;

   
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tugas Baru: ' . $this->assignment->title,
            'message' => 'Tugas untuk mata pelajaran ' . ($this->assignment->session->subject->name ?? 'Subject') . ' telah terbit.',
            'link' => route('pages.teacher.materi', $this->assignment->schedule_session_id), // Link ke halaman materi
            'type' => 'assignment_new'
        ];
    }
}
