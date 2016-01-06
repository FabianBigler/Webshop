'use strict';

(function (basket) {

    function BasketViewModel($scope, $http, rootUrl) {
        $scope.basket = {};                
		
		  getBasket().then(function (basket) {
            $scope.basket = basket;
        });
		
		function getBasket() {
            return $http({
                url: rootUrl + '/controller.php?controller=basket&action=getBasket',
                method: 'GET',
                params: null,
            })
            .then(maribelle.mapData);
        }
		
		function removeItemfromBasket()
		{
		
		}
		
		$scope.getTotal = function() {
			if(!$scope.basket.lines) { 
				return 0;
			}
			
			return $scope.basket.lines.reduce(function(acc, line) { 
				return acc + line.productPrice * line.amount; 
			}, 0);
		}
		
    }

    basket.basketRoute = {
        name: 'basket',
        url: '/basket',
        views: {
            '@': {
                templateUrl: 'views/basket.html',
                controller: BasketViewModel
            }
        }
    }

})(maribelle.basket || (maribelle.basket = {}));