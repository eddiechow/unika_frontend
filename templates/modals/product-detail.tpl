<div ng-controller="productDetailCtrl" ng-init="getProductDetail(option)">
    
    <div class="w3-row-padding">
        <div class="w3-col w3-border-bottom" ng-show="option.order">
            <div class="w3-cell" style="width:100%">
                <h3 ng-show="!productNotFound">{{product.productNameEnUs}}</h3>
            </div>
            <div class="w3-cell">
               <a href="#" ng-click="openModal('other', {template: 'order-detail.tpl', maxWidth: '1000px', headerTitle: 'Order Detail', order: option.order})" class="w3-hover-text-amber w3-margin"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
            </div>
            <div class="w3-cell">
                <a href="#" ng-click="closeModal()" class="w3-hover-text-red w3-margin"><i class="fa fa-times fa-2x" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="w3-col s12 m12 l5 w3-margin-top w3-margin-bottom" ng-show="!productNotFound">
            <img src="images/product-{{product.category}}-{{product.productID}}.jpg" style="width:100%">
        </div>
        <div class="w3-col s12 m12 l7" ng-show="!productNotFound">
            <p>{{product.descriptionEnUs}}</p>
            <p><span style="font-weight:bold">Release Date:</span> {{(product.releaseDate*1000) | date:'d MMM yyyy'}}</p>
            <p ng-show="product.category=='perfume'"><span style="font-weight:bold">Category:</span> {{product.categoryNameEnUs}}</p>
            <p ng-style="{'text-decoration':product.discount!=null ? 'line-through' : ''}">{{product.originalPrice | currency:"HKD"}}<span ng-show="product.category=='perfume'"> per mL</span></p>
            <p ng-show="product.discount!=null" style="font-weight:bold">Now {{product.nowPrice | currency:"HKD"}}<span ng-show="product.category=='perfume'"> per mL</span></p>
            <p>
                <span ng-show="product.qtyInStock>0">Quantity in Stock: {{product.qtyInStock}}<span ng-show="category=='perfume'"> mL</span></span>
                <span ng-show="product.qtyInStock<=0" class="w3-text-red" style="font-weight:bold">No item in stock</span>
            </p>
        </div>
        <div class="w3-col w3-margin-top w3-margin-bottom" ng-show="productNotFound">
            <h3>The product is not found</h3>
        </div>

    </div> 
</div>
