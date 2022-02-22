<?php

namespace App\Console;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule):void
    {
        // $schedule->command('inspire')->hourly();

//        $schedule->call(static function () {
//            info('hello');
//        })->everyMinute();

        //定时检测订单状态,超过10分钟未支付的,作废掉
        //真实项目中,不会这么做.真实项目一般会使用长连接，订单到期了，直接作废
        $schedule->call(static function () {
            $orders=Order::where(['status' => 1])
                ->where('created_at','<',Carbon::now()->subDay(1))
                ->with('orderDetails.goods')
                ->get();

            //循环订单,修改订单状态,还原商品库存
            try {
                DB::beginTransaction();

                foreach ($orders as $order){
                    $order->status=5;
                    $order->save();

                    //还原商品库存
                    foreach ($order->orderDetails as $details){
                        $details->goods()->increment('stock',$details->num);
                    }

                }

                DB::commit();
            }catch (\Throwable $throwable){
                DB::rollBack();
                Log::error($throwable);
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands():void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
