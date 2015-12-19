'use strict';

(function (user) {

    function RegisterViewModel($scope, $http, rootUrl) {
        $scope.newUser = {};
        $scope.statusAlerts = [];
        
        $scope.canSubmit = function() {
            return $scope.registerForm.$dirty 
                && $scope.registerForm.$valid;
        };
        
        $scope.submit = function() {
            registerUser($scope.newUser).then(function(res) {
                $scope.statusAlerts.push({ type: 'success', messageKey: 'registrationSuccessful' });
                $scope.newUser = {};
            });
        };
        
        $scope.removeAlert = function(statusAlert) {
            $scope.statusAlerts.splice($scope.statusAlerts.indexOf(statusAlert), 1);
        };
        
        function registerUser(newUser) {
            return $http({
                url: rootUrl + '/controller.php?controller=user&action=register',
                method: 'POST',
                data: newUser,
            });
        }
    }

    user.registerRoute = {
        name: 'user-register',
        url: '/user/register',
        views: {
            '@': {
                templateUrl: 'views/register.html',
                controller: RegisterViewModel
            }
        }
    }

})(maribelle.user || (maribelle.user = {}));