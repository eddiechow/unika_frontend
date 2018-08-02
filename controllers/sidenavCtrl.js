app.controller('sidenavCtrl', ['$scope', '$rootScope', '$window', function ($scope, $rootScope, $window) {

    $scope.sidebarToggle = function () {
        $scope.showSidebar = !$scope.showSidebar;
    };
    $scope.closeSidebar = function () {
        $scope.showSidebar = false;
        $scope.showAccordionDropdown = false;
    };
    
    $scope.productDropdownToggle = function () {
        if(!$scope.showProductDropdown)
            $rootScope.closeAll();
        $scope.showProductDropdown =! $scope.showProductDropdown;
        event.stopPropagation();
    };
    
    $scope.accountDropdownToggle = function () {
        if(!$scope.showAccountDropdown)
            $rootScope.closeAll();
        $scope.showAccountDropdown =! $scope.showAccountDropdown;
        event.stopPropagation();
    };
    
    $rootScope.closeAll = function () {
        if($rootScope.showStatusDetailOrderID && $rootScope.showStatusDetail[$rootScope.showStatusDetailOrderID]){
            $rootScope.showStatusDetail[$rootScope.showStatusDetailOrderID] = false;
            $rootScope.showStatusDetailOrderID = null;
        }
        if ($rootScope.showPageDropdown)
            $rootScope.showPageDropdown = false;
        if ($scope.showProductDropdown)
            $scope.showProductDropdown = false;
        if ($scope.showAccountDropdown)
            $scope.showAccountDropdown = false;
    };
    
    window.onclick = function () {
        $scope.closeAll();
        
        $rootScope.$apply();
        $scope.$apply();
    };

    $window.onresize = function(){
        $rootScope.topMenuHeight = document.querySelector("#top-menu").offsetHeight;
        $rootScope.footerHeight = document.querySelector("footer").offsetHeight;
        $rootScope.$apply();
    };
   
}]);