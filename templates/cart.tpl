<h5 class="w3-margin-left">Cart</h5>

<div class="w3-row-padding">
    <div class="w3-col l8 m7 s12 w3-padding-bottom">

        <h6>Package</h6>
        <div ng-if="package && !package.soldOut && !package.nonEnough" class="w3-row w3-card-2 w3-margin-bottom w3-white">
            <div class="w3-col w3-padding w3-padding-16 w3-display-container">
                <span ng-click="delete('package', package)" class="w3-display-right w3-padding w3-margin-right w3-hover-red"><i class="fa fa-trash" aria-hidden="true"></i></span>
                <img src="images/product-package-{{package.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                <span style="font-weight:bold;cursor:pointer" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', headerTitle: package.productName, category: 'package', productID: package.productID})">{{package.productName}}</span><br>
                <span class="w3-small" ng-style="{'text-decoration':package.originalPrice != package.nowPrice ? 'line-through' : ''}">{{package.originalPrice | currency:"HKD"}}</span><br>
                <span class="w3-small" ng-show="package.originalPrice != package.nowPrice" style="font-weight:bold">Now {{package.nowPrice | currency:"HKD"}}</span>
            </div>    
        </div>
        <div ng-if="package && package.nonEnough" class="w3-padding w3-padding-16 w3-card-2 w3-gray">
            The item does not exist in stock. Please <a href="#" ng-click="delete('package', package)">click</a> here to remove.
        </div>
        <div ng-if="package && package.soldOut" class="w3-padding w3-padding-16 w3-card-2 w3-gray">
            Sorry! The package is sold out. Please <a href="#" ng-click="delete('package', package)">click</a> here to remove.
        </div>
        <div ng-if="!package" class="w3-padding w3-padding-16 w3-card-2 w3-white">
            No any item
        </div>
  

        <h6>Bottle</h6>      
        <div ng-if="bottle && !bottle.soldOut && !bottle.nonEnough" class="w3-row w3-card-2 w3-margin-bottom w3-white">
            <div class="w3-col w3-container w3-red" ng-if="error.bottle">
                <p>{{error.bottle}}</p>
            </div>
            <div class="w3-col w3-padding w3-padding-16 w3-display-container">
                <span ng-click="delete('bottle', bottle)" class="w3-display-right w3-padding w3-margin-right w3-hover-red"><i class="fa fa-trash" aria-hidden="true"></i></span>
                <img src="images/product-bottle-{{bottle.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                <span style="font-weight:bold;cursor:pointer" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', headerTitle: bottle.productName, category: 'bottle', productID: bottle.productID})">{{bottle.productName}}</span><br>
                <span class="w3-small">Capacity: {{bottle.bottleCapacity}} mL</span><br>
                <span class="w3-small" ng-style="{'text-decoration':bottle.originalPrice != bottle.nowPrice ? 'line-through' : ''}">{{bottle.originalPrice | currency:"HKD"}}</span><br>
                <span class="w3-small" ng-show="bottle.originalPrice != bottle.nowPrice" style="font-weight:bold">Now {{bottle.nowPrice | currency:"HKD"}}</span>
            </div>
        </div>
        <div ng-if="bottle && bottle.nonEnough" class="w3-padding w3-padding-16 w3-card-2 w3-gray">
            The item does not exist in stock. Please <a href="#" ng-click="delete('bottle', bottle)">click</a> here to remove.
        </div>
        <div ng-if="bottle && bottle.soldOut" class="w3-padding w3-padding-16 w3-card-2 w3-gray">
            Sorry! The bottle is sold out. Please <a href="#" ng-click="delete('bottle', bottle)">click</a> here to remove.
        </div>
        <div ng-if="!bottle" class="w3-padding w3-padding-16 w3-card-2 w3-white">
            No any item
        </div>


        <h6>Perfume</h6>
        <div ng-if="perfume.length > 0">
            <div class="w3-row w3-card-2 w3-margin-botto w3-white w3-margin-bottom" ng-repeat="perfume in perfume" ng-init="selected.quantity=perfume.quantity;selected.note=perfume.note;editMode[perfume.productID]=false">
                <div ng-if="perfume && !perfume.soldOut && !perfume.nonEnough">
                    <div class="w3-col l12 m12 s12 w3-container" ng-class="{'w3-green':success.perfume[perfume.productID], 'w3-red': (error.perfume[perfume.productID] || perfume.qtyInStock < perfume.quantity)}" ng-if="success.perfume[perfume.productID] || error.perfume[perfume.productID] || perfume.qtyInStock < perfume.quantity">
                        <p>{{success.perfume[perfume.productID]}}{{error.perfume[perfume.productID]}}<span ng-show="perfume.qtyInStock < perfume.quantity">Sorry! The perfume is not enough in stock.</span></p>
                    </div>
                    <div class="w3-col l7 m12 s12 w3-padding w3-padding-16 w3-display-container">
                        <span ng-click="delete('perfume', perfume)" class="w3-display-right w3-padding w3-margin-right w3-hover-red"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        <img src="images/product-perfume-{{perfume.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                        <span style="font-weight:bold;cursor:pointer"  ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', headerTitle: perfume.productName, category: 'perfume', productID: perfume.productID})">{{perfume.productName}}</span><br>
                        <span class="w3-small" ng-style="{'text-decoration':perfume.originalPrice != perfume.nowPrice ? 'line-through' : ''}">{{perfume.originalPrice | currency:"HKD"}} per mL</span><br>
                        <span class="w3-small" ng-show="perfume.originalPrice != perfume.nowPrice" style="font-weight:bold">Now {{perfume.nowPrice | currency:"HKD"}} per mL</span><br>
                        <span class="w3-small">Sub-total: {{perfume.nowPrice*perfume.quantity | currency:"HKD"}}</span>
                    </div>
                    <div class="w3-col l5 m12 s12 w3-padding w3-padding-16 w3-khaki w3-display-container">
                        <span ng-click="editMode[perfume.productID]=true" class="w3-display-right w3-padding w3-margin-right w3-hover-yellow"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                        <br>
                        <span>Quantity: {{perfume.quantity}} mL</span><br>
                        <span>Note: {{perfume.note}}</span><br><br>
                        <div ng-if="editMode[perfume.productID]" class="w3-display-right w3-khaki w3-padding-left w3-padding-right">
                            <div style="margin-bottom:10px">
                                <div class="w3-cell" style="width:100%" style="display:flex;display:-webkit-flex;">
                                    <div style="width:100%">
                                        <div class="w3-cell">Quantity&nbsp;</div>
                                        <div class="w3-cell" style="width:100%">
                                            <input class="w3-input w3-border" min="1" max="200" maxlength="3" ng-model="selected.quantity" type="number" ng-change="checkQtySelection()">
                                        </div>
                                        <div class="w3-cell">&nbsp;mL</div>
                                    </div>
                                </div>
                                
                                <div class="w3-cell" style="padding-left:5px"><div style="width:80px"></div></div>
                            </div>
                            <div>
                                <div class="w3-cell" style="width:100%">
                                    <select class="w3-input w3-border" ng-model="selected.note">
                                        <option value="Base">Base note</option>
                                        <option value="Middle">Middle note</option>
                                        <option value="High">High note</option>
                                    </select>
                                </div>
                                <div class="w3-cell" style="padding-left:5px"><button ng-click="updatePerfume(perfume, selected)" class="w3-btn w3-btn-block w3-small w3-theme-action" style="width:80px">Finish</button></div>
                            </div>
                        </div>
                    </div>     
                </div>
                <div ng-if="perfume && perfume.nonEnough" class="w3-col l12 m12 s12 w3-padding w3-padding-16">
                    The item does not exist in stock. Please <a href="#" ng-click="delete('perfume', perfume)">click</a> here to remove.
                </div>
                <div ng-if="perfume && perfume.soldOut" class="w3-col l12 m12 s12 w3-padding w3-padding-16">
                    Sorry! The perfume is sold out. Please <a href="#" ng-click="delete('perfume', perfume)">click</a> here to remove.
                </div>
            </div>           
        </div>
        <div ng-if="!perfume || perfume.length <= 0" class="w3-padding w3-padding-16 w3-card-2 w3-white">
            No any item
        </div>
        <br>
    </div>
    <div class="w3-col l4 m5 s12 w3-padding-bottom">
        <div class="w3-card-2 w3-white">
            <div class="w3-container w3-theme-d2">
                <h4>Total</h4>
            </div>
            <div class="w3-container w3-small w3-border-bottom">
                <p class="w3-left">Order total:</p>
                <p class="w3-right">{{ getOrderTotal() | currency:"HKD"}}</p>
            </div>
            <div class="w3-container">
				<p class="w3-small">We accept <i class="fa fa-cc-paypal"></i> PayPal</p>
                <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="checkout()">Checkout</a></p>
            </div>
        </div>
    </div>
</div>