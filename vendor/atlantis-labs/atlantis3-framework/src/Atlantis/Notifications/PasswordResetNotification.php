<?php

namespace Atlantis\Notifications;

/**
 * Description of PasswordResetNotification
 *
 * @author gellezzz
 */
class PasswordResetNotification extends \Illuminate\Notifications\Notification {

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    public $user;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $user) {
        $this->token = $token;
        $this->user = $user;

    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable) {
        return ['mail'];

    }

    public function toMail($notifiable) {
        
        //$url = url('admin/password/reset', $this->token);
        
        //return (new \Illuminate\Notifications\Messages\MailMessage())         
                //->line(view('emails.password', ['token' => $this->token])->render());
        
        //return view('emails.password', ['token' => $this->token]);
        
        return new PasswordResetMail($this->token, $this->user);
    }   

}
