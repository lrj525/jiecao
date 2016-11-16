<?php
namespace webapp\filters;

use yii\filters\auth\HttpBearerAuth;
use common\helpers\Helper;

class FilterAuth extends HttpBearerAuth
{
	public $realm = 'webapp';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match("/^Bearer\\s+(.*?)$/", $authHeader, $matches)) {
            $identity = $user->loginByAccessToken($matches[1], get_class($this));
            if ($identity === null) {
                header('Access-Control-Allow-origin:*');
                header('Access-Control-Allow-Methods:POST');
                header('Access-Control-Allow-Headers:Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With, postman-token, authorization');
                header("Content-type:application/json");
                echo json_encode(array('success'=>false,'message'=>'身份验证失败,请重新登录','code'=>401));
                die;
            }


            return $identity;
        }
        header('Access-Control-Allow-origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With, postman-token, authorization');
        header("Content-type:application/json");
        echo json_encode(array('success'=>false,'message'=>'身份验证失败,请重新登录','code'=>401));
        exit;
    }
}