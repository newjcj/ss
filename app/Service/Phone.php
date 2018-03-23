<?php
namespace App\Service;

use GuzzleHttp\Client;

/**
 * Class Phone
 * @package App\Service
 */
class Phone
{
    private $username = "sousou_admin";
    private $privateKey = "a6BWeiKYpBG3m3v0G57lHNI29N1Q9ctS";


    public function register($mobile)
    {
        $url = "http://ows.ndp.ot24.net/user/register";
        $arr["mobile"] = $mobile;
        $data = $this->commonPar($arr);
        return  $this->request($url, $data);
    }

    public function balance($mobile)
    {
        $url = "http://ows.ndp.ot24.net/user/balance";
        $arr["mobile"] = $mobile;
        $data = $this->commonPar($arr);
        return  $this->request($url, $data);
    }

    public function charge($mobile, $pwd)
    {
        $url = "http://ows.ndp.ot24.net/card/pay/passwd";
        $arr["mobile"] = $mobile;
        $arr["pwd"] = $pwd;
        $data = $this->commonPar($arr);
        return  $this->request($url, $data);
    }

    public function call($mobile, $callee, $callBack)
    {
        $url = "http://ows.ndp.ot24.net/call/pstn";
        $arr["mobile"] = $mobile;
        $arr["callee"] = $callee;
//        $arr["cdr_callback"] = route('phone.callback');
        $arr["cdr_callback"] = '';

        $data = $this->commonPar($arr);
        return  $this->request($url, $data);
    }

    private function commonPar($par)
    {
        $data["username"] = $this->username;
        $data["data"] = base64_encode(json_encode($par));
        $data["timestamp"] = time() . "";
        $data["sign"] = strtolower(md5($this->privateKey . $data["data"] . $data["timestamp"]));
        return json_encode($data);
    }

    private function request($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res,TRUE);
    }
    
}


