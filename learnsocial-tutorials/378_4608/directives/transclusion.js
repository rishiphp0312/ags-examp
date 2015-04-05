var app = angular.module('myApp', []);

app.controller("appCtrl", function ($scope) {

});

app.directive("panel", function () {
	return {
	  transclude: true,
	  template: '<div>This is a panel component </div><ng-transclude></ng-transclude>'
	}
});