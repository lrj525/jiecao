<?php

namespace webapp\modules\feapi\models;
use Yii;
use yii\db\ActiveRecord;
use common\helpers\Helper;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use OAuth2\Storage\UserCredentialsInterface;
use filsh\yii2\oauth2server\models\OauthAccessTokens;
use webapp\models\ModelBase;
use yii\helpers\ArrayHelper;

/**
 *
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property integer $status
 */
class Votes extends ModelBase
{
    public static function tableName()
    {
        return 'jc_votes';
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert) {
                $this->create_time = date('Y-m-d H:i:s', time());
            }
            return true;
        } else {
            return false;
        }
    }
    public function attributeLabels()
    {
        return [
            'supporter_member_id' => '支持者',
            'god_member_id' => '被支持者',
            'notes'      => '备注',
        ];
    }
    public function rules()
    {
        return [
            [['supporter_member_id','god_member_id'],'integer'],
            [['supporter_member_id','god_member_id'],'required'],
        ];
    }

    /**
     * 取列表带分页
     * @param int $myid
     * @param int $page
     * @param int $pageSize
     * @return array
     * @author lrj
     */
	public static function getList($where, $page = 1, $pageSize = 10)
	{
        $query=self::find();
        $query->select(['jc_votes.*','date_format(jc_votes.create_time,"%c月") as month','(select name from jc_member where id=supporter_member_id) as supporter_name','(select name from jc_member where id=god_member_id) as god_name']);
        if($where){
            foreach ($where as $key=>$val)
            {
                if(is_array($val)){
                    $query->andWhere([$val[0],$key,$val[1]]);
                }
                else {
                    $query->andWhere([$key=>$val]);
                }
            }
        }
        $query->orderBy('jc_votes.id desc');
        //$sql=$query->createCommand()->getRawSql();
		// 总数
		$totalCount = $query->count();
        $list=[];
        $totalPage=1;
		// 当有结果时进行组合数据
		if ($totalCount > 0) {
			// 总页数
			$totalPage = ceil($totalCount / $pageSize);
			if ($page < 1) {
				$page = 1;
			} else if ($page > $totalPage) {
                $page = $totalPage;
            }

			$query->offset(($page - 1) * $pageSize);
			$query->limit($pageSize);
			$list =$query->asArray()->all();
		}
        return parent::createPageData($totalCount,$totalPage,$page,$list);
    }
    /**
     * 送节操
     * @author lrj
     */
    public static function saveModel($supporter_member_id,$god_member_id,$notes,$month){
        if($god_member_id==0){
            return array('success'=>false,'message'=>'要选一个送节操的对象哦！');
        }
        //$now=date('Y-m', time());
        $query=self::find();
        //$query->where(['supporter_member_id'=>$supporter_member_id,'god_member_id'=>$god_member_id,'month'=>$month]);//送多个人同一月
        //每个月只能送一个人
        $query->where(['supporter_member_id'=>$supporter_member_id,'month'=>$month]);
        //$sql=$query->createCommand()->getRawSql();
        $result=$query->all();

        if($result){
            return array('success'=>false,'message'=>'这个月您已经没有节操可送了！');
        }
        $model = new Votes();
        $model->supporter_member_id=$supporter_member_id;
        $model->god_member_id=$god_member_id;
        $model->notes=$notes;
        $model->month=$month;
        $result=$model->save();
        if($result){
            return $model;
        }
        return array('success'=>false,'message'=>'操作失败，请重试');
    }

}