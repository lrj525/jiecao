<?php

namespace webapp\modules\adminapi\models;
use Yii;
use yii\db\ActiveRecord;
use common\helpers\Helper;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use OAuth2\Storage\UserCredentialsInterface;
use filsh\yii2\oauth2server\models\OauthAccessTokens;
use webapp\models\ModelBase;
use yii\helpers\ArrayHelper;
class Settings extends ModelBase
{
    public static function tableName()
    {
        return 'jc_settings';
    }

    public function behaviors()
    {
        return [];
    }


    public static function getVoteClosed(){
        $model=self::findOne(['id'=>1]);
        if($model){
            return $model->vote_closed;
        }else{
            $model=new Settings();
            $model->id=1;
            $model->vote_closed=0;
            $model->save();
        }
        return $model->vote_closed;
    }
    public static function setVoteClosed(){
        $model=self::findOne(['id'=>1]);
        if($model){
            $model->vote_closed=$model->vote_closed==1?0:1;
            $model->save();
            return $model->vote_closed;
        }
        return ['success'=>false,'message'=>'出错了，请重试'];

    }




}