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
 * This is the model class for table "{{%jc_member}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property integer $status
 */
class Member extends ModelBase
{
    public static function tableName()
    {
        return 'jc_member';
    }
    public function fields(){
        $fields = parent::fields();
        unset($fields['password_hash']);
        return $fields;
    }

    /**
     * 取列表带分页
     * @param array $where
     * @param int $page
     * @param int $pageSize
     * @return array
     * @author lrj
     */
	public static function getList($where, $page = 1, $pageSize = 10,$order=null,$select=null)
	{
        $query=self::find();
        if($select){
            $query->select($select);
        }
        if($order){
            $query->orderBy($order);
        }
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
			$list =$query->all();
		}
        return parent::createPageData($totalCount,$totalPage,$page,$list);
    }
    /**
     * 查找
     * @author lrj
     */
    public static function findById($id){
        return self::findOne(['id'=>$id]);
    }
    /**
     * 查找
     * @author lrj
     */
    public static function findByUsername($username){
        return self::findOne(['username' => $username]);
    }
    /**
     * 修改密码
     * @author lrj
     */
    public static function modifyPassword($old_password,$password){
        $id=Yii::$app->user->identity->id;
        if(empty($password)){
            return ['success'=>false,'message'=>'新密码不能为空'];
        }
        $model=static::findById($id);
        if($model){
            if(!Yii::$app->security->validatePassword($old_password, $model->password_hash)){
                return ['success'=>false,'message'=>'旧密码输入不正确'];
            }
            $model->password_hash=Yii::$app->security->generatePasswordHash($password);
            return $model->save();
        }
        return ['success'=>false,'message'=>'修改密码失败，请稍后重试'];
    }

    /**
     * 排行
     * @param array $where
     * @param int $page
     * @param int $pageSize
     * @return array
     * @author lrj
     */
	public static function getRank($where, $page = 1, $pageSize = 10,$month='')
	{
        $query=self::find();
        if(empty($month)){
            $query->select(['id','username','name','(SELECT count(id) from jc_votes where jc_votes.god_member_id=jc_member.id) as jc']);
        }else{
            $query->select(['id','username','name','(SELECT count(id) from jc_votes where jc_votes.god_member_id=jc_member.id and jc_votes.month=\''.$month.'\') as jc']);
        }
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

        $sql=$query->createCommand()->getRawSql();
		// 总数
		$totalCount = $query->count();
        $query->orderBy('jc desc');
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
}