<?php
namespace webapp\controllers;

use Yii;
use yii\rest\Controller;
use yii\rest\ActiveController;
use webapp\controllers\BaseController;

use yii\helpers\ArrayHelper;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use webapp\filters\FilterAuth;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use yii\web\Response;
use yii\web\Request;
class ApiBaseController extends ActiveController
{
    public function init()
    {
        parent::init();
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
                $response = $event->sender;
                $data = $response->data;
                $success = $response->isSuccessful;
                if($success && isset($response->data['success'])){
                    $success = $response->data['success'];
                    unset($response->data['success']);
                }

                $response->data = [
                    'success' => $success,
                    'data' => $data,
                ];
                if(!$success){
                    $response->data['message']=$data['message'];
                    if(isset($data['code'])){
                        $response->data['code']=$data['code'];
                    }
                    if(isset($response->data['data'])){
                        unset($response->data['data']);
                    }
                }
                $response->statusCode = 200;

        });
        Yii::$app->request->parsers=[
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ];
    }
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    ['class' => FilterAuth::className()],
                ],
            ],
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }
     public function actions()
     {
        //当用put请求时，可以使用下面方式来接收参数,客户端要指定Content-Type为application/json;charset=UTF-8
         //$status=$request->post("status");
         //$status=$request->getBodyParam('status');

         $actions = parent::actions();
         // 禁用所有内置 操作
         unset($actions['index'], $actions['view'],$actions['create'], $actions['update'],$actions['delete']);

         return $actions;
     }
}
