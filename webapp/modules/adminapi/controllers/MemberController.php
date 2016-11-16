<?php
namespace webapp\modules\adminapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\adminapi\models\Member;
class MemberController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\adminapi\models\Member';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * ����
     * @author lrj
     */
    public function actionView($id){
        return Member::findById($id);
    }
    /**
     * �༭
     * @author lrj
     */
    public function actionSave(){
        $postData = Yii::$app->request->post();
        $result= Member::saveModel($postData);
        return $result;
    }
    /**
     * �б�
     * @author lrj
     */
    public function actionSearch(){
        $page = intval(Yii::$app->request->post('page',1));
        $pageSize = intval(Yii::$app->request->post('pagesize',10));
        $keyword=Yii::$app->request->post('keyword','');
        $order='id asc';
        $where = [];
        if ($keyword) {
            $where['name'] = ['like',$keyword];
        }
        return Member::getList($where,$page,$pageSize,$order);
    }
    
    /**
     * ����״̬
     * @author lrj
     */
    public function actionStatus($id,$status){
        return Member::Status($id,$status);
    }
}