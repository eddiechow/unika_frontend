app.controller('modalCtrl', ['$scope', '$rootScope', '$location', '$window', function($scope, $rootScope, $location, $window){
    
    var ngModalEl = angular.element(document.getElementById('modal'));
    var ngModalContentEl = angular.element(document.getElementById('modal-content')); 
    var version = new Date().getTime();
    
    $scope.template = null;
    
    $rootScope.openModal = function(type, option){
        $scope.option = option;
        if(!ngModalEl.hasClass('active')){
            ngModalEl.addClass('active');
        }
        if(!ngModalContentEl.hasClass('deactivate')){
            ngModalContentEl.addClass('deactivate');
        }
        if(ngModalContentEl.hasClass('progress')){
            ngModalContentEl.removeClass('progress');
        }
        if(type=="alert"){
            document.getElementById('modal-content').style.width="350px";
            document.getElementById('modal-content').style.textAlign="left";
            if($scope.option.hrefAfterClose==null){
                $scope.href = null;
            } else {
                $scope.href = $scope.option.hrefAfterClose;
            }     
            $scope.template = 'templates/modals/alert.tpl?version='+version;
        } else if(type=="progress"){
            ngModalContentEl.addClass('progress');
            $scope.template = 'templates/modals/progress.tpl?version='+version;
        } else if(type=="other"){
            if(!angular.isUndefined($scope.option.width) && $scope.option.width != null )
                document.getElementById('modal-content').style.width=$scope.option.width;
            if(!angular.isUndefined($scope.option.maxWidth) && $scope.option.maxWidth != null )
                document.getElementById('modal-content').style.maxWidth=$scope.option.maxWidth;
            
            document.querySelector("#modal-content div.w3-container").style.maxHeight = ($window.innerHeight - 110) +'px';
            
            $window.onresize = function(){
                document.querySelector("#modal-content div.w3-container").style.maxHeight = ($window.innerHeight - 110) +'px';
            };
            $scope.template = 'templates/modals/'+$scope.option.template+'?version='+version;
        } 
        ngModalContentEl.removeClass('deactivate');
    };
   
    $rootScope.closeModal = function(href){
        document.getElementById('modal-content').style.width=null;
        document.getElementById('modal-content').style.maxWidth=null;
        if(ngModalContentEl.hasClass('progress')){
            ngModalContentEl.removeClass('progress');
        }
        ngModalEl.removeClass('active');
        if(href!=null){
            $location.path(href);
        }
        $scope.template = null;
    };

}]);