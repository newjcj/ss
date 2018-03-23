<?php

namespace App\Entity;
class Curl {

    /**
     * @param $url
     * @param bool $cookies
     * @param string $cookie_file
     * @return mixed
     */
    public static function vget($url,$cookies=false,$cookie_file=''){
        $curl = curl_init(); // 启动一个CURL会话
        $headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers[] = 'Connection: Keep-Alive';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
        if($cookies){
            if (file_exists($cookie_file)) {
                $cookie_file=$cookie_file;
            } else {
                if($h = fopen($cookie_file,'w')){
                    $cookie_file=$cookie_file;
                    fclose($h);
                } else {
                    echo 'The cookie file could not be opened. Make sure this directory has the correct permissions';die;
                }
            }
        }
        //$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=GBK';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent); // 模拟用户使用的浏览器
        //curl_setopt($curl, CURLOPT_NOBODY, 0);
        //if ($this->cookies == TRUE) curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file);
        if ($cookies == TRUE) curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_MAXREDIRS,6); //跟随 重定向 跳转的次数
        //curl_setopt($curl, CURLOPT_POST, 0); // 发送一个常规的Post请求
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }





}