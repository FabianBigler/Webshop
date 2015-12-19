'use strict';

(function (product) {
	
	function OverviewViewModel() {
	}
	
    product.overviewRoute = {
		name: 'products-overview',
		url: '/products/overview',
		views: {
			'@': {
				templateUrl: 'views/product-overview.html',
				controller: OverviewViewModel
			}
		}
	}
	
})(maribelle.product || (maribelle.product = {}));