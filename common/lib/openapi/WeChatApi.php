<?php
namespace common\lib\openapi;

use Yii;
use common\helpers\Helper;
class WeChatApi
{
    const VerifyTicket = 'ComponentVerifyTicket';
    
    
    public static function getAppId()
    {
        return Yii::$app->params['wechat_component']['appid'];
    }
    
    public static function getAppSecret()
    {
        return Yii::$app->params['wechat_component']['appsecret'];
    }
    
    public static function getAppToken()
    {
        return Yii::$app->params['wechat_component']['token'];
    }
    
    public static function getAppEncodingAesKey()
    {
        return Yii::$app->params['wechat_component']['encodingAesKey'];
    }
    
    /**
     * 微信定时(10分钟推送一次)，从缓冲获取\
     * @example https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1453779503&token=&lang=zh_CN
     */
    public static function getVerifyTicket()
    {
        if(Yii::$app->redis_cache->exists(self::VerifyTicket)){
            return Yii::$app->redis_cache->get(self::VerifyTicket);
        }
        return '';
    }
    
    /**
     * 获取第三方平台component_access_token
     */
    public static function apiComponentToken()
    {
        
        if(Yii::$app->redis_cache->exists('component_expires_in') && Yii::$app->redis_cache->exists('component_access_token') )
        {
            $expires = Yii::$app->redis_cache->get('component_expires_in');
            
            //如果时间快到了，就重新获取
            if(time()>($expires-600))
            {
                return self::_getAccessToken();
            }
            else 
            {
                return Yii::$app->redis_cache->get('component_access_token');
            }
        }
        else 
        {
            return self::_getAccessToken();
        }
    }
    
    private static function _getAccessToken()
    {
        $data = [
            "component_appid"         => Yii::$app->params['wechat_component']['appid'] ,
            "component_appsecret"     => Yii::$app->params['wechat_component']['appsecret'],
            "component_verify_ticket" => self::getVerifyTicket()
        ];
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
        
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        if(isset($arr['component_access_token']))
        {
            Yii::$app->redis_cache->set('component_access_token',$arr['component_access_token']);
            Yii::$app->redis_cache->set('component_expires_in', time()+$arr['expires_in']);
            return $arr['component_access_token'];
        }
        return '';
    }
    
    /**
     * 获取预授权码pre_auth_code
     */
    public static function apiCreatePreauthcode($club_id)
    {
        //@todu 暂时不缓冲了，缓冲经常出问题
        if(false && Yii::$app->redis_cache->exists('component_pre_auth_code'.$club_id))
        {
            return Yii::$app->redis_cache->get('component_pre_auth_code'.$club_id);
        }
        else
        {
            $data = [
                'component_appid' => Yii::$app->params['wechat_component']['appid']
            ];
            $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=".self::apiComponentToken();
            
            $jsonStr = Helper::curlPost($url, json_encode($data));
            $arr = json_decode($jsonStr,true);
            
            if(isset($arr['pre_auth_code']) && isset($arr['expires_in']))
            {
                Yii::$app->redis_cache->set('component_pre_auth_code'.$club_id ,$arr['pre_auth_code'], 300);
                return $arr['pre_auth_code'];
            }
        }
        return '';
    }
    
    /**
     * 使用授权码换取公众号的接口调用凭据和授权信息
     * @param string $auth_code
     * @return array
     */
    public static function apiQueryAuth($auth_code)
    {
        $data = [
            "component_appid"    => self::getAppId(),
            "authorization_code" => $auth_code 
        ];
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=".self::apiComponentToken();
        
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        return $arr;
    }
    
    /**
     * 刷新token
     * @param unknown $authorizer_appid
     * @param unknown $authorizer_refresh_token
     * @return mixed
     */
    public static function apiAuthorizerToken($authorizer_appid,$authorizer_refresh_token)
    {
        $data = [
            "component_appid"          => self::getAppId(),
            "authorizer_appid"         => $authorizer_appid,
            "authorizer_refresh_token" => $authorizer_refresh_token,
        ];
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=".self::apiComponentToken();
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        return $arr;
    }
    
    /**
     * 获取授权方的公众号帐号基本信息
     * @param string $authorizer_appid 授权方app id
     * @return array
     */
    public static function apiGetAuthorizerInfo($authorizer_appid)
    {
        $data = [
            "component_appid"  => self::getAppId() ,
            "authorizer_appid" => $authorizer_appid
        ];
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=".self::apiComponentToken();
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        return $arr;
    }
    
    /**
     * 获取授权方的选项设置信息
     * 该API用于获取授权方的公众号的选项设置信息，如：地理位置上报，语音识别开关，多客服开关。注意，获取各项选项设置信息，需要有授权方的授权，详见权限集说明。
     * 
     * @param string $authorizer_appid 授权方app id
     * @param string $option_name  
     */
    public static function apiGetAuthorizerOption($authorizer_appid,$option_name)
    {
        $data = [
            "component_appid"  => self::getAppId(),
            "authorizer_appid" => $authorizer_appid,
            "option_name"      => $option_name
        ];
        
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_option?component_access_token=".self::apiComponentToken();
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        return $arr;
    }
    
    /**
     * 设置授权方的选项信息
     * @see  https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1453779503&token=&lang=zh_CN
     * 
     * @param string $authorizer_appid
     * @param string $option_name 选项目名 [location_report、voice_recognize、customer_service]
     * @param string $option_value 选项值 [0,1,2]
     * @return boolean
     */
    public static function apiSetAuthorizerOption($authorizer_appid, $option_name, $option_value)
    {
        $data = [
            "component_appid"  => self::getAppId(),
            "authorizer_appid" => $authorizer_appid,
            "option_name"      => $option_name,
            "option_value"     => $option_value
        ];
        
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_set_authorizer_option?component_access_token=".self::apiComponentToken();
        
        $jsonStr = Helper::curlPost($url, json_encode($data));
        $arr = json_decode($jsonStr,true);
        if($arr['errcode'] == 0){
            return true;
        }
        return $arr;
    }
    
    /**
     * 创建菜单 
     * @param array $arr 菜单数组
     * @param string $accessToken 第三方的 token
     * @return boolean|mixed
     */
    public static function addMenu($arr,$accessToken)
    {
        foreach ($arr as $key=>$val){
            $arr[$key]['name'] = urlencode($arr[$key]['name']);
            if(isset($val['sub_button']) && !empty($val['sub_button'])){
                foreach ($val['sub_button'] as $k=>$v){
                    $arr[$key]['sub_button'][$k]['name'] = urlencode($arr[$key]['sub_button'][$k]['name']);
                }
            }
        }
    
        $menu['button'] = $arr;
        $json = json_encode($menu);
        $json = urldecode($json);
    
        //获取ACCESS_TOKEN
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$accessToken;
        $result = Helper::curlPost($url, $json);
        $result = json_decode($result,true);
        if(isset($result['errcode']) && $result['errcode'] == 0){
            return true;
        }
        return $result;
    }
    
    /**
     * 生成推广二维码
     * @param $actionName 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值
     * @param $sceneID 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
     * @return JSON
     * @author niu
     */
    public static function createQrcode($accessToken,$actionName,$sceneID)
    {
        $json = [
            "action_name"  => $actionName,
            "action_info" => ["scene"=> ["scene_id" => $sceneID]]
            
        ];

        //获取ACCESS_TOKEN
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accessToken;
        $result = Helper::curlPost($url, json_encode($json));
        $result = json_decode($result,true);
        
        return $result;
    }
}