<?php
namespace common\helpers;

use Yii;
use common\models\Log;
use common\models\UserToken;
use common\push\Push;
class SendPush
{

    public static function AppPush($title,$content,$message,$msg_type,$icon,$user_id,$play_time)
    {
        //try {
            //调用极光推送,
            $data = [
                'title' => $title,
                'badge' => 1,
                'content' => $content,
                'message' => $message,
                'type' => $msg_type,
                'time' => date('Y-m-d H:i:s'),
                'icon' => $icon,
                'user_id' => $user_id,
                'play_time' => $play_time,
            ];
            $UserToken = UserToken::find()->where(['user_id' => $user_id])->orderBy('id desc')->one();
            if(!$UserToken)
            {
                return '没有这个信息';
            }
            $push_token = $UserToken->push_token;//对应用户表的push_token
            $model = 'dev';//dev 开发环境 dis生产环境
            //是否使用个人证书
            $push = new Push();
            $device = $UserToken->device;
            $push->getAdapter($device)->setModel($model)->setNotificationAndMessageAll($push_token, $data, $title)->send();
            /* //写日志
             $msg = new Msg;
             $msg->getDb();
             $msg->type = $getData->msg_type;
             $msg->push_type = Msg::__PUSH_PART;
             $msg->status = Msg::__STATUS_YES;
             $msg->title = $getData->title;
             $msg->content = $getData->content;
             //$msg->message = $getData->message;
             $msg->create_time = date('Y-m-d H:i:s');
             $msg->oper_time = date('Y-m-d H:i:s');
             $msg->content_id = 0;
             $msg->contract = isset($getData->contract) ? $getData->contract : '';
             $msgStatus = $msg->save();
             if ($msgStatus) {
                 $msgId = $msg->id;
                 $msgUser = new MsgUser;
                 $msgUser->getDb();
                 $msgUser->user_id = $getData->user_id;
                 $msgUser->msg_id = $msgId;
                 $msgUser->save();
             }

             Log::save($msgStatus, 'AppPush.log');
             Log::save('Params:' . var_export($data, true), 'AppPush.log');
             return self::DELETE;
            */
       // } catch (\Exception $e) {
            //Log::save($e->getMessage(), 'AppPush.error.log');
           // return self::BURY;
      //  }
    }
}
