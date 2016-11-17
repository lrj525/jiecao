<!DOCTYPE html>
<html ng-app="App" ng-controller="loginCtrl" no-auth="true">
<head>
    <meta charset="utf-8" />   
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1 maximum-scale=1,user-scalable=no" />
    <meta name="renderer" content="webkit" />
    <meta name="wap-font-scale" content="no">
    <title>节操币系统后台-管理登录</title>
    <!--公用CSS引用-->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/angular/angular-loading-bar/loading-bar.min.css" />
    <link rel="stylesheet" href="/static/admin/style.css" />
    
    <!--公用JS引用-->
    <script src="/static/angular/angular.min.js"></script>
    <script src="/static/angular/angular-animate.min.js"></script>
    <script src="/static/angular/angular-sanitize.min.js"></script>
    <script src="/static/angular/ui-bootstrap-tpls-2.1.3.min.js"></script>
    <script src="/static/angular/angular-loading-bar/loading-bar.min.js"></script>
    <script src="/static/moment/moment.min.js"></script>
    <script src="/static/moment/zh-cn.js"></script>
    <script src="/static/function.js"></script>
    <script src="/static/admin/common.js"></script>
    <script src="/static/admin/controller.js"></script>
</head>
<body>    
    <div class="container-fluid">
        <div class="login">
            <p class="loginTip">节操币系统后台管理登录</p>         
            <div class="form-horizontal" >
                <div class="form-group">
                    <label class="col-xs-3 control-label">用户名</label>
                    <div class="col-xs-9">
                        <input type="text" class="form-control"   placeholder="用户名" ng-model="loginData.username" ng-keypress="enter($event)" />
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
    </div>
</body>
</html>