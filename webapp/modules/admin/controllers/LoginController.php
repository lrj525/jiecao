<?php
namespace webapp\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\BaseController;

class LoginController extends BaseController
{
    public $layout=false;    
    public function actionIndex(){
        return $this->render('index');
    }
}