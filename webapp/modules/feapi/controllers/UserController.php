<?php
namespace webapp\modules\feapi\controllers;

use Yii;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBearerAuth;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use api\controllers\BaseController;
use OAuth2\Request;
use common\models\oauth\OauthAccessTokens;
use webapp\models\User;
use yii\web\Response;
class UserController extends ActiveController
{
    public $modelClass = 'webapp\modules\User';
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
                    ['class' => HttpBearerAuth::className()],
                ],
                'except'=>[
                    'login'
                ],
            ],
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    /**
     *
     *
     * @return void
     * @author
     **/
    protected function token()
    {
        $client_id=Yii::$app->request->headers->get('client_id');
        $client_secret=Yii::$app->request->headers->get('client_secret');
        $data = [
             'client_id'     => $client_id,
             'client_secret' => $client_secret
         ];
        $request = Request::createFromGlobals();

        if(!isset($request->request['grant_type']))
        {
            $request->request['grant_type'] = 'password';
        }


        $request->request = ArrayHelper::merge($request->request,$data);

        $response = Yii::$app->getModule('oauth2')->getServer()->handleTokenRequest($request);
        return $response->getParameters();
    }

    /**
     *
     *
     * @return void
     * @author
     **/
    public function actionLogin()
    {

        $token = $this->token();
        if (isset($token['emessage'])) {
            Yii::$app->response->statusCode = 400;
            return ['message' => $token['emessage']];
        }
        if(!isset($token['error']))
        {
            $user = Yii::$app->user->loginByAccessToken($token['access_token']);

            if($user->status == 0)
                return array('success'=>false,'message'=>'该账号已被停用','code'=>401);

            if(Yii::$app->user->isGuest){
                return array('success'=>false,'message'=>'登录失败','code'=>401);
            }

            $data = array(
                'user'      => $user,
                'token'     => $token,
            );
            return $data;

        }
    }

    /**
     * 退出
     * @return void
     * @author xi
     **/
    public function actionRevoke()
    {
        //退出登录需要post token="token",并且需要验证
        $request = Request::createFromGlobals();
        $response = Yii::$app->getModule('oauth2')->getServer()->handleRevokeRequest($request);
        if(isset($response->getParameters()['revoked']) && $response->getParameters()['revoked']==1)
            return [
                'code' => 0,
                'msg' => 'ok',

            ];
        return [
            'success'=>false,
            'code'=> -1,
            'msg' => '退出失败',
        ];
    }

}