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

class Votes extends ModelBase
{
    public static function tableName()
    {
        return 'jc_votes';
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


}