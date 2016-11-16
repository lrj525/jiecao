<?php
$this->title='管理员列表';
?>
<div class="row list" ng-controller="adminCtrl" ng-init="getList(1)">
    <div class="col-xs-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width:80px;">#</th>
                    <th style="width:120px;">邮箱</th>
                    <th>姓名</th>
                    <th style="width:80px;">状态</th>
                    <th style="width:200px;">操作</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in list">
                    <td>{{(currentPage-1)*pagesize+($index+1)}}</td>
                    <td ng-bind-html="item.username"></td>
                    <td ng-bind-html="item.name"></td>
                    <td ng-bind-html="getStatusDes(item.status)"></td>
                    <td>
                        <a class="btn btn-default" ng-href="/admin/main/admin-edit?id={{item.id}}">编辑</a>
                        <a class="btn btn-default" ng-click="changeStatus(item)">{{getStatusBtnDes(item.status)}}</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-12" style="padding-left:30px;">
        <ul uib-pagination boundary-links="true" boundary-link-numbers="true" force-ellipses="true" items-per-page="pagesize" max-size="10" ng-change="getList()" ng-model="currentPage" total-items="totalCount" class="pagination" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" style="{{totalCount==0?'display:none':''}}"></ul>
    </div>
</div>