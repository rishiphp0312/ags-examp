/*
 * Angular.JS definitions
 */
var app = angular.module("myApp", ["ngResource"]);
app.config(configFunc);
app.controller("mainCtrl", ["$scope", "myResource", mainCtrlFunc]);
app.factory('myResource', function($resource) {
  return $resource('https://api.github.com/repos/angular/angular.js/:category/:sha');
});


/*
 * Javascript business logic    
 */
function configFunc($resourceProvider) {
  // $resourceProvider.defaults.stripTrailingSlashes = false;
};

function mainCtrlFunc($scope, myResource) {
    var output = myResource.get({category: "commits", sha: "41428477ed15d73fc7e93bd80e6eafe6b8fc6aec"});
    // var output = myResource.query({category: "commits"});
    console.log(output);

// output.name = "Mike Smith";
// output.$save();
};