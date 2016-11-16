<?php
namespace webapp\models;
use yii\db\ActiveRecord;

class ModelBase extends ActiveRecord
{    
    public static function createPageData($totalCount,$totalPage,$page,$list){
        return array(
            'list'      => $list,
            'totalCount'  => intval($totalCount),
            //'pagination'=>[
            //    'totalCount'  => intval($totalCount),
            //    //'totalPage' => intval($totalPage),
            //    //'page'      => intval($page),
            //    //'pages'=>$pages
            //]
        );
    }
}