app.controller('forgetPasswordCtrl', ['$scope', '$http',  function ($scope, $http) {

    $scope.sendSecurityToken = function () {
        
        $scope.waitting = true;
        
        $scope.error = null;
        $scope.seccess = null;
        
        $http({
            method: 'post',
            url: 'models/forgetPassword.php?action=sendSecurityToken',
            data: {
                email: $scope.email
            }
        }).then(function (response) {
            if (response.data.error) {
                $scope.error = response.data.error;
            } else {
                $scope.seccess = response.data.seccess;
            }
            $scope.waitting = false;
        });
    };
    
    $scope.resetPassword = function () {
        
        $scope.error = null;
        
        $http({
            method: 'post',
            url: 'models/forgetPassword.php?action=resetPassword',
            data: {
                securityToken: $scope.securityToken,
                newPassword: $scope.newPassword,
                confirmPassword:  $scope.confirmPassword
            }
        }).then(function (response) {
            if(response.data.error) {
                $scope.error = response.data.error;
            } else if (response.data.success.final) {
                $scope.openModal('alert', {alertMessage: 'Password is changed'});
            }
            $scope.waitting = false;
        });
    };
    
    $scope.close = function () {
        $http({ method: 'get', url: 'models/forgetPassword.php?action=closeModal'});
        $scope.closeModal();
    };
}]);