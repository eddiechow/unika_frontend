app.controller('productDetailCtrl',['$scope', '$http', function($scope, $http) {

    $scope.getProductDetail = function (option){
        $scope.productNotFound = false;

        if(option.category == "package" || option.category == "bottle" || option.category == "perfume"){
            $http({
                method: 'get',
                url: 'models/productDetail.php?category='+option.category+'&productID='+option.productID,
            }).then(function (response) {
                if (response.data != null && response.data.databaseError==null) {
                    $scope.product = response.data;
                    
                } else {
                    $scope.productNotFound = true;
                }
            });
            
        } else {
            $scope.productNotFound = true;
        }
    };

}]);