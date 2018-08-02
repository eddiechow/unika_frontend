<h5 class="w3-margin-left">Products - {{categoryName}}<span ng-show="perfumeCategoryName"> - {{perfumeCategoryName}}</span></h5>
    
<div class="w3-margin w3-theme-l4 w3-card-2" ng-init="sortBy='productName';reverse=false">
    <div class="w3-row-padding">
        <div class="w3-col s12 l3 m6">
            <h6 class="w3-padding-top" style="font-weight: bold"><span class="w3-hide-small">Sort by</span><span class="w3-hide-medium w3-hide-large" ng-click="showSortBy = !showSortBy">Sort by <i class="fa" ng-class="{'fa-caret-down':!showSortBy, 'fa-caret-up':showSortBy}"></i></span></h6> 
            <div class="w3-bar w3-small w3-border w3-white w3-margin-bottom" ng-class="{'w3-hide-small':!showSortBy}" style="display:flex;display:-webkit-flex;">
                <select class="w3-bar-item w3-input w3-medium" ng-model="sortBy" style="width:100%">
                    <option value="productName">Name</option>
                    <option value="originalPrice">Price</option>
                    <option ng-show="category=='perfume' && perfumeCategoryCode==null" value="categoryName">Category</option>
                    <option ng-show="category=='bottle'" value="bottleCapacity">Capacity</option>
                </select>
                <a href="#" ng-click="reverse=false" class="w3-bar-item w3-button w3-border-left" ng-class="{'w3-theme-l1' : reverse==false}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <a href="#" ng-click="reverse=true" class="w3-bar-item w3-button w3-border-left" ng-class="{'w3-theme-l1' : reverse==true}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
            </div>        
        </div>
        <div class="w3-col l9 w3-hide-medium w3-hide-small" ng-show="category == 'perfume'">
            <h6 class="w3-padding-top" style="font-weight: bold">Category</h6>
            <div class="w3-bar w3-border w3-white w3-margin-bottom">
                <a class="w3-bar-item w3-button" style="padding:7px" ng-class="{'w3-theme-l1' : perfumeCategoryCode == null}" href="product/perfume">All</a>
                <a class="w3-bar-item w3-button" style="padding:7px" ng-repeat="perfumeCategory in perfumeCategories" ng-class="{'w3-theme-l1' : perfumeCategoryCode == perfumeCategory.perfumeCategoryCode}" href="product/perfume/{{perfumeCategory.perfumeCategoryCode}}">{{perfumeCategory.categoryNameEnUs}}</a>
            </div>
        </div>   
    </div>

    <h6 class="w3-padding-left" ng-class="{'w3-padding-bottom':!showFilterBy}" style="font-weight: bold"><span class="w3-hide-small">Filter by</span><span class="w3-hide-medium w3-hide-large" ng-click="showFilterBy = !showFilterBy">Filter by <i class="fa" ng-class="{'fa-caret-down':!showFilterBy, 'fa-caret-up':showFilterBy}"></i></span></h6>
    <div class="w3-row-padding" ng-class="{'w3-hide-small':!showFilterBy}">
        <div class="w3-col s12 w3-margin-bottom" ng-class="{'l9 m7':category != 'bottle','l6 m12':category == 'bottle'}">
            <label>Product Name</label>
            <input class="w3-input w3-border" type="text" placeholder="Search product..." ng-model="search.productName" ng-change="productFilter(search)">
        </div>


        <div class="w3-col s12 w3-margin-bottom" ng-class="{'l3 m5':category != 'bottle','l3 m6':category == 'bottle'}">
            <label>Price</label>
            <div class="w3-row">
                <div class="w3-col" style="width:calc(50% - 17px)">
                    <input class="w3-input w3-border" type="number" min="1" maxlength="4" ng-model="search.priceFrom" ng-change="productFilter(search)">
                </div>
                <div class="w3-col" style="width: 34px; padding: 9px; text-align: center">
                    to
                </div>
                <div class="w3-col" style="width:calc(50% - 17px)">
                    <input class="w3-input w3-border" type="number" max="9999" maxlength="4" ng-model="search.priceTo" ng-change="productFilter(search)">
                </div>
            </div>
        </div>

        <div class="w3-col s12 w3-margin-bottom" ng-show="category == 'bottle'" ng-class="{'l3 m4':category != 'bottle','l3 m6':category == 'bottle'}">
            <label>Capacity (mL)</label>
            <div class="w3-row">
                <div class="w3-col" style="width:calc(50% - 17px)">
                    <input class="w3-input w3-border" type="number" min="5" maxlength="3" ng-model="search.minCapacity" ng-change="productFilter(search)">
                </div>
                <div class="w3-col" style="width: 34px; padding: 9px; text-align: center">
                    to
                </div>
                <div class="w3-col" style="width:calc(50% - 17px)">
                    <input class="w3-input w3-border" type="number" max="300" maxlength="3" ng-model="search.maxCapacity" ng-change="productFilter(search)">
                </div>
            </div>
        </div>            
    </div>

    <h6 class="w3-hide-small w3-padding-left" style="font-weight: bold">Release Date Range</h6>
    <h6 class="w3-hide-medium w3-hide-large w3-padding-left" ng-class="{'w3-padding-bottom':!showReleaseDateRange}" style="font-weight: bold" ng-click="showReleaseDateRange = !showReleaseDateRange">
        Release Date Range <i class="fa" ng-class="{'fa-caret-down':!showReleaseDateRange, 'fa-caret-up':showReleaseDateRange}"></i>
    </h6>
    <div class="w3-row-padding" ng-class="{'w3-hide-small':!showReleaseDateRange}">
        <div class="w3-col s12 l2 m2 w3-margin-bottom">
            <label class="w3-hide-small">&nbsp;</label>
            <a class="w3-button w3-white w3-border" style="width:100%;padding:9px" ng-click="releaseDateRangeAll = !releaseDateRangeAll"  ng-class="{'w3-theme-l1' : releaseDateRangeAll}">All</a>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>Start</label>
            <input class="w3-input w3-border" type="date" ng-model="search.releaseDateFrom" ng-change="productFilter(search)" ng-disabled="releaseDateRangeAll" required>
        </div>

        <div class="w3-col s12 l5 m5 w3-margin-bottom">
            <label>End</label>
            <input class="w3-input w3-border" type="date" ng-model="search.releaseDateTo" ng-change="productFilter(search)" ng-disabled="releaseDateRangeAll" required>
        </div>       
    </div>
</div>

<div class="w3-panel w3-red w3-margin-left w3-margin-right" ng-if="cartError">
  <p>{{cartError}}</p>
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

<div class="w3-row-padding">
    <div class="w3-col l3 m6 w3-margin-bottom" ng-repeat="product in (getProducts() | orderBy:sortBy:reverse) | limitTo:pageSize:(currentPage*pageSize)" ng-init="selected[product.productID].quantity = 1;showPerfumeSelection[product.productID]=false">
        <div class="w3-display-container w3-card-2 w3-white">
            <div class="w3-display-container">
                <img src="images/product-{{product.category}}-{{product.productID}}.jpg" style="width:100%">
                <span ng-show="getTimeNow()<((product.releaseDate*1000)+(14 * 24 * 60 * 60 * 1000))" class="w3-badge w3-small w3-red w3-display-topleft" style="width:45px;height:45px;line-height:45px;margin:5px;transform:rotate(-30deg)">NEW</span>
                <span ng-show="product.discount" class="w3-tag w3-tiny w3-theme-d2 w3-display-topright">{{product.discount}}%<br>OFF</span>
                <span ng-show="product.discountTitle" class="w3-small w3-theme-l2 w3-display-bottomleft" style="padding:4px">{{product.discountTitle}}</span>
            </div>
            <div class="w3-padding w3-small">
                <p style="font-weight:bold">{{product.productName}}</p>
                <p ng-show="product.category == 'perfume' && product.perfumeCategoryName">Category: {{product.perfumeCategoryName}}</p>
                <p ng-show="product.category == 'bottle'">Capacity: {{product.bottleCapacity}} mL</p>
                <p>Release Date: {{(product.releaseDate*1000) | date:'d MMM yyyy'}}</p>
                <p ng-style="{'text-decoration':product.discount!=null ? 'line-through' : ''}">{{product.originalPrice | currency:"HKD"}}<span ng-show="product.category=='perfume'"> per mL</span></p>
                <p><span ng-show="product.discount!=null" style="font-weight:bold">Now {{product.nowPrice | currency:"HKD"}}<span ng-show="product.category=='perfume'"> per mL</span></span><br></p>
                <p>
                    <span ng-show="product.qtyInStock>0">Quantity in Stock: {{product.qtyInStock}}<span ng-show="product.category=='perfume'"> mL</span></span>
                    <span ng-show="product.qtyInStock<=0" class="w3-text-red" style="font-weight:bold">No item in stock</span>
                </p>



                <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="addToCart(product.category, product.productID)"><i class="fa fa-cart-plus" aria-hidden="true"></i> Add to Cart</a></p>
                <p><a class="w3-btn w3-btn-block w3-small w3-theme-action" ng-click="openModal('other', {template: 'product-detail.tpl', maxWidth: '1000px', headerTitle: product.productName, category: product.category, productID: product.productID})">Detail</a></p>
            </div>

            <div class="w3-container w3-display-bottommiddle w3-block w3-khaki" ng-if="product.category == 'perfume' && showPerfumeSelection[product.productID]">
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
<div class="w3-margin-left w3-margin-right w3-margin-bottom w3-container w3-white w3-card-2" ng-show="getProducts().length <= 0 || noItem">
    <p>No Item</p>
</div>    
