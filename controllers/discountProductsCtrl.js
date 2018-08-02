app.controller('discountProductsCtrl', ['$rootScope', '$scope', '$http', '$routeParams', '$filter', '$timeout', '$location', function ($rootScope, $scope, $http, $routeParams, $filter, $timeout, $location) {

    $http({
        method: 'get',
        url: 'models/discountProducts.php?code=' + $routeParams.code
    }).then(function (response) {
        if (response.data != null) {
            if(response.data.discountTitle){
                $scope.discountTitle = response.data.discountTitle;
                $scope.discount = response.data.discount;
                
                var products = response.data.products;
                
                $scope.responseData = [];
                $scope.selected = [];
                $scope.showPerfumeSelection = [];

                $scope.counter = [];
                $scope.onTimeout = [];
                var thisTimeout = [];

                $scope.shownPerfumeCategoryName = null;

                $scope.currentPage = 0;
                $scope.pageSize = 12;

                var dateForm = new Date();
                dateForm.setTime($scope.getTimeNow());
                dateForm.setFullYear(dateForm.getFullYear() - 1);
                dateForm.setHours(24, 00, 00, 000);

                var dateTo = new Date();
                dateTo.setTime($scope.getTimeNow());
                dateTo.setHours(23, 59, 59, 999);

                var searchProductName = '', searchCategory = '', searchPerfumeCategoryCode = '',
                    searchPriceFrom = 1, searchPriceTo = 300, searchReleaseDateFrom = dateForm, searchReleaseDateTo = dateTo;


                var search = function (array) {
                    if($scope.releaseDateRangeAll) {
                        if (searchCategory == 'perfume')
                            return (array.nowPrice >= searchPriceFrom && array.nowPrice <= searchPriceTo &&
                                    (array.productName.toLowerCase()).includes(searchProductName.toLowerCase()) &&
                                    ((searchCategory=='')?(array.category).includes(''):array.category == searchCategory) &&
                                    ((searchPerfumeCategoryCode=='')?(array.perfumeCategoryCode).includes(''):array.perfumeCategoryCode == searchPerfumeCategoryCode)); 
                        else
                            return (array.nowPrice >= searchPriceFrom && array.nowPrice <= searchPriceTo &&
                                    (array.productName.toLowerCase()).includes(searchProductName.toLowerCase()) &&
                                    ((searchCategory=='')?(array.category).includes(''):array.category == searchCategory)); 
                    } else {
                        if (searchCategory == 'perfume')
                            return (array.nowPrice >= searchPriceFrom && array.nowPrice <= searchPriceTo &&
                                (array.productName.toLowerCase()).includes(searchProductName.toLowerCase()) &&
                                ((searchCategory=='')?(array.category).includes(''):array.category == searchCategory) &&
                                ((searchPerfumeCategoryCode=='')?(array.perfumeCategoryCode).includes(''):array.perfumeCategoryCode == searchPerfumeCategoryCode) &&
                                array.releaseDate >= (searchReleaseDateFrom.getTime() / 1000) && array.releaseDate <= (searchReleaseDateTo.getTime() / 1000));   
                        else
                            return (array.nowPrice >= searchPriceFrom && array.nowPrice <= searchPriceTo &&
                                (array.productName.toLowerCase()).includes(searchProductName.toLowerCase())&&
                                ((searchCategory=='')?(array.category).includes(''):array.category == searchCategory) &&
                                array.releaseDate >= (searchReleaseDateFrom.getTime() / 1000) && array.releaseDate <= (searchReleaseDateTo.getTime() / 1000));
                    }

                };

                $scope.productFilter = function (search) {
                    searchProductName = search.productName;

                    if (search.priceFrom > search.priceTo || search.priceTo <= 0 || angular.isUndefined(search.priceTo) || search.priceTo === null)
                        $scope.search.priceTo = searchPriceFrom;

                    if (search.priceFrom <= 0 || angular.isUndefined(search.priceFrom) || search.priceFrom === null)
                        $scope.search.priceFrom = searchPriceFrom;

                    searchPriceFrom = search.priceFrom;
                    searchPriceTo = search.priceTo;

                    $scope.shownPerfumeCategoryName = search.perfumeCategoryName;

                    searchCategory = search.category;
                    searchPerfumeCategoryCode = search.perfumeCategoryCode;

                    search.releaseDateFrom.setHours(00, 00, 00, 000);
                    search.releaseDateTo.setHours(23, 59, 59, 999);

                    searchReleaseDateFrom = search.releaseDateFrom;
                    searchReleaseDateTo = search.releaseDateTo;
                    $scope.currentPage = 0;
                };

                $scope.getProducts = function () {
                    return $filter('filter')(products, search);
                };

                $scope.numberOfPages = function () {
                    return Math.ceil($scope.getProducts().length / $scope.pageSize);
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

                $scope.search = {
                    productName: searchProductName,
                    priceFrom: searchPriceFrom,
                    priceTo: searchPriceTo,
                    releaseDateFrom: searchReleaseDateFrom,
                    releaseDateTo: searchReleaseDateTo,
                    category: searchCategory,
                    perfumeCategoryCode: searchPerfumeCategoryCode
                };

                $rootScope.addToCart = function (category, productID) {

                    $rootScope.cartError = null;

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

                $scope.checkQtySelection = function (productID, quantity) {
                    if (quantity < 1 || angular.isUndefined(quantity) || quantity === null)
                        $scope.selected[productID].quantity = 1;
                };               
            } else {
                $location.path('.');
            }



        } else {
            $scope.noItem = false;
        }
    });
}]);


