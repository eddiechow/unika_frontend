app.controller('ordersCtrl', ['$scope', '$rootScope', '$http', '$filter', function ($scope, $rootScope, $http, $filter) {

    $scope.openModal('progress');
    
    $http({
        method:'get', 
        url:'models/orders.php'
    }).then(function (response) {
        if(!response.data.error) {
            var orders = response.data;
            
            var statusList = [{approved:'Approving', status:'Unpaid'},
                              {approved:'Rejected', status:'Unpaid'},
                              {approved:'Approving', status:'Paid'},
                              {approved:'Rejected', status:'Paid'},
                              {approved:'Rejected', status:'Refunded'},
                              {approved:'Approved', status:'Paid'},
                              {approved:'Approved', status:'Refunded'}];
        
            $scope.currentPage = 0;
            $scope.pageSize = 12;
            
            $rootScope.showStatusDetail = [];
            $rootScope.showStatusDetailOrderID = null;
            
            var dateForm = new Date();
            dateForm.setTime($scope.getTimeNow());
            dateForm.setMonth(dateForm.getMonth() - 1);
            dateForm.setHours(24, 00, 00, 000);

            var dateTo = new Date();
            dateTo.setTime($scope.getTimeNow());
            dateTo.setHours(23, 59, 59, 999);
            
            var searchStatusIndex = null, searchOrderDateFrom = dateForm, searchOrderDateTo = dateTo;
            
            var search = function (array) {
                if(!$scope.orderDateRangeAll) {
                    return ((searchStatusIndex)?(array.approved==statusList[searchStatusIndex].approved && array.status==statusList[searchStatusIndex].status):((array.approved).includes('') && (array.status).includes(''))) && array.orderDate >= (searchOrderDateFrom.getTime() / 1000) && array.orderDate <= (searchOrderDateTo.getTime() / 1000);
                } else {
                    return (searchStatusIndex)?(array.approved==statusList[searchStatusIndex].approved && array.status==statusList[searchStatusIndex].status):((array.approved).includes('') && (array.status).includes(''));
                }
            };
            
            $scope.orderFilter = function (search) {
                searchStatusIndex = search.statusIndex;
                
                search.orderDateFrom.setHours(00, 00, 00, 000);
                search.orderDateTo.setHours(23, 59, 59, 999);

                searchOrderDateFrom = search.orderDateFrom;
                searchOrderDateTo = search.orderDateTo;

                $scope.currentPage = 0;
            };
            
            $scope.getOrders = function () {
                return $filter('filter')(orders, search);
            };
        
            $scope.numberOfPages = function () {
                return Math.ceil($scope.getOrders().length / $scope.pageSize);
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
            
            $scope.statusDetailToggle = function (orderID) {
                if($rootScope.showStatusDetailOrderID &&
                        $rootScope.showStatusDetailOrderID != orderID &&
                        $rootScope.showStatusDetail[$rootScope.showStatusDetailOrderID]){
                    $rootScope.showStatusDetail[$rootScope.showStatusDetailOrderID] = false;
                    $rootScope.showStatusDetailOrderID = null;
                }
                
                if(!$rootScope.showStatusDetail[orderID])
                    $rootScope.closeAll();
                
                $rootScope.showStatusDetail[orderID] = !$rootScope.showStatusDetail[orderID];
                if(!$rootScope.showStatusDetail[orderID]){
                    $rootScope.showStatusDetailOrderID = null;
                } else if($rootScope.showStatusDetail[orderID]){
                    $rootScope.showStatusDetailOrderID = orderID;
                }
                event.stopPropagation();
            };

        
            $rootScope.orderPayment = function(orderID){
                $scope.openModal('progress', {alertMessage: 'Entering PayPal payment system'});
                $http({ method:'get', url:'models/payment.php?orderID='+orderID}).then(function (response) {
                    if(response.data.error) {
                        $scope.openModal('alert', {alertMessage: response.data.error});
                        if(response.data.rejected){
                            for(var i = 0; i<$scope.getOrders().length; i++){
                                if($scope.getOrders()[i].orderID == orderID){
                                    $scope.getOrders()[i].approved = "Rejected";
                                    break;
                                }
                            }
                        }
                    } else if(response.data.approvalUrl) {
                        location.href = response.data.approvalUrl;
                    }
                });
            };
            
            $scope.search = {
                statusIndex: searchStatusIndex,
                orderDateFrom: searchOrderDateFrom,
                orderDateTo: searchOrderDateTo
            };

            $scope.closeModal();
        } else {
            $scope.error = response.data.error;
            $scope.closeModal();
        }
    });

    
}]);