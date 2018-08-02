<div ng-controller="forgetPasswordCtrl">
    <h3>Forget Password</h3>
    <div class="w3-panel w3-red" ng-if="error.system"><p>{{error.system}}</p></div>        
    <p>
        <label>Email</label>
        <input class="w3-input w3-border" ng-model="email" type="email" ng-disabled="waitting || seccess.email">
    </p>
    <div class="w3-panel" ng-if="error.email || seccess.email || waitting" ng-class="{'w3-red':error.email, 'w3-green':seccess.email,'w3-border':waitting}">
        <p>{{error.email}}{{seccess.email}}<span ng-if="waitting">Please wait...</span></p>
    </div>                     
    <p class="w3-center"><button class="w3-btn-block w3-theme-d5" ng-click="sendSecurityToken()" ng-disabled="waitting">Send Security Token</button></p>
    <hr>
    <p>
        <label>Security Token</label>
        <input class="w3-input w3-border" ng-model="securityToken" type="text" maxlength="6" ng-disabled="!seccess.email">
    </p>
    <p>
        <label>New Password</label>
        <input class="w3-input w3-border" ng-model="newPassword" type="password" ng-disabled="!seccess.email">
    </p>
    <p>
        <label>Confirm Password</label>
        <input class="w3-input w3-border" ng-model="confirmPassword" type="password" ng-disabled="!seccess.email">
    </p>
    <div class="w3-panel w3-red" ng-if="error.other"><p>{{error.other}}</p></div>       
    <p class="w3-center"><button class="w3-btn-block w3-theme-d5" ng-click="resetPassword()" ng-disabled="!seccess.email">Reset Password</button></p>
    <hr>
    <p class="w3-center"><button class="w3-btn-block w3-theme-d5" ng-click="close()">Close</button></p>
</div>