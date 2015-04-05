// Supported attributes: fu-multiple, fu-resetable
angular.module('fu.directives.fileBrowser', []).directive('fuFileBrowser', function () {
    return {
        restrict: 'EA',
        require: '?ngModel',
        replace: true,
        template: '<div><div><input type="file" style="cursor:pointer"/></div></div>',
        link: function (scope, element, attrs, ngModel) {
            var container = element.children();
            var bindFileControlChange = function () {
			                var reader = new FileReader();
                reader.onload = function (loadEvent) {
				
				                    scope.$apply(function () {
                        scope.fileread = loadEvent.target.result;
						alert(loadEvent.target.result);
                    });
				}
			
                var fileControl = container.children();
                fileControl.prop('multiple', attrs.fuMultiple !== undefined);
                fileControl.change(function (evt) {
                    scope.$apply(function () {
					    console.log(evt.target.result);
                        ngModel.$setViewValue(evt.target.files);
						
                    });
                    if (attrs.fuResetable === undefined) {
                        return;
                    }
                    container.html(container.html()); // Reset must be done on div level
                    bindFileControlChange(); // Rebind after reset
                });
            };
            bindFileControlChange();
        }
    };
});

