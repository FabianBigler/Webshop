'use strict';

(function (basket) {

    function BasketViewModel($scope, $http, rootUrl) {
        $scope.basket = {};                
		
        getBasket().then(function (basket) {
            $scope.basket = basket;
        });

		$scope.removeBasketLine = function(line) {				
			removeLineFromBasket(line.productId).then(function() {
                var index = $scope.basket.lines.indexOf(line);						
                $scope.basket.lines.splice(index, 1);
            });
        };		

		$scope.canCompleteOrder = function() {
            return $scope.basket.lines 
                && $scope.basket.lines.length > 0;
        };		
		
		$scope.completeOrder = function() {
			completeOrder();
		}
        
        $scope.getTotal = function() {
			if(!$scope.basket.lines) { 
				return 0;
			}
			
			return $scope.basket.lines.reduce(function(acc, line) { 
				return acc + line.productPrice * line.amount; 
			}, 0);
		}

		function getBasket() {
            return $http({
                url: rootUrl + '/controller.php?controller=basket&action=getBasket',
                method: 'GET',
                params: null,
            })
            .then(maribelle.mapData);
        }
		
		function removeLineFromBasket(id) {
            return $http({
                url: rootUrl + '/controller.php?controller=basket&action=removeLinefromBasket',
                method: 'POST',
                data: { productId: id }
            });
        }
		
		function completeOrder() {
			return $http({
                url: rootUrl + '/controller.php?controller=basket&action=completeOrder',
                method: 'POST',                
            });
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