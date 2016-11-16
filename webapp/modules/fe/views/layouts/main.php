<?php
use yii\helpers\Html;
$this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo  Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/angular/angular-loading-bar/loading-bar.min.css" />
    <link rel="stylesheet" href="/static/fe/style.css" />
    <script src="/static/angular/angular.min.js"></script>
    <script src="/static/angular/i18n/angular-locale_zh-cn.js"></script>
    <script src="/static/angular/angular-animate.min.js"></script>
    <script src="/static/angular/angular-sanitize.min.js"></script>
    <script src="/static/angular/angular-loading-bar/loading-bar.min.js"></script>
    <script src="/static/moment/moment.min.js"></script>
    <script src="/static/moment/zh-cn.js"></script>
    <script src="/static/angular/ui-bootstrap-tpls-2.1.3.min.js"></script>
    <script src="/static/function.js"></script>
    <script src="/static/fe/common.js"></script>
    <script src="/static/fe/controller.js"></script>

    <?php $this->head() ?>
</head>
<body ng-app="App">
    <div ng-controller="mainCtrl">
        <div class="row header" ng-if="showHeader()">
            <div class="container-fluid">
                <ul class="user-nav">

                    <li>
                        <a href="/fe/center">首页</a>
                    </li>
                    <li>
                        <a href="/fe/center/rank">排名</a>
                    </li>
                    
                    <li>
                        <a href="/fe/center/votes">送节操</a>
                    </li>
                    <li>
                        <a ng-href="/fe/center/member-votes?id={{user.id}}">我的节操</a>
                    </li>
                    <li>
                        <a href="/fe/center/modify-password">修改密码</a>
                    </li>
                    <li style="float:right;">
                        <a  ng-click="logout()">退出</a>
                    </li>
                    <li style="float:right;">
                        你好，{{user.name}}
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="pbody">
            <?php $this->beginBody() ?>
            <?php echo $content;?>
            <?php $this->endBody() ?>
        </div>
    </div>
</body>
</html>
<?php $this->endPage() ?>