<?php
namespace webapp\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use OAuth2\Storage\UserCredentialsInterface;
use filsh\yii2\oauth2server\models\OauthAccessTokens;
use common\helpers\Helper;
use OAuth2\Request;
use yii\helpers\ArrayHelper;

class User extends ActiveRecord implements IdentityInterface,UserCredentialsInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        $client_id=Yii::$app->request->headers->get('client_id');
        if($client_id=='jc_admin'){
            return 'jc_admin';
        }else if($client_id=='jc_fe'){
            return 'jc_member';
        }else{
            throw new \ErrorException('Invalid clientId',400);
        }
    }
    public function fields()
    {
        return [
            'id',
            'username',
            'name',
            'create_time',
            'update_time',
            'last_login_ip',
            'last_login_time',
            'status'
        ];
    }

    public function behaviors()
    {
        return [];
    }


    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id,'status' => self::STATUS_ACTIVE]);
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username,'status' => self::STATUS_ACTIVE]);
    }
    public static function findByPasswordResetToken($token)
    {
        if (! static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE
        ]);
    }
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function updateLoginInfo($data = [])
    {
        $data['last_ip'] = Helper::getIp();
        $data['last_login_time'] = date('Y-m-d H:i:s');
        return $this->updateUserInfo($data);
    }
    public function updateUserInfo($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this->save();
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user_token = OauthAccessTokens::findOne(['access_token' => $token]);
        if($user_token){
            return self::findOne(['id' => $user_token->user_id]);
        }
        return false;
    }


    public function checkUserCredentials($username, $password)
    {
        $user = static::findByUsername($username);
        if(!$user)
            return false;
        return $user->validatePassword($password);
    }

    public function getUserDetails($username)
    {
        $user = static::findByUsername($username);
        return ['user_id'=>$user->getId()];
    }
}
