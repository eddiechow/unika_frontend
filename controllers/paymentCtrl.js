app.controller('paymentCtrl', ['$scope', '$http', '$routeParams', '$location', function ($scope, $http, $routeParams, $location) {

    $scope.openModal('progress');

    if($routeParams.orderID && (($routeParams.approved=="true" && $routeParams.paymentId && $routeParams.token && $routeParams.PayerID) || ($routeParams.approved=="false" && $routeParams.token))) {
        $http({
            method:'get', 
            url:'models/payment.php?orderID='+$routeParams.orderID+
                    '&approved='+$routeParams.approved+
                    (($routeParams.paymentId)?'&paymentId='+$routeParams.paymentId:'')+
                    (($routeParams.token)?'&token='+$routeParams.token:'')+
                    (($routeParams.PayerID)?'&PayerID='+$routeParams.PayerID:'')
        }).then(function (response) {
            $scope.message = response.data.message;
            $scope.status = response.data.status;
            $scope.closeModal();
        }); 
    } else {
        $scope.closeModal();
        $location.url(".");       
    }

    
}]);