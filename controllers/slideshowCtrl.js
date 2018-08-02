app.controller('slideshowCtrl', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

    $http({
        method:'get', 
        url:'models/slideshow.php'
    }).then(function (response) {
        $scope.slideshow = response.data;
        
        $scope.slideshowIndex = 1;
        $scope.slideshowDisplay = [];
        
        $scope.showDivs = function(n) {
            $scope.slideshowIndex = n;
            if($scope.slideshow.length < n) $scope.slideshowIndex = 1;
            if(1 > n) $scope.slideshowIndex = $scope.slideshow.length;
            
            for (var i = 0; i < $scope.slideshow.length; i++) {
                $scope.slideshowDisplay[i] = false;
            }
            $scope.slideshowDisplay[$scope.slideshowIndex-1] = true;
        };
        
        $scope.showDivs($scope.slideshowIndex);
        
        if($scope.slideshow.length>1){
            var countUp = function() {
                $scope.clickLeft = false;
                $scope.showDivs($scope.slideshowIndex + 1);
                $timeout(countUp, 12000);
            };

            $timeout(countUp, 12000);            
        }
    });
    
}]);