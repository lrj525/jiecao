<?php

namespace webapp\modules\adminapi\models;
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
class Admin extends ModelBase
{
    public static function tableName()
    {
        return 'jc_admin';
    }
    public function fields(){
        $fields = parent::fields();
        unset($fields['password_hash']);
        return $fields;
    }
    public function behaviors()
    {
        return [];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert) {
                $this->create_time = $this->update_time = date('Y-m-d H:i:s', time());
            } else {
                $this->update_time =  date('Y-m-d H:i:s', time());
            }
            return true;
        } else {
            return false;
        }
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '邮箱',
            'name' => '姓名',
            'password'      => '密码',
        ];
    }

    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['username','name'],'required','on'=>'edit'],
            [['username','password_hash','name'],'required','on'=>'add'],
            ['username','email']
        ];
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
     * 编辑
     * @author lrj
     */
    public static function saveModel($postData){
        if (isset($postData['id'])&&intval($postData['id'])>0) {
            $model = static::findById($postData['id']);
            $model->scenario='edit';
        }else{
            $admin=static::findByUsername($postData['username']);
            if($admin){
                return array('success'=>false,'message'=>'已存在用户，请更换邮箱');
            }
            $model = new Admin();
            $model->status=1;
            $model->scenario='add';
        }
        if($model->load($postData,'')){
            if(!empty($postData['password'])){
                $model->password_hash = Yii::$app->security->generatePasswordHash($postData['password']);
            }
            if ($model->validate()) {
                if($model->save())
                {
                    return $model;
                }
            }else{
                return ArrayHelper::merge(['success'=>false], ['message'=>$model->errors]);
            }

        }
        return array('success'=>false,'message'=>'添加失败，请重试');
    }

    /**
     * 更改状态
     * @author lrj
     */
    public static function Status($id,$status){
        $model = static::findById($id);
        if($model){
            $model->status=$status;
            return $model->save();
        }
        return false;
    }
}