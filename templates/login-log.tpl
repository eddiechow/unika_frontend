<h5 class="w3-margin-left">Login Log</h5>

<div class="w3-margin w3-theme-l4 w3-card-2">
    <div class="w3-row-padding">
        <div class="w3-col s12 l3 m6">
            <h6 class="w3-padding-top" style="font-weight: bold"><span class="w3-hide-small">Status</span><span class="w3-hide-medium w3-hide-large" ng-click="showStatus = !showStatus">Status <i class="fa" ng-class="{'fa-caret-down':!showStatus, 'fa-caret-up':showStatus}"></i></span></h6> 
            <input class="w3-input w3-border" ng-class="{'w3-hide-small':!showStatus}" type="text" ng-model="search.status" ng-change="logFilter(search)">     
        </div>
    </div>
    
    <h6 class="w3-padding-left w3-hide-small" style="font-weight: bold">Date Range</h6>
    <h6 class="w3-padding-left w3-hide-medium w3-hide-large" style="font-weight: bold" ng-class="{'w3-padding-bottom':!showDateRange}" ng-click="showDateRange = !showDateRange">
        Date Range <i class="fa" ng-class="{'fa-caret-down':!showDateRange, 'fa-caret-up':showDateRange}"></i></span>
    </h6>
    <div class="w3-row-padding" ng-class="{'w3-hide-small':!showDateRange}">
        <div class="w3-col s12 l2 m2 w3-margin-bottom">
            <label class="w3-hide-small">&nbsp;</label>
            <a class="w3-button w3-white w3-border" style="width:100%;padding:9px" ng-click="dateRangeAll = !dateRangeAll"  ng-class="{'w3-theme-l1' : dateRangeAll}">All</a>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>Start</label>
            <input class="w3-input w3-border" type="date" ng-model="search.dateFrom" ng-change="logFilter(search)" ng-disabled="dateRangeAll" required>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>End</label>
            <input class="w3-input w3-border" type="date" ng-model="search.dateTo" ng-change="logFilter(search)" ng-disabled="dateRangeAll" required>
        </div>       
    </div>
       
</div>

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

<ul class="w3-ul w3-card-2 w3-white w3-margin-left w3-margin-right w3-margin-bottom" ng-if="getLogs().length>0">
    <li class="w3-display-container" ng-repeat="log in (getLogs() | orderBy:'-date') | limitTo:pageSize:(currentPage*pageSize)">
        <div class="w3-row">
            <div class="w3-col l4 m6"><strong>Time:</strong> {{(log.date*1000) | date:'d MMM yyyy H:mm'}}</div>
            <div class="w3-col l3 m6 w3-hide-small w3-hide-large"><strong>IP:</strong> {{log.ip}}</div>
            <div class="w3-col l8 m12"><strong>Status:</strong> {{log.status}}</div>
            <div class="w3-col l4 m6"><strong>Operating System:</strong> {{log.os}}</div>
            <div class="w3-col l5 m6"><strong>Browser:</strong> {{log.browser}}</div>
            <div class="w3-col l3 w3-hide-medium"><strong>IP:</strong> {{log.ip}}</div>
        </div>
    </li>
</ul>

<ul class="w3-panel w3-white w3-card-2 w3-margin-left w3-margin-right" ng-if="getLogs().length<=0">
    <p>No Record</p>
</ul>
            