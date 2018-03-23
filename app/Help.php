<?php

if (!function_exists('responseFormat')) {

    function responseFormat($code = 1, $message = 'success', $data = [])
    {
        return ['code' => $code, 'message' => $message, 'data' => $data];
    }
}


if (!function_exists('generateOrderNo')) {
    /**
     * 生成订单号
     * @return string
     */
    function generateOrderNo()
    {
        // 14位长度当前的时间 20150709105750
        $orderDate = date('YmdHis');
        return $orderDate . str_pad(rand(1, 9999), 8, 0, STR_PAD_LEFT);
    }
}

/**
 * 生成不重复的随机数
 * @param  int $start 需要生成的数字开始范围
 * @param  int $end 结束范围
 * @param  int $length 需要生成的随机数个数
 * @return string       生成的随机数
 */

if (!function_exists('randNumber')) {
    function randNumber($start = 1, $end = 10, $length = 4)
    {
        $count = 0;
        $temp = [];
        while ($count < $length) {
            $temp[] = mt_rand($start, $end);
            $data = array_unique($temp);
            $count = count($data);
        }
        sort($data);
        return implode('', $data);
    }
}