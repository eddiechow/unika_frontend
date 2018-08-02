<div class="w3-margin-top" ng-controller="mainCtrl">
    <div ng-if="discount.length>0" ng-init="showIndex['package']=0">
        <div class="w3-center w3-xxlarge">Sale</div>
        <div class="w3-red w3-margin-left w3-margin-right w3-margin-bottom w3-container" ng-if="cartError">
          <p>{{cartError}}</p>
        </div> 
        <div class="w3-row-padding w3-display-container">
            <div class="w3-col l3 m6 w3-margin-bottom" ng-repeat="product in discount" ng-init="selected[product.productID].quantity = 1;showPerfumeSelection[product.productID]=false">
                <div class="w3-display-container w3-card-2 w3-white">
                    <div class="w3-display-container">
                        <img src="images/product-{{product.category}}-{{product.productID}}.jpg" style="width:100%">
                        <span ng-show="getTimeNow()<((product.releaseDate*1000)+(14 * 24 * 60 * 60 * 1000))" class="w3-badge w3-small w3-red w3-display-topleft" style="width:45px;height:45px;line-height:45px;margin:5px;transform:rotate(-30deg)">NEW</span>
                        <span ng-show="product.discount" class="w3-tag w3-tiny w3-theme-d2 w3-display-topright">{{product.discount}}%<br>OFF</span>
                        <span ng-show="product.discountTitle" class="w3-small w3-theme-l2 w3-display-bottomleft" style="padding:4px">{{product.discountTitle}}</span>
                    </div>
                    <div class="w3-padding w3-small">
                        <p style="font-weight:bold">{{product.productName}}</p>
                        <p>Release Date: {{(product.releaseDate*1000) | date:'d MMM yyyy'}}</p>
                        <p ng-style="{'text-decoration':product.discount!=null ? 'line-through' : ''}">{{product.originalPrice | currency:"HKD"}}</p>
                        <p><span ng-show="product.discount!=null" style="font-weight:bold">Now {{product.nowPrice | currency:"HKD"}}</span><br></p>
                        <p>
                            <span ng-show="product.qtyInStock>0">Quantity in Stock: {{product.qtyInStock}}<span ng-show="product.category=='perfume'"> mL</span></span>
                            <span ng-show="product.qtyInStock<=0" class="w3-text-red" style="font-weight:bold">No item in stock</span>
                        </p>
                        <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="addToCart(product.category, product.productID)"><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart</a></p>
                        <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', headerTitle: product.productName, category: product.category, productID: product.productID})">Detail</a></p>
                    </div>
                    
                    <div class="w3-container w3-display-bottommiddle w3-block w3-khaki" ng-if="showPerfumeSelection[product.productID]">
                        <p><div>
                            <div class="w3-cell" style="padding-right:5px"><label>Quantity</label></div>
                            <div class="w3-cell" style="width:100%">
                                <input class="w3-input w3-border" min="1" max="200" maxlength="3" ng-model="selected[product.productID].quantity" type="number" ng-change="checkQtySelection(product.productID, selected[product.productID].quantity)">
                            </div>
                        </div></p>
                        <p><div>
                            <div class="w3-cell" style="padding-right:5px"><label>Note</label></div>
                            <div class="w3-cell" style="width:100%">
                                <select class="w3-input w3-border" ng-model="selected[product.productID].note">
                                    <option value="" disabled selected>Please select</option>
                                    <option value="Base">Base</option>
                                    <option value="Middle">Middle</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                        </div></p>
                        <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="addToCart(product.category, product.productID)">OK</a></p>
                        <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="showPerfumeSelection[product.productID]=false;selected[product.productID].quantity=1;selected[product.productID].note=null">Cancel</a></p>
                    </div>   
                
                    <div class="w3-container w3-display-bottommiddle" style="width:100%" ng-show="responseData[product.productID].messageBoxShow" ng-class="{'w3-red': responseData[product.productID].error, 'w3-green': responseData[product.productID].sucess}">
                        <p>{{responseData[product.productID].error}}{{responseData[product.productID].sucess}}</p>
                    </div>
                </div>
            </div>   
        </div>   
    </div>
                    
    <div class="w3-center w3-xxlarge">Product</div>
    <div class="w3-row-padding">
        <div class="w3-col l4 m4">
            <a href="product/package" style="text-decoration:none">
                <div class="w3-card-2 w3-white w3-margin-bottom" style="width:100%">
                    <img src="images/package.png" class="w3-hide-small" style="width:100%">
                    <div class="w3-padding" style="display:table">
                        <img src="images/package.png" class="w3-hide-medium w3-hide-large w3-margin-right" style="height:80px">
                        <span class="w3-xlarge" style="display:table-cell;vertical-align:middle">Package</span>
                    </div> 
                </div>                
            </a>
        </div>
        <div class="w3-col l4 m4">
            <a href="product/bottle" style="text-decoration:none">
                <div class="w3-card-2 w3-white w3-margin-bottom" style="width:100%">
                    <img src="images/bottle.png" class="w3-hide-small" style="width:100%">
                    <div class="w3-padding" style="display:table">
                        <img src="images/bottle.png" class="w3-hide-medium w3-hide-large w3-margin-right" style="height:80px">
                        <span class="w3-xlarge" style="display:table-cell;vertical-align:middle">Bottle</span>
                    </div> 
                </div>                        
            </a>
        </div>
        <div class="w3-col l4 m4">
            <a href="product/perfume" style="text-decoration:none">
                 <div class="w3-card-2 w3-white w3-margin-bottom" style="width:100%">
                    <img src="images/perfume.png" class="w3-hide-small" style="width:100%">
                    <div class="w3-padding" style="display:table">
                        <img src="images/perfume.png" class="w3-hide-medium w3-hide-large w3-margin-right" style="height:80px">
                        <span class="w3-xlarge" style="display:table-cell;vertical-align:middle">Perfume</span>
                    </div> 
                </div>                
            </a>
        </div>
    </div>

</div>

    
