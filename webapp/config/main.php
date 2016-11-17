<?php
//配置
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'jiecao',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'webapp\controllers',
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'log',
    ],
    'defaultRoute'=>'/fe/center',
    'modules' => [
        'oauth2' => [
            'class'                => 'filsh\yii2\oauth2server\Module',
            'tokenParamName'       => 'accessToken',
            'tokenAccessLifetime'  => 3600 * 24 * 360,
            'refreshTokenLifetime' => 3600 * 24 * 360,
            'storageMap' => [
                'user_credentials' => 'webapp\models\User',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ]
        ],
        'fe' => [
            'basePath' => '@webapp/modules/fe',
            'class'    => 'webapp\modules\fe\Module',
        ],
        'feapi' => [
            'basePath' => '@webapp/modules/feapi',
            'class'    => 'webapp\modules\feapi\Module',
        ],
        'admin' => [
            'basePath' => '@webapp/modules/admin',
            'class'    => 'webapp\modules\admin\Module',
        ],
        'adminapi' => [
            'basePath' => '@webapp/modules/adminapi',
            'class'    => 'webapp\modules\adminapi\Module',
        ],

    ],
    'components' => [
        'user' => [
            'identityClass'   => 'webapp\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],

       'urlManager' => [
           'enablePrettyUrl' => true,
           'showScriptName' => false,
           'rules'=>[
               '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
           ],
        ],

    ],
    'params' => $params,
];
