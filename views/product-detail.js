'use strict';

(function (product) {

    function DetailViewModel($scope, $http, $stateParams, rootUrl) {
		$scope.product = {};
		
		getProduct($stateParams.id).then(function (product) {
			$scope.product = product;
		});
		
		function getProduct(id) {
			return $http({
                url: rootUrl + '/controller.php?controller=product&action=get',
                method: 'GET',
                params: { productId: id },
            })
			.then(maribelle.mapData);
		}
    }

    product.detailRoute = {
        name: 'product-detail',
        url: '/product/:id/detail',
        views: {
            '@': {
                templateUrl: 'views/product-detail.html',
                controller: DetailViewModel
            }
        }
    }

})(maribelle.product || (maribelle.product = {}));