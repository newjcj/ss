<?php

namespace App\Entity;
class M3result {

    public $status;
    public $message;
    public $returnurl;
    public $data;

    public function toJson()
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

    public function build($status,$message,$returnurl="",$data='',$end=true)
    {
        $this->status=$status;
        $this->message=$message;
        $this->returnurl=$returnurl;
        $this->data=$data;
        if($end==true){
            return $this->toJson();
        }
    }

    public static function time($time)
    {
        return $time + (8*3600);
    }
    //设置价格
    public static function price($price){
//        return $price;
        return $price;
    }
    //发送邮件地址设置
    public static function email()
    {
        return "paybone@aliyun.com";
    }



    public static function pregReturn($preg,$content,$fun=''){
        preg_match($preg,$content,$re);
        if($fun!=''){
            if(isset($re[1])){
                return $fun($re[1]);
            }else{
                return [];
            }
        }
        if(isset($re[1])){
            return $re[1];
        }else{
            return '';
        }
    }
    public static function pregAllReturn($preg,$content){
        preg_match_all($preg,$content,$re);
        if(isset($re[1])){
            return $re[1];
        }else{
            return [];
        }
    }




}
