/*通用顶部*/
App.controller("mainTopCtrl", [
    "$scope",
    "authService",
    "ngSettings",
    "apiService",
    "$uibModal",
    function ($scope, authService, ngSettings, apiService, $uibModal) {
        $scope.user = JSON.parse(window.localStorage.getItem("jc_admin"));
        $scope.logout = function () {
            authService.logOutFromServer();
        };
        $scope.batchAdd = function () {
            apiService.get('/member/batch-add').then(function (res) {
                if (res.success) {
                    alert('成功')
                }
            });
        };
    }]);
/*通用左则*/
App.controller("mainLeftCtrl", [
    "$scope",
    "authService",
    "ngSettings",
    "apiService",
    "$uibModal",
    function ($scope, authService, ngSettings, apiService, $uibModal) {
        
    }]);
/*登录*/
App.controller("loginCtrl", [
    "$scope",    
    "authService",
    "ngSettings",
    "apiService",
    "$uibModal",
    function ($scope, authService, ngSettings, apiService, $uibModal) {
        $scope.loginData = { username: "", password: "" };
        $scope.login = function () {            
            authService.login($scope.loginData).then(function (response) {
                if (response.success) {
                    //登录成功                    
                    var lasturl = getQueryString("lasturl");
                    if (lasturl) {
                        window.location.href = lasturl;
                    } else {
                        window.location.href = ngSettings.homeUrl;
                    }
                } else {
                    //登录失败                    
                    var message = response.message;
                    var modalInstance = $uibModal.open({
                        template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>' + message + '</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">确定</button></div>',
                        //size: 400,                    
                    });
                }                
            });
        };
        $scope.enter = function (ev) {
            if (ev.keyCode == "13") {
                $scope.login();
            }
        };
        
    }]);
/*管理员*/
App.controller("adminCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    function ($scope, apiService, $uibModal) {
        //添加编辑
        $scope.initEdit = function () {
            $scope.id = getQueryString("id");
            $scope.dataModel = {
                id: 0,
                username: "",
                name: "",
                sex: "",
                mobile: "",
                create_time: "",
                update_time: "",
                auth_key: "",
                password_hash: "",
                role_ids: "",
                last_login_ip: "",
                last_login_time: "",
                status: 1
            };
            if ($scope.id) {
                apiService.get("/admin/view?id=" + $scope.id).then(function (res) {
                    if (res.success) {
                        $scope.dataModel = res.data;
                    }
                });
            } else {
                $scope.id = 0;
            }
        };
        $scope.submit = function (form) {
            form.$setDirty();
            if (form.$valid) {
                $scope.errors = [];
                apiService.post("/admin/save", $scope.dataModel).then(function (res) {
                    if (res.success) {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>操作成功</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="continue()">继续</button><button type="button" class="btn btn-default" ng-click="golist()">返回列表</button></div>',
                            controller: function ($scope,id) {
                                $scope.continue = function () {
                                    if (id > 0) {
                                        window.location.href = "/admin/main/admin-edit?id=" + id;
                                    } else {
                                        window.location.href = "/admin/main/admin-edit";
                                    }
                                };
                                $scope.golist = function () {
                                    window.location.href = "/admin/main/admin-list";
                                };
                            },
                            resolve: {
                                id: function () { return $scope.id; }
                            }
                        });
                    }
                });


            } else {
                $scope.errors = formValidate(form);
            }
        };
        //列表
        $scope.currentPage = 1;
        $scope.pagesize = 10;
        $scope.totalCount = 0
        $scope.query = {
            page: $scope.currentPage,
            pagesize: $scope.pagesize,
            keyword: "",
        };
        $scope.getList = function (page) {
            if (page) {
                $scope.currentPage = page;
            }
            $scope.query.page = $scope.currentPage;
            apiService.post("/admin/search", $scope.query).then(function (res) {
                if (res.success) {
                    $scope.list = res.data.list;
                    $scope.totalCount = res.data.totalCount;
                }
            });
        };
        //更改状态
        $scope.changeStatus = function (item) {
            var status = item.status;
            if (status == 0) {
                status = 1;
            } else if (status == 1) {
                status = 0;
            }
            apiService.get("/admin/status?id=" + item.id + "&status=" + status).then(function (res) {
                if (res.success) {
                    if (res.data) {
                        item.status = status;
                    } else {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>操作失败，请重试</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">关闭</button></div>'
                        });
                    }
                }
            });
        };
        $scope.getStatusBtnDes = function (status) {
            var des = "";
            if (status == 0) {
                des = "启用";
            }
            if (status == 1) {
                des = "禁用";
            }
            return des;
        };
        $scope.getStatusDes = function (status) {
            var des = "";
            if (status == 0) {
                des = "已禁用";
            }
            if (status == 1) {
                des = "正常";
            }
            return des;
        };
    }]);

/*员工*/
App.controller("memberCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    function ($scope, apiService, $uibModal) {
        //添加编辑
        $scope.initEdit = function () {
            $scope.id = getQueryString("id");
            $scope.dataModel = {
                id: 0,
                username: "",
                name: "",
                sex: "",
                mobile: "",
                create_time: "",
                update_time: "",
                auth_key: "",
                password_hash: "",
                role_ids: "",
                last_login_ip: "",
                last_login_time: "",
                status: 1
            };
            if ($scope.id) {
                apiService.get("/member/view?id=" + $scope.id).then(function (res) {
                    if (res.success) {
                        $scope.dataModel = res.data;
                    }
                });
            } else {
                $scope.id = 0;
            }
        };
        $scope.submit = function (form) {
            form.$setDirty();
            if (form.$valid) {
                $scope.errors = [];
                apiService.post("/member/save", $scope.dataModel).then(function (res) {
                    if (res.success) {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>操作成功</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="continue()">继续</button><button type="button" class="btn btn-default" ng-click="golist()">返回列表</button></div>',
                            controller: function ($scope,id) {
                                $scope.continue = function () {
                                    if (id > 0) {
                                        window.location.href = "/admin/member/edit?id=" + id;
                                    } else {
                                        window.location.href = "/admin/member/edit";
                                    }
                                };
                                $scope.golist = function () {
                                    window.location.href = "/admin/member/list";
                                };
                            },
                            resolve: {
                                id: function () { return $scope.id; }
                            }
                        });
                    }
                });


            } else {
                $scope.errors = formValidate(form);
            }
        };
        //列表
        $scope.currentPage = 1;
        $scope.pagesize = 10;
        $scope.totalCount = 0
        $scope.query = {
            page: $scope.currentPage,
            pagesize: $scope.pagesize,
            keyword: "",
        };
        $scope.getList = function (page) {
            if (page) {
                $scope.currentPage = page;
            }
            $scope.query.page = $scope.currentPage;
            apiService.post("/member/search", $scope.query).then(function (res) {
                if (res.success) {
                    $scope.list = res.data.list;
                    $scope.totalCount = res.data.totalCount;
                }
            });
        };        
        
        //更改状态
        $scope.changeStatus = function (item) {
            var status = item.status;
            if (status == 0) {
                status = 1;
            } else if (status == 1) {
                status = 0;
            }
            apiService.get("/member/status?id=" + item.id + "&status=" + status).then(function (res) {
                if (res.success) {
                    if (res.data) {
                        item.status = status;
                    } else {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>操作失败，请重试</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">关闭</button></div>'
                        });
                    }
                }
            });
        };
        $scope.getStatusBtnDes = function (status) {
            var des = "";
            if (status == 0) {
                des = "启用";
            }
            if (status == 1) {
                des = "禁用";
            }
            return des;
        };
        $scope.getStatusDes = function (status) {
            var des = "";
            if (status == 0) {
                des = "已禁用";
            }
            if (status == 1) {
                des = "正常";
            }
            return des;
        };
    }]);



/*月归档操作*/
App.controller("monthStatisticCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    function ($scope, apiService, $uibModal) {
        var minDate = moment("2016-10");
        var maxDate = moment();
        $scope.datepickerOptions = {
            showWeeks: false,
            startingDay: 1,
            datepickerMode: "month",
            maxMode: "month",
            minMode: "month",
            minDate: minDate,
            maxDate: maxDate
        };
        $scope.query = {
            month:new Date()
        }
        $scope.checkArchive = function () {
            var query = angular.copy($scope.query);
            if (query.month != "") {
                query.month = moment(query.month).format("YYYY-MM");
            }
                apiService.post("/month-archive/exist", query).then(function (res) {
                    if (res.success) {
                        if (res.data) {
                            if (confirm("所选月份的归档数据已经存在了，你确定要重新生成归档数据吗？")) {
                                $scope.createArchive();
                            }
                        } else {
                            $scope.createArchive();
                        }
                    }
                });
        }
        $scope.createArchive = function () {
            var query = angular.copy($scope.query);
            if (query.month != "") {
                query.month = moment(query.month).format("YYYY-MM");
            }
            apiService.post("/month-archive/create", query).then(function (res) {
                if (res.success) {
                    
                }
            });
        }
        ////列表
        //$scope.currentPage = 1;
        //$scope.pagesize = 10;
        //$scope.totalCount = 0
        //$scope.query = {
        //    page: $scope.currentPage,
        //    pagesize: $scope.pagesize,
        //    keyword: "",
        //};
        //$scope.getList = function (page) {
        //    if (page) {
        //        $scope.currentPage = page;
        //    }
        //    $scope.query.page = $scope.currentPage;
        //    apiService.post("/member/search", $scope.query).then(function (res) {
        //        if (res.success) {
        //            $scope.list = res.data.list;
        //            $scope.totalCount = res.data.totalCount;
        //        }
        //    });
        //};

    
    }]);