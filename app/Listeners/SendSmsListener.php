<?php

namespace App\Listeners;

use App\Events\SendSms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class SendSmsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\SendSms $event
     * @throws \Exception
     */
    public function handle(SendSms $event)
    {
        //发送验证码到手机
        $config=config('sms');
        $easySms = new EasySms($config);
        $code=random_int(1000,9999);
        //缓存验证码
        Cache::put('phone-code_'.$event->phone,$code,now()->addMinutes(10));

        try {
            $easySms->send($event->phone, [
                'template' => $config['template'],
                'data' => [
                    'code' => $code
                ],
            ]);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
}
