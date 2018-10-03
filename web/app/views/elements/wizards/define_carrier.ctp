<table class="form table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
    <thead></thead>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Name') ?> </td>
        <td>
            <?php echo $form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '500', 'class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]')) ?>
        </td>
        <td class="align_right padding-r20"><?php echo __('status') ?> </td>
        <td>
            <?php
            $st = array('true' => __('Active', true), 'false' => __('Inactive', true));
            echo $form->input('status', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20">
            <span id="ht-100001" class="helptip" rel="helptip"><?php echo __('mode') ?></span>
            <span id="ht-100001-tooltip" class="tooltip" style="z-index: auto;">
            If Prepaid selected - this client`s Balance+Credit value will be checked on RADIUS authorization,
                if Postpaid selected - RADIUS authorization check is disabled
            </span>:
        </td>
        <td>
            <?php
            $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
            echo $form->input('mode', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
        <td class="align_right padding-r20"><?php echo __('currency') ?> </td>
        <td>
            <?php $currency_default = isset($default_currency) ? $default_currency : ""; ?>
            <?php echo $form->input('currency_id', array('options' => $currency, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'validate[required]', 'value' => $currency_default)); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('allowedcredit') ?></td>
        <td>
            <span id="unlimited_panel">
                <?php __('Unlimited')?>
                <?php echo $form->input('unlimited_credit', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
            </span>
            <?php echo $form->input('allowed_credit', array('label' => false, 'value' => '0.000', 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display: inline-block;')) ?>
            <span class='money' style="display:inline-block"></span>
        </td>
        <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit') ?></span> </td>
        <td style="text-align: left;">
            <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text', 'maxlength' => '6', 'style' => 'width:43%')) ?>
            <?php echo $xform->input('profit_type', array('options' => Array(1 => __('Percentage', true), 2 => __('Value', true)), 'style' => 'width:43%')) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('CPS') ?> </td>
        <td>
            <?php echo $form->input('cps_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '100', 'class' => 'input in-text validate[custom[onlyNumberSp]]')) ?>
        </td>
        <td class="align_right padding-r20"><?php echo __('Call limit') ?> </td>
        <td>
            <?php echo $form->input('call_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '100', 'class' => 'input in-text validate[custom[onlyNumberSp]]')) ?>
        </td>
    </tr>

</table>