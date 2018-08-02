app.controller('contactusCtrl', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {
        
    var center = new google.maps.LatLng(22.312448, 114.219337);
    var mapOptions = {
        zoom: 17,
        center: center
    };
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    var infowindow = new google.maps.InfoWindow({
        content: '<b>PIMS Service Hong Kong Limited</b><br>Unit 1802, 18/F, Tamsin Plaza, <br>No.161 Wai Yip Street, <br>Kwun Tong, Hong Kong'
    });   
    var marker = new google.maps.Marker({
        position: center,
        map: map
    });
    marker.addListener('click', function () {
        infowindow.open(map, marker);
    });
    
    window.setTimeout(function(){
        google.maps.event.trigger(map, 'resize');
        map.setCenter(center);
     }, 100);
	 

	$http({
		method: 'get',
		url: 'models/contactus.php'
	}).then(function (response) {
		$scope.name = response.data.name;
		$scope.email = response.data.email;
	});


    $scope.send = function() {
        $scope.error = null;
        $scope.success = null;
        
        $scope.openModal('progress');
        
        $http({
            method: 'post',
            url: 'models/contactus.php',
            data: {
                name: $scope.name,
                email: $scope.email,
                message: $scope.message
            }
        }).then(function (response) {
            if(response.data.error)
                $scope.error = response.data.error;
            else {
                $scope.success = response.data.success;
                
                if(!$scope.loggedInCustomer()){
                    $scope.name = null;
                    $scope.email = null;                    
                }

                $scope.message = null;
            }
            $scope.closeModal();
        });
    };

}]);