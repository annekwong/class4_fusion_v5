<?php

function getAgentNameById($id, $agentList)
{
    $name = '';

    if(isset($agentList) && !empty($agentList)) {
        foreach ($agentList as $item) {
            if($item['Agent']['agent_id'] == $id) {
                $name = $item['Agent']['agent_name'];
                break;
            }
        }
    }

    return $name;
}

?>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>registration">
        <?php echo __('Management', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>registration">
        <?php echo __('Registration') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Registration',true);?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Status');?>:</label>
                        <select name="status_type">
                            <option value=""><?php __('All')?></option>
                            <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] === '0') echo 'selected="selected"'; ?>  value="0"><?php echo $status_name[0]?></option>
                            <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 1) echo 'selected="selected"'; ?>  value="1"><?php echo $status_name[1]?></option>
                            <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 2) echo 'selected="selected"'; ?>  value="2"><?php echo $status_name[2]?></option>
                        </select>
                    </div>
                    <!--             // Filter END-->
                    <!--             Filter-->
                    <div>
                        <label><?php __('Registration Time')?>:</label>
                        <input type="text" value="<?php echo isset($_GET['start_time'])?$_GET['start_time']:'' ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                    </div>

                    <div>
                        <label><?php __('~')?></label>
                        <input type="text" value="<?php echo isset($_GET['end_time'])?$_GET['end_time']:''; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                    </div>
                    <div>
                        <button class="btn query_btn" name="submit"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                </form>
            </div>
            <div class="clearfix"></div>
            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <!--                    <th>--><?php //echo $appCommon->show_order('Registration.id', __('ID', true)) ?><!--</th>-->
                    <th><?php echo $appCommon->show_order('Registration.login', __('Username', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.email', __('Main Email Address', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.company', __('Company Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.agent_assoc_id', "Referral") ?></th>
                    <th><?php echo $appCommon->show_order('Client.name', "Client Name") ?></th>
                    <!--                    <th>--><?php //echo $appCommon->show_order('Registration.address', __('Address', true)) ?><!--</th>-->
                    <th><?php echo $appCommon->show_order('Registration.phone', __('Phone Number', true)) ?></th>

                    <th><?php echo $appCommon->show_order('Registration.signup_time', __('Registration Time', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.modify_time', __('Operation Time', true)) ?></th>
                    <!--                    <th>--><?php //echo $appCommon->show_order('Registration.send_email', __('Send Email to User', true)) ?><!--</th>-->
                    <th><?php echo $appCommon->show_order('Registration.status', __('Status', true)) ?></th>
                    <th><?php __('Action')?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($this->data as $item): ?>
                    <tr>
                        <!--                        <td>--><?php //echo $item["Registration"]['id']; ?><!--</td>-->
                        <td><?php echo $item["Registration"]['login']; ?></td>
                        <td><?php echo $item["Registration"]['email']; ?></td>
                        <td><?php echo $item["Registration"]['company']; ?></td>
                        <td >
                        <?php if(isset($item["Registration"]['agent_assoc_id'])) {
                           echo getAgentNameById($item["Registration"]['agent_assoc_id'], $agentList);
                         }?>
                        </td>
                        <td class="carrier_name">
                        <?php if(isset($item["Client"]['name'])) {
                           echo $item["Client"]['name'];
                         }?>
                        </td>
                        <!--                        <td>--><?php //echo $item["Registration"]['address']; ?><!--</td>-->
                        <td><?php echo $item["Registration"]['phone']; ?></td>


                        <td><?php echo $item["Registration"]['signup_time']; ?></td>
                        <td><?php echo $item["Registration"]['modify_time']; ?></td>
                        <!--                        <td>--><?php //echo $email_status[$item["Registration"]['send_email']] ?><!--</td>-->
                        <td><?php echo $status_name[$item["Registration"]['status']]; ?></td>
                        <td>
                            <a title="<?php __('Edit')?>" href="<?php echo $this->webroot ?>registration/edit/<?php echo base64_encode($item['Registration']['id']).'?'.$html->getParams('getUrl') ?>" class=""><i class="icon-edit"></i></a>
                            <?php if ($_SESSION['role_menu']['Management']['registration']['model_w']): ?>
                                <?php if($item["Registration"]['status']==0 || $item["Registration"]['status']==2): ?>

                                    <a title="<?php __('Approve')?>" href="#myModal_approve" class="myModal_approve" data-agent="<?php echo $item['Registration']['agent_assoc_id']; ?>" data-toggle="modal" value-id="<?php echo $item['Registration']['id']?>" value-username="<?php echo $item['Registration']['login']?>"><i class="icon-ok"></i></a>
                                <?php endif; ?>
                                <?php if($item["Registration"]['status']==0): ?>
                                    <a title="<?php __('Reject')?>" onclick="return myconfirm('Are you sure to reject the user [<?php echo $item["Registration"]['login'] ?>] ?', this)" href="<?php echo $this->webroot ?>registration/del/<?php echo base64_encode($item["Registration"]['id']).'?'.$html->getParams('getUrl') ?>" class=""><i class="icon-remove"></i></a>
                                <?php endif; ?>
                                <a title="Delete" onclick="return myconfirm('Are you sure to delete the user [<?php echo $item["Registration"]['login'] ?>] ?', this)" href="<?php echo $this->webroot ?>registration/remove/<?php echo base64_encode($item["Registration"]['id']).'?'.$html->getParams('getUrl') ?>" class=""><i class="icon-ban-circle"></i></a>
                            <?php endif; ?>
                            <!--                            <?php /*if($item["Registration"]['send_email']==2): */?>
                                <a title="<?php /*__('Resend')*/?>" onclick="return myconfirm('Are you sure to resend email to the user [<?php /*echo $item["Registration"]['login'] */?>//] ?', this)" href="<?php /*echo $this->webroot */?>registration/resend/<?php /*echo base64_encode($item["Registration"]['id'].'?'.$html->getParams('getUrl')) */?>" class=""><i class="icon-reply"></i></a>
                            --><?php /*endif; */?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <div class="clearfix"></div>


        </div>
    </div>
</div>
<form action="" id="approve_form" method="post">
    <input id="approve_id" name="approve_id" type="hidden" value=""/>
    <input id="approve_url" name="approve_url" type="hidden" value=""/>
    <div id="myModal_approve" class="modal hide" style="position:absolute;width:800px;top: 5%;left: 40%;">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3 id="approve_user"></h3>
        </div>
        <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <colgroup>
                    <col width="21%">
                    <col width="79%">
                </colgroup>
                <tr>
                    <td class="align_right"><?php echo __('Using Carrier Template')?> </td>
                    <td>
                        <?php if(empty($carrier_template_arr) ):?>
                        <input class="input in-text" name="approve_is_template"  id="approve_is_template" type="hidden" value=""/>
                            <a href="<?php echo $this->webroot?>carrier_template/add" target="_blank">Create new Carrier template</a>
                            <script>
                                $(function(){
                                    $('.isshow_carrier').hide();
                                    $('#isshow_carrier_template').hide();
//                                    $('#approve_is_template').attr("checked",'false');
//                                    $('.myModal_approve').live('click',function(){$('#approve_is_template').attr('disabled','disabled')});
                                    //setTimeout("$('#approve_is_template').attr('disabled','disabled')",50);
                                })
                            </script>
                        <?php else:
                        if(isset($_SESSION['role_menu']['Management']['clients']['model_w']) && $_SESSION['role_menu']['Management']['clients']['model_w']):
                        ?>
                        <input class="input width220 in-text" name="approve_is_template"  id="approve_is_template" type="checkbox" />
                        <?php else: ?>
                        <input class="input width220 in-text" name="approve_is_template"  id="approve_is_template" type="checkbox" checked="checked" disabled />
                        <?php endif;
                        endif;?>
                    </td>
                </tr>
                <tr id="isshow_carrier_template">
                    <td class="align_right"><?php echo __('Carrier Template')?> </td>
                    <td id="isshow_carrier_template">
                        <select name="data[Client][template_id]" class="input width220 in-text in-select"  id="approve_carrier_template">
                            <?php foreach($carrier_template_arr as $value):?>
                                <option value="<?php echo $value[0]['id']?>"><?php echo $value[0]['template_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr id="isshow_carrier_template">
                    <td class="align_right">Agent</td>
                    <td id="isshow_carrier_template">
                        <select name="data[Client][agent_assoc_id]" class="input width220 in-text in-select" id="approve_agent">
                            <option value=""></option>
                            <?php foreach($agentList as $value):?>
                                <option value="<?php echo $value['Agent']['agent_id']?>"><?php echo $value['Agent']['agent_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="isshow_carrier">
                    <td class="align_right"><?php echo __('Carrier Name')?> </td>
                    <td><input type="text" class="input width220 in-textarea validate[required] approve_carrier" style="width: 100%;" name="data[Client][name]"  id="approve_carrier" /></td>
                </tr>
                <tr class="isshow_carrier">
                    <td class="align_right"><?php echo __('type')?> </td>
                    <td>
                        <select id="ClientStatus" class="input width220 in-text in-select" name="data[Client][status]">
                            <option value="true">Active</option>
                            <option value="false">Inactive</option>
                        </select>
                    </td>
                </tr>
                <tr class="isshow_carrier">
                    <td class="align_right padding-r20">Permission </td>
                    <td class="value" colspan="3">
                        <?php echo $this->element('portal/add_permission_div'); ?>
                    </td>
                </tr>
                <!--tr class="isshow_carrier">
                    <td class="align_right padding-r20"><?php echo __('Send Welcom Letter')?> </td>
                    <td>
                        <input class="input in-text" name="is_send_welcom_letter"  id="is_send_welcom_letter" type="checkbox" />
                    </td>
                </tr-->

            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="approve_submit" class="btn btn-primary" value="<?php __('Approve'); ?>">
            <a href="javascript:void(0)" id="approve_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script>
    $(function(){
        $('.myModal_approve').click(function(){

            let agentId = $(this).data('agent');
            $('#approve_agent').val(agentId);

            var val_id = $(this).attr('value-id');
            $('#approve_id').val(val_id);
            $('#approve_url').val("<?php echo $html->getParams('getUrl');?>");
            var val_username = $(this).attr('value-username');
            var vhtml = '';
            vhtml = '<?php __('Approve User: '); ?>' + val_username;
            $('#approve_user').html(vhtml);


            var carrier_name = $(this).closest('tr').find('.carrier_name').text().trim();
             $("#approve_carrier").val(carrier_name);
        });

        $('#approve_is_template').click(function(){

            if($(this).is(':checked')) {
                $('.isshow_carrier').show();
                $('#isshow_carrier_template').show();


            } else {
                $('.isshow_carrier').hide();
                $('#isshow_carrier_template').hide();
            }
        }).trigger('click');

        $("#approve_submit").click(function(){

            $('#approve_is_template').attr("disabled",false);
            var is_checked = $('#approve_is_template').is(':checked');
            if(!is_checked){
                $("#approve_form").attr("action","<?php echo $this->webroot?>clients/add_registration");
                $("#approve_form").submit();
                return;
            } else {
                $("#approve_form").attr("action","<?php echo $this->webroot?>carrier_template/add_carrier_by_template");
                $("#approve_form").submit();
                return;
            }


        });
    })

    $(document).on('DOMNodeInserted', function(){
        $('.sorting').attr('title', 'Sort');
    });
</script>