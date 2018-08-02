<h5 class="w3-margin-left">Contact Us</h5>
<div class="w3-row-padding">
    <div class="w3-col l8 m7 s12">
        <label style="font-weight:bold">Tel</label>
        <div class="w3-margin-bottom">+852 9072 4756</div>
        <label style="font-weight:bold">Facebook</label>
        <div class="w3-margin-bottom">
            <i class="fa fa-facebook-square"></i> 
            <a href="//www.facebook.com/PIMSTech" target="_blank">PIMS Facebook Page</a>
        </div> 
        <label style="font-weight:bold">Address</label>
        <div class="w3-margin-bottom">
            Unit 1802, 18/F, Tamsin Plaza, 
            No.161 Wai Yip Street, 
            Kwun Tong, Hong Kong <br>
            <div id="map" style="width:100%;height:300px"></div>
        </div>
        <div class="w3-hide-medium w3-hide-large w3-padding-12"></div>
    </div>
    <div class="w3-col l4 m5 s12">
        <div class="w3-margin-bottom w3-container" ng-if="error.main || success" ng-class="{'w3-red':error.main,'w3-green':success}">
          <p>{{error.main}}{{success}}</p>
        </div> 

        <div class="w3-row">
            <div class="w3-col w3-margin-bottom">
                <label>Name</label>
                <input class="w3-input w3-border" ng-model="name" ng-readonly="loggedInCustomer()" type="text">
                <label class="w3-text-red" ng-show="error.invalid.name">{{error.invalid.name}}</label>
            </div>
            <div class="w3-col w3-margin-bottom">
                <label>Email</label>
                <input class="w3-input w3-border" ng-model="email" ng-readonly="loggedInCustomer()" type="email">
                <label class="w3-text-red" ng-show="error.invalid.email">{{error.invalid.email}}</label>
            </div>
            <div class="w3-col w3-margin-bottom">
                <label>Your Message</label>
                <textarea class="w3-input w3-border" ng-model="message"></textarea>
                <label class="w3-text-red" ng-show="error.invalid.message">{{error.invalid.message}}</label>
            </div>
            
            <p><button class="w3-btn w3-theme-d5" ng-click="send()">Send</button></p>
        </div>
    </div>
</div>