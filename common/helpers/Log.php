<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class Log
{
    /**
     * 自定义log 保存文件
     * @param string $message
     * @param string $filename
     * @return void
     * @author xi
     * @date 2015-3-29
     */
    public static function save($message,$filename='')
    {
        $runtimePath = Yii::$app->getBasePath().'/../../runtime/logs/';
        if(!file_exists($runtimePath)){
            @mkdir($runtimePath,0777,true);
        }
        $filename = $filename!=''?$filename:'mylog.log';
        $filePath = $runtimePath.$filename;
        
        @file_put_contents($filePath, "Time:".date('Y-m-d H:i:s')."  ".var_export($message,true)."\r\n",FILE_APPEND);
        
    }
    
    /**
     * 自定义保存文本
     * @param string $message
     * @param string $filename
     * @author xi
     */
    public static function add($message,$filename='')
    {
        $runtimePath = Yii::$app->getBasePath().'/../../runtime/logs/';
        if(!file_exists($runtimePath)){
            @mkdir($runtimePath,0777,true);
        }
        $filename = $filename!=''?$filename:'mylog.log';
        $filePath = $runtimePath.$filename;
        
        @file_put_contents($filePath, $message ,FILE_APPEND);
    }

    /**
     * 发送邮件
     * @param string $subject 邮件主题 
     * @param string $body 邮件内容
     * @author xi
     */
    public static function mail($subject,$body,$to='xiyi_pp@qq.com',$cc='')
    {
        $params = [
            'to' => $to,
            'cc' => $cc,
            'subject' => $subject,
            'body' => $body
        ];
        Yii::$app->beanstalk->putInTube('sendemail', $params,2048);
        
    }
    
    /**
     * 定时发送邮件
     * @param string $subject 邮件主题
     * @param string $body 邮件内容
     */
    public static function timingEmail($email,$body) {
        
        $mail = Yii::$app->mailer->compose();
        $mail->setTo($email);
        //$mail->setCc('443654413@qq.com');
        $mail->setSubject('加班报名');
        $mail->setHtmlBody($body);
        $mail->send();
    }
    
    /**
     * 把日志存到远程服务器上
     * @param string $message
     * @param string $filename
     * @return void
     * @author xi
     * @date 2015-9-22
     */
    public static function addRemote($message,$filename)
    {
        $post_data = [
            'message'  => $message,
            'filename' => $filename
        ];
        Helper::curlGet(Yii::$app->params['api_url'].'/log/index/?message='.base64_encode($message).'&filename='.$filename);
    }
}