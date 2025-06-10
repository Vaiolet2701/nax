<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseInvitationNotification extends Notification
{
    public function __construct(
        public Course $course,
        public User $inviter,
        public string $message
    ) {}

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Приглашение на курс: ' . $this->course->title)
                    ->line('Вы получили приглашение вести курс: ' . $this->course->title)
                    ->line('От: ' . $this->inviter->name)
                    ->line('Сообщение: ' . $this->message)
                    ->action('Просмотреть приглашение', route('teacher.invitations.show', $this->course->id))
                    ->line('Спасибо за использование нашей платформы!');
    }

    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'inviter_name' => $this->inviter->name,
            'message' => $this->message,
            'type' => 'course_invitation'
        ];
    }
}