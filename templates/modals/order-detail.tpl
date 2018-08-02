<div class="w3-margin-top w3-margin-bottom" ng-init="order=option.order;showStatusDetailInModal=false">
    <div class="w3-row w3-border w3-margin-bottom">
        <div class="w3-col w3-black w3-border-bottom w3-padding">
            Information
        </div>
        <div class="w3-col w3-padding">
            <div class="w3-row">
                <div class="w3-col l6 m6 s12 w3-small">
                    Order No.: {{order.orderID}}<br>
                    Order Date: {{(order.orderDate*1000) | date:'d MMM yyyy H:mm'}}
                </div>
                <div class="w3-col l6 m6 s12 w3-small">
                    Total: {{order.total | currency:"HKD"}}<br>
                    <span ng-show="order.status=='Unpaid' && order.approved=='Approving'">Status: {{order.status}} (Click <a href="#" ng-click="orderPayment(order.orderID)">here</a> to pay)</span>
                    <span ng-show="order.status=='Unpaid' && order.approved=='Rejected'">Status: {{order.approved}}</span>
                    <div class="w3-dropdown-click w3-hover-white" ng-show="order.status=='Paid' || order.status=='Refunded'">
                        <span ng-click="showStatusDetailInModal = !showStatusDetailInModal">Status: {{order.approved}} - {{order.status}} <i class="fa" ng-class="{'fa-caret-down':!showStatusDetail[order.orderID], 'fa-caret-up':showStatusDetail[order.orderID]}"></i></span>
                        <div class="w3-dropdown-content w3-bar-block w3-border w3-padding w3-small w3-hide-large w3-hide-small" style="width:250px !important;right:0" ng-class="{'w3-show':showStatusDetailInModal}">
                            Paid Date: {{(order.paidDate*1000) | date:'d MMM yyyy H:mm'}}
                            <span ng-show="order.refundedDate"><br>Refunded Date: {{(order.refundedDate*1000) | date:'d MMM yyyy H:mm'}}</span>
                            <span ng-show="order.refundedDate"><br>Refunded Amount: {{order.refundedAmount | currency:"HKD"}}</span>
                        </div>
                        <div class="w3-dropdown-content w3-bar-block w3-border w3-padding w3-small w3-hide-medium" style="width:250px !important" ng-class="{'w3-show':showStatusDetailInModal}">
                            Paid Date: {{(order.paidDate*1000) | date:'d MMM yyyy H:mm'}}
                            <span ng-show="order.refundedDate"><br>Refunded Date: {{(order.refundedDate*1000) | date:'d MMM yyyy H:mm'}}</span>
                            <span ng-show="order.refundedDate"><br>Refunded Amount: {{order.refundedAmount | currency:"HKD"}}</span>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
                
                
    <div class="w3-row w3-border w3-white">
        <div class="w3-col w3-border-bottom" ng-show="order.package">
            <div class="w3-row">
                <div class="w3-col l7 m12 s12 w3-padding w3-padding-16">
                    <img src="images/product-package-{{order.package.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                    <span style="font-weight:bold;cursor:pointer" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', category: 'package', productID: order.package.productID, order:order})">{{order.package.productName}}</span>
                    <span class="w3-small"><br>Category: Package</span>
                </div>
                <div class="w3-col l5 m12 s12 w3-padding w3-padding-16">
                    <span class="w3-small">Quantity: 1</span>
                    <span class="w3-small"><br>Original unit price: 
                        <span ng-style="{'text-decoration':order.package.originalPrice != order.package.nowPrice ? 'line-through' : ''}">{{order.package.originalPrice | currency:"HKD"}}</span>
                        <span ng-show="order.package.discount">({{order.package.discount}}% OFF)</span>
                    </span>
                    <span class="w3-small" ng-show="order.package.originalPrice != order.package.nowPrice"><br>Sale unit price: {{order.package.nowPrice | currency:"HKD"}}</span>
                    <span class="w3-small"><br>Sub-total: {{order.package.subTotal | currency:"HKD"}}</span>
                </div>
            </div>
        </div>  

        <div class="w3-col w3-border-bottom" ng-show="order.bottle">
            <div class="w3-row">
                <div class="w3-col l7 m12 s12 w3-padding w3-padding-16">
                    <img src="images/product-bottle-{{order.bottle.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                    <span style="font-weight:bold;cursor:pointer" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', category: 'bottle', productID: order.bottle.productID, order:order})">{{order.bottle.productName}}</span>
                    <span class="w3-small"><br>Category: Bottle</span>
                    <span class="w3-small"><br>Capacity: {{order.bottle.bottleCapacity}} mL</span>
                </div>
                <div class="w3-col l5 m12 s12 w3-padding w3-padding-16">
                    <span class="w3-small">Quantity: 1</span>
                    <span class="w3-small"><br>Original unit price: 
                        <span ng-style="{'text-decoration':order.bottle.originalPrice != order.bottle.nowPrice ? 'line-through' : ''}">{{order.bottle.originalPrice | currency:"HKD"}}</span> 
                        <span ng-show="order.bottle.discount">({{order.bottle.discount}}% OFF)</span>
                    </span>
                    <span class="w3-small" ng-show="order.bottle.originalPrice != order.bottle.nowPrice"><br>Sale unit price: {{order.bottle.nowPrice | currency:"HKD"}}</span>
                    <span class="w3-small"><br>Sub-total: {{order.bottle.subTotal | currency:"HKD"}}</span>
                </div>
            </div>
        </div>   

        <div ng-show="order.perfume.length > 0">
            <div class="w3-col" ng-repeat="perfume in order.perfume" ng-class="{'w3-border-bottom':order.perfume.length != $index + 1}">


                <div class="w3-row">
                    <div class="w3-col l7 m12 s12 w3-padding w3-padding-16">
                        <img src="images/product-perfume-{{perfume.productID}}.jpg" class="w3-left w3-margin-right" style="width:86px">
                        <span style="font-weight:bold;cursor:pointer"  ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', category: 'perfume', productID: perfume.productID, order:order})">{{perfume.productName}}</span>
                        <span class="w3-small"><br>Category: Perfume - {{perfume.categoryName}}</span>
                        <span class="w3-small"><br>Note: {{perfume.note}}</span>
                    </div>
                    <div class="w3-col l5 m12 s12 w3-padding w3-padding-16">
                        <span class="w3-small">Quantity: {{perfume.quantity}} mL</span>
                        <span class="w3-small"><br>Original unit price: 
                            <span ng-style="{'text-decoration':perfume.originalPrice != perfume.nowPrice ? 'line-through' : ''}">{{perfume.originalPrice | currency:"HKD"}} per mL</span> 
                            <span ng-show="perfume.discount">({{perfume.discount}}% OFF)</span>
                        </span>
                        <span class="w3-small" ng-show="perfume.originalPrice != perfume.nowPrice"><br>Sale unit price: {{perfume.nowPrice | currency:"HKD"}} per mL</span>
                    </div>
                </div>



            </div> 
        </div>

    </div>
</div>
