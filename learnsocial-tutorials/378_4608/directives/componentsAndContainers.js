var app = angular.module('myApp', []);
app.directive("myContainer", function () {
	return {
		transclude: true,
		scope:{
			text:"@"
		},
		template: '<table border="1"><tr><td>{{text}}</td></tr><tr><td><ng-transclude></ng-transclude></td></tr></table>'
	}
});
app.directive("myComponent", function () {
	return {
		transclude: true,
		scope:{
			text:"@"
		},
		template: '<div>The sample text is {{text}}</div>'
	}
});