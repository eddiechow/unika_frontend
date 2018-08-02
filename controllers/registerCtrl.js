app.controller('registerCtrl', ['$scope', '$location', '$http', function($scope, $location, $http){
    

    $http({
        method: 'get',
        url: 'models/ageCode.php'
    }).then(function (response){
        $scope.ageGroups = response.data;
    }); 
   
    $scope.register = function () {
     
        $scope.openModal('progress');

        $scope.invalidSurname = null;
        $scope.invalidGivenName = null;
        $scope.invalidGender = null;
        $scope.invalidEmail = null;
        $scope.invalidPassowrd = null;
        $scope.invalidConfirmPassword = null;
        $scope.invalidRegionCode = null;
        $scope.invalidMobilePhoneNumber = null;
        $scope.invalidAgeGroupCode = null;
        $scope.invalidAddress = null;
        
        $http({
            method: 'post',
            url: 'models/register.php',
            data: {
                surname: $scope.surname,
                givenName: $scope.givenName,
                gender: $scope.gender,
                email: $scope.email,
                password: $scope.password,
                confirmPassword: $scope.confirmPassword,
                regionCode: $scope.regionCode,
                mobilePhoneNumber: $scope.mobilePhoneNumber,
                ageGroupCode: $scope.ageGroupCode,
                address: $scope.address
            }
        }).then(function (response){
            var data = response.data;
            if(data.error){
                $scope.invalidSurname = data.error.invalidSurname;
                $scope.invalidGivenName = data.error.invalidGivenName;
                $scope.invalidGender = data.error.invalidGender;
                $scope.invalidEmail = data.error.invalidEmail;
                $scope.invalidPassowrd = data.error.invalidPassowrd;
                $scope.invalidConfirmPassword = data.error.invalidConfirmPassword;
                $scope.invalidRegionCode = data.error.invalidRegionCode;
                $scope.invalidMobilePhoneNumber = data.error.invalidMobilePhoneNumber;
                $scope.invalidAgeGroupCode = data.error.invalidAgeGroupCode;
                $scope.invalidAddress = data.error.invalidAddress;
                $scope.mainError = data.error.main; 

                $scope.closeModal();

            } else if(data.success==true) {
                $scope.openModal('other',{template:'emailVerification.tpl',width:'400px'});
            }   

        });  

    };

}]);