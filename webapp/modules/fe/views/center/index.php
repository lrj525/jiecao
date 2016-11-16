<?php
$this->title='节操币系统-首页';
?>
<div class="vote ngCloak" ng-controller="centerCtrl" ng-init="getList(1)">
    <div class="row list" ng-repeat="item in list">
        <div class="col-xs-3">
            <p class="month" ng-bind-html="item.month"></p>
            <p> 
                <a ng-href="/fe/center/member-votes?id={{item.god_member_id}}" class="glyphicon glyphicon-thumbs-up">&nbsp;{{item.god_name}}</a>
            </p>
            <p class="time">
                <a class="time" ng-href="/fe/center/member-votes?id={{item.supporter_member_id}}">{{item.supporter_name}}</a><br/>@
<span ng-bind-html="item.create_time"></span></p>
        </div>
        <div class="col-xs-9">
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