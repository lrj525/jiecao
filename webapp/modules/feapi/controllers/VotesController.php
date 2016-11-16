<?php
namespace webapp\modules\feapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\feapi\models\Votes;
class VotesController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\feapi\models\Votes';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * 编辑
     * @author lrj
     */
    public function actionSave(){
        $supporter_member_id=Yii::$app->user->identity->id;
        $god_member_id = intval(Yii::$app->request->post('god_member_id',0));
        $notes=Yii::$app->request->post('notes','');
        $month=Yii::$app->request->post('month','');
        return Votes::saveModel($supporter_member_id,$god_member_id,$notes,$month);
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