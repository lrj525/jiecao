<?php
$this->title='节操币系统-节操排名';
?>
<div class="vote ngCloak" ng-controller="rankCtrl" ng-init="getList(1)">
    <div class="row">
        <div class="col-xs-12">
            <table>
                <tr>
                    <td>
                        <input type="text" class="form-control" readonly="readonly" style="cursor:text;background:#ffffff;" placeholder="选择月份" uib-datepicker-popup="yyyy-MM" is-open="is_open" show-button-bar="false" datepicker-options="datepickerOptions" ng-model="query.month" ng-click="is_open=true" />
                    </td>
                    <td>
                        &nbsp;
                        <button href="" class="btn btn-success " ng-click="clearMonth()">清除</button>

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row list" >
        <div class="col-xs-12">
            <table class="table table-hover">
                <caption align="top">{{getTableTitle(query.month)}}</caption>
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:100px;">
姓名</th>                        
                        <th >节操</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in list">
                        <td>{{(currentPage-1)*pagesize+($index+1)}}</td>
                        <td >
<a ng-href="/fe/center/member-votes?id={{item.god_member_id}}">{{item.name}}</a></td>
                        <td ng-bind-html="item.jc | number"></td>

                    </tr>
                </tbody>
            </table>           
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="padding-left:30px;">
            <ul uib-pagination boundary-links="true" boundary-link-numbers="true" force-ellipses="true" items-per-page="pagesize" max-size="10" ng-change="getList()" ng-model="currentPage" total-items="totalCount" class="pagination" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" style="{{totalCount==0?'display:none':''}}"></ul>
        </div>
    </div>
</div>