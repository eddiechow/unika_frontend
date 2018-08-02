app.controller('mainCtrl', ['$scope', '$http', '$rootScope', '$timeout', function($scope, $http, $rootScope, $timeout){
    
    $http({
        method: 'get',
        url: 'models/main.php'
    }).then(function (response) {
        if(response.data != null){
            $scope.discount = response.data.discount;
            
            $scope.responseData = [];
            $scope.selected = [];
            $scope.showPerfumeSelection = [];

            $scope.counter = [];
            $scope.onTimeout = [];
            var thisTimeout = [];
            
            
            $rootScope.addToCart = function (category, productID) {
                
                $scope.cartError = null;

                if(!$scope.showPerfumeSelection[productID] && category=="perfume"){
                    $scope.showPerfumeSelection[productID] = true;
                } else {
                    $http({
                        method: 'post',
                        url: 'models/cart.php?action=add',
                        data: {
                            category: category,
                            productID: productID,
                            quantity: $scope.selected[productID].quantity,
                            note: $scope.selected[productID].note
                        }
                    }).then(function (response) {

                        if(category=="perfume" && (!$scope.selected[productID].quantity || !$scope.selected[productID].note)){
                            $scope.responseData[productID] = {messageBoxShow:true, error:"Please select note"};
                        } else if(category=="perfume" && !($scope.selected[productID].quantity > 0 && $scope.selected[productID].quantity <= 200)) {
                            $scope.responseData[productID] = {messageBoxShow:true, error:"Please select quantity 1 to 200 mL"};
                        } else {

                            if ($scope.loggedInCustomer() != null) {

                                $scope.responseData[productID] = response.data;
                                $scope.responseData[productID].messageBoxShow = true;

                                if (response.data.sucess)
                                    $scope.loggedInCustomer().itemNumInCart = $scope.loggedInCustomer().itemNumInCart + 1;

                                if($scope.responseData[productID].cartError) {
                                    $scope.cartError = $scope.responseData[productID].cartError;
                                    $scope.responseData[productID].error =  $scope.responseData[productID].cartError;
                                }

                                if(category=="perfume"){
                                    $scope.selected[productID].quantity = 1;
                                    $scope.selected[productID].note = null;
                                    $scope.showPerfumeSelection[productID] = false;
                                }

                            } else {
                                var func = function () {
                                    $rootScope.addToCart(category, productID);
                                };
                                $scope.openModal('other', {template: 'login.tpl', width: '350px', headerTitle: 'Login', func: func});
                            }

                        }

                        if($scope.responseData[productID]){
                            $scope.counter[productID] = 2;
                            $scope.onTimeout[productID] = function () {
                                $scope.counter[productID]--;
                                if ($scope.counter[productID] == 0) {
                                    $timeout.cancel(thisTimeout[productID]);
                                    $scope.responseData[productID].messageBoxShow = false;
                                    $scope.counter[productID] = 2;
                                } else {
                                    thisTimeout[productID] = $timeout($scope.onTimeout[productID], 1000);
                                }
                            };
                            thisTimeout[productID] = $timeout($scope.onTimeout[productID], 1000);                      
                        }   

                    });                    
                }

            };
        }
    });


}]);