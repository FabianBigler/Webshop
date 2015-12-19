'use strict';

(function (product) {

    function DetailViewModel() {
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