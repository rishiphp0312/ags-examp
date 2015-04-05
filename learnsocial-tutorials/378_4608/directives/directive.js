var app = angular.module("app", []);
app.controller("appCtrl", ["$scope", appCtrlFunc]);
app.controller("timerCtrl", ["$scope", "$interval", "dateFilter", timerCtrlFunc]);
app.controller("dialogCtrl", ["$scope", dialogCtrlFunc]);
app.directive('myCustomer', [myCustomerDirectiveFunc]);
app.directive("myCurrentTime", ['$interval', 'dateFilter', myCurrentTimeDirectiveFunc]);
app.directive('myDialog', [myDialogDirectiveFunc]);
app.directive('enter', [enterFunc]);
app.directive('leave', [leaveFunc]);

function appCtrlFunc($scope) {
    $scope.customer = {
        name: 'Rahul',
        address: 'Lutyens, New Delhi'
    };
    $scope.a1 = {
        name: 'Ram',
        address: 'Mumbai'
    };
    $scope.a2 = {
        name: 'Lakshman',
        address: 'Lutyens, New Delhi'
    };
};

function timerCtrlFunc() {
};

function dialogCtrlFunc($scope) {
    $scope.name = "Rahul";
};

function myCustomerDirectiveFunc() {
    return {
        restrict: 'AEC',
        // template: 'Name: {{customer.name}} <br/> Address: {{customer.address}}'
        // templateUrl: "templates/template1.htm"
        templateUrl: function (elem, attr) {
            console.log(elem, attr);
            console.log(attr.test);
            console.log(attr.type);
            var url;
            if (attr.type) {
                url = 'templates/customer-' + attr.type + '.htm';
            } else {
                url = "templates/template1.htm";
            }
            return url;
        },
        scope: {
            customerid: "=data"
        }
    };
};

function myCurrentTimeDirectiveFunc($interval, dateFilter) {
    return {
        link: function (scope, element, attrs) {
            var format = "M/d/yy h:mm:ss a", timeoutId;

            timeoutId = $interval(function () {
                updateTime(); 
            }, 1000);

            element.on('$destroy', function () {
                $interval.cancel(timeoutId);
            });

            function updateTime() {
                element.text(dateFilter(new Date(), format));
            }
        }
    }
};

function myDialogDirectiveFunc() {
    return {
        transclude: true,
        templateUrl: 'templates/myDialog.htm'
    };
};

function enterFunc(){
    var eventHandler = function(scope, element, promise){
        element.on("mouseenter", function(){
            console.log("mouse entered");
            //element.addClass("highlight");
            //showPopup();
        });
    };
    return eventHandler;
};

function leaveFunc(){
    return function(scope, element, promise){
        element.on("mouseleave", function(){
            console.log("mouse left");
            //element.removeClass("highlight");
            //hidePopup();
        });
    }
};


