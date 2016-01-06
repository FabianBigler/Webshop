'use strict';

(function (product) {

    function DetailViewModel($scope, $http, $stateParams, rootUrl) {
		$scope.product = {};
		$scope.amount = 1;
		$scope.statusAlerts = [];
		
		getProduct($stateParams.id).then(function (product) {
			$scope.product = product;
		});
		
		function getProduct(id) {
			return $http({
                url: rootUrl + '/controller.php?controller=product&action=get',
                method: 'GET',
                params: {productId: id}
            })
			.then(maribelle.mapData);
		}
		
		$scope.canAddItemToBasket = function() {
            return ($scope.amount > 0);
        };
        
        $scope.addItemToBasket = function() {
            addItemToBasket($scope.product.id, $scope.amount).then(function(res) {
                $scope.statusAlerts.push({ type: 'success', messageKey: 'addedItemToBasket' });
                $scope.amount = 1;
            });
        };
                
        $scope.removeAlert = function(statusAlert) {
            $scope.statusAlerts.splice($scope.statusAlerts.indexOf(statusAlert), 1);
        };
        
        function addItemToBasket(id, amount) {
            return $http({
                url: rootUrl + '/controller.php?controller=basket&action=addItemToBasket',
                method: 'POST',
                data: { productId: id, amount: amount }
            });
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