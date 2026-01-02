<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GradeReleased extends Notification
{
    use Queueable;

    public $assignment;
    public $score;

    /**
     * Create a new notification instance.
     *
     * @param $assignment
     * @param $score
     */
    public function __construct($assignment, $score)
    {
        $this->assignment = $assignment;
        $this->score = $score;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assignment_id' => $this->assignment->id,
            'title' => $this->assignment->title,
            'score' => $this->score,
            'message' => 'Nilai tugas "' . $this->assignment->title . '" sudah keluar: ' . $this->score,
            'type' => 'grade_released'
        ];
    }
}
