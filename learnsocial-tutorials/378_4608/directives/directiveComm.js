var app = angular.module('myApp', []);

app.directive("mainCategory", function () {
	return {
		controller: function($scope){
			this.postMessage=function(msg){
				console.log("Main Category: " + msg);
			};
			$scope.postMessage = this.postMessage;
		},
		link:function(scope, ele, attr){
			// console.log(scope);
			scope.postMessage("testing...");
		}
	}
});

app.directive("subCategory", function () {
	return {
		require:"^mainCategory",
		controller: function(){
			this.postMessage=function(msg){
				console.log("Sub Category: " + msg);
			};	
		},
		link:function(scope, ele, attr, requireCtrl){
			requireCtrl.postMessage("Hello");
		}
	}
});

app.directive("subSubCategory", function () {
	return {
		require:["^mainCategory", "^subCategory"],
		controller: function(){
			
		},
		link:function(scope, ele, attr, requireCtrl){
			requireCtrl[0].postMessage("Hello Main.");
			requireCtrl[1].postMessage("Hello Sub.");
		}
	}
});