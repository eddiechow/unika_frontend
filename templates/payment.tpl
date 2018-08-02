<div ng-if="status">
    <h5 class="w3-margin-left">Order Payment</h5>

    <div class="w3-white w3-margin w3-border">
        <div class="w3-container w3-cell w3-mobile w3-center">
            <h3 class="w3-hide-medium w3-hide-large">
                <span ng-show="status=='success'">Success!</span>
                <span ng-show="status=='fail'">Error!</span>
                <span ng-show="status=='cancel'">Alert!</span>
            </h3>
            <hr class="w3-hide-medium w3-hide-large">
            <p><i class="fa fa-5x" ng-class="{'fa-check-circle w3-text-green':status=='success', 'fa-times-circle w3-text-red':status=='fail', 'fa-info-circle w3-text-blue':status=='cancel'}" aria-hidden="true"></i></p>
            <p class="w3-hide-medium w3-hide-large">{{message}}</p>
            <p class="w3-hide-medium w3-hide-large"><a href="orders">Order History</a> | <a href=".">Home</a></p>
        </div>

        <div class="w3-container w3-cell w3-mobile w3-hide-small" style="width:100%">
            <h3 class="w3-hide-small">
                <span ng-show="status=='success'">Success!</span>
                <span ng-show="status=='fail'">Error!</span>
                <span ng-show="status=='cancel'">Alert!</span>
            </h3>
            <p>{{message}}</p>
            <p><a href="orders">Order History</a> | <a href=".">Home</a></p>
        </div>
    </div>
</div>