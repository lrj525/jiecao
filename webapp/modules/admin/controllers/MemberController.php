<?php
namespace webapp\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\BaseController;


class MemberController extends BaseController{
    public function actionList(){
        return $this->render('list');
    }
    public function actionEdit(){
        return $this->render('edit');
    }
}