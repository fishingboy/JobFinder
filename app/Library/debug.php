<?php
namespace App\Library;

use FirePHP;

/**
 * Debug library
 */
class Debug
{
    /**
     * 使用 FirePHP log 訊息
     *
     * 可傳一個到多個參數
     * 如傳二個參數以上，第一個參數將會當成 FirePHP Group 標題使用
     *
     * @param  mixed $msg 任何變數
     */
    public static function fblog()
    {
        static $init;

        // 初始化
        if ( ! $init)
        {
            $firephp = FirePHP::getInstance(true);

            // 調整設定 (拿掉最深 5 層的限制)
            $firephp->setOption('maxObjectDepth', 20);
            $firephp->setOption('maxArrayDepth', 20);
        }

        // 多參數寫法
        $arg_num = func_num_args();
        if ($arg_num == 1)
        {
            $firephp->log(func_get_arg(0));
        }
        else
        {
            $firephp->group(func_get_arg(0));
            for ($i=1; $i<$arg_num; $i++)
            {
                $firephp->log(func_get_arg($i));
            }
            $firephp->groupEnd();
        }
    }
}
