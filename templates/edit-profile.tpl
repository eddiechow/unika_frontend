<h5 class="w3-padding-left">Edit Profile</h5>

<div class="w3-row-padding w3-margin-bottom" ng-if="success || mainError">
    <div class="w3-col s12 m12 l12">
        <div class="w3-container" ng-class="{'w3-green': success, 'w3-red': mainError}">
            <p>{{success.message}}{{mainError}}</p>
        </div>
    </div>
</div>
                
<div class="w3-row-padding">
    <div class="w3-col l6 m6 s12 w3-margin-bottom">
        <label>Current Password</label>
        <input class="w3-input w3-border" ng-model="currentPassword" type="password">
        <label ng-if="invalidCurrentPassword" class="w3-text-red">{{invalidCurrentPassword}}&nbsp;</label>
        <label ng-if="!invalidCurrentPassword">Please input current password fisrt</label>
    </div>
</div>
    
<div class="w3-row-padding">
    <div class="w3-half w3-margin-bottom">
        <label>New Password</label>
        <input class="w3-input w3-border" ng-model="password" type="password" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidPassowrd}}&nbsp;</label>
    </div>
    <div class="w3-half w3-margin-bottom">
        <label>Confirm Password</label>
        <input class="w3-input w3-border" ng-model="confirmPassword" type="password" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidConfirmPassword}}&nbsp;</label>
    </div>
</div>

<div class="w3-row-padding">
    <div class="w3-col l6 m6 s12 w3-margin-bottom">
        <label>Surname</label>
        <input class="w3-input w3-border" ng-model="surname" type="text" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidSurname}}&nbsp;</label>
    </div>
    <div class="w3-col l6 m6 s12 w3-margin-bottom">
        <label>Given Name</label>
        <input class="w3-input w3-border" ng-model="givenName" type="text" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidGivenName}}&nbsp;</label>
    </div>
</div>
    
<div class="w3-row-padding">
    <div class="w3-col l3 m3 s6 w3-margin-bottom">
        <label>Gender</label>
        <select class="w3-select w3-border" ng-model="gender" ng-disabled="!currentPassword">
            <option value="" disabled selected>Please select</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
        <label class="w3-text-red">{{invalidGender}}&nbsp;</label>
    </div>
    <div class="w3-col l3 m3 s6 w3-margin-bottom">
        <label>Age</label>
        <select class="w3-select w3-border" ng-model="ageGroupCode" ng-disabled="!currentPassword">
            <option value="" disabled selected>Please select</option>
            <option ng-repeat="x in ageGroups" value="{{x.ageGroupCode}}">{{x.ageGroupEnUs}}</otion>
        </select>
        <label class="w3-text-red">{{invalidAgeGroupCode}}&nbsp;</label>
    </div>        
    <div class="w3-col l6 m6 s12 w3-margin-bottom">
        <label>Email</label>
        <input class="w3-input w3-border" ng-model="email" type="email" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidEmail}}&nbsp;</label>
    </div>
</div>
        
<div class="w3-row-padding">
    <div class="w3-half w3-margin-bottom">
        <label>Country/Region Code</label>
        <select class="w3-select w3-border" ng-model="regionCode" ng-disabled="!currentPassword">
            <option value="" disabled selected>Please select</option>
            <option value="852">Hong Kong (+852)</option>
        </select>
        <label class="w3-text-red">{{invalidRegionCode}}&nbsp;</label>
    </div>
    <div class="w3-half w3-margin-bottom">
        <label>Mobile Phone Number</label>
        <input class="w3-input w3-border" ng-model="mobilePhoneNumber" type="tel" ng-disabled="!currentPassword">
        <label class="w3-text-red">{{invalidMobilePhoneNumber}}&nbsp;</label>
    </div>
</div>
    
<div class="w3-row-padding">
    <div class="w3-col w3-margin-bottom">
        <label>Address</label>
        <textarea class="w3-input w3-border" ng-model="address" ng-disabled="!currentPassword"></textarea>
        <label class="w3-text-red">{{invalidAddress}}&nbsp;</label>
    </div>
</div>     
    
<p class="w3-padding"><button class="w3-btn w3-theme-d5" ng-click="edit()" ng-disabled="!currentPassword">edit</button></p>