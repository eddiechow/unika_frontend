<?php
    require 'models/configuration.php';
    $version = time(); 
?>
<!DOCTYPE html>
<html ng-app="unika">
    <head>        
        <title>UNIKA</title>
        <base href="<?= ROOT_PATH ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">        
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/w3.css?version=<?= $version ?>">
        <link rel="stylesheet" href="css/theme.css?version=<?= $version ?>">
        <link rel="stylesheet" href="css/style.css?version=<?= $version ?>">
        <link rel="shortcut icon" href="favicon.ico" />
        <script src="libs/angular.min.js?version=<?= $version ?>" type="text/javascript"></script>
        <script src="libs/angular-route.min.js?version=<?= $version ?>" type="text/javascript"></script>
        <script src="app.js?version=<?= $version ?>" type="text/javascript"></script>
        <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBi6go6yKF7YnYQi-chEkwtAuX0ZdJnWxY&sensor=false" type="text/javascript"></script>
        <?php
        $ctrl = '';
        $ctrlDir = '';
        $ctrlFile = '';
        if ($ctrlDir = opendir('controllers')) {
            while (false !== ($ctrlFile = readdir($ctrlDir))) {
                if (is_file('./controllers/' . $ctrlFile)) {
                    if ($ctrlFile != 'index.php') {
                        $ctrl .= '<script src="controllers/' . $ctrlFile . '?version=' . $version . '" type="text/javascript"></script>' . "\n";
                    }
                }
            }
        }
        closedir($ctrlDir);
        echo $ctrl;
        ?>
    </head>
    <body class="w3-theme-l5">
        <div ng-show="getDbError() != undefined && !getDbError()">
            <div ng-controller="sidenavCtrl">
                <div class="w3-white w3-top" style="z-index:6 !important">
                    <div class="w3-bar w3-center w3-bottombar" id="top-menu" style="border-color:#ffe066 !important">
                        <a ng-click="sidebarToggle();" class="w3-nav-item w3-button w3-left w3-padding-12 w3-hide-large w3-hover-amber" ng-class="{'w3-theme' : showSidebar}"><i class="fa fa-bars w3-xlarge"></i></a>
                        <div class="w3-dropdown-click w3-left w3-hide-medium w3-hide-small">
                            <button class="w3-button w3-hover-amber" ng-class="{'w3-amber' : showProductDropdown, 'w3-theme': ((getRoutePath() == '/product/:category' || getRoutePath() == '/product/:category/:perfumeCategory') && !showProductDropdown)}" style="padding:14px !important" ng-click="productDropdownToggle()" >
                                Products&nbsp;<i class="fa" ng-class="{'fa-caret-down':!showProductDropdown, 'fa-caret-up':showProductDropdown}"></i>
                            </button>
                            <div id="product-dropdown-content" class="w3-dropdown-content w3-white w3-border w3-left-align" ng-class="{'w3-show' : showProductDropdown}" style="z-index: 5 !important;">
                                <a href="product/package" class="w3-hover-amber">Package</a>
                                <a href="product/bottle" class="w3-hover-amber">Bottle</a>
                                <a href="product/perfume" class="w3-hover-amber">Perfume</a>
                                <a ng-repeat="perfumeCategory in perfumeCategories" href="product/perfume/{{perfumeCategory.perfumeCategoryCode}}" class="w3-hover-amber">&emsp;{{perfumeCategory.categoryNameEnUs}}</a>
                            </div>
                        </div>
                        <a href="about" class="w3-nav-item w3-hover-amber w3-left w3-button w3-hide-medium w3-hide-small" ng-click="closeSidebar()" ng-class="{'w3-theme': getRoutePath() == '/about'}" style="padding:14px !important">About Us</a>
                        <a href="contactus" class="w3-nav-item w3-hover-amber w3-left w3-button w3-hide-medium w3-hide-small" ng-click="closeSidebar()" ng-class="{'w3-theme': getRoutePath() == '/contactus'}" style="padding:14px !important">Contact Us</a>

                        <a href="." ng-click="closeSidebar()" class="w3-nav-item w3-button w3-padding-8 w3-white w3-hover-white" style="padding-left:0 !important;padding-right:0 !important"><img src="images/logo_small.png" style="height: 34px"></a>

                        <a ng-show="loggedInCustomer() == null" class="w3-nav-item w3-hover-amber w3-button w3-right w3-padding-12" ng-click="closeSidebar();openModal('other', {template: 'login.tpl', width: '350px', headerTitle: 'Login'});">
                            <i class="fa fa-sign-in w3-xlarge"></i><span class="w3-hide-small w3-right">&nbsp;Login</span>
                        </a>
                        <div ng-show="loggedInCustomer() != null" class="w3-dropdown-click w3-right">
                            <button class="w3-button w3-hover-amber w3-padding-12" ng-class="{'w3-amber' : showAccountDropdown}" ng-click="closeSidebar();accountDropdownToggle()"> 
                                <i class="fa fa-user w3-xlarge"></i><span class="w3-hide-small w3-right">&nbsp;{{loggedInCustomer().customerGivenName}}&nbsp;<i class="fa" ng-class="{'fa-caret-down':!showAccountDropdown, 'fa-caret-up':showAccountDropdown}"></i></span>
                            </button>
                            <div id="account-dropdown-content" class="w3-dropdown-content w3-border w3-left-align" ng-click="closeSidebar()" ng-class="{'w3-show' : showAccountDropdown}" style="right:0;z-index:7 !important;">
                                <a href="orders" class="w3-hover-amber">Order History</a>
                                <a href="login-log" class="w3-hover-amber">Login Log</a>
                                <a href="edit-profile" class="w3-hover-amber">Edit Profile</a>
                                <a href="#" class="w3-hover-amber" ng-click="logout()">Logout</a>
                            </div>
                        </div>
                        <a href="cart" class="w3-nav-item w3-hover-amber w3-button w3-right w3-padding-12" ng-click="closeSidebar()"><i class="fa fa-shopping-cart w3-xlarge"></i><span class="w3-hide-small w3-right">&nbsp;Cart</span><span class="w3-badge w3-right w3-tiny" style="width:22px;height:22px;line-height:22px;padding:0" ng-show="loggedInCustomer().itemNumInCart">{{loggedInCustomer().itemNumInCart}}</span></a>
                    </div>
                </div>

                <nav class="w3-sidenav w3-white w3-animate-left w3-hide-large w3-border-top" ng-class="{'w3-show' : showSidebar}"> 
                    <div class="w3-accordion">
                        <a ng-click="showAccordionDropdown = !showAccordionDropdown" ng-class="{'w3-amber' : showAccordionDropdown, 'w3-theme': ((getRoutePath() == '/product/:category' || getRoutePath() == '/product/:category/:perfumeCategory') && !showAccordionDropdown)}" class="w3-hover-amber w3-padding-8 w3-border-bottom">Products <i class="fa" ng-class="{'fa-caret-down':!showAccordionDropdown, 'fa-caret-up':showAccordionDropdown}"></i></a>
                        <div class="w3-accordion-content w3-hide w3-border-bottom" ng-click="closeSidebar()" ng-class="{'w3-show' : showAccordionDropdown}">
                            <a href="product/package" class="w3-hover-amber w3-padding-8">Package</a>
                            <a href="product/bottle" class="w3-hover-amber w3-padding-8">Bottle</a>
                            <a href="product/perfume" class="w3-hover-amber w3-padding-8">Perfume</a>
                            <a ng-repeat="perfumeCategory in perfumeCategories" href="product/perfume/{{perfumeCategory.perfumeCategoryCode}}" class="w3-hover-amber w3-padding-8">&emsp;{{perfumeCategory.categoryNameEnUs}}</a>
                        </div>
                    </div>
                    <a href="about" ng-click="closeSidebar()" class="w3-hover-amber w3-padding-8 w3-border-bottom"  ng-class="{'w3-theme': getRoutePath() == '/about'}">About Us</a>
                    <a href="contactus" ng-click="closeSidebar()" class="w3-hover-amber w3-padding-8 w3-border-bottom"  ng-class="{'w3-theme': getRoutePath() == '/contactus'}">Contact Us</a>
                </nav>

                <div class="w3-overlay w3-animate-opacity w3-hide-large" style="z-index:5 !important" ng-click="closeSidebar()" ng-class="{'w3-show' : showSidebar}"></div>
            </div>

            
            <div ng-style="{'min-height':'calc(100vh - '+footerHeight+'px - '+topMenuHeight+'px)','margin-top':topMenuHeight+'px'}">
                <div ng-class="{'w3-white w3-border-bottom':getRoutePath()=='/'}">
                    <div class="w3-content" ng-style="{'max-width':'1310px'}">
                        <div class="w3-padding-top w3-padding-left w3-padding-right"ng-if="!loggedInCustomer().activated && loggedInCustomer() != null">
                            <div class="w3-container w3-yellow">
                                <p>Your email address is not verified. Please <a style="cursor:pointer;text-decoration:underline;" ng-click="openModal('other', {template: 'emailVerification.tpl', width: '400px'})">click</a> here to verify email.</p>
                            </div>
                        </div>
                        <div ng-show="getRoutePath()=='/'" class="w3-padding-top w3-display-container w3-padding-bottom" ng-controller="slideshowCtrl">
                            <div class="w3-content w3-center" style="max-width:1500px">
                                <div class="w3-hide-medium w3-hide-large" style="height:5vh;min-height:70px"></div>
                                <div class="w3-hide-small w3-hide-large" style="height:2vh;min-height:6px"></div>
                                <img src="images/slideshow.png" style="width:90%">
                            </div>
                            
                            <div class="w3-display-container">
                                <div class="w3-display-bottommiddle w3-center" style="color:#dbc448;width:90vw">   
                                    <div ng-repeat="slideshow in slideshow" ng-class="{'w3-animate-right':!clickLeft,'w3-animate-left':clickLeft}" ng-if="slideshowDisplay[$index]">
                                        <span class="w3-hide-small w3-hide-medium" style="font-size:7vh;padding:0"><span ng-show="!slideshow.discountCode">{{slideshow.content}}</span><a ng-show="slideshow.discountCode" href="discount-product/{{slideshow.discountCode}}" style="text-decoration:none">{{slideshow.content}}</a><br></span>
                                        <span class="w3-hide-large" style="font-size:8.1vw;padding:0"><span ng-show="!slideshow.discountCode">{{slideshow.content}}</span><a ng-show="slideshow.discountCode" href="discount-product/{{slideshow.discountCode}}" style="text-decoration:none">{{slideshow.content}}</a><br></span>
                                        <span class="w3-hide-small w3-hide-medium" style="font-size:5vh;padding:0" ng-show="slideshow.discount">{{slideshow.discount}}% OFF<br></span>
                                        <span class="w3-hide-large" style="font-size:4.8vw;padding:0" ng-show="slideshow.discount">{{slideshow.discount}}% OFF<br></span>
                                    </div>
                                    <div class="w3-hide-small w3-hide-medium" ng-show="slideshow.length<=0"><img src="images/logo_small.png" style="height:160px"><br></div>
                                    <div class="w3-hide-large" ng-show="slideshow.length<=0"><img src="images/logo_small.png" style="width:60vw"><br></div>
                                    <div class="w3-hide-small w3-hide-medium" style="height:43vw;max-height:550px"></div>
                                    <div class="w3-hide-large" style="height:41vw;min-height:80px;max-height:550px"></div>
                                </div>
                            </div>
                            
                            
                            <div ng-show="slideshow.length>1">
                                <a href="#" class="w3-display-left w3-text-yellow w3-hover-text-amber w3-margin-left w3-hide-small" ng-click="clickLeft=true;showDivs(slideshowIndex-1)"><i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i></a>
                                <a href="#" class="w3-display-left w3-text-yellow w3-hover-text-amber w3-hide-medium w3-hide-large" ng-click="clickLeft=true;showDivs(slideshowIndex-1)" style="margin-left:10px"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>

                                <a href="#" class="w3-display-right w3-text-yellow w3-hover-text-amber w3-margin-right w3-hide-small" ng-click="clickLeft=false;showDivs(slideshowIndex+1)"><i class="fa fa-chevron-right fa-2x" aria-hidden="true"></i></a>
                                <a href="#" class="w3-display-right w3-text-yellow w3-hover-text-amber w3-hide-medium w3-hide-large" ng-click="clickLeft=false;showDivs(slideshowIndex+1)" style="margin-right:10px"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>                                
                            </div>
                        </div>
                    </div> 
                </div>     
                <div class="w3-content w3-padding-top w3-padding-bottom" style="max-width:1310px"><ng-view ng-if="getShowView()"></ng-view></div>
            </div>
            

            <footer class="w3-padding-large w3-center w3-theme-d5">
                <span class="w3-small">
                    Tel: +852 9072 4756<br>
                    &copy; PIMS Service Hong Kong Limited 
                </span>
            </footer>

            <div class="w3-modal" id="modal" ng-controller="modalCtrl" style="padding-top:3% !important">
                <div class="w3-modal-content w3-card-2" id="modal-content">
                    <header class="w3-container" ng-show="option.headerTitle" style="border-bottom: 1px #ccc solid"> 
                        <span class="w3-closebtn w3-hover-red w3-container w3-display-topright" ng-click="closeModal()" style="width: 56px; height: 56px; text-align: center; line-height: 56px"><i class="fa fa-times"></i></span>
                        <h3>{{option.headerTitle}}</h3>
                    </header>
                    <div class="w3-container" style="overflow-y: auto" ng-show="template"><ng-include src="template"></ng-include></div>  
                </div>
            </div>            

        </div>
        <div ng-show="getDbError() != undefined && getDbError()" class="w3-content w3-padding-64 w3-container" style="min-height:100vh;max-width:600px;">
            <div class="w3-row-padding w3-padding-top w3-padding-bottom w3-border w3-white">
                <div class="w3-col s12 m4 l5">
                    <img src="images/icon_large.png" style="width:100%">
                </div>
                <div class="w3-col s12 m8 l7">
                    <br>
                    <div class="w3-center">
                        <span class="w3-xlarge">Database Error</span><br>
                        <span>Please <a href="#" onclick="window.location.reload()">click</a> here to refresh</span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>