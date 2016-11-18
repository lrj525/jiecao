<?php
// 控制器
namespace webapp\modules\feapi\controllers;

use Yii;
use yii\web\Controller;
use webapp\controllers\ApiBaseController;
use webapp\modules\feapi\models\Member;
use common\helpers\ImageThumb;
class FileController extends ApiBaseController
{

    public $modelClass = 'webapp\modules\feapi\models\Member';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * 上传图片
     * @author lrj
     */
    public function actionUploadBase64(){
        //上传图片
        $basePath=Yii::getAlias('@webapp');
        $saveDir = $basePath . '/web/uploads/avatar/';
       
        $base64_image_content=Yii::$app->request->post('base64_image_content','');
        if(!empty($base64_image_content)){
            //保存base64字符串为图片
            //匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
                $type = $result[2];
                $filename = date('YmdHis').'_'.rand(100000,999999).'.'.$type;
                if (file_put_contents($saveDir.$filename, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                    return ['pictureUrl'=>'/uploads/avatar/'.$filename];
                }
            }else{
                return ['success'=>false,'message'=>'请上传[data:image/*;base64]格式的数据'];
            }
        }

        return ['success'=>false,'message'=>'上传出错，请重试'];
    } 

}