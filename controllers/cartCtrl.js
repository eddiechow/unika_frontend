app.controller('cartCtrl', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

    $scope.openModal('progress');
    
    $http({
        method: 'get',
        url: 'models/cart.php'
    }).then(function (response) {
        
        
        $scope.package = response.data.package;
        $scope.bottle = response.data.bottle;
        $scope.perfume = response.data.perfume;

        $scope.closeModal();
    });
    
    $scope.editMode = [];

    $scope.getOrderTotal = function () {
        var packageSubTotal = ($scope.package && !$scope.package.soldOut && !$scope.package.nonEnough)?$scope.package.nowPrice:0;
        var bottleSubTotal = ($scope.bottle && !$scope.bottle.soldOut && !$scope.bottle.nonEnough)?$scope.bottle.nowPrice:0;
        var perfumeSubTotal = 0;
        
        if($scope.perfume && $scope.perfume.length > 0)
            for (var i = 0, len = $scope.perfume.length; i < len; i++) {
                perfumeSubTotal = perfumeSubTotal + ($scope.perfume[i].nowPrice * $scope.perfume[i].quantity);
            }
        
        return packageSubTotal + bottleSubTotal + perfumeSubTotal;
    };
    
    $scope.updatePerfume = function (product, selected) {
        
        $scope.success = {};
        $scope.error = {};
        $scope.success.perfume = [];
        $scope.error.perfume = [];
        
        $scope.counter = [];
        $scope.onTimeout = [];
        var thisTimeout = [];
        
        $http({ method:'post', url:'models/cart.php?action=update', data:{category:'perfume',productID:product.productID,quantity:selected.quantity,note:selected.note}}).then(function (response) {
            if(!response.data.error) {
                product.quantity = selected.quantity;
                product.note = selected.note;
                $scope.editMode[product.productID]=false;
                $scope.success.perfume[product.productID] = response.data.sucess;

                $scope.counter[product.productID] = 2;
                $scope.onTimeout[product.productID] = function () {
                    $scope.counter[product.productID]--;
                    if ($scope.counter[product.productID] == 0) {
                        $timeout.cancel(thisTimeout[product.productID]);
                        $scope.success.perfume[product.productID] = null;
                        $scope.counter[product.productID] = 2;
                    } else {
                        thisTimeout[product.productID] = $timeout($scope.onTimeout[product.productID], 1000);
                    }
                };
                thisTimeout[product.productID] = $timeout($scope.onTimeout[product.productID], 1000);   

            } else {
                $scope.error.perfume[product.productID] = response.data.error;
            }
        });
    };
    
    $scope.checkQtySelection = function (){
        if(this.selected.quantity < 1 || angular.isUndefined(this.selected.quantity) || this.selected.quantity === null)
            this.selected.quantity = this.perfume.quantity;
    };

    $scope.delete = function (category, product) {
        
        $scope.success = null;
        $scope.error = null;
        
        if (category == 'package' || category == 'bottle' || category == 'perfume') {
            $http({ method:'post', url:'models/cart.php?action=remove', data:{category:category,productID:product.productID}}).then(function (response) {
                if(!response.data.error) {
                    if (category == 'package') {
                        $scope.package = null;
                        $scope.loggedInCustomer().itemNumInCart = $scope.loggedInCustomer().itemNumInCart - 1;
                        $scope.success = response.data.success;
                    } else if (category == 'bottle') {
                        $scope.bottle = null;
                        $scope.loggedInCustomer().itemNumInCart = $scope.loggedInCustomer().itemNumInCart - 1;
                        $scope.success = response.data.success;
                    } else if (category == 'perfume') {
                        var index = $scope.perfume.indexOf(product);
                        $scope.perfume.splice(index, 1);
                        $scope.loggedInCustomer().itemNumInCart = $scope.loggedInCustomer().itemNumInCart - 1;
                        $scope.success = response.data.success;
                    }
                } else {
                    $scope.error = response.data.error;
                }
            });
        }
    };
    
    $scope.checkout = function() {
        $scope.openModal('progress', {alertMessage: 'Entering PayPal payment system'});
        $http({ method:'post', url:'models/checkout.php'}).then(function (response) {
            if(response.data.error) {
                if (response.data.error.checkout)
                    $scope.openModal('alert', {alertMessage: response.data.error.checkout});
            } else if(response.data.success) {
                if(response.data.success.checkout){
                    $scope.loggedInCustomer().itemNumInCart = 0;
                    $scope.package = null;
                    $scope.bottle = null;
                    while($scope.perfume.length > 0)
                        $scope.perfume.pop();
                    
                    $http({ method:'get', url:'models/payment.php?orderID='+response.data.success.orderID}).then(function (response) {
                        if(response.data.error) {
                            $scope.openModal('alert', {alertMessage: response.data.error});
                        } else if(response.data.approvalUrl) {
                            location.href = response.data.approvalUrl;
                        }
                    });
                }
            }
        });
    };

}]);