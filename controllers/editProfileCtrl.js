app.controller('editProfileCtrl', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http) {
    
    $http({
        method: 'get',
        url: 'models/editProfile.php'
    }).then(function(response){
        if(response.data) {
            $http({
                method: 'get',
                url: 'models/ageCode.php'
            }).then(function (response){
                $scope.ageGroups = response.data;
            }); 
            $scope.surname = response.data.surname;
            $scope.givenName = response.data.givenName;
            $scope.gender = response.data.gender;
            $scope.email = response.data.email;
            $scope.regionCode = response.data.regionCode.toString();
            $scope.mobilePhoneNumber = response.data.mobilePhoneNumber;
            $scope.ageGroupCode = response.data.ageGroupCode.toString();;
            $scope.address = response.data.address;
        }
    });
    

    $scope.edit = function () {

        $scope.openModal('progress');
        
        $scope.invalidCurrentPassword = null;
        $scope.invalidPassowrd = null;
        $scope.invalidConfirmPassword = null;
        $scope.invalidSurname = null;
        $scope.invalidGivenName = null;
        $scope.invalidGender = null;
        $scope.invalidEmail = null;
        $scope.invalidRegionCode = null;
        $scope.invalidMobilePhoneNumber = null;
        $scope.invalidAgeGroupCode = null;
        $scope.invalidAddress = null;
                
        $scope.mainError = null;
        $scope.success = null;

        $http({
            method: 'post',
            url: 'models/editProfile.php?action=edit',
            data: {
                currentPassword: $scope.currentPassword,
                password: $scope.password,
                confirmPassword: $scope.confirmPassword,
                surname: $scope.surname,
                givenName: $scope.givenName,
                gender: $scope.gender,
                email: $scope.email,
                regionCode: $scope.regionCode,
                mobilePhoneNumber: $scope.mobilePhoneNumber,
                ageGroupCode: $scope.ageGroupCode,
                address: $scope.address
            }
        }).then(function (response){
            var data = response.data;
            if(data.error){
                $scope.invalidCurrentPassword = data.error.invalidCurrentPassword;
                $scope.invalidPassowrd = data.error.invalidPassowrd;
                $scope.invalidConfirmPassword = data.error.invalidConfirmPassword;
                $scope.invalidSurname = data.error.invalidSurname;
                $scope.invalidGivenName = data.error.invalidGivenName;
                $scope.invalidGender = data.error.invalidGender;
                $scope.invalidEmail = data.error.invalidEmail;
                $scope.invalidRegionCode = data.error.invalidRegionCode;
                $scope.invalidMobilePhoneNumber = data.error.invalidMobilePhoneNumber;
                $scope.invalidAgeGroupCode = data.error.invalidAgeGroupCode;
                $scope.invalidAddress = data.error.invalidAddress;
                
                $scope.mainError = data.error.main; 

            } else if(data.success) {
                $scope.success = data.success;
                if(data.success.emailChanged)
                    $scope.loggedInCustomer().activated = false;
            }
            
            $scope.currentPassword = "";
            $scope.password = "";
            $scope.confirmPassword = "";
            
            $scope.closeModal();
        });  


    };

}]);


