<?php
$this->title='节操币系统-首页';
?>
<div class="vote ngCloak" ng-controller="centerCtrl" ng-init="getList(1)">
    <h1 class="title1 line-bottom">节操墙</h1>
    <div class="row list" ng-repeat="item in list">
        <div class="col-xs-3">
            <!--<p class="month" ng-bind-html="item.month"></p>-->
            <div style="height:50px;line-height:50px;"> 
                
                <div class="avatarIcon avatarList pull-left">
                    <div class="glyphicon glyphicon-user default-icon"></div>
                    <div class="photo" ng-show="userData.avatar">
                        <img ng-src="{{userData.avatar}}" />
                    </div>
                </div>
                <div class="avatarName pull-left">
<a ng-href="/fe/center/member-votes?id={{item.god_member_id}}"  title="点击查看他/她的节操">&nbsp;{{item.god_name}}</a></div>
            </div>
            <p class="time"><span class="glyphicon glyphicon-time" ng-bind-html="item.create_time"></span></p>           
        </div>
        <div class="col-xs-9 right">
            <div class="chevron-right line-right"></div>
            <span>{{item.notes}}</span>
        </div>
    </div>
    <div class="row list" ng-if="totalCount<=0">
        <div class="col-xs-12 noData">暂无数据</div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="padding-left:30px;">
            <ul uib-pagination boundary-links="true" boundary-link-numbers="true" force-ellipses="true" items-per-page="pagesize" max-size="10" ng-change="getList()" ng-model="currentPage" total-items="totalCount" class="pagination" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" style="{{totalCount==0?'display:none':''}}"></ul>
        </div>
    </div>
</div>