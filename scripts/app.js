'use strict';

var maribelle;
    (function (maribelle) {
    
    function AppViewModel($scope, $translate) {
        var self = this;
        
        this.changeLanguage = function (langKey) {
            $translate.use(langKey);
        };
    };
    
    angular.module('maribelle', ['maribelle.routing', 'maribelle.translations', 'ui.bootstrap', 'ngAnimate'])
        .constant("rootUrl", "/fab")
        .controller('AppViewModel', AppViewModel)
        .run(function($rootScope, $rootElement, rootUrl) {
            $rootScope.$ignore = function() { return false };
            $rootScope.$today = moment().startOf('day').toDate();
            $rootScope.$tomorrow = moment().add(1, 'day').startOf('day').toDate();
            $rootScope.$yesterday = moment().add(-1, 'day').startOf('day').toDate();
            $rootScope.rootUrl = rootUrl;
        });

    maribelle.mapData = function (promise) {
        return promise.data;  
    };

})(maribelle || (maribelle = {}));