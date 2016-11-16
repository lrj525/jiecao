<?php
$this->title='节操币系统-修改密码';
?>
<div class="login" style="width:290px" ng-controller="modifyPasswordCtrl">
    <p class="loginTip">修改密码</p>
    <form class="form-horizontal" name="submit_form">
        <div class="form-group">
            <label class="col-xs-3 control-label">旧密码</label>
            <div class="col-xs-9">
                <input type="password" class="form-control" placeholder="旧密码" ng-model="userData.old_password" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">新密码</label>
            <div class="col-xs-9">
                <input type="password" class="form-control" placeholder="新密码" ng-model="userData.password" required name="password" errormsg="新密码不能为空" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <ul class="errlist">
                    <li ng-repeat="err in errors">{{err.msg}}</li>
                </ul>
            </div>

        </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <button type="button" class="btn btn-primary" ng-click="submit(submit_form)">修改</button>
            </div>
        </div>
    </form>
</div>