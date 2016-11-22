<?php
return [
    'language'=>'zh-CN',
    'timeZone'=>'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql.juniu.tv;dbname=jiecao;port=3306',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,
            'viewPath' => '@common/mail',
             'transport' => [
               'class' => 'Swift_SmtpTransport',
               'host' => 'smtp.exmail.qq.com',
               'username' => 'jiecao@jiecao.com',
               'password' => '2016',
               'port' => '465',
               'encryption' => 'ssl',
             ],
           'messageConfig'=>[
               'charset'=>'UTF-8',
               'from'=>['jiecao@juniu.tv'=>'节操币系统']
           ],
        ],

    ],
];
