<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit')?> <?php echo  ($type == 'ingress') ? "Ingress" : "Egress" ; ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Billing')?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Billing')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

	<?php if ($type == 'ingress'): ?>
	<?php echo  $this->element('ingress_tab',array('active_tab'=>'billing'));?>
	<?php else: ?>
	<?php echo  $this->element('egress_tab',array('active_tab'=>'billing'));?>
	<?php endif; ?>
        </div>
        <div class="widget-body">
	
		<?php echo $form->create ('Gatewaygroup', array ('url' => '/' . $this->params['url']['url']));?>
                
		<div id="support_panel" style="text-align:center;">


                	<?php if ($type == 'ingress'): ?>

            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">

                </colgroup>
                <?php echo $this->element("gatewaygroups/more_public_fields") ?>
                <tr>
                    <td class="align_right padding-r10"><?php __('Rounding'); ?></td>
                    <td>
                        <?php echo $form->input('rate_rounding', array('options' => array('Up', 'Down'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rate_rounding'])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('Rounding Decimal Places'); ?></td>
                    <td>
                        <?php echo $form->input('rate_decimal', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['rate_decimal'])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('Determine Jurisdiction By'); ?></td>
                    <td>
                        <?php
                            $arr = array('false' => 'LRN', 'true' => 'DNIS');
                            echo $form->input('jurisdiction_use_dnis', array('options' => $arr, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => [$post['Gatewaygroup']['jurisdiction_use_dnis']]));
                        ?>
                    </td>
                </tr>
                <tr>
<!--        <td class="align_right padding-r10">--><?php //__('Rate Profile'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('rate_profile', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['rate_profile']));
//                ?>
<!--        </td>-->
    </tr>
<!--    <tr class="rate_profile_control">-->
<!--        <td class="align_right padding-r10">--><?php //__('USA'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_route']));
//                ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr class="rate_profile_control">-->
<!--        <td class="align_right padding-r10">--><?php //__('US Territories'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_other']));
//                ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr class="rate_profile_control">-->
<!--        <td class="align_right padding-r10">--><?php //__('Canada'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_route']));
//                ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr class="rate_profile_control">-->
<!--        <td class="align_right padding-r10">--><?php //__('Non USA/Canada Territories'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_other']));
//                ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr class="rate_profile_control">-->
<!--        <td class="align_right padding-r10">--><?php //__('International'); ?><!--</td>-->
<!--        <td>-->
<!--            --><?php
//                echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['intl_route']));
//                ?>
<!--        </td>-->
<!--    </tr>-->

                </table>
            <?php else: ?>

            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">

                </colgroup>
                <tr>
                    <td class="align_right padding-r10"><?php __('Rounding'); ?></td>
                    <td>
                        <?php echo $form->input('rate_rounding', array('options' => array(__('Up', true), __('Down', true)), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rate_rounding'])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('Rounding Decimal Places'); ?></td>
                    <td>
                        <?php echo $form->input('rate_decimal', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['rate_decimal'])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('Determine Jurisdiction By'); ?></td>
                    <td>
                        <?php
                            $arr = array('false' => 'LRN', 'true' => 'DNIS');
                            echo $form->input('jurisdiction_use_dnis', array('options' => $arr, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => [$post['Gatewaygroup']['jurisdiction_use_dnis']]));
                        ?>
                    </td>
                </tr>
<!--                <tr>-->
<!--                    <td class="align_right padding-r10">--><?php //__('Rate Profile'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('rate_profile', array('options' => array(__('False', true), __('True', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['rate_profile']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr class="rate_profile_control">-->
<!--                    <td class="align_right padding-r10">--><?php //__('USA'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_route']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr class="rate_profile_control">-->
<!--                    <td class="align_right padding-r10">--><?php //__('US Territories'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_other']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr class="rate_profile_control">-->
<!--                    <td class="align_right padding-r10">--><?php //__('Canada'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_route']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr class="rate_profile_control">-->
<!--                    <td class="align_right padding-r10">--><?php //__('Non USA/Canada Territories'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_other']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr class="rate_profile_control">-->
<!--                    <td class="align_right padding-r10">--><?php //__('International'); ?><!--</td>-->
<!--                    <td>-->
<!--                        --><?php
//                        echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['intl_route']));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->


            </table>

            <?php endif; ?>
            <br />
            <br />
            <br />
            
            <div class="button-groups">
                <input type="submit" class="btn btn-primary" value="<?php __('Submit')?>" />
            </div>
        </div>
        <?php echo $form->end(); ?>
</div>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(
        function() {
            jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
        }

    );

    $(function() {




        var $rate_profile_control = $('.rate_profile_control');

        $('#GatewaygroupRateProfile').change(function() {
            var $this = $(this);
            var val = $this.val();
            if (val == 0)
            {
                $rate_profile_control.hide();
            }
            else
            {
                $rate_profile_control.show();
            }
        }).trigger('change');





    });
</script>