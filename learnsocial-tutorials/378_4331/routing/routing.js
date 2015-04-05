var app = angular.module("routingSampleApp", ["ngRoute"]);

app.config(function ($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "templates/landingPage.htm",
            controller: "landingPageCtrl"
        })
        .when("/books", {
            templateUrl: "templates/books.htm",
            controller: "booksCtrl"
        })
        .when("/music", {
            templateUrl: "templates/music.htm",
            controller: "musicCtrl",
            resolve: {
               loadingData: function ($q, $timeout, $http) {
                   var defer = $q.defer();
                   console.log(Date.now());
                   $http.get("http://api.openweathermap.org/data/2.5/weather?q=Mumbai,in")
                   // $http.get("http://api.openweathermapzz.org/data/2.5/weather?q=Mumbai,in")
                       .success(
                            function (data, status, headers, config) {
                               console.log(data);
                                // defer.resolve({"message" : "Got data"});
                                defer.resolve("Got data");
                               console.log("got Data at : " + Date.now());
                           }
                       )
                       .error(function (data, status, headers, config) {
                            defer.reject("Could not get the data");
                           console.log("failed");
                       })
                   console.log(Date.now());
                   return defer.promise;
               }
            }
        })
        // .when("/electronics", {
        .when("/electronics/:type/:subType", {
            templateUrl: "templates/electronics.htm",
            controller: "electronicsCtrl",

            //redirectTo: "/"

            redirectTo: function (routeParams, path, search) {
               console.log(routeParams);
               console.log(path);
               console.log(search);
               return "/";
            }
        })
        .otherwise({
            redirectTo:"/"
        });
    //console.log(routeParams, path, search);
});

app.controller("appCtrl", function ($scope, $rootScope) {
    //#2
    $rootScope.$on("$routeChangeStart", function (event, next, current) {
        console.log("route Change Success!");
        console.log(event, next, current);
    });

    //#3
    $rootScope.$on("$routeChangeSuccess", function (event, current, previous) {
        console.log("route Change Success!");
        console.log(event, current, previous);
    });
    $rootScope.$on("$routeChangeError", function (event, current, previous, rejection) {
        console.log(event, current, previous, rejection);
    });
});

app.controller("landingPageCtrl", function ($scope) {
    $scope.test= "123";
});

app.controller("booksCtrl", function ($scope) {

});

//#4
// app.controller("musicCtrl", function ($scope, loadingData) {
app.controller("musicCtrl", function ($scope) {
    console.log("On Music Ctrl");
    // console.log(loadingData);
});

app.controller("electronicsCtrl", function ($scope) {

});