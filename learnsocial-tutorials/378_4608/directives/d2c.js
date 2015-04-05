var app = angular.module('mainApp', []);

app.controller("appCtrl", ['$scope',function ($scope) {
    $scope.message = function(){
    	alert("loading");
    };
}]);

app.directive("enter", function () {
    return function(scope, element, attr){
    	element.on("mouseenter", function(){
    		// scope.message();
    		// scope.$apply("message()");
    		// scope.$apply(attr.enter);
            console.log(attr.enter);
    	});
    };
});