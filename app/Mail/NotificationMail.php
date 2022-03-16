<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $notifications;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notifications, $user)
    {
        $this->notifications = $notifications;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@openit.gr', 'OpenAgro')
            ->view('notification')
            ->with([
                'user' => $this->user->fullName(),
                'notifications' => $this->notifications
            ]);
    }
}
