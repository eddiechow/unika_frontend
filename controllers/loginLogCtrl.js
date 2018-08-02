app.controller('loginLogCtrl', ['$rootScope', '$scope', '$http', '$filter',  function ($rootScope, $scope, $http, $filter) {
    
    $scope.openModal('progress');
    
    var dateForm = new Date();
    dateForm.setTime($scope.getTimeNow());
    dateForm.setMonth(dateForm.getMonth() - 1);
    dateForm.setHours(24, 00, 00, 000);

    var dateTo = new Date();
    dateTo.setTime($scope.getTimeNow());
    dateTo.setHours(23, 59, 59, 999);

    var searchStatus = '', searchDateFrom = dateForm, searchDateTo = dateTo;

    $scope.search = {
        status: searchStatus,
        dateFrom: searchDateFrom,
        dateTo: searchDateTo
    };
    
    $http({
        method: 'get',
        url: 'models/loginLog.php'
    }).then(function (response) {
        if(!response.data.error){
            var logs = response.data;
            
            $scope.currentPage = 0;
            $scope.pageSize = 20;
            
            var search = function (array) {
                if($scope.dateRangeAll) {
                    return ((array.status).toLowerCase()).includes(searchStatus.toLowerCase());
                } else {    
                    return ((array.status).toLowerCase()).includes(searchStatus.toLowerCase()) &&
                    array.date >= (searchDateFrom.getTime() / 1000) && array.date <= (searchDateTo.getTime() / 1000);     
                }

            };
            
            $scope.logFilter = function (search) {
                searchStatus = search.status;

                search.dateFrom.setHours(00, 00, 00, 000);
                search.dateTo.setHours(23, 59, 59, 999);

                searchDateFrom = search.dateFrom;
                searchDateTo = search.dateTo;
                $scope.currentPage = 0;
            };
            
            $scope.getLogs = function () {
                return $filter('filter')(logs, search);
            };
            
            $scope.numberOfPages = function () {
                return Math.ceil($scope.getLogs().length / $scope.pageSize);
            };

            $scope.pageList = function () {
                var pages = [];
                for (var i = 1; i <= $scope.numberOfPages(); i++) {
                    pages.push(i);
                }
                return pages;
            };
            
            $scope.goToPreviousPage = function () {
                if ($scope.currentPage > 0) {
                    $scope.changeToPage(($scope.currentPage + 1) - 1);
                }
            };

            $scope.goToNextPage = function () {
                if ($scope.currentPage < ($scope.numberOfPages() - 1)) {
                    $scope.changeToPage(($scope.currentPage + 1) + 1);
                }
            };

            $scope.goToFirstPage = function () {
                if ($scope.currentPage > 0) {
                    $scope.changeToPage(1);
                }
            };

            $scope.goToLastPage = function () {
                if ($scope.currentPage < ($scope.numberOfPages() - 1)) {
                    $scope.changeToPage($scope.numberOfPages());
                }
            };

            $scope.changeToPage = function (page) {
                $scope.currentPage = page - 1;
                document.getElementById('pagination-dropdown-content').scrollTop = 0;
            };

            $scope.pageDropdownToggle = function () {
                if(!$rootScope.showPageDropdown)
                    $rootScope.closeAll();
                $rootScope.showPageDropdown = !$rootScope.showPageDropdown;
                event.stopPropagation();
            };
            
            $scope.closeModal();
        }
    });

}]);