<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Management'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit')?> <?php echo  ($type == '2') ? "Ingress" : "Egress" ; ?>[<?php echo $name;?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Limit')?></li>
</ul>
<?php //pre($client_name);?>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Limit')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
        <?php if ($type == '2'): ?>
        <?php echo  $this->element('ingress_tab',array('active_tab'=>'limit'));?>
        <?php else: ?>
        <?php echo  $this->element('egress_tab',array('active_tab'=>'limit'));?>
        <?php endif; ?>
        </div>
        <div class="widget-body">

        <?php echo $form->create('Gatewaygroup', array('id' => 'myform', 'action' => "edit_egress_ingress_limit/" . base64_encode($post['Gatewaygroup']['resource_id']))); ?>
		<div id="support_panel" style="text-align:center;">

            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">
                <tr>
                    <td class="align_right padding-r10"><?php __('calllimit') ?></td>
                    <td>
                    <?php if ($type == '2'): ?>
                        <?php echo $form->input('ingress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
                        <?php echo $form->input('egress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
                    <?php else: ?>
                        <?php echo $form->input('ingress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
                        <?php echo $form->input('egress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
                    <?php endif; ?>
                        <?php echo $form->input('alias', array('label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['alias'], 'type' => 'hidden', 'maxlength' => '100')); ?>
                        <?php echo $form->input('resource_id', array('id' => 'alias', 'label' => false, 'value' => $post['Gatewaygroup']['resource_id'], 'div' => false, 'type' => 'hidden', 'maxlength' => '6')); ?>
                        <?php echo $form->input('client_id', array('id' => 'alias', 'label' => false, 'value' => $post['Gatewaygroup']['client_id'], 'div' => false, 'type' => 'hidden', 'maxlength' => '6')); ?>
                        <?php echo $form->input('capacity', array('class' => "width220],custom[onlyNumberSp]]", 'id' => 'totalCall', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['capacity'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                <tr>
                    <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="resource_id"/>
                    <td class="align_right padding-r10"><?php __('cps') ?></td>
                    <td>
                        <?php echo $form->input('cps_limit', array('class' => "width220],custom[onlyNumberSp]]", 'id' => 'totalCPS', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['cps_limit'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10"><?php __('ANI CPS Limit') ?></td>
                    <td>
                        <?php echo $form->input('ani_cps_limit', array('class' => "width220,custom[onlyNumberSp]]", 'id' => 'aniCPS', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['ani_cps_limit'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10"><?php __('ANI CAP Limit') ?></td>
                    <td>
                        <?php echo $form->input('ani_cap_limit', array('class' => "width220,custom[onlyNumberSp]]", 'id' => 'aniCAP', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['ani_cap_limit'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10"><?php __('DNIS CPS Limit') ?></td>
                    <td>
                        <?php echo $form->input('dnis_cps_limit', array('class' => "width220,custom[onlyNumberSp]]", 'id' => 'dnisCPS', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['dnis_cps_limit'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10"><?php __('DNIS CAP Limit') ?></td>
                    <td>
                        <?php echo $form->input('dnis_cap_limit', array('class' => "width220,custom[onlyNumberSp]]", 'id' => 'dnisCAP', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['dnis_cap_limit'], 'type' => 'text', 'maxlength' => '8')); ?>
                    </td>
                </tr>

                </colgroup>
                </table>

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

    });
</script>