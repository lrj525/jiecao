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
     * 详情
     * @author lrj
     */
    public function actionView($id){
        return Member::findById($id);
    }
    /**
     * 编辑
     * @author lrj
     */
    public function actionSave(){
        $postData = Yii::$app->request->post();
        $result= Member::saveModel($postData);
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
        $order='id asc';
        $where = [];
        if ($keyword) {
            $where['name'] = ['like',$keyword];
        }
        return Member::getList($where,$page,$pageSize,$order);
    }

    /**
     * 更改状态
     * @author lrj
     */
    public function actionStatus($id,$status){
        return Member::Status($id,$status);
    }
    public function actionBatchAdd(){
        //$memberTxt='冯浩,fenghao@juniu.tv;魏方,weifang@juniu.tv;张裕珍,zhangyuzhen@juniu.tv;胡进,hujin@juniu.tv;匡永钢,kuangyonggang@juniu.tv;李鹏,lipeng@juniu.tv;宋东娅,songdongya@juniu.tv;韩红林,hanhonglin@juniu.tv;刘泽钢,liuzegang@juniu.tv;牛晓佳,niuxiaojia@juniu.tv;潘姝,panshu@juniu.tv;席益,xiyi@juniu.tv;朱利,zhuli@juniu.tv;鲍禹卿,baoyuqing@juniu.tv;郑建亚,zhengjianya@juniu.tv;潘春城,panchuncheng@juniu.tv;何栋,hedong@juniu.tv;李汝金,lirujin@juniu.tv;崔思思,cuisisi@juniu.tv;马亮,maliang@juniu.tv;谭剑,tanjian@juniu.tv;何静,hejing@juniu.tv;方立,fangli@juniu.tv;郭亚帅,guoyashuai@juniu.tv;蔡新新,caixinxin@juniu.tv;朱正苑,zhuzhengyuan@juniu.tv;潘月杰,panyuejie@juniu.tv;马莲红,malianhong@juniu.tv;汤占帅,tangzhanshuai@juniu.tv';
        $memberTxt='李汝金,lirujin@juniu.tv;坏猫,lrj525@sina.com';
        $memberList=explode(';',$memberTxt);
        foreach($memberList as $key=>$val){
            $m=explode(',',$val);
            $password=rand(100000,999999);
            $data=array('id'=>0,'username'=>$m[1],'password'=>$password,'name'=>$m[0]);
            $result= Member::saveModel($data);
            if(!isset($result['success'])){
                //发送邮件
                Yii::$app->mailer->compose()
                    ->setTo($result->username)
                    ->setSubject('节操币系统账号开通')
                    ->setHtmlBody('登录地址：http://jc.juniulvxing.cn，或从http://oa.juniu.tv/进入<br />登录邮箱：'.$result->username.'<br />初始密码：'.$password)
                    ->send();
            }
        }
        return $memberList;
    }
}