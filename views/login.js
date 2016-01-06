'use strict';

(function (user) {

    function LoginViewModel($scope, $http, rootUrl) {
        $scope.credentials = {};
        $scope.status = {};
        
        $scope.canSubmit = function() {
            return $scope.loginForm.$dirty 
                && $scope.loginForm.$valid;
        };
        
        $scope.submit = function() {
            loginUser($scope.credentials).then(function(isSuccessful) {
                if (isSuccessful === true) {
                    $scope.status = { type: 'success', messageKey: 'loginSuccessful', show: true };
                }
                else {
                    $scope.status = { type: 'danger', messageKey: 'loginFailed', show: true };
                }
            });
        };
        
        function loginUser(credentials) {
            return $http({
                url: rootUrl + '/controller.php?controller=user&action=login',
                method: 'POST',
                data: credentials,
            })
            .then(maribelle.mapData);
        }
    }

    user.loginRoute = {
        name: 'user-login',
        url: '/user/login',
        views: {
            '@': {
                templateUrl: 'views/login.html',
                controller: LoginViewModel
            }
        }
    }

})(maribelle.user || (maribelle.user = {}));