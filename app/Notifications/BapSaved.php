<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\TeachingJournal;
use Carbon\Carbon;

class BapSaved extends Notification
{
    use Queueable;

    public $journal;

    /**
     * Create a new notification instance.
     */
    public function __construct(TeachingJournal $journal)
    {
        $this->journal = $journal;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Load relationships if needed
        if (!$this->journal->relationLoaded('session'))
            $this->journal->load('session.subject', 'session.classroom');

        $subject = $this->journal->session->subject->name ?? 'Mata Pelajaran';
        $class = $this->journal->session->classroom->name ?? 'Kelas';
        $date = Carbon::parse($this->journal->journal_date)->isoFormat('D MMMM Y');

        return [
            'title' => 'BAP Tersimpan ✅',
            'message' => "BAP {$subject} ({$class}) tanggal {$date} berhasil disimpan.",
            'link' => route('teacher.schedule.history', $this->journal->schedule_session_id), // Link ke history
            'type' => 'attendance'
        ];
    }
}
