angular.module('fu.directives.fileDropper', []).directive('fuFileDropper', function () {
    return {
        restrict: 'EA',
        require: '?ngModel',
        replace: true,
        transclude: true,
        template: '<div class="fu-drop-area" ng-transclude></div>',
        link: function (scope, element, attrs, ngModel) {
            var dropZone = element;
            var dropZoneDom = element.get(0);
            dropZoneDom.addEventListener('dragover', function (evt) {
                evt.stopPropagation();
                evt.preventDefault();
                evt.dataTransfer.dropEffect = 'copy';
                dropZone.addClass("dragover");
            }, false);
            dropZoneDom.addEventListener('dragleave', function (evt) {
                evt.stopPropagation();
                evt.preventDefault();
                dropZone.removeClass("dragover");
            }, false);
            dropZoneDom.addEventListener('drop', function (evt) {
                evt.stopPropagation();
                evt.preventDefault();
                dropZone.removeClass("dragover");
                scope.$apply(function () {
                    ngModel.$setViewValue(evt.dataTransfer.files);
                });
            }, false);
        }
    };
});

