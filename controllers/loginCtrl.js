app.controller('loginCtrl', ['$location', '$scope', '$http',  function ($location, $scope, $http) {
        
    $scope.waitting = false;

    $scope.login = function () {

        $scope.error = null;
        
        $scope.waitting = true;

        $http({
            method: 'post',
            url: 'models/logging.php?action=login',
            data: {
                email: $scope.loginEmail,
                password: $scope.loginPassword
            }
        }).then(function (response) {
            $scope.loginEmail = null;
            $scope.loginPassword = null;
            if (response.data.error) {
                $scope.error = response.data.error;
                $scope.waitting = false;
            } else {

                if ($location.path() == '/register')
                    $location.path('/');
                else if ($scope.option.locationPath != null)
                    $location.path($scope.option.locationPath);
                else {
                    $scope.updateCustStatus($scope.option.func);
                }

                $scope.closeModal();
            }

        });
    };
}]);
