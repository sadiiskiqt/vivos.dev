<?php

namespace Atlantis\Notifications;

/**
 * Description of PasswordResetMail
 *
 * @author gellezzz
 */
class PasswordResetMail extends \Illuminate\Mail\Mailable {
   
    protected $token;
    protected $user;


    public function __construct($token, $user) {
        $this->token = $token;
        $this->user = $user;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        
        $this->to($this->user->email);
        
        return $this->view('emails.password', ['token' => $this->token]);
    }
}
