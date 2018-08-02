<h5 class="w3-margin-left">Order History</h5>

<div class="w3-margin w3-theme-l4 w3-card-2">
    <div class="w3-row-padding">
        <div class="w3-col s12 l3 m6">
            <h6 class="w3-padding-top" style="font-weight: bold"><span class="w3-hide-small">Status</span><span class="w3-hide-medium w3-hide-large" ng-click="showStatus = !showStatus">Status <i class="fa" ng-class="{'fa-caret-down':!showSortBy, 'fa-caret-up':showSortBy}"></i></span></h6> 
            <div class="w3-bar w3-small w3-border w3-white w3-margin-bottom" ng-class="{'w3-hide-small':!showStatus}" style="display:flex;display:-webkit-flex;">
                <select class="w3-bar-item w3-input w3-medium" ng-model="search.statusIndex" ng-change="orderFilter(search)" style="width:100%">
                    <option value="">All</option>
                    <option value="0">Unpaid</option>
                    <option value="1">Rejected</option>
                    <option value="2">Approving - Paid</option>
                    <option value="3">Rejected - Paid</option>
                    <option value="4">Rejected - Refunded</option>
                    <option value="5">Approved - Paid</option>
                    <option value="6">Approved - Refunded</option>
                </select>
            </div>        
        </div>
    </div>
    
    <h6 class="w3-padding-left w3-hide-small" style="font-weight: bold">Order Date Range</h6>
    <h6 class="w3-padding-left w3-hide-medium w3-hide-large" style="font-weight: bold" ng-class="{'w3-padding-bottom':!showOrderDateRange}" ng-click="showOrderDateRange = !showOrderDateRange">
        Order Date Range <i class="fa" ng-class="{'fa-caret-down':!showOrderDateRange, 'fa-caret-up':showOrderDateRange}"></i></span>
    </h6>
    <div class="w3-row-padding" ng-class="{'w3-hide-small':!showOrderDateRange}">
        <div class="w3-col s12 l2 m2 w3-margin-bottom">
            <label class="w3-hide-small">&nbsp;</label>
            <a class="w3-button w3-white w3-border" style="width:100%;padding:9px" ng-click="orderDateRangeAll = !orderDateRangeAll"  ng-class="{'w3-theme-l1' : orderDateRangeAll}">All</a>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>Start</label>
            <input class="w3-input w3-border" type="date" ng-model="search.orderDateFrom" ng-change="orderFilter(search)" ng-disabled="orderDateRangeAll" required>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>End</label>
            <input class="w3-input w3-border" type="date" ng-model="search.orderDateTo" ng-change="orderFilter(search)" ng-disabled="orderDateRangeAll" required>
        </div>       
    </div>
</div>

<div ng-show="!error">    
    <div ng-if="numberOfPages() > 1" class="w3-show-inline-block w3-margin-left">      
        <div id="pagination-bar" class="w3-bar w3-small w3-border w3-white w3-margin-top">
            <a href="#" class="w3-bar-item w3-button w3-border-right" ng-click="goToFirstPage()" ng-class="{'w3-disabled': currentPage == 0}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
            <a href="#" class="w3-bar-item w3-button" ng-click="goToPreviousPage()" ng-class="{'w3-disabled': currentPage == 0}"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
            <div class="w3-dropdown-click">
                <button id="pagination-button" class="w3-button w3-border-left w3-border-right" ng-click="pageDropdownToggle()" style="width:80px">
                    <span class="w3-left">{{currentPage + 1}}</span><span class="w3-right"><i class="fa" ng-class="{'fa-caret-down':!showPageDropdown,'fa-caret-up':showPageDropdown}" aria-hidden="true"></i></span>
                </button>
                <div id="pagination-dropdown-content" class="w3-dropdown-content w3-border" ng-class="{'w3-show': showPageDropdown}">
                    <a href="#" ng-repeat="page in pageList()" ng-click="changeToPage(page)">{{page}}</a>
                </div>
            </div>
            <a href="#" class="w3-bar-item w3-button" ng-click="goToNextPage()" ng-class="{'w3-disabled': currentPage >= numberOfPages() - 1}"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            <a href="#" class="w3-bar-item w3-button w3-border-left" ng-click="goToLastPage()" ng-class="{'w3-disabled': currentPage >= numberOfPages() - 1}"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
        </div>
    </div>

    <div class="w3-row w3-card-2 w3-margin w3-white" ng-repeat="order in (getOrders() | orderBy:'-orderDate') | limitTo:pageSize:(currentPage*pageSize)">
        <div class="w3-col w3-padding w3-padding-16">
            <div class="w3-row">
                <div class="w3-col l5 m7 s12">
                    Order No.: {{order.orderID}}<br>
                    Order Date: {{(order.orderDate*1000) | date:'d MMM yyyy'}}
                    <span class="w3-hide-large"><br>Total: {{order.total | currency:"HKD"}}</span>
                </div>
                <div class="w3-col l5 w3-hide-medium w3-hide-small">
                    Total: {{order.total | currency:"HKD"}}<br>
                    Status: <span ng-show="order.status=='Unpaid' && order.approved=='Approving'">{{order.status}} (Click <a href="#" ng-click="orderPayment(order.orderID)">here</a> to pay)</span>
                    <span ng-show="order.status=='Unpaid' && order.approved=='Rejected'">{{order.approved}}</span>
                    <div class="w3-dropdown-click w3-hover-white" ng-show="order.status=='Paid' || order.status=='Refunded'">
                        <span ng-click="statusDetailToggle(order.orderID)">{{order.approved}} - {{order.status}} <i class="fa" ng-class="{'fa-caret-down':!showStatusDetail[order.orderID], 'fa-caret-up':showStatusDetail[order.orderID]}"></i></span>
                        <div class="w3-dropdown-content w3-bar-block w3-border w3-padding w3-small" style="width:250px !important" ng-class="{'w3-show':showStatusDetail[order.orderID]}">
                            Paid Date: {{(order.paidDate*1000) | date:'d MMM yyyy H:mm'}}
                            <span ng-show="order.refundedDate"><br>Refunded Date: {{(order.refundedDate*1000) | date:'d MMM yyyy H:mm'}}</span>
                            <span ng-show="order.refundedDate"><br>Refunded Amount: {{order.refundedAmount | currency:"HKD"}}</span>
                        </div>
                    </div>
                </div>
                <div class="w3-col l2 m5 s12">
                    <div class="w3-row">
                        <div class="w3-col s10 w3-left-align w3-hide-large w3-hide-medium">
                            <span ng-show="order.status=='Unpaid' && order.approved=='Approving'"><span style="font-weight:bold">{{order.status}}</span> (Click <a href="#" ng-click="orderPayment(order.orderID)">here</a> to pay)</span>
                            <span ng-show="order.status=='Unpaid' && order.approved=='Rejected'" style="font-weight:bold">{{order.approved}}</span>
                            <div class="w3-dropdown-click w3-hover-white" ng-show="order.status=='Paid' || order.status=='Refunded'">
                                <span ng-click="statusDetailToggle(order.orderID)" style="font-weight:bold">{{order.approved}} - {{order.status}} <i class="fa" ng-class="{'fa-caret-down':!showStatusDetail[order.orderID], 'fa-caret-up':showStatusDetail[order.orderID]}"></i></span>
                                <div class="w3-dropdown-content w3-bar-block w3-border w3-padding w3-small" style="width:250px !important;" ng-class="{'w3-show':showStatusDetail[order.orderID]}">
                                    Paid Date: {{(order.paidDate*1000) | date:'d MMM yyyy H:mm'}}
                                    <span ng-show="order.refundedDate"><br>Refunded Date: {{(order.refundedDate*1000) | date:'d MMM yyyy H:mm'}}</span>
                                    <span ng-show="order.refundedDate"><br>Refunded Amount: {{order.refundedAmount | currency:"HKD"}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w3-col s2 m12 l12 w3-right-align"><span class="w3-hide-small w3-hide-large">
                            <span ng-show="order.status=='Unpaid' && order.approved=='Approving'"><span style="font-weight:bold">{{order.status}}</span> (Click <a href="#" ng-click="orderPayment(order.orderID)">here</a> to pay)</span>
                            <span ng-show="order.status=='Unpaid' && order.approved=='Rejected'" style="font-weight:bold">{{order.approved}}</span>
                            <div class="w3-dropdown-click w3-hover-white" ng-show="order.status=='Paid' || order.status=='Refunded'">
                                <span ng-click="statusDetailToggle(order.orderID)" style="font-weight:bold">{{order.approved}} - {{order.status}} <i class="fa" ng-class="{'fa-caret-down':!showStatusDetail[order.orderID], 'fa-caret-up':showStatusDetail[order.orderID]}"></i></span>
                                <div class="w3-dropdown-content w3-bar-block w3-border w3-padding w3-small w3-left-align" style="width:250px !important;right:0;" ng-class="{'w3-show':showStatusDetail[order.orderID]}">
                                    Paid Date: {{(order.paidDate*1000) | date:'d MMM yyyy H:mm'}}
                                    <span ng-show="order.refundedDate"><br>Refunded Date: {{(order.refundedDate*1000) | date:'d MMM yyyy H:mm'}}</span>
                                    <span ng-show="order.refundedDate"><br>Refunded Amount: {{order.refundedAmount | currency:"HKD"}}</span>
                                </div>
                            </div>  
                            <br>
                            </span><a href="#" ng-click="openModal('other', {template: 'order-detail.tpl', maxWidth: '1000px', headerTitle: 'Order Detail', order: order})">Order Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="w3-margin-left w3-margin-right w3-padding w3-white w3-border" ng-show="error">
    {{error}}
</div>

