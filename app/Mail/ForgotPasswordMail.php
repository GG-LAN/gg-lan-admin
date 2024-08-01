<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * User instance
     *
     * @var User
     */
    public $user;

    /**
     * Reset token
     *
     * @var string
     */
    public $token;

    /**
     * Reset url
     *
     * @var string
     */
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user  = $user;

        $this->url = config("app.app_front_url_forgot_password") . "?token=" . $token . "&email=" . $user->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("mail.forgot_password"))
                    ->markdown('emails.forgot-password', [
                        "url" => $this->url
                    ]);
    }
}
