/*
 * Angular.JS definitions
 */
var app = angular.module("myApp", []);
app.config(configFunc);
app.controller("mainCtrl", ["$scope", "$http", mainCtrlFunc]);


/*
 * Javascript business logic    
 */
function configFunc() {
};

function mainCtrlFunc($scope, $http) {
    var correctURL = "http://api.openweathermap.org/data/2.5/weather?q=Mumbai,in",
        wrongURL = "http://api.openweathermapp.org/data/2.5/weather?q=Mumbai,in";

    $http.get(wrongURL).
    success(function (data, status, headers, config) {
        console.log("SUCCESS");
        console.log(data);
        console.log(status);
        console.log(headers());
        console.log(config);
    }).
    error(function (data, status, headers, config) {
        console.log("ERROR");
        console.log(data);
        console.log(status);
        console.log(headers());
        console.log(config);
    });
};