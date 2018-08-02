<div ng-controller="emailVerificationCtrl">
    <h3>Email Verification</h3>
    <p>Your verification code has been sent to your email.<br>
    <label style="font-weight:bold">Please input verification code</label>
    <input class="w3-input w3-border" maxlength="6" ng-model="verificationToken" type="text"></p>
    <div class="w3-panel w3-red" ng-show="error"><p>{{error}}</p></div>                     
    <p class="w3-center"><button class="w3-btn-block w3-theme-d5" ng-click="verifyEmail()">Verify</button></p>
    <p class="w3-center"><button class="w3-btn-block w3-theme-d5" ng-click="close()">Close</button></p>
</div>