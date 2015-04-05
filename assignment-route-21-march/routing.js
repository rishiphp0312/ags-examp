
	var myapp =   angular.module('app', ['ngRoute']);

	// injecting provider
	
	myapp.config(['$routeProvider',
    function($routeProvider) {
      $routeProvider.
       when('/', {
         templateUrl: 'templates/phone-list2.htm',
         controller: 'DetailCtrl'
      }).
      when('/phones', {
        templateUrl: 'templates/phone-list.htm',
        controller: 'PhoneDetailCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);
  
  myapp.directive('myCustomer',['mycustomerfunc']);
  
  function mycustomerfunc(){
   return{
   //restrict:'AEC',
   templateUrl:'templates/temp1',
    /*scope:[customer: {
        name: 'Rahul',
        address: 'Lutyens, New Delhi'
     }];*/

	 };  
  
  };
	
	myapp.controller("ExampleController", function($scope,$rootScope) {
	alert('1');
		$rootScope.$on("$routeChangeError",function(event,current,previous,rejection){
	//console.log(event,current,previous,rejection);
	$rootScope.customer= {
        name: 'Rahul',
        address: 'Lutyens, New Delhi'
     };
	console.log('goood');
	
	});
	
	$rootScope.$on("$routeChangeStart",function(event,next,current){
	//console.log(event,next,current);
		alert('2');
	
	console.log('start');
	
	});
	
	
	$rootScope.$on("$routeChangeSuccess",function(event,current,previous){
		//console.log(event,current,previous);
		alert('3');	
	console.log('goood current');
	  $scope.customer = {
        name: 'Rahul',
        address: 'Lutyens, New Delhi'
    };

	});
	
	
	
	});
		
	myapp.controller("DetailCtrl", function($scope) {
	alert('hua');
	console.log('Details Controller');
	});
		
	myapp.controller("PhoneDetailCtrl", function($scope) {
	alert('4');	
	console.log('PhoneDetailCtrlDetailCtrl');
	});
		
		