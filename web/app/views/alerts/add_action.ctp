<style type="text/css">
    .in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{ width:250px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Action') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Action') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Add Action') ?></h4>
    <font class="editname" title="Name">   <?php echo empty($p['name']) || $p['name'] == '' ? '' : "[" . $p['name'] . "]" ?></font>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>alerts/action"><i></i>Back</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
                <?php
                $flag = isset($_GET['flag']) ? "?flag=1" : "";
                ?>
                <?php $id = array_keys_value($this->params, 'pass.0') ?>
                <?php echo $form->create('Alert', array('action' => "add_action{$flag}", 'onsubmit' => "return save_action();")); ?>

                <table style="width:60%; margin:0 auto;" class="form">
                    <tbody>

                        <tr>
                            <td><?php echo __('Action name') ?>:</td>
                            <td>
                                <input  type="hidden"   id="action_id" name="action_id" value="<?php echo array_keys_value($this->params, 'pass.0'); ?>">  

                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '66', 'value' => empty($p['name']) ? '' : $p['name'])); ?>
                            </td>
                        </tr>
                        <?php /*
                          <tr>
                          <td><?php echo __('Email to Noc')?>:</td>
                          <td>
                          <?php
                          $tmp=array('0'=>'InActive','1'=>'Active');
                          echo $form->input('email_to_noc',
                          array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['email_to_noc'])?0:$p['email_to_noc']));
                          ?>
                          </td>
                          </tr>
                         * 
                         */ ?>
                        <tr>
                            <td><?php echo __('Email Notification') ?>:</td>
                            <td>
                                <?php
                                $tmp = array('0' => 'Email NOC', '1' => 'Email Carrier\'s NOC', '2' => 'Email Both NOC');
                                echo $form->input('email_notification', array('options' => $tmp, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text', 'selected' => empty($p['email_notification']) ? 0 : $p['email_notification']));
                                ?>
                            </td>
                        </tr> 

                        <tr>
                            <td><?php echo __('Disable Host') ?>:</td>
                            <td>
                                <?php
                                $tmp = array('0' => 'No', '1' => 'Yes');
                                echo $form->input('disable_host', array('options' => $tmp, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text', 'selected' => empty($p['disable_host']) ? 0 : $p['disable_host']));
                                ?>
                            </td>
                        </tr> 

                        <tr>
                            <td><?php echo __('Disable Trunk') ?>:</td>
                            <td>
<?php
$tmp = array('0' => 'No', '1' => 'Yes');
echo $form->input('disable_resource', array('options' => $tmp, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text', 'selected' => empty($p['disable_resource']) ? 0 : $p['disable_resource']));
?>
                            </td>
                        </tr> 
                        <tr>
                            <td><?php echo __('Disable Duration') ?>:</td>
                            <td>
<?php
echo $form->input('disable_duration', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '66', 'value' => isset($p['disable_duration']) ? $p['disable_duration'] : 0));
?>

                            </td>
                        </tr> 
<?php
/*
  <tr>
  <td><?php echo __('Noc Email Subject')?>:</td>
  <td>
  <?php
  echo $form->input('noc_email_subject',array('label'=>false ,'div'=>false,'type'=>'textarea', 'value'=>empty($p['noc_email_subject'])?'':$p['noc_email_subject']));
  ?>

  </td>
  </tr>
  <tr>
  <td><?php echo __('Noc Email Content')?>:</td>
  <td>
  <?php
  echo $form->input('noc_email_content',array('label'=>false ,'div'=>false,'type'=>'textarea', 'value'=>empty($p['noc_email_content'])?'':$p['noc_email_content']));
  ?>

  </td>
  </tr>
 * 
 */
?>
                        <?php /*
                          <tr>
                          <td><?php echo __('Noc Attach Type')?>:</td>
                          <td>
                          <?php
                          $tmp=array('0'=>'none','1'=>'pdf', '2'=>'xls', '3'=>'html');
                          echo $form->input('noc_attach_type',
                          array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['noc_attach_type'])?0:$p['noc_attach_type']));
                          ?>
                          </td>
                          </tr>
                         */ ?>
                        <?php /*
                          <tr>
                          <td><?php echo __('Backup Type')?>:</td>
                          <td>
                          <?php
                          $tmp=array('0'=>'none','1'=>'pdf', '2'=>'xls', '3'=>'html');
                          echo $form->input('backup_type',
                          array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['backup_type'])?0:$p['backup_type']));
                          ?>
                          </td>
                          </tr>
                         * 
                         */ ?>
                        <tr>
                            <td><?php echo __('Host Priority') ?>:</td>
                            <td>
                        <?php
                        echo $form->input('host_priority', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'input in-text', 'value' => empty($p['host_priority']) ? 0 : $p['host_priority']));
                        ?>
                            </td>
                        </tr>     						
                        <tr>
                            <td><?php echo __('Resource Priority') ?>:</td>
                            <td>
<?php
echo $form->input('res_priority', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'input in-text', 'value' => empty($p['res_priority']) ? 0 : $p['res_priority']));
?>
                            </td>
                        </tr> 
                        <tr>
                            <td><?php echo __('Priority Change Duration') ?>:</td>
                            <td>
                                <?php
                                echo $form->input('pri_chg_duration', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '66', 'value' => empty($p['pri_chg_duration']) ? 0 : $p['pri_chg_duration']));
                                ?>

                            </td>
                        </tr> 

                        <tr>
                            <td><?php echo __('Disable Code For Trunk') ?>:</td>
                            <td>
<?php
$tmp = array('0' => 'No', '1' => 'Yes');
echo $form->input('disable_code_trunk', array('options' => $tmp, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text', 'selected' => empty($p['disable_code_trunk']) ? 0 : $p['disable_code_trunk']));
?>
                            </td>
                        </tr> 
                        <tr>
                            <td><?php echo __('Code Trunk Disable Duration') ?>:</td>
                            <td>
                                <?php
                                echo $form->input('code_trunk_disable_duration', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '66', 'value' => empty($p['code_trunk_disable_duration']) ? 0 : $p['code_trunk_disable_duration']));
                                ?>

                            </td>
                        </tr> 
                                <?php /*
                                  <tr>
                                  <td><?php echo __('Carrier Email Subject')?>:</td>
                                  <td>
                                  <?php
                                  echo $form->input('carrier_email_subject',array('label'=>false ,'div'=>false,'type'=>'textarea', 'value'=>empty($p['carrier_email_subject'])?'':$p['carrier_email_subject']));
                                  ?>

                                  </td>
                                  </tr>
                                  <tr>
                                  <td><?php echo __('Carrier Email Content')?>:</td>
                                  <td>
                                  <?php
                                  echo $form->input('carrier_email_content',array('label'=>false ,'div'=>false,'type'=>'textarea', 'value'=>empty($p['carrier_email_content'])?'':$p['carrier_email_content']));
                                  ?>

                                  </td>
                                  </tr>
                                 * 
                                 */ ?>
                        <?php /*
                          <tr>
                          <td><?php echo __('Carier Attach Type')?>:</td>
                          <td>
                          <?php
                          $tmp=array('0'=>'none','1'=>'pdf', '2'=>'xls', '3'=>'html');
                          echo $form->input('carrier_attach_type',
                          array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['carrier_attach_type'])?0:$p['carrier_attach_type']));
                          ?>
                          </td>
                          </tr>
                         * 
                         */ ?>
                        <tr>

                    </tbody>
                </table>
                        <?php if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w'])
                        { ?>
            <div id="form_footer" class="center">
                        <input type="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary"/>
                    </div>
<?php } ?>
        </div>
    </div>
</div>
<?php echo $form->end(); ?>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        //jQuery('#AlertAddConditionForm').submit(function(){ 
                        //     return true;
                        //});



                    });

                    function save_action()
                    {
                        var flag = true;

                        var action_name = $("#AlertName").val();
                        if (!/^([a-zA-Z0-9]|[._]){1,16}$/.exec(action_name)) {
                            $("#AlertName").attr('class', 'input in-input in-text');
                            jGrowl_to_notyfy('Action Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length!  ._', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertName").attr('class', 'input in-text');
                        }

                        var email_noc = $("#AlertEmailToNoc").val();
                        var email_carrier = $("#AlertEmailToCarrier").val();
                        var disable_host = $("#AlertDisableHost").val();
                        var disable_res = $("#AlertDisableResource").val();
                        var disable_dur = $("#AlertDisableDuration").val();
                        if (!/^[0-9]+$/.exec(disable_dur))
                        {
                            $("#AlertDisableDuration").attr('class', 'input in-input in-text');
                            jGrowl_to_notyfy('Disable Duration must be numeric', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertDisableDuration").attr('class', 'input in-text');
                        }
                        var email_noc_subject = $("#AlertNocEmailSubject").val();
                        var email_noc_content = $("#AlertNocEmailContent").val();
                        var attach_noc_type = $("#AlertNocAttachType").val();
                        var backup_type = $("#AlertBackupType").val();
                        var host_pri = $("#AlertHostPriority").val();
                        if (!/^[0-9]+$/.exec(host_pri))
                        {
                            $("#AlertHostPriority").attr('class', 'input in-input in-text');
                            jGrowl_to_notyfy('Priority must be numeric', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertHostPriority").attr('class', 'input in-text');
                        }
                        var res_pri = $("#AlertResPriority").val();
                        if (!/^[0-9]+$/.exec(res_pri))
                        {
                            $("#AlertResPriority").attr('class', 'input in-input in-text');
                            jGrowl_to_notyfy('Priority  must be numeric', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertResPriority").attr('class', 'input in-input in-text');
                        }
                        var pri_chg_dur = $("#AlertPriChgDuration").val();
                        if (!/^[0-9]+$/.exec(pri_chg_dur))
                        {
                            $("#AlertPriChgDuration").attr('class', 'invalid');
                            jGrowl_to_notyfy('Priority Change Duration must be numeric', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertPriChgDuration").attr('class', 'input in-input in-text');
                        }
                        var disable_code = $("#AlertDisableCodeTrunk").val();
                        var dis_code_dur = $("#AlertCodeTrunkDisableDuration").val();
                        if (!/^[0-9]+$/.exec(dis_code_dur))
                        {
                            $("#AlertCodeTrunkDisableDuration").attr('class', 'invalid');
                            jGrowl_to_notyfy('Code Trunk Disable Duration must be numeric', {theme: 'jmsg-alert'});
                            flag = false;
                        }
                        else
                        {
                            $("#AlertCodeTrunkDisableDuration").attr('class', 'input in-text');
                        }
                        var email_carrier_subject = $("#AlertCarrierEmailSubject").val();
                        var email_carrier_content = $("#AlertCarrierEmailContent").val();
                        var attach_carrier_type = $("#AlertCarrierAttachType").val();

                        return flag;
                    }


                </script>

            </div>
