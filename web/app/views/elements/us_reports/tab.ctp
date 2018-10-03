<style type="text/css">
    .width10_5{
        width: 10.5%;
    }
    .width19{
        max-width: 19%;
    }
</style>
<ul class="row-fluid row-merge">
    <li class="<?php if (!strcmp('rcr', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('Return Code Report'); ?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/return_code_report">
            <i></i>
            <?php
            if(!strcmp('rcr', $active))
                __('Return Code Report');
            else
                __('Return Code...');
            ?>
        </a>
    </li>
    <li class="<?php if (!strcmp('lr', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('LCR Report'); ?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/lcr_report">
            <i></i>
            <?php
            if(!strcmp('lr', $active))
                __('LCR Report');
            else
            __('LCR Report');
            ?>
        </a>
    </li>
    <li class="<?php if (!strcmp('lvr', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('LCR Vendor Report'); ?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/lcr_vendor_report">
            <i></i>
            <?php
            if(!strcmp('lvr', $active))
                __('LCR Vendor Report');
            else
                __('LCR Vendor...');
            ?>
        </a>
    </li>

    <li class="<?php if (!strcmp('tvr', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('Termination Vendor Report') ?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/termination_vendor_report">
            <i></i>
            <?php
            if(!strcmp('tvr', $active))
                __('Termination Vendor Report');
            else
                __('Termination...');
            ?>
        </a>
    </li>
<!--    <li class="--><?php //if (!strcmp('vrmr', $active))
//    { ?><!--active width19--><?php //}else{echo "width10_5";} ?><!--">-->
<!--        <a title="--><?php //__('Vendor Rate Match Report')?><!--" class="glyphicons list" href="--><?php //echo $this->webroot; ?><!--us_domestic_traffic/return_code_report">-->
<!--            <i></i>-->
<!--            --><?php
//            if(!strcmp('vrmr', $active))
//                __('Vendor Rate Match Report');
//            else
//                __('Vendor Rate...');
//            ?>
<!--        </a>-->
<!--    </li>-->
    <li class="<?php if (!strcmp('far', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('Frequent ANI Report')?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/frequent_ani_report">
            <i></i>
            <?php
            if(!strcmp('far', $active))
                __('Frequent ANI Report');
            else
                __('Frequent ANI...');
            ?>
        </a>
    </li>
    <li class="<?php if (!strcmp('flr', $active))
    { ?>active width19<?php }else{echo "width10_5";} ?>">
        <a title="<?php __('Frequent LRN Report')?>" class="glyphicons list" href="<?php echo $this->webroot; ?>us_domestic_traffic/frequent_lrn_report">
            <i></i>
            <?php
            if(!strcmp('flr', $active))
                __('Frequent LRN/DNIS Report');
            else
                __('Frequent LRN...');
            ?>
        </a>
    </li>
</ul>