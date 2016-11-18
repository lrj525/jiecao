<?php
$this->title='送节操设置';
?>

<div class="row member-edit" ng-controller="settingsCtrl" ng-init="getVoteClosed()">
    <div class="col-xs-12">
        <p>当前状态：{{currStatus}}</p>
        <button class="btn btn-primary" ng-click="setVoteClosed()">{{btnDesc}}</button>
    </div>
</div>