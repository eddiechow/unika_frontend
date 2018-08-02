<div ng-controller="loginCtrl">
    <p>
        <label>Email</label>
        <input class="w3-input w3-border" ng-model="loginEmail" type="email" ng-disabled="waitting">
    </p>
    <p>
        <label>Password</label>
        <input class="w3-input w3-border" ng-model="loginPassword" type="password" ng-disabled="waitting">
    </p>
    <div class="w3-panel" ng-class="{'w3-red':error,'w3-border':waitting}" ng-show="error || waitting"><p>{{error}}<span ng-if="waitting">Please wait...</span></p></div>
    <p><button class="w3-btn-block w3-theme-d5" ng-click="login()" ng-disabled="waitting">Login</button></p>
    <hr>
    <p><button class="w3-btn-block w3-theme-d5" ng-click="openModal('other', {template: 'forgetPassword.tpl'})" ng-disabled="waitting">Forget Password</button></p>
    <p><a class="w3-btn-block w3-theme-d5" ng-click="closeModal('register')" ng-disabled="waitting">Register</a></p>
</div>
