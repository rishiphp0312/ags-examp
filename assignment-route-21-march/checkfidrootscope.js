var myApp = angular.module('myModule', []);

myApp.controller('myCtrl1', ['$scope','$rootScope', '$http', function($scope, $rootScope, $http) {
    $rootScope.name={fname:"",lname:""};
    
    $rootScope.search = function() {

        var search_url = "/search?fname=" + $rootScope.name.fname + "&lname=" + $rootScope.name.lname;
       alert (search_url);
//      alert("/search?fname=" + $scope.fname + "&lname=" + $scope.lname);
        $http({method:'GET', url:search_url})
        .success(function(data, status, headers, config) {
            $rootScope.status = status;
            $rootScope.data = data;
            $rootScope.headers = headers;
            $rootScope.config = config;
        });
    };
}]);