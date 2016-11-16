<?php
namespace common\lib\insure;

use Yii;
use common\helpers\Helper;
class InsureApi
{
    // 测试环境partnerId:251255
    // 密钥:25218^*#%
    // 测试环境地址:http://testchannel.hzins.com/api/
    
    const partnerId = 251255;//秘钥

    /*
    * sign
    * @array $data
    *MD5（signStr.getBytes("UTF-8")）mb_convert_encoding
    */
    public static function getSign($data)
    {   
       
        $newdata =  mb_convert_encoding("25218^*#%".json_encode($data), "UTF-8", "UTF-8"); 
        return md5($newdata);  
    }


    
   
    /*
    *保险列表
    */
    public static function apiInsureList()
    {
        $data = [
            "partnerId"  => self::partnerId,
            "transNo"    =>  time()
        ];
       
        $sign    = self::getSign($data);
        $url     = "http://testchannel.hzins.com/api/productList?sign=".$sign;
        $jsonStr = Helper::curlPost($url, json_encode($data) ,array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);

        return $arr;
    }

    /*
    * 保险详情
    * @array $data
    */
    public static function apiInsuretDetail($data){ 
       
        $data['partnerId'] = self::partnerId;

        $sign    = self::getSign($data);
        $url     = "http://testchannel.hzins.com/api/productDetail?sign=".$sign;
        $jsonStr = Helper::curlPost($url, json_encode($data) ,array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);

        return $arr;
    }


    /*
    * 保单
    * @array $data 
    */
    public static function apiInsureOrder($data){
        
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);
        $url     = "http://testchannel.hzins.com/api/orderApply?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, json_encode($data) ,array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }



    

    

}