<?php
$this->title='节操币系统-送节操';
?>
<div class="vote" ng-controller="votesCtrl" ng-init="getList(1)">
    <form class="form-horizontal" style="margin-top:15px;" name="submit_form">
        <div class="form-group">
            <label class="col-xs-3 control-label">送给谁</label>
            <div class="col-xs-9">

                <select class="form-control" ng-model="voteData.god_member_id" ng-options="member.id as member.name for member in list" required name="god_member_id" errormsg="必须选一个人"></select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">月份</label>
            <div class="col-xs-3">
                <input type="text" class="form-control" readonly="readonly" style="cursor:text;background:#ffffff;" placeholder="选择月份" uib-datepicker-popup="yyyy-MM" is-open="is_open" show-button-bar="false" datepicker-options="datepickerOptions" ng-model="voteData.month" ng-click="is_open=true" name="month" required errormsg="必须选择一个月份" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label">说两句</label>
            <div class="col-xs-9">
                <textarea class="form-control" ng-model="voteData.notes"></textarea>
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
                <button type="button" class="btn btn-primary" ng-click="submit(submit_form)">提交</button>
            </div>
        </div>
    </form>
</div>