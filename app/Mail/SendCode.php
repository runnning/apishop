<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SendCode extends Mailable
{
    use Queueable, SerializesModels;

    protected string $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @throws \Exception
     */
    public function build(): static
    {

        //生成code
        $code=random_int(1000,9999);
        //缓存邮箱对应的code
        Cache::put('email-code_'.$this->email,$code,now()->addMinutes(15));
        return $this->view('emails.send-code',['code'=>$code]);
    }
}
