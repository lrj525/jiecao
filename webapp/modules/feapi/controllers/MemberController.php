<?php
// 控制器
namespace webapp\modules\feapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\feapi\models\Member;
class MemberController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\feapi\models\Member';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * 详情
     * @author lrj
     */
    public function actionView($id){
        return Member::findById($id);
    }
    /**
     * 修改密码
     * @author lrj
     */
    public function actionModifyPassword(){
        $old_password=Yii::$app->request->post('old_password','');
        $password=Yii::$app->request->post('password','');
        $result= Member::modifyPassword($old_password,$password);
        return $result;
    }

    /**
     * 列表
     * @author lrj
     */
    public function actionSearch(){
        $page = intval(Yii::$app->request->post('page',1));
        $pageSize = intval(Yii::$app->request->post('pagesize',10));
        $keyword=Yii::$app->request->post('keyword','');
        $id=Yii::$app->user->identity->id;
        $order='id asc';
        $where = [
            'id'=>['<>',$id],
            'status'=>['<>',0]
        ];
        if ($keyword) {
            $where['name'] = ['like',$keyword];
        }
        return Member::getList($where,$page,$pageSize,$order);
    }
    /**
     * 列表
     * @author lrj
     */
    public function actionRank(){
        $page = intval(Yii::$app->request->post('page',1));
        $pageSize = intval(Yii::$app->request->post('pagesize',10));
        $month=Yii::$app->request->post('month','');
        $where = [
            'status'=>['<>',0]
        ];

        return Member::getRank($where,$page,$pageSize,$month);
    }
}