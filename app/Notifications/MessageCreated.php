<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageCreated extends Notification
{
    use Queueable;

    protected $user_id;
    protected $group_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $group_id)
    {
        $this->user_id = $user_id;
        $this->group_id = $group_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => (integer)$this->user_id,
            'group' => (integer)$this->group_id
        ];
    }
}
