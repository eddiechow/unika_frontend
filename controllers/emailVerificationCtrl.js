app.controller('emailVerificationCtrl', ['$scope', '$http', function ($scope, $http) {

    $http({
        method: 'get',
        url: 'models/emailVerification.php'
    }).then(function (response) {
        if (response.data.error) {
            $scope.openModal('alert', {
                alertMessage: response.data.error,
                hrefAfterClose: ''
            });
        }
    });

    $scope.verifyEmail = function () {
        $http({
            method: 'post',
            url: 'models/emailVerification.php',
            data: {verificationToken: $scope.verificationToken}
        }).then(function (response) {
            var data = response.data;
            if (data.error) {
                if (data.error.databaseError) {
                    $scope.openModal('alert', {alertMessage: data.error.databaseError, hrefAfterClose: ''});
                } else {
                    $scope.error = data.error.verificationTokenError;
                }
            } else if (data.success == true) {
                $scope.openModal('alert', {alertMessage: 'Email Verification Success', hrefAfterClose: (($scope.loggedInCustomer()==null)?'':null)});
                if($scope.loggedInCustomer()!=null){
                    $scope.loggedInCustomer().activated = true;
                }
            }
        });
    };
    
    $scope.close = function () {
        $http({ method: 'get', url: 'models/emailVerification.php?action=closeModal'});
        $scope.openModal('alert',{alertMessage:(($scope.loggedInCustomer()==null)?'Please try again after login.':'Please try again later.'), hrefAfterClose:(($scope.loggedInCustomer()==null)?'':null)});
    };

}]);