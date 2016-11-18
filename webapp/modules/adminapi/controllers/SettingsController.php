<?php
namespace webapp\modules\adminapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\adminapi\models\Settings;
class SettingsController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\adminapi\models\Settings';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * 详情
     * @author lrj
     */
    public function actionGetVoteClosed(){
        return Settings::getVoteClosed();
    }
    public function actionSetVoteClosed(){
        return Settings::setVoteClosed();
    }


    

}