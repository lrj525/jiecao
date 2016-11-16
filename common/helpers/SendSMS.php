<?php
namespace common\helpers;
/**
 * 华信短信接口
 */
use Yii;
use yii\db\ActiveRecord;

class SendSMS extends ActiveRecord
{
    //发送地址 对应UTF-8(返回值为json格式)
//     const SEND_SMS_URL = 'http://dx.ipyy.net/smsJson.aspx';
//     const SN = 'AC00105';
//     const PWD = 'AC0010552';
    
    const SEND_SMS_URL = 'http://dx.ipyy.net/smsJson.aspx';
    const SN = 'AC00239';
    const PWD = '796283';

    public static function tableName()
    {
        return 'club_sms_log';
    }

    /**
     * 发送短信短信
     * @param string $mobile 手机号，如多个手机号用逗号隔开
     * @param string $content
     * @author xi
     */
    public static function send($mobile,$content)
    {
        if(!preg_match('/^1[2-9]\d{9}(\,1[2-9]\d{9})*$/', $mobile) || trim($content) == '')
        {
            return false;
        }
        try
        {
            header("Content-type:text/html;charset=utf-8");
            $sendQuery = 'action=send&userid=%s&account=%s&password=%s&mobile=%s&content=%s&sendTime=%s&extno=%s';
            $sendTime = '';//date('Y-m-d H:i:s');
            $userId = '';
            $user_id = '';
            $password = strtoupper(md5(self::PWD));
            $content = self::strToUtf8($content);
            $extNo = '';
            $sendQuery = sprintf($sendQuery,$userId,self::SN,$password,$mobile,$content,$sendTime,$extNo);
            $jsonData = Helper::curlPost(self::SEND_SMS_URL,$sendQuery);
            $result = json_decode($jsonData,true);
            if(isset($result['returnstatus']) && $result['returnstatus'] == 'Success')
            {
                return self::saveDB($mobile, $content);
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * 保存到数据库里
     * @param string $moblie
     * @param string $content
     * @return boolean
     * @author xi
     */
    private static function saveDB($moblie,$content)
    {
        $model = new self();
        $model->mobile = $moblie;
        $model->content = $content;
        $model->create_time = date('Y-m-d H:i:s');
        $model->ip = Helper::getIp();
        $model->log = var_export($_SERVER,true);
        return $model->save();
    }
    /**
     * 生成验证码的key
     * @param $mobile
     * @param null $type
     * @return string
     */
    public static function createAuthCodeKey($mobile,$type = null)
    {
        return !empty($type) ? $mobile . 'M' . str_pad($type,3,'0',STR_PAD_LEFT) : $mobile;
    }
    /**
     * 设置验证码
     * @return void
     * @author zhangjunliang
     * @version 1.0.7
     **/
    public static function setAuthCode($mobile,$type = null)
    {
        $code = Helper::getRandNum();
        $key = self::createAuthCodeKey($mobile,$type);
        Yii::$app->redis_cache->set('flag' . $key, $code , 59);
        Yii::$app->redis_cache->set($key , $code , 1800);
        return $code;
    }
    /**
     * 删除验证码
     * @author zhangjunliang
     * @param $mobile
     * @param null $type
     * @date 2015-10-19
     */
    public static function deleteAuthCode($mobile,$type = null)
    {
        $key = self::createAuthCodeKey($mobile,$type);
        if(Yii::$app->redis_cache->exists('flag' . $key))
        {
            Yii::$app->redis_cache->delete('flag' . $key);
        }
        if(Yii::$app->redis_cache->exists($key))
        {
            Yii::$app->redis_cache->delete($key);
        }
        return true;
    }
    /**
     * 检测验证码
     * @return void
     * @author hcj
     * @version 1.0
     **/
    public static function checkAuthCode($mobile,$check_code,$type = null)
    {
        $code = self::getCode($mobile,$type);
        if($code == $check_code)
        {
            return true;
        }
        return false;
    }
    /**
     * 获取验证码
     * @return int
     * @author hcj
     * @version 1.0
     **/
    public static function getCode($mobile,$type = null)
    {
        $key = self::createAuthCodeKey($mobile,$type);
        $code = Yii::$app->redis_cache->get($key);
        if(!$code)
        {
            $code = self::setAuthCode($mobile,$type);
        }
        return $code;
    }

    public static function strToUtf8($data)
    {
        if( !empty($data) )
        {
            $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
            if( $fileType != 'UTF-8')
            {
                $data = mb_convert_encoding($data ,'utf-8' , $fileType);
            }
        }
        return $data;
    }

}