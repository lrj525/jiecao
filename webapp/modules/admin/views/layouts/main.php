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
    <title>
        <?php echo  Html::encode($this->title) ?>
    </title>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/angular/angular-loading-bar/loading-bar.min.css" />
    <link rel="stylesheet" href="/static/admin/style.css" />
    <script src="/static/angular/angular.min.js"></script>
    <script src="/static/angular/i18n/angular-locale_zh-cn.js"></script>
    <script src="/static/angular/angular-animate.min.js"></script>
    <script src="/static/angular/angular-sanitize.min.js"></script>
    <script src="/static/angular/angular-loading-bar/loading-bar.min.js"></script>
    <script src="/static/moment/moment.min.js"></script>
    <script src="/static/moment/zh-cn.js"></script>
    <script src="/static/angular/ui-bootstrap-tpls-2.1.3.min.js"></script>
    <script src="/static/function.js"></script>
    <script src="/static/admin/common.js"></script>
    <script src="/static/admin/controller.js"></script>
    <?php $this->head() ?>
</head>
<body ng-app="App">
    <div class="container-fluid">
        <div class="row header" ng-controller="mainTopCtrl">
            <div class="col-xs-6 brand">
                <a href="/admin/main" class="brand">节操币系统后台管理</a>
            </div>
            <div class="col-xs-6 ">
                <ul class="user-nav">
                    <li>
                        你好，{{user.name}}
                    </li>

                    <!--<li class="mgr15">
                        <a>修改密码</a>
                    </li>-->
                    <li>
                        <a ng-click="logout()">退出</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row main">
            <div class="col-xs-3 mleft menu" ng-controller="mainLeftCtrl">
                <dl>
                    <dt>设置</dt>
                    <dd>
                        <a href="/admin/settings/vote">
                            <span class="leftmenu-dot"></span>送节操设置
                        </a>
                    </dd>
                    
                </dl>
                <dl>
                    <dt>员工管理</dt>
                    <dd>
                        <a href="/admin/member/edit">
                            <span class="leftmenu-dot"></span>添加员工
                        </a>
                    </dd>
                    <dd>
                        <a href="/admin/member/list">
                            <span class="leftmenu-dot"></span>员工列表
                        </a>
                    </dd>
                </dl>                
                <dl>
                    <dt>管理员管理</dt>
                    <dd>
                        <a href="/admin/main/admin-edit">
                            <span class="leftmenu-dot"></span>管理员添加
                        </a>
                    </dd>
                    <dd>
                        <a href="/admin/main/admin-list">
                            <span class="leftmenu-dot"></span>管理员列表
                        </a>
                    </dd>
                </dl>                
            </div>
            <div class="col-xs-9 mright">
                <div class="row">
                    <div class="col-xs-12 path-nav">
                        <b>当前位置:</b><?php echo $this->title;?>
                    </div>
                </div>
                <div class="mrbody">                    
                        <?php $this->beginBody() ?>
                        <?php echo $content;?>
                        <?php $this->endBody() ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $this->endPage() ?>