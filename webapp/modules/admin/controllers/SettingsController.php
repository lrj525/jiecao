<?php
//登录
namespace webapp\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\BaseController;

class SettingsController extends BaseController
{
    public function actionVote(){
        return $this->render('vote');
    }
}