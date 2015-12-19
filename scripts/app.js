'use strict';

(function (maribelle) {
	
	function AppViewModel($scope, $state, $window, $location, $http) {
		var self = this;
		
		this.state1 = "hallo2";
		this.state2 = 15;
		
		this.sayHelloWorld = function(name) {
			$window.alert('Hello world ' + name);
			
			self.state2++;
		};
	}
	
	angular.module('maribelle', ['ui.router', 'ui.bootstrap', 'ngAnimate'])
		.controller('AppViewModel', AppViewModel)
		.config(function($stateProvider, $urlRouterProvider, $uiViewScrollProvider) {
			$uiViewScrollProvider.useAnchorScroll();
			
			$urlRouterProvider.otherwise(maribelle.product.overviewRoute.url);
			$stateProvider
				.state(maribelle.product.overviewRoute)
				.state(maribelle.product.detailRoute);
		})
		.run(function($rootScope, $rootElement) {
			$rootScope.$ignore = function() { return false };
			$rootScope.$today = moment().startOf('day').toDate();
			$rootScope.$tomorrow = moment().add(1, 'day').startOf('day').toDate();
			$rootScope.$yesterday = moment().add(-1, 'day').startOf('day').toDate();
		});
		
})(maribelle || (maribelle = {}));