<?php
// 控制器
 
namespace webapp\modules\fe\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\BaseController;

class CenterController extends BaseController
{
    public function actionIndex(){
        return $this->render('index');
    }
    public function actionModifyPassword(){
        return $this->render('modify-password');
    }
    public function actionLogin(){
        return $this->render('login');
    }
    
    public function actionVotes(){
        return $this->render('votes');
    }
    public function actionMemberVotes(){
        return $this->render('member-votes');
    }
    public function actionRank(){
        return $this->render('rank');
    }
}