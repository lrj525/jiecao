<?php
namespace webapp\modules\feapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\feapi\models\Votes;
use webapp\modules\feapi\models\Member;
use webapp\modules\feapi\models\Settings;
class VotesController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\feapi\models\Votes';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * 送节操
     * @author lrj
     */
    public function actionSave(){
        $vote_closed=Settings::getVoteClosed();
        if($vote_closed==1){
            return ['success'=>false,'message'=>'送节操功能当前处于关闭状态'];
        }
        $supporter_member_id=Yii::$app->user->identity->id;
        $god_member_id = intval(Yii::$app->request->post('god_member_id',0));
        $notes=Yii::$app->request->post('notes','');
        $month=Yii::$app->request->post('month','');
        $result= Votes::saveModel($supporter_member_id,$god_member_id,$notes,$month);
        if(!isset($result['success'])){
            try{
                $god=Member::findById($result->god_member_id);
                if($god){
                    //发送邮件
                    Yii::$app->mailer->compose()
                        ->setTo($god->username)
                        ->setSubject('节操币系统通知')
                        ->setHtmlBody($god->name.'您好：<br/>有人给您送节操了，可以从：http://jc.juniulvxing.cn 或 http://oa.juniu.tv/进行登录查看<br />')
                        ->send();
                }
            }
            catch(Exception $e){

            }
        }
        return $result;
    }
    /**
     * 列表
     * @author lrj
     */
    public function actionSearch(){
        $page = intval(Yii::$app->request->post('page',1));
        $pageSize = intval(Yii::$app->request->post('pagesize',10));
        $where = [];
        return Votes::getList($where,$page,$pageSize);
    }

    /**
     * 列表
     * @author lrj
     */
    public function actionSearchById(){
        //$id=Yii::$app->user->identity->id;
        $id = intval(Yii::$app->request->post('id',1));
        $page = intval(Yii::$app->request->post('page',1));
        $pageSize = intval(Yii::$app->request->post('pagesize',10));
        $month = Yii::$app->request->post('month','');
        $where=['jc_votes.god_member_id'=>$id];
        if(!empty($month)){
            $where['date_format(create_time,"%Y-%m")']=$month;
        }
        return Votes::getList($where,$page,$pageSize);
    }
}