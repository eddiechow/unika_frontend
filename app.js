'use strict';

var app = angular.module('unika', ['ngRoute']);

app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    var version = new Date().getTime();
    $routeProvider
            .when("/", {
                templateUrl: "templates/main.tpl?version="+version,
                authenticate: false
            })
            .when("/register", {
                templateUrl: "templates/register.tpl?version="+version,
                controller: "registerCtrl",
                authenticate: false
            })
            .when("/discount-product/:code", {
                templateUrl: "templates/discount-products.tpl?version="+version,
                controller: "discountProductsCtrl",
                authenticate: false
            })
            .when("/product/:category", {
                templateUrl: "templates/products.tpl?version="+version,
                controller: "productsCtrl",
                authenticate: false
            })
            .when("/product/:category/:perfumeCategory", {
                templateUrl: "templates/products.tpl?version="+version,
                controller: "productsCtrl",
                authenticate: false
            })
            .when("/cart", {
                templateUrl: "templates/cart.tpl?version="+version,
                controller: "cartCtrl",
                authenticate: true
            })
            .when("/edit-profile", {
                templateUrl: "templates/edit-profile.tpl?version="+version,
                controller: "editProfileCtrl",
                authenticate: true
            })
            .when("/payment", {
                templateUrl: "templates/payment.tpl?version="+version,
                controller: "paymentCtrl",
                authenticate: true
            })
            .when("/login-log", {
                templateUrl: "templates/login-log.tpl?version="+version,
                controller: "loginLogCtrl",
                authenticate: true
            })
            .when("/orders", {
                templateUrl: "templates/orders.tpl?version="+version,
                controller: "ordersCtrl",
                authenticate: true
            })
            .when("/about", {
                templateUrl: "templates/about.tpl?version="+version,
                authenticate: false
            })
            .when("/contactus", {
                templateUrl: "templates/contactus.tpl?version="+version,
                controller: "contactusCtrl",
                authenticate: false
            })
            .otherwise({
                redirectTo: "/"
            });
    $locationProvider.html5Mode(true);
    
}]).run(['$rootScope', '$http', '$location', '$route', function ($rootScope, $http, $location, $route) {
    
    var previousPath = null;
    var customer = null;
    var showView = false;
    
    var needTologin = false;
    var loginToPage = null;
    var previousTopMenuHeight = null;
    var previousFooterHeight = null;
    
    var dbError = undefined;

    $rootScope.loggedInCustomer = function () {
        return customer;
    };

    $rootScope.getShowView = function (){
        return showView;
    };

    $rootScope.getDbError = function (){
        return dbError;
    };
    
    $rootScope.updateCustStatus = function (func){
        $http({
            method: 'get',
            url: 'models/logging.php?action=getLoggedInAccount'
        }).then(function (response) {
            var responseData = response.data;
            if (responseData.databaseError == null) {
                customer = responseData.loggedInCustomer;
                if(customer==null && $route.current.authenticate) {
                    $location.path('/');
                } else if(customer!=null && $location.path()=='/register') {
                    $location.path('/');
                } else if($location.path()=='/contactus') {
                    $route.reload();
                }
                if (func != null)
                    func();   
            } else {
                dbError = true;
            }
        });
    };
    

    $rootScope.$on('$routeChangeStart', function () {
        
        showView = false;
        dbError = undefined;
        $http({
            method: 'get',
            url: 'models/logging.php?action=getLoggedInAccount'
        }).then(function (response) {
            var data = response.data;
            customer = data.loggedInCustomer;
            if (data.databaseError != null) {
                dbError = true;
            } else {
                dbError = false;
                
                if(needTologin) {
                    $rootScope.openModal('other', {template: 'login.tpl', width: '350px', headerTitle: 'Login', locationPath: loginToPage});
                    needTologin = false;
                    loginToPage = null;
                }
                
                if(customer==null && $route.current.authenticate) {
                    if(previousPath){
                        needTologin = true;
                        loginToPage = $location.path();
                        $location.path(previousPath);
                    } else if(!previousPath)
                        $location.path('/');
                } else if(customer!=null && $location.path()=='/register') {
                    $location.path('/');
                } else {
                    showView = true;
                    previousPath = $location.path();
                }  
            }
            
            $rootScope.getRoutePath = function(){
                return $route.current.originalPath;
            };
            
            $rootScope.getTimeNow =  function () {
                var dateForm = new Date();
                return (dateForm.getTime() + (dateForm.getTimezoneOffset() * 60000)) + (3600000*(+8));
            };
            
            $http({
                method: 'get',
                url: 'models/perfumeCategory.php'
            }).then(function (response) {
                $rootScope.perfumeCategories = response.data;
            });    
            
        });

        
        if(needTologin){
            $rootScope.topMenuHeight = previousTopMenuHeight;
            $rootScope.footerHeight = previousFooterHeight;
        } else {
            $rootScope.topMenuHeight = document.querySelector("#top-menu").offsetHeight;
            $rootScope.footerHeight = document.querySelector("footer").offsetHeight;
            previousTopMenuHeight = $rootScope.topMenuHeight;
            previousFooterHeight = $rootScope.footerHeight;
        }
    });
    
    $rootScope.logout = function () {
        $http({
            method: 'get',
            url: 'models/logging.php?action=logout'
        }).then(function (response) {
            if (response.data.success) {
                $rootScope.updateCustStatus();
            }
        });
    };
    
}]).filter('startFrom', function () {
    return function (input, start) {
        start = +start;
        return input.slice(start);
    };
});