var app = angular.module('mainApp', []);

app.controller("appCtrl", ['$scope',function ($scope) {
}]);

app.directive("vehicle", vehicleFunc);

app.directive("car", carFunc);

app.directive("boat", boatFunc);

app.directive("bike", bikeFunc);


function vehicleFunc() {
    return{
        restrict:"E",
        scope:{},
        controller:function($scope){
            $scope.vehicle = {};
            this.addWheels = function(w){
                w=w?w:0;
                $scope.vehicle.wheels=w;
            };
            this.addEngine = function(type){
                $scope.vehicle.engine=type;
            };
            this.addBody = function(){
                $scope.vehicle.body=true;
            };
        },
        link: function(scope, element, attr){
            element.on("click", function(){
                console.log(scope.vehicle);
            });
        }
    }
};

function carFunc(){
    return{
        require:"vehicle",
        link: function(scope, element, attr, vehicleCtrl){
            vehicleCtrl.addWheels(4);
            vehicleCtrl.addEngine("V8 engine");
            vehicleCtrl.addBody();
        }
    }
};

function boatFunc(){
    return{
        require:"vehicle",
        link: function(scope, element, attr, vehicleCtrl){
            vehicleCtrl.addWheels(0);
            vehicleCtrl.addEngine("boat engine");
            vehicleCtrl.addBody();
        }
    }
};

function bikeFunc(){
    return{
        require:"vehicle",
        link: function(scope, element, attr, vehicleCtrl){
            vehicleCtrl.addWheels(2);
        }
    }
};