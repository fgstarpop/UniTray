<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notify extends Notification
{
     use Queueable;
     public $text,$link,$type,$count;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text,$link,$type,$count,$user,$story)
    {
        $this->text  = $text;
        $this->link  = $link;
        $this->type  = $type;
        $this->count = $count;
        $this->user  = $user;
        $this->story = $story;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [ 
            'text'  => $this->text,
            'link'  => $this->link,
            'type'  => $this->type,
            'count' => $this->count,
            'user'  => $this->user,
            'story' => $this->story, 
        ];
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
            //
        ];
    }
}
