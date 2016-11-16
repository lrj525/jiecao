<?php
use yii\web\View;

$this->title='节操币系统-登录';

?>
<?php $this->beginBlock("script") ?>
    var html = document.getElementsByTagName("html")[0];
    html.setAttribute("no-auth",true);
<?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks["script"], \yii\web\View::POS_HEAD); ?>
<div class="login" ng-controller="loginCtrl">
    <p class="loginTip">节操币系统登录</p>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-xs-3 control-label">邮箱</label>
            <div class="col-xs-9">
                <input type="text" class="form-control" placeholder="邮箱" ng-model="loginData.username" ng-keypress="enter($event)" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">密码</label>
            <div class="col-xs-9">
                <input type="password" class="form-control" placeholder="密码" ng-model="loginData.password" ng-keypress="enter($event)" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <button type="button" class="btn btn-primary" ng-click="login()">登录</button>
            </div>
        </div>
    </div>
</div>