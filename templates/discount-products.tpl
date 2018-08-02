<div class="w3-margin-left w3-margin-right">
    <div class="w3-cell-row">
        <div class="w3-cell w3-mobile w3-white w3-cell-middle w3-xlarge w3-center w3-border w3-border-deep-orange" style="width:70%;border-width:5px !important">
            {{discountTitle}}
        </div>
        <div class="w3-cell w3-mobile w3-deep-orange w3-cell-middle w3-center">
            <span class="w3-xxlarge">{{discount}}%</span> <span class="w3-xlarge">OFF</span>
        </div>
    </div>        
</div>

<div class="w3-margin w3-theme-l4 w3-card-2">
    <div class="w3-row">
        <div class="w3-col s12 l3 m6" ng-init="sortBy='productName';reverse=false">
            <h6 class="w3-padding-left" style="font-weight: bold"><span class="w3-hide-small">Sort by</span><span class="w3-hide-medium w3-hide-large" ng-click="showSortBy = !showSortBy">Sort by <i class="fa" ng-class="{'fa-caret-down':!showSortBy, 'fa-caret-up':showSortBy}"></i></span></h6> 
            <div class="w3-row-padding" ng-class="{'w3-hide-small':!showSortBy}">
                <div class="w3-col">
                    <div class="w3-bar w3-small w3-border w3-white w3-margin-bottom" style="display:flex;display:-webkit-flex;">
                        <select class="w3-bar-item w3-input w3-medium" ng-model="sortBy" style="width:100%">
                            <option value="productName">Name</option>
                            <option value="originalPrice">Price</option>
                            <option value="categoryName">Category</option>
                        </select>
                        <a href="#" ng-click="reverse=false" class="w3-bar-item w3-button w3-border-left" ng-class="{'w3-theme-l1' : reverse==false}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                        <a href="#" ng-click="reverse=true" class="w3-bar-item w3-button w3-border-left" ng-class="{'w3-theme-l1' : reverse==true}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div> 
        </div>
        <div class="w3-col m6 w3-hide-large">
            <h6 class="w3-padding-left" style="font-weight: bold"><span class="w3-hide-small">Category</span><span class="w3-hide-medium w3-hide-large" ng-click="showCategory = !showCategory">Category <i class="fa" ng-class="{'fa-caret-down':!showCategory, 'fa-caret-up':showCategory}"></i></span></h6> 
            <div class="w3-row-padding" ng-class="{'w3-hide-small':!showCategory}">
                <div class="w3-col">
                    <div class="w3-dropdown-click w3-margin-bottom" style="width:100%">
                        <button class="w3-button w3-border w3-white" ng-click="showCategoryDropdown = !showCategoryDropdown" style="width:100%">
                            <span class="w3-left">
                                {{search.category == '' && 'All' || search.category == 'package' && 'Package' || search.category == 'bottle' && 'Bottle' || search.category == 'perfume' && (!search.perfumeCategoryName && 'Perfume (All)' || search.perfumeCategoryName && 'Perfume ('+search.perfumeCategoryName+')')}}
                            </span>
                            <span class="w3-right"><i class="fa" ng-class="{'fa-caret-down':!showCategoryDropdown,'fa-caret-up':showCategoryDropdown}" aria-hidden="true"></i></span>
                        </button>
                        <div class="w3-dropdown-content w3-border" ng-click="showCategoryDropdown = false" ng-class="{'w3-show': showCategoryDropdown}" style="width:100%;max-height:150px;overflow-y:auto;z-index:2">
                            <a href="#" ng-click="search.category='';productFilter(search)">All</a>
                            <a href="#" ng-click="search.category='package';productFilter(search)">Package</a>
                            <a href="#" ng-click="search.category='bottle';productFilter(search)">Bottle</a>
                            <a href="#" ng-click="search.category='perfume';search.perfumeCategoryCode='';shownPerfumeCategoryName=null;search.perfumeCategoryName=null;productFilter(search)">Perfume (All)</a>
                            <a href="#" ng-repeat="perfumeCategory in perfumeCategories" ng-click="search.category='perfume';search.perfumeCategoryCode=perfumeCategory.perfumeCategoryCode;search.perfumeCategoryName=perfumeCategory.categoryNameEnUs;productFilter(search)">Perfume ({{perfumeCategory.categoryNameEnUs}})</a>
                        </div>
                    </div>
                </div>
            </div> 
        </div>  
        <div class="w3-col l9 w3-hide-medium w3-hide-small">
            <h6 class="w3-padding-left" style="font-weight: bold">Category</h6>
            <div class="w3-row-padding">
                <div class="w3-col">
                    <div class="w3-bar w3-border w3-white w3-margin-bottom">
                        <a class="w3-bar-item w3-button" style="padding:7px" href="#" ng-class="{'w3-theme-l1' : search.category == ''}" ng-click="search.category='';productFilter(search)">All</a>
                        <a class="w3-bar-item w3-button" style="padding:7px" href="#" ng-class="{'w3-theme-l1' : search.category == 'package'}" ng-click="search.category='package';productFilter(search)">Package</a>
                        <a class="w3-bar-item w3-button" style="padding:7px" href="#" ng-class="{'w3-theme-l1' : search.category == 'bottle'}" ng-click="search.category='bottle';productFilter(search)">Bottle</a>
                        <div class="w3-dropdown-click w3-left w3-hide-medium w3-hide-small">
                            <button class="w3-button" ng-class="{'w3-theme-l1' : search.category == 'perfume'}" style="padding:7px !important" ng-click="perfumeCategoryMenuShow = !perfumeCategoryMenuShow" >
                                Perfume <span ng-if="search.category == 'perfume'">({{search.perfumeCategoryCode=='' && 'All' || shownPerfumeCategoryName}})</span>&nbsp;<i class="fa" ng-class="{'fa-caret-down':!perfumeCategoryShow, 'fa-caret-up':perfumeCategoryShow}"></i>
                            </button>
                            <div class="w3-dropdown-content w3-white w3-border w3-left-align" ng-class="{'w3-show' : perfumeCategoryMenuShow}" ng-click="perfumeCategoryMenuShow = false">
                                <a href="#" ng-click="search.category='perfume';search.perfumeCategoryCode='';shownPerfumeCategoryName=null;search.perfumeCategoryName=null;productFilter(search)">All</a>
                                <a ng-repeat="perfumeCategory in perfumeCategories" href="#" ng-click="search.category='perfume';search.perfumeCategoryCode=perfumeCategory.perfumeCategoryCode;search.perfumeCategoryName=perfumeCategory.categoryNameEnUs;productFilter(search)">{{perfumeCategory.categoryNameEnUs}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>  
    </div>

    <h6 class="w3-padding-left" ng-class="{'w3-padding-bottom':!showFilterBy}" style="font-weight: bold"><span class="w3-hide-small">Filter by</span><span class="w3-hide-medium w3-hide-large" ng-click="showFilterBy = !showFilterBy">Filter by <i class="fa" ng-class="{'fa-caret-down':!showFilterBy, 'fa-caret-up':showFilterBy}"></i></span></h6>
    <div class="w3-row-padding" ng-class="{'w3-hide-small':!showFilterBy}">
        <div class="w3-col s12 l9 m7 w3-margin-bottom">
            <label>Product Name</label>
            <input class="w3-input w3-border" type="text" placeholder="Search product..." ng-model="search.productName" ng-change="productFilter(search)">
        </div>


        <div class="w3-col s12 l3 m5 w3-margin-bottom">
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
    </div>

    <h6 class="w3-padding-left" ng-class="{'w3-padding-bottom':!showReleaseDateRange}" style="font-weight: bold"><span class="w3-hide-small">Release Date Range</span><span class="w3-hide-medium w3-hide-large" ng-click="showReleaseDateRange = !showReleaseDateRange">Release Date Range <i class="fa" ng-class="{'fa-caret-down':!showReleaseDateRange, 'fa-caret-up':showReleaseDateRange}"></i></span></h6>
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
    <div class="w3-col l3 m6 w3-margin-bottom" ng-repeat="product in (getProducts() | orderBy:(sortBy=='categoryName'?['categoryName','perfumeCategoryName']:sortBy):reverse) | limitTo:pageSize:(currentPage*pageSize)" ng-init="selected[product.productID].quantity = 1;showPerfumeSelection[product.productID]=false">
        <div class="w3-display-container w3-card-2 w3-white">
            <img src="images/product-{{product.category}}-{{product.productID}}.jpg" style="width:100%">
            <div class="w3-padding w3-small">
                <p style="font-weight:bold">{{product.productName}}</p>
                <p>Category: {{product.categoryName}}<span ng-show="product.category=='perfume'"> - {{product.perfumeCategoryName}}</span></p>
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