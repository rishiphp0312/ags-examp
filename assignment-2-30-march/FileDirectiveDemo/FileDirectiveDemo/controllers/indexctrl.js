var mod = angular.module('demoApp', ['fu.directives.fileBrowser', 'fu.directives.fileDropper']);
mod.controller('IndexController', function($scope) {
    $scope.word = 'hello all!!';
});