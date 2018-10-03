<?php 

class DidBillingPlan extends DidAppModel
{
    var $name = 'DidBillingPlan';
    var $useTable = 'did_billing_plan';
    var $primaryKey = 'id';
    var $rateTypes = array(
        1 => 'Fixed Rate',
        2 => 'Variable Rate',
        3 => 'US LRN Variable Rate'
    );

    var $payTypes = array(
        0 => 'Weekly',
        1 => 'Monthly'
    );
}
