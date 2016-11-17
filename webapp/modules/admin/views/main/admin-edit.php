<?php
$adminId=Yii::$app->request->get('adminId',0);
if($adminId>0){
    $this->title='节操币系统后台-管理员编辑';
}else{
    $this->title='节操币系统后台-管理员添加';
}

?>

<div class="row member-edit" ng-controller="adminCtrl" ng-init="initEdit()">
    <div class="col-xs-12">
        <form class="form-horizontal" name="submit_form">
            <div class="form-group">
                <label class="col-xs-2 control-label">邮箱</label>
                <div class="col-xs-10">
                    <input type="text" class="form-control" placeholder="邮箱" ng-model="dataModel.username" name="username" required errormsg="邮箱必须填写" ng-readonly="id>0" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label">密码</label>
                <div class="col-xs-10">
                    <input type="password" class="form-control" placeholder="密码" ng-model="dataModel.password" name="password" errormsg="密码必须填写" ng-if="id>0" />
                    <span ng-if="id>0">输入密码时则修改当前密码，留空则不修改</span>
                    <input type="password" class="form-control" placeholder="密码" ng-model="dataModel.password" name="password" required errormsg="密码必须填写" ng-if="id==0" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label">姓名</label>
                <div class="col-xs-10">
                    <input type="text" class="form-control" placeholder="姓名" ng-model="dataModel.name" name="name1" required errormsg="姓名必须填写" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <ul class="errlist">
                        <li ng-repeat="err in errors">{{err.msg}}</li>
                    </ul>
                </div>
                
            </div>
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <button type="button" class="btn btn-primary" ng-click="submit(submit_form)">提交</button>
                </div>
                
            </div>
        </form>
    </div>
</div>