'use strict';

(function (user) {

    function ProfileViewModel($scope, userService) {
        $scope.newUser = userService.currentUser();
    }

    user.profileRoute = {
        name: 'user-profile',
        url: '/user/profile',
        views: {
            '@': {
                templateUrl: 'views/user-profile.html',
                controller: ProfileViewModel
            }
        }
    }

})(maribelle.user || (maribelle.user = {}));