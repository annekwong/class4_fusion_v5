<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Action', true); ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Action', true); ?></h4>
    <div class="buttons pull-right">
        <?php if (isset($edit_return)) { ?>
            <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>alerts/action">
                <i></i>
                &nbsp;<?php echo __('goback') ?>
            </a>
        <?php } ?>
        <?php if ($_SESSION['role_menu']['Monitoring']['alerts:action']['model_w']) { ?>
            <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" id="add" title="<?php echo __('creataction') ?>"  href="###">
                <i></i><?php echo __('createnew') ?>
            </a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">




            <ul class="tabs">
                <li >
                    <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rule">
                        <i></i><?php __('Rule'); ?>			
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons no-js tag" href="<?php echo $this->webroot; ?>alerts/action">
                        <i></i><?php __('Action'); ?>			
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js projector" href="<?php echo $this->webroot; ?>alerts/condition">
                        <i></i><?php __('Condition'); ?>			
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_ani">
                        <i></i><?php __('Block'); ?>			
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                        <i></i><?php __('Trouble Tickets'); ?>			
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                        <i></i><?php __('Trouble Tickets Mail Template'); ?>			
                    </a>
                </li>
            </ul> 

        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search'); ?>:</label>
                        <input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search') ?>..." value="<?php
                        if (isset($searchkey)) {
                            echo $searchkey;
                        } else {
                            echo __('pleaseinputkey');
                        }
                        ?>"  onclick="this.value = ''" name="searchkey">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)):
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>

                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">

                    </div> 
                </div>

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                    <thead style="background:none;">
                        <tr>
                            <th class="footable-first-column expand" data-class="expand"><?php __('Action Name'); ?></th>
                            <th><?php __('Block ANI'); ?></th>
                            <th><?php __('Loop Detection'); ?></th>
                            <th><?php __('Trouble Tickets Mail Template'); ?></th>
                            <th><?php __('Send Email'); ?></th>
                            <th colspan="3"><?php __('Disable Route'); ?></th>
                            <th colspan="2"><?php __('Change Priority'); ?></th>
                            <th data-hide="phone,tablet"  style="display: table-cell;" rowspan="2"><?php echo __('Update By'); ?></th>
                            <th data-hide="phone,tablet"  style="display: table-cell;" rowspan="2"><?php echo __('Update At'); ?></th>
                            <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;" rowspan="2"><?php echo __('Action'); ?></th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Code Only'); ?></th>
                            <th><?php __('Enable After (min)'); ?></th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Change to Priority'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
<?php else:
    ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead style="background:none;">
                        <tr>
                            <th class="footable-first-column expand" data-class="expand"><?php __('Action Name'); ?></th>
                            <th><?php __('Block ANI'); ?></th>
                            <th><?php __('Loop Detection'); ?></th>
                            <th><?php __('Trouble Tickets Mail Template'); ?></th>
                            <th><?php __('Send Email'); ?></th>
                            <th colspan="3"><?php __('Disable Route'); ?></th>
                            <th colspan="2"><?php __('Change Priority'); ?></th>
                            <th data-hide="phone,tablet"  style="display: table-cell;" rowspan="2"><?php echo __('Update By'); ?></th>
                            <th data-hide="phone,tablet"  style="display: table-cell;" rowspan="2"><?php echo __('Update At'); ?></th>
                            <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;" rowspan="2"><?php echo __('Action'); ?></th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Code Only'); ?></th>
                            <th><?php __('Enable After (min)'); ?></th>
                            <th><?php __('Target'); ?></th>
                            <th><?php __('Change to Priority'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($mydata as $item): ?>
                            <tr>
                                <td class="footable-first-column expand" data-class="expand"><?php echo $item[0]['name']; ?></td>
                                <td><?php echo $item[0]['block_ani'] ? "Yes" : "No"; ?></td>
                                <td><?php echo $item[0]['loop_detection'] ? "Yes" : "No"; ?></td>
                                <td><?php echo $item[0]['template_name']; ?></td>
                                <td><?php echo $send_mail_type[(int) $item[0]['email_notification']]; ?></td>
                                <td><?php echo $disable_route_target[(int) $item[0]['disable_route_target']]; ?></td>
                                <td>
                                    <?php
                                    echo $item[0]['disable_code_trunk'] == 0 ? 'NO' : 'Yes';
                                    ?>
                                </td>
                                <td><?php echo $item[0]['disable_duration']; ?></td>
                                <td><?php echo $change_prioprity[(int) $item[0]['change_prioprity']]; ?></td>
                                <td><?php echo $item[0]['change_to_priority']; ?></td>
                                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $item[0]['update_at']; ?></td>
                                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $item[0]['update_by']; ?></td>
                                <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                                    <?php
                                    /*
                                      <?php if($item[0]['block_ani']): ?>
                                      <a title="Exclude ANI" class="exclude_ani" href="###" control="<?php echo $item[0]['id']?>" >
                                      <img src="<?php echo $this->webroot?>images/unlock.png"/>
                                      </a>
                                      <?php endif; ?>
                                     */
                                    ?>
                                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item[0]['id'] ?>" >
                                        <i class="icon-edit"></i>
                                    </a>

                                    <a title="Delete" class="delete" control="<?php echo $item[0]['id'] ?>" href='###'>
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
    <?php endforeach; ?>
                    <span style="display: none;"><a id="del_hidden" href=""  /></a></span>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>

<?php endif; ?>
        </div>

        <div id="popup_window" style="background:#fff;padding:10px;display:none;">
            <h1 style="padding:0;font-size:14px;">Excluded ANI</h1>
            <p>
                <textarea name="exclude_anis" id="exclude_anis" style="width:200px;height:100px;display:block;"></textarea>
            </p>
            <p style="text-align:center;">
                <input type="button" id="exclude_save" value="Save">
                <input type="button" id="exclude_cancel" value="Cancel">
            </p>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.center.js"></script>
<script type="text/javascript">
            $(function() {
                $('.delete').click(function() {
                    var $this = $(this);
                    var action_id = $this.attr('control');
                    $.ajax({
                        url: '<?php echo $this->webroot; ?>alerts/action_used/' + action_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var result = false;
                            if (data.length > 0) {
                                window.myconfirm("The Action is being used by the following Rules:\n" + data.join(', ') + "\nDeleting this Action will causes the above rules to be removed.",$this);
                            } else {
                                var $delete = $("#del_hidden");
                                $delete.attr('href', "<?php echo $this->webroot; ?>alerts/delete_alert_action/" + action_id);
                                window.myconfirm("Are your sure?",$delete);
                            }  
                        }
                    });
                });

                jQuery('a.edit_item').click(function() {
                    jQuery(this).parent().parent().trAdd({
                        action: '<?php echo $this->webroot ?>alerts/action_edit_panel/' + jQuery(this).attr('control'),
                        ajax: '<?php echo $this->webroot ?>alerts/action_edit_panel/' + jQuery(this).attr('control'),
                        saveType: 'edit',
                        callback: function() {
                            /*
                             $('#ActionBlockAni').live('change', function() {
                             var $this = $(this);
                             var $select = $this.parent().next().find('select');
                             if ($this.attr('checked'))
                             {
                             $select.show();
                             }
                             else
                             {
                             //$select.hide();
                             $('option[value=""]', $select).attr('selected', true);
                             }
                             }).trigger('change');
                             */
                        },
                        onsubmit: function(options)
                        {
                            var name = $("#ActionName").val();

                            var BlockAnichecked = $("#ActionBlockAni").attr('checked');
                            var LoopDetectionchecked = $("#ActionLoopDetection").attr('checked');
                            var DisableCodeTrunkchecked = $("#ActionDisableCodeTrunk").attr('checked');

                            if (BlockAnichecked)
                            {
                                $("#ActionBlockAni_").val(1);
                            }
                            if (LoopDetectionchecked)
                            {
                                $("#ActionLoopDetection_").val(1);
                            }
                            if (DisableCodeTrunkchecked)
                            {
                                $("#ActionDisableCodeTrunk_").val(1);
                            }
                            if ('' == name)
                            {
                                jGrowl_to_notyfy("The action's name is required!", {theme: 'jmsg-error'});
                                return false;
                            }
                            return true;
                        }
                    });
                });

                /*
                 jQuery('#add').click(
                 function(){
                 $('.msg').hide();
                 jQuery('table.list body').trAdd({
                 ajax:"<?php echo $this->webroot ?>alerts/action_edit_panel",
                 action:"<?php echo $this->webroot ?>alerts/action_edit_panel",
                 saveType:'add',
                 removeCallback:function(){
                 if(jQuery('table.list tr').size()==1){
                 jQuery('table.list').hide();
                 }
                 },
                 onsubmit: function(options)
                 {
                 var name = $("#ActionName").val();
                 if ('' == name)
                 {
                 jGrowl_to_notyfy("The action's name is required!",{theme:'jmsg-error'});
                 return false;
                 }
                 return true;
                 }
                 });
                 jQuery(this).parent().parent().show();
                 }
                 );
                 */

                jQuery('a.link_btn').click(function() {
                    jQuery('table.list').show().trAdd({
                        action: '<?php echo $this->webroot ?>alerts/action_edit_panel',
                        ajax: '<?php echo $this->webroot ?>alerts/action_edit_panel',
                        removeCallback: function() {
                            if (jQuery('table.list tr').size() == 1) {
                                jQuery('table.list').hide()
                            }
                        },
                        onsubmit: function(options)
                        {
                            var name = $("#ActionName").val();
                            if ('' == name)
                            {
                                jGrowl_to_notyfy("The action's name is required!", {theme: 'jmsg-error'});
                                return false;
                            }
                            return true;
                        }
                    });
                    //jQuery('#DigitTranslationName').attr('mycheck','add','maxLength','256');
                });

                var $popup_window = $('#popup_window');
                var $exclude_anis = $('#exclude_anis');


                $('#exclude_cancel').click(function() {
                    $popup_window.hide();
                });

                jQuery('.exclude_ani').click(function() {
                    var $this = $(this);
                    var control = $this.attr('control');

                    $('#exclude_save').unbind('click');

                    $.ajax({
                        'url': '<?php echo $this->webroot ?>alerts/get_exclude_anis',
                        'type': 'POST',
                        'dataType': 'json',
                        'data': {'id': control},
                        'success': function(data) {
                            $exclude_anis.val(data.data);

                            $popup_window.center().show();
                        }
                    });


                    $('#exclude_save').click(function() {
                        $.ajax({
                            'url': '<?php echo $this->webroot ?>alerts/change_exclude_ani',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {'id': control, 'anis': $exclude_anis.val()},
                            'success': function(data) {
                                jGrowl_to_notyfy("Saved excluded anis!", {theme: 'jmsg-success'});
                            }
                        });
                    });
                });

            });
</script>
<script type="text/javascript">
    $(function() {



    })




</script>