'use strict';

(function (user) {

    function DetailViewModel($scope, userService) {
        $scope.newUser = userService.currentUser();
    }

    user.detailRoute = {
        name: 'user-detail',
        url: '/user/detail',
        views: {
            '@': {
                templateUrl: 'views/user-detail.html',
                controller: DetailViewModel
            }
        }
    }

})(maribelle.user || (maribelle.user = {}));