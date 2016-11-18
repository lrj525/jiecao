var App = angular.module("App", [
    "angular-loading-bar",
    "ngAnimate",
    "ngSanitize",
    "ui.bootstrap",
    "ngFileUpload",
    "ngImgCrop"
]);
App.config([
    "cfpLoadingBarProvider",
    "$httpProvider",    
    function (cfpLoadingBarProvider,$httpProvider) {
        cfpLoadingBarProvider.includeSpinner = false;
        cfpLoadingBarProvider.includeBar = true;
        $httpProvider.interceptors.push("authInterceptorService");        
    }
]);


App.constant("ngSettings", {
    apiServiceBaseUri: "/feapi",
    authorizationData: encodeURIComponent(base64encode("authorizationData")),
    loginUrl: "/fe/center/login",
    homeUrl: "/fe/center",
    client_id: "jc_fe",
    client_secret:"jc_fe_pass"
});

App.run([
    "authService",
    "ngSettings",
    "$rootScope",
    "$uibModal",
    "$timeout",
    function (authService, ngSettings, $rootScope,$uibModal,$timeout) {
        authService.fillAuthData();
        var serverUrl = ngSettings.apiServiceBaseUri;        
        $rootScope.goback = function () {
            window.history.back();
        }        
        $rootScope.goToLogin = function(){
            window.location.href = ngSettings.loginUrl;
        }       
    }
]);


App.factory("authInterceptorService", [
    "$q",
    "ngSettings",
    function ($q, ngSettings) {
        var localStorageKey = ngSettings.authorizationData;
        var authInterceptorServiceFactory = {};
        var _request = function (config) {
            config.headers = config.headers || {};
            var authData = null;
            var encodeData =window.localStorage.getItem(localStorageKey);
            if (encodeData) {
                authData = JSON.parse(base64decode(encodeData));
            }
            if (authData) {
                config.headers.Authorization = "Bearer " + authData.access_token;
            }
            //config.headers["Content-Type"] = "text/plain;charset=UTF-8";//"application/x-www-form-urlencoded";
            config.headers["client-id"] = ngSettings.client_id;
            config.headers["client-secret"] = ngSettings.client_secret;
            return config;
        }      
        var _responseError = function (rejection) {           
            return $q.reject(rejection);
        }
        authInterceptorServiceFactory.request = _request;
        authInterceptorServiceFactory.responseError = _responseError;
        return authInterceptorServiceFactory;
    }
]);

App.factory("authService", [
    "$http",
    "$q",    
    "ngSettings",
    "$uibModal",
    function ($http, $q, ngSettings, $uibModal) {
        var localStorageKey = ngSettings.authorizationData;
        var serviceBase = ngSettings.apiServiceBaseUri;
        var authServiceFactory = {};
        var _authentication = {
            access_token: "",
            token_type: "",
            expires_in: 0,            
        };
        var _login = function (loginData) {
            _logOut();
            var data = "grant_type=password&username=" + loginData.username + "&password=" + loginData.password;
            var deferred = $q.defer();
            $http.post(serviceBase + "/user/login", data, { "headers": { "Content-Type": "application/x-www-form-urlencoded", "client-id": ngSettings.client_id, "client-secret": ngSettings.client_secret } }).success(function (response) {
                if (response.success) {
                    var data = response.data;
                    _authentication.access_token = data.token.access_token;
                    _authentication.token_type = data.token.token_type;
                    _authentication.expires_in = data.token.expires_in;                                        
                    window.localStorage.setItem("jc_user", JSON.stringify(data.user));
                    var lStorageData = JSON.stringify(_authentication);
                    window.localStorage.setItem(localStorageKey, base64encode(lStorageData));

                }
                deferred.resolve(response);
            });
            return deferred.promise;
        };

        var _logOut = function () {            
            window.localStorage.removeItem(localStorageKey);
            _authentication.access_token = null;
            _authentication.token_type = null;
            _authentication.expires_in = -1;
            window.localStorage.removeItem("jc_user");
           
        };
        var _logOutFromServer = function () {
            var data = {};
            var encodeData = window.localStorage.getItem(localStorageKey);
            var authData = null;
            if (encodeData) {
                authData = JSON.parse(base64decode(encodeData));
            }
            if (authData) {
                data.token = authData.access_token;
            }
            $http.post(serviceBase + "/user/revoke", data).success(function (response) {               
                if (response.success) {
                    _logOut();
                    window.location.href = ngSettings.loginUrl;
                }                
            });
        };

        var _fillAuthData = function () {

            var authData = null;
            var encodeData = window.localStorage.getItem(localStorageKey);
            
            if (encodeData) {
                authData = JSON.parse(base64decode(encodeData));
            }
            if (authData) {
                _authentication.access_token = authData.access_token;
                _authentication.token_type = authData.token_type;
                _authentication.expires_in = authData.expires_in;
                
            } else {
                var html = document.getElementsByTagName("html")[0];
                if (html.getAttribute("no-auth") != "true") {
                    //if (window.location.href.indexOf("login.html") <= 0)
                    window.location.href = ngSettings.loginUrl + "?lasturl=" + encodeURIComponent(window.location.href);
                }
            }

        };
        
        authServiceFactory.login = _login;
        authServiceFactory.logOut = _logOut;
        authServiceFactory.fillAuthData = _fillAuthData;
        authServiceFactory.authentication = _authentication;
        authServiceFactory.logOutFromServer = _logOutFromServer;
        
        return authServiceFactory;
    }
]);

App.factory("apiService", [
    "$http",
    "$q",
    "ngSettings",
    "authService",
    "$uibModal",
    function ($http, $q, ngSettings, authService, $uibModal) {
        var serviceBase = ngSettings.apiServiceBaseUri;
        var apiServiceFactory = {};
        var _post = function (api, postData) {            
            api += (/\?/.test(api) ? "&" : "?") + "ran=" + Math.random();
            var deferred = $q.defer();
            $http.post(serviceBase + api, postData).success(function (response) {
                _errAuth(response);
                deferred.resolve(response);
            }).error(function (data, status) {
               //请求出错
            });
            return deferred.promise;
        };
        var _get = function (api, params) {
            if (params) {
                api += (/\?/.test(api) ? "&" : "?") + toUrlQuery(params);
            }
            api += (/\?/.test(api) ? "&" : "?") + "ran=" + Math.random();
            var deferred = $q.defer();
            $http.get(serviceBase + api).success(function (response) {
                _errAuth(response);
                deferred.resolve(response);
            }).error(function (data, status) {
                //请求出错
            });
            return deferred.promise;
        }
        /*
        * 参数apis是一个对象数组
        * var apis = [
        *        {method:"GET",url:"api/adminGroup/groupType"},
        *       {method:"POST",url:"api/adminGroup/query",postData:{}}
        *    ];
        */
        var _getAll = function (apis) {
            var promises = [];
            angular.forEach(apis, function (api) {
                if (api.method.toUpperCase() == "GET") {
                    promises.push(_get(api.url));
                } else if (api.method.toUpperCase() == "POST") {
                    promises.push(_post(api.url, api.postData));
                } else if (api.method.toUpperCase() == "PUT") {
                    promises.push(_put(api.url, api.postData));
                } else if (api.method.toUpperCase() == "DELETE") {
                    promises.push(_delete(api.url));
                }
            });
            return $q.all(promises);
        }
        var _put = function (api, postData) {
            api += (/\?/.test(api) ? "&" : "?") + "ran=" + Math.random();
            var deferred = $q.defer();            
            $http.put(serviceBase + api, postData, { "headers": { "Content-Type": "text/plain;charset=UTF-8" } }).success(function (response) {
                _errAuth(response);
                deferred.resolve(response);
            }).error(function (data, status) {
                //请求出错
            });
            return deferred.promise;
        }
        var _delete = function (api) {
            api += (/\?/.test(api) ? "&" : "?") + "ran=" + Math.random();
            var deferred = $q.defer();
            $http.delete(serviceBase + api).success(function (response) {
                _errAuth(response);
                deferred.resolve(response);
            }).error(function (data, status) {
                //请求出错
            });
            return deferred.promise;
        }
        var _errAuth = function (response) {
            if (!response.success && (response.code == 401)) {                
                var modalInstance = $uibModal.open({
                    template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p>身份验证失败</p><p>请重新登录</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="logOut()">确定</button></div>',
                    controller: function ($scope) {
                        $scope.logOut = function () {
                            authService.logOut();
                            window.location.href = ngSettings.loginUrl;
                        }
                    }
                });
            } else if (!response.success) {               
                var modalInstance = $uibModal.open({
                    template: '<div class="modal-header"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title"></h4></div><div class="modal-body"><p ng-repeat="msg in messages">{{msg}}</p></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="$dismiss()">确定</button></div>',
                    controller: function ($scope) {
                        $scope.messages = [];
                        if (angular.isObject(response.message)) {
                            angular.forEach(response.message, function (val,key) {
                                if (angular.isArray(val)) {
                                    angular.forEach(val, function (v, k) {
                                        $scope.messages.push(v);
                                    });
                                }
                            });
                        } else {
                            $scope.messages = [response.message];
                        }
                    }
                });
            }
        }
        apiServiceFactory.post = _post;
        apiServiceFactory.get = _get;
        apiServiceFactory.put = _put;
        apiServiceFactory.delete = _delete;
        apiServiceFactory.getAll = _getAll;
        return apiServiceFactory;
    }
]);


App.factory("imageCrop", [
    "$uibModal", 
    function ($uibModal) {
        return {
            open: function (options) {
                options = angular.extend({
                    changeOnFly: false,
                    areaType: "circle",
                    areaMinSize: 80,
                    resultImageSize: 200,
                    resultImageFormat: "image/png",
                    resultImageQuality: 1,
                    resultImage: ''
                }, options);

                var modalInstance = $uibModal.open({
                    backdrop: 'static',
                    templateUrl: "/file-upload-single-tpl.html",
                    controller: "imageCropCtrl",
                    resolve: {
                        options: function () {
                            return options;
                        }
                    }
                });
                return modalInstance;
            }
        };
    }
]);

App.controller("imageCropCtrl", [
    "$scope",
    "options",
    "Upload",
    "ngSettings",
    "$uibModalInstance",
    "apiService",
    function ($scope, options, Upload, ngSettings, $uibModalInstance, apiService) {

        $scope.options = options;
        $scope.onChange = function () {

        };
        $scope.onLoadBegin = function () {

        };
        $scope.onLoadDone = function () {

        };
        $scope.onLoadError = function () {

        };
        $scope.selectedImage = function (files) {
            if (files.length > 0) {
                var file = files[0];
                var reader = new FileReader();
                reader.onload = function (evt) {
                    $scope.$apply(function ($scope) {
                        $scope.options.image = evt.target.result;
                    });
                };
                reader.readAsDataURL(file);
            }
        };
        $scope.ok = function () {
            $scope.result = {};
            var serviceBase = ngSettings.apiServiceBaseUri;
            apiService.post('/file/upload-base64', { base64_image_content: $scope.options.resultImage }).then(function (res) {
                if (res.success) {
                    $scope.result.progress = 100;
                    $scope.result.pictureUrl = res.data.pictureUrl;
                    $uibModalInstance.close($scope.result.pictureUrl);
                } else {
                    $scope.result.errorMsg = res.message;
                }
            });
        };

    }
]);
App.run([
      '$templateCache',
      function ($templateCache) {
          $templateCache.put('/file-upload-single-tpl.html', '<div class="modal-header no-line"><button type="button" class="close" ng-click="$dismiss()"><span aria-hidden="true">×</span></button><h4 class="modal-title">图片裁切 <span class="action">图片将按照要求的比例进行裁切</span></h4></div><div class="modal-body"><div class="cropArea"><img-crop image="options.image" result-image="options.resultImage" change-on-fly="options.changeOnFly" area-type="{{options.areaType}}" area-min-size="options.areaMinSize" result-image-size="options.resultImageSize" result-image-format="{{options.resultImageFormat}}" result-image-quality="options.resultImageQuality" on-change="onChange()" on-load-begin="onLoadBegin()" on-load-done="onLoadDone()" on-load-error="onLoadError()"></img-crop></div></div><div class="modal-footer"><div class="row"><div class="col-xs-6"><ul><li style="font:smaller"><span class="progress" ng-show="result.progress >= 0">上传进度：<span style="width:{{result.progress}}%" ng-bind="result.progress + \'%\'"></span></span> <span>{{result.errorMsg}}</span></li></ul></div><div class="col-xs-6"><button id="fileUploadSingleBtn" class="btn btn-primary" style="width:88px" ng-model="files" ngf-select="selectedImage($files,$invalidFiles)" ngf-multiple="false" accept="\'.png,.jpg,.bmp,.gif,jpeg\'" ngf-max-size="5MB">选择图片</button> <button class="btn btn-primary" style="width:88px" ng-click="ok()">确定</button></div></div></div>');
      }
]);