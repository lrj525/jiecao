<?php
namespace webapp\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\BaseController;

class MainController extends BaseController
{
    public function actionIndex(){
        //Yii::$app->mailer->compose()            
        //    ->setTo('badcat525@sina.com')
        //    ->setSubject('测试一下3')
        //    ->setTextBody('测试一下内容1')
        //    ->send();
        return $this->render('index');
    }
    public function actionAdminEdit(){
        return $this->render('admin-edit');
    }
    public function actionAdminList(){
        return $this->render('admin-list');
    }
    public function actionArchive(){
        return $this->render('archive');
    }
}

