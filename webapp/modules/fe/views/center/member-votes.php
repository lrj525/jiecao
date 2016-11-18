<?php
$this->title='节操币系统-{{0}}的节操';
?>
<div class="vote ngCloak" ng-controller="memberVotesCtrl" ng-init="getList(1)">
    <div class="row list">
        <div class="col-xs-4 title">共获得{{totalCount}}节操</div>
        <div class="col-xs-8 title">
            <table>
                <tr>
                    <td>
                        <input type="text" class="form-control" readonly="readonly" style="cursor:text;background:#ffffff;" placeholder="选择月份" uib-datepicker-popup="yyyy-MM" is-open="is_open" show-button-bar="false" datepicker-options="datepickerOptions" ng-model="query.month" ng-click="is_open=true" />
                    </td>
                    <td>
                        &nbsp;
                        <button href="" class="btn btn-success " ng-click="getList()">按月查看</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <div class="row list" ng-repeat="item in list">
        <div class="col-xs-3">
            <div style="height:50px;line-height:50px;">

                <div class="avatarIcon avatarList pull-left">
                    <div class="glyphicon glyphicon-user default-icon"></div>
                    <div class="photo" ng-show="userData.avatar">
                        <img ng-src="{{userData.avatar}}" />
                    </div>
                </div>
                <div class="avatarName pull-left">
                    <a ng-href="/fe/center/member-votes?id={{item.god_member_id}}" title="点击查看他/她的节操">&nbsp;{{item.god_name}}</a>
                </div>
            </div>
            <p class="time">
                <span class="glyphicon glyphicon-time" ng-bind-html="item.create_time"></span>
            </p>
        </div>
        <div class="col-xs-9 right">
            <div class="chevron-right line-right"></div>
            <span>{{item.notes}}</span>
        </div>
    </div>
    <div class="row list" ng-if="totalCount<=0">
        <div class="col-xs-12 noData">还没人送他/她节操 :(</div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="padding-left:30px;">
            <ul uib-pagination boundary-links="true" boundary-link-numbers="true" force-ellipses="true" items-per-page="pagesize" max-size="10" ng-change="getList()" ng-model="currentPage" total-items="totalCount" class="pagination" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" style="{{totalCount==0?'display:none':''}}"></ul>
        </div>
    </div>
</div>