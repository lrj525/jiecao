<?php
namespace common\helpers;

use common\helpers\Baidu;
use Yii;
use linslin\yii2\curl;
use common\models\Log;
class Helper
{

	/**
	 *
	 * curl 功能简单封装
	 * @param string $url
	 * @author xi
	 * @since 2014-5-20
	 */
	public static function curlPost($url,$post_data,$headers=[])
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if($headers){
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER , $headers);
        }
        else {
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        curl_setopt($ch, CURLOPT_NOBODY, false); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_REFERER, Yii::$app->params['sh_url']);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
	}

	/**
	 *
	 * curl 功能简单封装
	 * @param string $url
	 * @author xi
	 * @since 2014-5-20
	 */
	public static function curlGet($url)
	{
	    try{
            return file_get_contents($url);
	    }
	    catch (\Exception $e){
	        try{
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $url);
	            curl_setopt($ch, CURLOPT_HEADER, false);
	            curl_setopt($ch, CURLOPT_NOBODY, false); // remove body
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	            curl_setopt($ch, CURLOPT_SSLVERSION, 1);

	            $result = curl_exec($ch);
	            curl_close($ch);
	            return $result;
	        }
	        catch (\Exception $e1){
	            Log::mail('file_get_content 取数据失败', "请求地址:$url");
	            return '';
	        }
	    }
	}

	/**
	 *
	 * @param unknown $url
	 * @param unknown $vars
	 * @param number $second
	 * @param unknown $aHeader
	 * @return mixed|boolean
	 */
	public static function curlPostSsl($url, $vars, $second=30,$aHeader=array())
	{
	    $ch = curl_init();
	    //超时时间
	    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	    //这里设置代理，如果有的话
	    //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
	    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

	    //以下两种方式需选择一种

	    //第一种方法，cert 与 key 分别属于两个.pem文件
	    //默认格式为PEM，可以注释
	    $dirname = dirname(__FILE__);
	    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
	    curl_setopt($ch,CURLOPT_SSLCERT, $dirname.'/wechat/cert/apiclient_cert.pem');
	    //默认格式为PEM，可以注释
	    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	    curl_setopt($ch,CURLOPT_SSLKEY, $dirname.'/wechat/cert/apiclient_key.pem');
	    curl_setopt($ch,CURLOPT_CAINFO, $dirname.'/models/wechat/cert/rootca.pem');

	    //第二种方式，两个文件合成一个.pem文件
	    //curl_setopt($ch,CURLOPT_SSLCERT,'/models/wechat/rootca.pem');

	    if( count($aHeader) >= 1 ){
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
	    }

	    curl_setopt($ch,CURLOPT_POST, 1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
	    $data = curl_exec($ch);

	    if($data){
	        curl_close($ch);
	        return $data;
	    }
	    else {
	        $error = curl_errno($ch);
	        echo "call faild, errorCode:$error\n";
	        curl_close($ch);
	        return false;
	    }
	}

	/**
	 *
	 * 取ip 地址
	 * @author xi
	 * @since 2014-5-23
	 */
	public static function getIp()
	{
		 $ip = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                if (strstr($ip, ",")) {
                    $x = explode(',', $ip);
                    $ip = trim(end($x));
                }
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["REMOTE_ADDR"])) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } else {
                $ip = $_SERVER["SSH_CLIENT"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } else {
                $ip = getenv("REMOTE_ADDR");
            }
        }
        return $ip;
	}
    /**
     * 计算当前所在城市名称,不带市 默认北京
     * @param string $city 城市名称
     * @param double $lng 企业百度经度
     * @param double $lat 企业百度纬度
     * @return string
     * @author zhangjunliang
     * @dete 2015-08-19
     */
    public static function getAddressByLocation($city , $lng = '' ,$lat = '')
    {
        $city = str_ireplace('市','',trim($city));
        if(empty($city))
        {
            if($lng && $lat)
            {
                $addressInfo = Baidu::getAddressByLocation($lng , $lat);
                if(empty($addressInfo['city']))
                {
                    return $addressInfo['city'];
                }
            }
            $ip = static::getIp();
            $addressInfo = static::getAddressByIp($ip);
            return !empty($addressInfo['city']) ? $addressInfo['city'] : '';
        }
        else
        {
            return $city;
        }
    }
	/**
	 * 根据ip查询地址
	 * @param string $ip
	 * @return unknown
	 * @author xi
	 * @date 2015-3-18
	 */
	public static function getAddressByIp($ip)
	{
	    $urls [] = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip";
	    $urls [] = "http://ip.taobao.com/service/getIpInfo.php?ip=$ip";

	    $rand = rand(0, 1);
	    $result = self::curlGet($urls[$rand]);
	    $result = json_decode($result,true);

	    if(isset($result['data'])){
	        return [
	            'country' => isset($result['data']['country']) ?  $result['data']['country'] : '',
	            'province' => isset($result['data']['region']) ? $result['data']['region'] : '',
	            'city' => isset($result['data']['city']) ? str_replace('市', '',  $result['data']['city']) : ''
	        ];
	    }
	    else{
	        return [
	            'country' => isset($result['country']) ? $result['country'] : '',
	            'province' => isset($result['province']) ? $result['province'] : '',
	            'city' => isset($result['city']) ? str_replace('市', '', $result['city']) : ''
	        ];
	    }
	}
	
	/**
	 * 计算字符串长度
	 * @param string $str
	 * @return int
	 */
	public static function strCount($str)
	{
	    if(empty($str)){
	        return 0;
	    }
	    if(function_exists('mb_strlen')){
	        return mb_strlen($str,'utf-8');
	    }
	    else {
	        preg_match_all("/./u", $str, $ar);
	        return count($ar[0]);
	    }
	}
	
	/**
	 * 字符串截取
	 * @param string $str
	 * @param number $start
	 * @param int $length
	 * @param string $charset
	 * @param string $suffix
	 */
	public static function csubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
	{
	    if(function_exists("mb_substr"))
	    {
	        $slice = mb_substr($str, $start, $length, $charset);
	    }
	    else
	    {
	        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	        $re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	        $re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	
	        preg_match_all($re[$charset], $str, $match);
	        if(count($match[0]) <= $length) return $str;
	        $slice = join("",array_slice($match[0], $start, $length));
	    }
	    if($suffix) return $slice."…";
	    return $slice;
	}

	/**
	 * substr 方法可以截取UTF-8 中文字符
	 * @param string $string 是必须的，是被截取的
	 * @param integer $length 是必须的，截多少个字
	 * @param string $etc 可选，默认为...
	 * @return string  返回一个新的串
	 * @author xi
	 * @since 2014-5-13
	 */
	public static function subString($string, $length, $etc = '...') {
			$result = '';
			$string = html_entity_decode ( trim ( strip_tags ( $string ) ), ENT_QUOTES, 'UTF-8' );
			$strlen = strlen ( $string );
			for($i = 0; (($i < $strlen) && ($length > 0)); $i ++) {
				if ($number = strpos ( str_pad ( decbin ( ord ( substr ( $string, $i, 1 ) ) ), 8, '0', STR_PAD_LEFT ), '0' )) {
					if ($length < 1.0) {
						break;
					}
					$result .= substr ( $string, $i, $number );
					$length -= 1.0;
					$i += $number - 1;
				} else {
					$result .= substr ( $string, $i, 1 );
					$length -= 0.5;
				}
			}
			$result = htmlspecialchars ( $result, ENT_QUOTES, 'UTF-8' );
			if ($i < $strlen) {
				$result .= $etc;
			}
			return $result;
	}

	/**
	 * 取随机ip
	 * @return string
	 * @author xi
	 * @date 2015-3-20
	 */
	public static function getRandIp()
	{
	    return rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);
	}

	/**
	 * 调试方法
	 * @param minx $val
	 * @return void
	 * @author xi
	 * @date 2015-1-18
	 */
	public static function dump($val)
	{
	    header("Content-type: text/html; charset=utf-8");
	    echo '<pre>';
	    print_r($val);
	    echo '</pre>';
	    die;
	}

	/**
	 * 米转换成公里
	 * @param int $m
	 * @return string
	 */
	public static function m2km($m)
	{
	    if($m<1000 && $m>0){
	        return $m.'米';
	    }
	    else if($m<50000){
	        return round($m/1000,1).'公里';
	    }
	    return '';
	}

	/**
	 * 随机概率
	 * @param unknown $proArr
	 * @return int
	 */
	public static function getRand($proArr) {
	    $result = '';
	    $proSum = array_sum($proArr);
	    foreach ($proArr as $key => $proCur) {
	        $randNum = rand(1, $proSum);             //抽取随机数
	        if ($randNum <= $proCur) {
	            $result = $key;                         //得出结果
	            break;
	        } else {
	            $proSum -= $proCur;
	        }
	    }
	    unset ($proArr);
	    return $result;
	}

	/**
	 * 获取操作系统
	 * @return number
	 * @author xi
	 */
	public static function getOS()
	{
	    if(stristr($_SERVER['HTTP_USER_AGENT'],'Android')) {
	        return 2;
	    }
	    else if(stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
	        return 1;
	    }
	    else if(stristr($_SERVER['HTTP_USER_AGENT'],'Windows')){
	        return 3;
	    }
	    else{
	        return 4;
	    }
	}

	/**
	 * 获取手机归属地
	 * @param string $mobile
	 * @return string
	 * @date 2015-07-22
	 */

	public static function getMobileArea($mobile){
	    $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?format=json&tel=".$mobile."&t=".time();
	    $content = file_get_contents($url);
	    $content = @mb_convert_encoding($content, 'utf8' , 'gbk');
	    preg_match_all("/(\w+):'([^']+)/", $content, $m);
	    $result = array_combine($m[1], $m[2]);
	    return $result;
	}


	/**
 	 * 返回json统一数据格式
 	 * @author hcj
 	 * @param $result int 消息编号 默认为 0 （错误）
 	 * @param $message string 消息内容
 	 * @param $data string|int|array 扩张数据 会覆盖result、message
 	 */
	public static function result($result, $message="", $data=array()){
		$res = array(
			'success'=>$result,
			'message'=>$message,
            'data'=>$data,
		);
	    if(!$res['success'] || $res['success']<0){
	        $res['success'] = false;
	    }else{
	        $res['success'] = true;
	        unset($res['message']);
	    }
		return $res;
	}

	/**
	 * 生成access_token
	 *
	 * @return string
	 * @author hcj
	 **/
	public static function getAccessToken()
	{
		return md5(uniqid(time(),true));
	}


	/**
	 * 验证手机号
	 *
	 * @return void
	 * @author hcj
	 **/
	public static function verifyMobile($mobile)
	{
		return preg_match('/^((\d3)|(\d{3}\-))?13[0-9]\d{8}|15[0-9]\d{8}|18[0-9]\d{8}|17[89]\d{8}$/u', trim($mobile));
	}

    /**
     * 验证邮箱
     *
     * @return void
     * @author zhuangjunliang
     **/
    public static function verifyEmail($email)
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }


	/**
	 * 生成随机数
	 *
	 * @return void
	 * @author hcj
	 * @version 1.0
	 **/
	public static function getRandNum($num=6)
	{
		if($num>9)
			return self::getRandNum(9).self::getRandNum($num-9);
		$bn = "1".str_repeat("0", $num-1);
		return rand($bn,$bn*10-1);
	}

	/**
	 * 写入日志文件
	 *
	 * @return void
	 * @author hcj
	 * @version 1.0
	 **/
	public static function console()
	{
		foreach (func_get_args() as $k => $v) {
			Log::save($v,'app.log');
		}
	}

	/**
	 * 生成二维码
	 * @param string $content
	 * @return string
	 * @author xi
	 * @date 2015-8-24
	 */
	public static function getQR($content)
	{
	    return  base64_encode( static::curlGet('http://qr.liantu.com/api.php?text='.$content));
	}


	/**
	 * 把给定字符串转换成蛇形
	 *
	 * @param string $value 如 fooBar =>foo_bar
	 * @return string
	 * @author hcj
	 * @version 1.0
	 **/
	public static function snake($value,$delimiter="_")
	{
		if(ctype_lower($value)) return $value;
		$replace = '$1'.$delimiter.'$2';
		return strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
	}

	/**
	 * 把给定字符串转换成首字母大写
	 *
	 * @param string $value 如 foo_bar=>FooBar
	 * @return string
	 * @author hcj
	 * @version 1.0
	 **/
	public static function studly($value)
	{
		$value = ucwords(str_replace(array('-','_'), ' ', $value));
		return str_replace(' ', '', $value);
	}

	/**
	 * 转换成字符串
	 * @param array $arr
	 * @return array
	 * @author xi
	 * @date 2015-8-26
	 */
	public static function toString($arr)
	{
	    foreach($arr as $key=>$val)
	    {
	        if(is_array($val)){
	            $arr[$key] = self::toString($val);
	        }
	        else if(is_int($val) || is_float($val) || is_double($val)){
	            $arr[$key] = (string)$val;
	        }
	        else if(is_null($val)){
	            $arr[$key] = '';
	        }
	    }
	    return $arr;
	}
    /**
     * 获取手机类型
     * @return string
     * @author zhangjunliang
     * @date 2015-9-15
     */
    public static function getMobileType()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $mobileType = '';
        if(stristr($userAgent,'android'))
        {
            $mobileType = 'android';
        }
        else if(stristr($userAgent,'iphone'))
        {
            $mobileType = 'ios';
        }
        else if(stristr($userAgent,'windows phone'))
        {
            $mobileType = 'windows phone';
        }
        else {
            $mobileType = 'PC';
        }
        return strtolower($mobileType);
    }
    
    /**
     * 获取浏览器，是手机浏览器，微信浏览器，pc
     * @return int
     */
    public static function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        if ( strpos($userAgent, 'MicroMessenger') !== false ) {
            return 1;
        }
        else if(stristr($userAgent,'android') || stristr($userAgent,'iphone') || stristr($userAgent,'windows phone')){
            return 2;
        }
        else {
            return 3;
        }
    }
    
    /**
     * 计算当前日期，位于上半年或下半年；
     * 返回起始日期点
     * 
     * @param int $month
     * @return array
     * @author lijing
     * @date 2016-03-02
     */
    public static function getMonthYear($month)
    {
    	$month_year = [];
    	$month = $month>0 ? $month : $month+12;
    		
    	//下半年
    	if( 6 <= $month && $month  < 12 )
    	{
    		$month_year['start_time'] = date('Y').'-06-01';
    		$month_year['end_time'] = date('Y').'-11-31';
    	}
    	elseif( $month == 12 )
    	{
    		$month_year['start_time'] = date('Y').'-12-01';
    		$month_year['end_time'] = (date('Y')+1).'-05-31';
    	}
    	else
    	{
    		$month_year['start_time'] = (date('Y')-1).'-12-01';
    		$month_year['end_time'] = date('Y').'-05-31';
    	}
    	
    	return $month_year;
    }
    
    /**
     * 删除数组中元素，并重新设置索引
     *
     * @param array 	&$arr 数组的引用，在原数组上操作
     * @param int|array $keys  要删除的数组的索引值
     * @return null
     * @author lijing
     * @date 2016-04-26
     */
    public static function array_remove_value(&$arr, $keys)
    {
    	if (!is_array($keys)) {
    		$keys = array($keys);
    	}
    	foreach ($keys as $k) {
    		//unset($array[$k]);
    		array_splice($arr, $k, 1);
    	}
    	$arr = array_values($arr);
    }
    
    /**
     * 根据身份证获取生日
     * @param string $id_card 
     * @return string
     * @author xi
     * @date 2016-5-27
     */
    public static function getBirthdayByIdCard($id_card)
    {
        if(in_array(strlen($id_card), [15,18]) ){
            return substr($id_card,6,4).'-'.substr($id_card,10,2).'-'.substr($id_card,12,2);
        }
        return '';
    }
    
    /**
     * 生成uuid
     * @return string
     * @author xi
     */
    public static function createUUID(){
        $str = md5(uniqid(mt_rand(), true));
        return $str;
    }
    
    /**
     * 替换新的尺寸
     * @example  /2016/09/11/20161234.jpg  返回/2016/09/11/20161234_**_**.jpg
     * @param string $srcFile
     * @param string $replaceSize
     * @return string
     */
    public static function toSizeImagePath($srcFile,$replaceSize)
    {
        if($srcFile && $replaceSize)
        {
            $extension = pathinfo($srcFile, PATHINFO_EXTENSION);
            $dictFile  = $dstpath = str_replace('.'.$extension,  '_'.$replaceSize.'.'.$extension,$srcFile);
            return $dictFile;
        }
        return $srcFile;
    }
    
    /**
     * 获取身份证性别
     * @param string $idCard
     * @return int
     */
    public static function getSexByIdCard($idCard)
    {
        if( strlen($idCard) ==15 ){
            return $idCard[13]%2 ==0 ?2:1;
        }
        else if(strlen($idCard) == 18){
            return $idCard[16]%2 ==0 ?2:1;
        }
        return 0;
    }

    /**
	* 活动力加密
	* 
    **/
	public static function think_ucenter_md5($str, $key = 'vk0lQBLK`6"c&d-AbDtu[F47$;i~%!#YCZGRh^>?')     //活动力加密
    {
        return '' === $str ? '' : md5(sha1($str) . $key);
    }
    
}
