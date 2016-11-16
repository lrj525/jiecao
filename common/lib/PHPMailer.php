<?php
namespace common\lib;

use Yii;

class PHPMailer
{
    
    public static function send($to,$cc,$subject,$body,$attachment='')
    {
        require_once realpath(dirname(__DIR__).'/../extend/PHPMailer/PHPMailerAutoload.php');
        
        $mail = new \PHPMailer();
        
        //$mail->SMTPDebug = 3;                             
        
        $mail->isSMTP(); 
        $mail->CharSet = 'UTF-8';                                     
        $mail->Host = Yii::$app->params['phpMailer']['host'];  
        $mail->SMTPAuth   = true;                              
        $mail->Username   = Yii::$app->params['phpMailer']['username'];
        $mail->Password   = Yii::$app->params['phpMailer']['password']; 
        $mail->SMTPSecure = Yii::$app->params['phpMailer']['encryption'];
        $mail->Port       = Yii::$app->params['phpMailer']['port'];
        
        $mail->From = Yii::$app->params['phpMailer']['username'];
        $mail->FromName = '俱牛';
        
        //收件人
        if(is_array($to)){
            foreach ($to as $val){
                $mail->addAddress($val);
            }
        }
        else if($to){
            $mail->addAddress($to);
        }
        
        //抄送
        if($cc && is_array($cc)){
            foreach ($cc as $val){
                $mail->addCC($val);
            }
        }
        else if($cc) {
            $mail->addCC($cc);
        }
        
        //带附件
        if($attachment && is_array($attachment)){
            foreach ($attachment as $val){
                if(file_exists($val)){
                    $mail->addAttachment($val);
                }
            }
        }
        else if($attachment && file_exists($attachment)){
            $mail->addAttachment($attachment);
        }
        
        $mail->isHTML(true);
        
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        if(!$mail->send()) {
            print_r($mail->ErrorInfo);
            return false;
        } 
        return true;
    }
}