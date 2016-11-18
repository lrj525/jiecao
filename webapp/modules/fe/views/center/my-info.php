<?php
$this->title='节操币系统-个人信息';
?>
<div class="myinfo" ng-controller="myInfoCtrl" ng-init="getMyInfo()">
    <p class="title1" style="margin-bottom:30px;">个人信息</p>
    <form class="form-horizontal" name="submit_form">
        <div class="form-group">
            <label class="col-xs-3 control-label">姓名</label>
            <div class="col-xs-9">
                <input type="text" readonly class="form-control input-200" ng-model="userData.name" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">邮箱</label>
            <div class="col-xs-9">
                <input type="text" readonly class="form-control input-200" ng-model="userData.username" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">头像</label>
            <div class="col-xs-9">               
                <div class="avatar" ng-click="openImageCropModal()">
                    <div class="glyphicon glyphicon-user default-icon"></div>
                    <div class="photo" ng-show="userData.avatar">
                        <img ng-src="{{userData.avatar}}" />
                    </div>
                    <div class="edit">更换</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">昵称</label>
            <div class="col-xs-9">
                <input type="text" class="form-control input-200" ng-model="userData.nick_name" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">电话</label>
            <div class="col-xs-9">
                <input type="text" class="form-control input-200" ng-model="userData.mobile" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">微信</label>
            <div class="col-xs-9">
                <input type="text" class="form-control input-200" ng-model="userData.wechat" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">QQ</label>
            <div class="col-xs-9">
                <input type="text" class="form-control input-200" ng-model="userData.qq" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">签名</label>
            <div class="col-xs-9">
                <textarea class="form-control" ng-model="userData.introduce"></textarea>
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
                <button type="button" class="btn btn-primary" ng-click="submit(submit_form)">更新</button>
            </div>
        </div>
    </form>
</div>