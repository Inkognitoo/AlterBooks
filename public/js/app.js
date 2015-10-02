'use strict';

/* App Module */

var alterBooksApp = angular.module('alterBooksApp', [
    'ngRoute',
    'alterBooksControllers'
]);

alterBooksApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'pages/welcome.html',
                controller: 'WelcomePageCtrl'
            }).
            when('/registration', {
                templateUrl: 'pages/registration.html',
                controller: 'RegistrationPageCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }]);

//
//businessCardApp.directive('subcategoryPart', function () {
//    return {
//        templateUrl: 'partials/subcategory.html',
//        scope: {},
//        controller: 'SubcategoryCtrl'
//    }
//});
//
//businessCardApp.directive('tabPart', function () {
//    return {
//        templateUrl: 'partials/tab.html',
//        controller: 'TabCtrl'
//    }
//});
//
//businessCardApp.directive('generalPreviewBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/general_preview.html',
//        //controller: 'GeneralPreviewCtrl',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('listDataBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/list_data.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('listDataBlockZoom', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/list_data_zoom.html',
//        controller: 'ListDataZoomCtrl',
//        link: linkFunction
//    };
//});
//
//businessCardApp.directive('listDataHeaderBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/list_data_header.html',
//        link: linkFunction
//    };
//});
//
//businessCardApp.directive('informationSpacesBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/information_spaces.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('delimiterBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/delimiter.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('delimiterTransparentBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/delimiter_transparent.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textImageBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/textImage.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textAndHeaderBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text_and_header.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textAndHeaderCenterBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text_and_header_center.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textLineBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text_line.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textLineLittleBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text_line_little.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('territoryBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/territory.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('threeColumnNumberBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/threeColumnNumber.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('threeColumnBottomLineBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/threeColumnBottomLine.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('threeColumnLeftLineBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/threeColumnLeftLine.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('lineNumberBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/lineNumber.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('textImageHeaderBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/text_image_header.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('imageBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/image.html',
//        link: linkFunction
//    }
//});
//
//businessCardApp.directive('headerBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/header.html',
//        link: linkFunction
//    }
//});
//
//
//businessCardApp.directive('chartBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/chart.html',
//        controller: 'ChartCtrl',
//        link: linkFunction
//    };
//});
//
//businessCardApp.directive('chartPieBlock', function () {
//    var linkFunction = function(scope, element, attributes) {
//        scope.index = attributes.index;
//    };
//    return {
//        templateUrl: 'blocks/chart_pie.html',
//        controller: 'ChartPieCtrl',
//        link: linkFunction
//    };
//});
