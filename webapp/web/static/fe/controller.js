/*通用顶部*/
App.controller("mainCtrl", [
    "$scope",
    "authService",
    "ngSettings",
    "apiService",
    "$uibModal",
    function ($scope, authService, ngSettings, apiService, $uibModal) {
        $scope.user = JSON.parse(window.localStorage.getItem("jc_user"));
        
        $scope.logout = function () {
            authService.logOutFromServer();
        };
        $scope.showHeader = function () {
            return angular.isObject($scope.user);
        }
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
                        template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>' + message + '</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">确定</button></div>'
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
/*首页*/
App.controller("centerCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    function ($scope, apiService, $uibModal) {        
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
            apiService.post("/votes/search", $scope.query).then(function (res) {
                if (res.success) {
                    $scope.list = res.data.list;
                    $scope.totalCount = res.data.totalCount;
                }
                angular.element(document.querySelectorAll(".ngCloak")).removeClass("ngCloak");
            });
        };
        
    }]);

/*员工节操*/
App.controller("memberVotesCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    function ($scope, apiService, $uibModal) {
        var id = getQueryString("id");
        apiService.get("/member/view?id="+id).then(function (res) {
            if (res.success) {
                document.title = document.title.replace("{{0}}",res.data.name);
            }
        });
        var minDate = moment("2016-10");
        var maxDate = moment();
        $scope.datepickerOptions = {
            showWeeks: false,
            startingDay: 1,
            datepickerMode :"month",
            maxMode: "month",
            minMode: "month",
            minDate: minDate,
            maxDate:maxDate
        };
        //列表
        $scope.currentPage = 1;
        $scope.pagesize = 10;
        $scope.totalCount = 0
        $scope.query = {
            page: $scope.currentPage,
            pagesize: $scope.pagesize,
            id: id,
            month: ""
        };
        $scope.getList = function (page) {
            if (page) {
                $scope.currentPage = page;
            }
            $scope.query.page = $scope.currentPage;
            var query = angular.copy($scope.query);            
            if (query.month != "") {
                query.month = moment(query.month).format("YYYY-MM");
            }
            apiService.post("/votes/search-by-id", query).then(function (res) {
                if (res.success) {
                    $scope.list = res.data.list;
                    $scope.totalCount = res.data.totalCount;
                }
                angular.element(document.querySelectorAll(".ngCloak")).removeClass("ngCloak");
            });
        };
        
    }]);

/*修改密码*/
App.controller("modifyPasswordCtrl", [
    "$scope",
    "apiService",
    "$uibModal",
    "authService",
    function ($scope, apiService, $uibModal,authService) {
        $scope.userData = {};
        $scope.submit = function (form) {
            form.$setDirty();
            if (form.$valid) {
                $scope.errors = [];
                apiService.post("/member/modify-password", $scope.userData).then(function (res) {
                    if (res.success) {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>操作成功,请重新登录</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">关闭</button></div>'
                        });
                        modalInstance.closed.then(function () {
                            authService.logOutFromServer();
                        });
                    }
                });


            } else {
                $scope.errors = formValidate(form);
            }
        };

    }]);


/*送节操*/
App.controller("votesCtrl", [
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
            maxDate: maxDate,
        };
        $scope.voteData = {
            god_member_id: 0,
            supporter_member_id: 0,
            notes: "",
            month: new Date()
        };
        //员工列表
        $scope.currentPage = 1;
        $scope.pagesize = 1000;
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
                    $scope.voteData.god_member_id = $scope.list[0].id;
                    $scope.totalCount = res.data.totalCount;
                }
            });
        };
        $scope.submit = function (form) {
            form.$setDirty();
            if (form.$valid) {
                $scope.errors = [];
                var voteData = angular.copy($scope.voteData);
                if (voteData.month != "") {
                    voteData.month = moment(voteData.month).format("YYYY-MM");
                }
                apiService.post("/votes/save", voteData).then(function (res) {
                    if (res.success) {
                        var modalInstance = $uibModal.open({
                            template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>送节操成功</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">关闭</button></div>'
                        });
                        modalInstance.closed.then(function () {
                            $scope.voteData.notes="";
                        });
                    }
                });

            } else {
                $scope.errors = formValidate(form);
            }
        };

    }]);

/*排名*/
App.controller("rankCtrl", [
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
        
        //列表
        $scope.currentPage = 1;
        $scope.pagesize = 10;
        $scope.totalCount = 0
        $scope.query = {
            page: $scope.currentPage,
            pagesize: $scope.pagesize,
            month: "",
        };
        $scope.getList = function (page) {
            if (page) {
                $scope.currentPage = page;
            }
            $scope.query.page = $scope.currentPage;
            var query = angular.copy($scope.query);
            if (query.month != "") {
                query.month = moment(query.month).format("YYYY-MM");
            }
            apiService.post("/member/rank", query).then(function (res) {
                if (res.success) {
                    $scope.list = res.data.list;
                    $scope.totalCount = res.data.totalCount;
                }
                angular.element(document.querySelectorAll(".ngCloak")).removeClass("ngCloak");
            });
        };
        $scope.getTableTitle = function (month) {
            if (!month || month == "") {
                return "总排名";
            }
            else {
                return moment(month).format("YYYY-MM");
            }
        }
        $scope.clearMonth = function () {
            $scope.query.month = "";
            //$scope.getList();
        };
        $scope.$watch('query.month', function (newValue, oldValue, scope) {
            if (newValue != oldValue) {
                $scope.getList();
            }
        })

    }]);