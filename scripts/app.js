'use strict';

var maribelle;
    (function (maribelle) {
    
    function AppViewModel($scope, $translate, userService) {
        var self = this;
        
        $scope.$watchCollection(
            function() { return userService.currentUser() },
            function() { 
                self.userName = userService.currentUser().name;
                self.userAuthenticated = userService.isAuthenticated();
            }
        );

        self.changeLanguage = function (langKey) {
            $translate.use(langKey);
        };
    };
    
    function UserServiceFactory($http, rootUrl) {
        var user = {};
        var userInitialized = false;
        var userInitializationInProgress = false;

        return {
            currentUser: function() {
                if (!userInitialized) {
                    initializeUser();
                }

                return user;
            },
            
            isAuthenticated: function() {
                if (!userInitialized) {
                    initializeUser();
                }
                
                return user.hasOwnProperty("id");
            },
            
            login: function(credentials) {
                return loginUser(credentials).then(function (isSuccessful) {
                    if (isSuccessful === true) {
                        userInitializationInProgress = false;
                        initializeUser();
                    }

                    return isSuccessful;
                });
            }
        };
        
        function initializeUser() {
            if (!userInitializationInProgress) {
                userInitializationInProgress = true;
                
                getCurrentUser().then(function(result) {
                    if (result) {
                        angular.extend(user, result);
                    }
                    
                    userInitialized = true;
                    userInitializationInProgress = false;
                });
            }
        }
        
        function getCurrentUser() {
            return $http({
                url: rootUrl + '/controller.php?controller=user&action=getCurrentUser',
                method: 'GET',
                params: null,
            })
            .then(maribelle.mapData)
        }
        
        function loginUser(credentials) {
            return $http({
                url: rootUrl + '/controller.php?controller=user&action=login',
                method: 'POST',
                data: credentials,
            })
            .then(maribelle.mapData);
        }
    }
    
    function DebounceFactory($timeout) {
        return function(callback, interval) {
            var timeout = null;
            return function() {
                var args = arguments;
                $timeout.cancel(timeout);
                timeout = $timeout(
                    function () { callback.apply(this, args); }, 
                    interval
                );
            };
        }; 
    }
    
    angular.module('maribelle', ['maribelle.routing', 'maribelle.translations', 'ui.bootstrap', 'ngAnimate'])
        .constant("rootUrl", "/fab")
        .controller('AppViewModel', AppViewModel)
        .service('userService', UserServiceFactory)
        .factory('debounce', DebounceFactory)
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