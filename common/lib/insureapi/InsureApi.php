<?php
namespace common\lib\insureapi;

use Yii;
use common\helpers\Helper;
class InsureApi
{
    // 测试环境partnerId:251255
    // 密钥:25218^*#%
    // 测试环境地址:http://testchannel.hzins.com/api/
    // const partnerId = "251255";//秘钥
    // const url = "http://testchannel.hzins.com/api/";//url
    // const pwd = "25218^*#%";

    /**
    * 正式
    * partnerId:448602
    * key:303881&(@!!)#JL*#31
    * 生产环境地址:http://channel.hzins.com/api/
    *
    **/
    const partnerId = "448602";//秘钥
    const url = "http://channel.hzins.com/api/";//url
    const pwd = "303881&(@!!)#JL*#31";

    /*
    * sign
    * @array $data
    *MD5（signStr.getBytes("UTF-8")）mb_convert_encoding
    */
    public static function getSign($data)
    {   
       
        //$newdata =  mb_convert_encoding("25218^*#%".json_encode($data), "UTF-8", "UTF-8"); 
        $newdata = urldecode(self::pwd.json_encode($data));
        return md5($newdata);  
    }

    /*
    * 保单流水号
    * @transNo 
    */
    public static function getTransno(){ 
         $transNo = 'HZ'.date('YmdHis',time()).mt_rand(1000,9999);
         return $transNo;
    }
    
   
    /*
    *保险列表
    */
    public static function apiInsureList()
    {
        $data = [
            "partnerId"  => self::partnerId,
            "transNo"    => self::getTransno()
        ];      
        $sign    = self::getSign($data);
        $url     = self::url."productList?sign=".$sign;
        $jsonStr = Helper::curlPost($url,urldecode( json_encode($data) )  ,array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);

        return $arr;
    }

    /*
    * 保险详情/产品详情
    * @array $data
    */
    public static function apiProductDetail($data){ 
       
        $data['partnerId'] = self::partnerId;

        $sign    = self::getSign($data);
        $url     = self::url."productDetail?sign=".$sign;
        $jsonStr = Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);

        return $arr;
    }

    /*
    * 产品详情(含含富文本)
    * productInfo
    */
    public static function apiProductInfo($data){ 
        $data['partnerId'] = self::partnerId;

        $sign    = self::getSign($data);
        $url     = self::url."productInfo?sign=".$sign;
        $jsonStr = Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
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

        $url     = self::url."orderApply?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }

    /*
    * 支付 
    * pay /参数样例 { "transNo": "HZ201504212000019", "partnerId": 2820,"insureNum": "20160203002633", "userId": null,"caseCode": "0000056044100441","onliePaymnetId": 21,"bankId": -1,"pageNoticeUrl": "http://192.168.11.47:8080/te/notify.jsp","price": 209}
    * @data array
    */
    public static function apiPay($data){ 
            $data['partnerId'] = self::partnerId;
            $sign    = self::getSign($data);

            $url     = self::url."pay?sign=".$sign;
            $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
            $arr     = json_decode($jsonStr,true);
            return $arr;
    }


    /*
    * 投保单详情
    * insureDetail /参数样例 {"transNo":"HZ201504212000030","partnerId":5171,"insureNum":"15080546794635"}
    * @array $data
    */
    public static function  apiInsureDetail($data){
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);

        $url     = self::url."insureDetail?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }

    /*
    * 投保单查询
    * orderDetail /参数样例 {"transNo":"HZ201504212000020","partnerId":9411,"insureNum":"20160226034087", "pageNum":1,"pageSize":5,"userId":"10007"}
    * @array $data
    */
    public static function apiOrderDetail($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);

        $url     = self::url."orderDetail?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;


    }


    /*
    * 退保
    * orderCancel /参数样例 {"transNo":"HZ201504212000023","partnerId":5171,"insureNum":"15080544341441","extendInfo":{"userId":"10007","email":null,"userName":null,"phone":null}}
    * @array $data
    */
    public static function apiOrderCancel($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data); 
        $url     = self::url."orderCancel?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;
    }



    /*
    * 批量查询保单（分页）
    * orderSearch  /参数样例 {"transNo":"HZ201504212000029","partnerId":5171,"insureNums":["15080546794635","15080544341441"],"applicant":"贝书文","insurant":"贝书文","idCard":"460108198404070653","userId":"1007","startTime":"2015-08-01 13:00:00","endTime":"2015-08-06 16:00:00","pageNum":1,"pageSize":5}
    * @array $data 
    */
    public static function apiOrderSearch($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);

        $url     = self::url."orderSearch?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;
    } 





    /*
    * 保单试算
    * orderTrial
    */
    public static function apiInsureOrderTrial($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);

        $url     = self::url."orderTrial?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }


    /*
    * 保单下载
    * download 
    * 参数样例 {"transNo":"HZ201504212000024","partnerId":5171,"insureNum":"15080546794635"}
    */
    public static function apiInsureDownload($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);

        $url     = self::url."orderTrial?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }



    /*
    * 渠道注册
    * register /参数样例 {"transNo":"HZ201504212000027","partnerId":9411,"userId":"1007","channelType":0,"webType":2,"email":"lv1125@hzins.com","contactName":"张三","contactNumber":"18576623598"}
    * @array $data 
    */
    public static function apiRegister($data){ 
        $data['partnerId'] = self::partnerId;
        $sign    = self::getSign($data);
        $url     = self::url."register?sign=".$sign;
        $jsonStr =  Helper::curlPost($url, urldecode( json_encode($data) ),array("Content-Type: application/json; charset=utf-8") );
        $arr     = json_decode($jsonStr,true);
        return $arr;

    }



    

    

}