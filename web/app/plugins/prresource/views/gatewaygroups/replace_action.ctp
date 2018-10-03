<style>
    input[type="text"]{margin:5px 0;}
    .widget-body .table td,.widget-body .table th{text-align: center;}
    #replace_actions_wrapper .btn-group-sm { margin-bottom:30px !important;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit')?> <?php echo ucfirst($type); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Replace Action')?>[<?php echo $res['Gatewaygroup']['alias']; ?>]</li>
</ul>


<div class="heading-buttons">
    <h4 class="heading">
        <?php __('Replace Action')?>[<?php echo $res['Gatewaygroup']['alias']; ?>]
    </h4>


</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="javascript:void(0)" class="btn btn-primary btn-icon glyphicons circle_plus" id="add_replace_action">
        <i></i> <?php __('Create New')?></a>
    <a class="list-export btn btn-primary btn-icon glyphicons file_import" href="<?php echo $this->webroot; ?>prresource/gatewaygroups/upload_replace_action/<?php echo $this->params['pass'][0] . "/" . $this->params['pass'][1]; ?>">
        <i></i>
        <?php __('Upload')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php if ($type == 'egress'): ?>
                <?php echo $this->element('egress_tab', array('active_tab' => 'replace_action')); ?>
            <?php else: ?>
                <?php echo $this->element('ingress_tab', array('active_tab' => 'replace_action')); ?>
            <?php endif; ?>
        </div>
        <div class="widget-body" class="overflow_x">
            <form id="myform" method="post">
                <div class="overflow_x">
                    <table id="replace_actions" class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded table-condensed">
                        <thead>
                        <tr>
                            <th rowspan="3"><?php __('Type')?></th>
                            <th colspan="4"><?php __('ANI Manipulation'); ?></th>
                            <th colspan="4"><?php __('DNIS Manipulation'); ?></th>
                            <th rowspan="3"><?php __('Action')?></th>
                        </tr>
                        <tr>
                            <th colspan="3"><?php __('Match Criteria')?></th>
                            <th rowspan="2"><?php __('Change to')?></th>
                            <th colspan="3"><?php __('Match Criteria')?></th>
                            <th rowspan="2"><?php __('Change to')?></th>
                        </tr>
                        <tr>
                            <th><?php __('Prefix')?></th>
                            <th><?php __('Min')?></th>
                            <th><?php __('Max')?></th>
                            <th><?php __('Prefix')?></th>
                            <th><?php __('Min')?></th>
                            <th><?php __('Max')?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr id="clone"  style="display: none;">
                                <td>
                                    <select name="change_type"  class="change_type width120">
                                        <?php foreach ($type_arr as $key => $value): ?>
                                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="width80" name="ani_prefix[]" />
                                </td>
                                <td>
                                    <input type="text" maxlength="2" class="width80" name="ani_min_length[]" />
                                </td>
                                <td>
                                    <input type="text" maxlength="2" class="width80" name="ani_max_length[]" />
                                </td>
                                <td>
                                    <input type="text" name="ani[]" />
                                </td>
                                <!--DNIS-->
                                <td>
                                    <input type="text" class="width80" name="dnis_prefix[]" />
                                </td>
                                <td>
                                    <input type="text" maxlength="2" class="width80" name="dnis_min_length[]" />
                                </td>
                                <td>
                                    <input type="text" maxlength="2" class="width80" name="dnis_max_length[]" />
                                </td>
                                <td>
                                    <input type="text" name="dnis[]" />
                                </td>
                                <td>
                                    <a href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>

                            <?php foreach ($result as $item): ?>
                                <tr class="result">
                                    <td>
                                        <select name="change_type" class="change_type width120" >
                                            <?php foreach ($type_arr as $key => $value): ?>
                                                <option value="<?php echo $key ?>" <?php if (!strcmp($item[0]['type'], $key)): ?>selected="selected"<?php endif; ?>><?php echo $value ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="ani_prefix[]" value="<?php echo $item[0]['ani_prefix']; ?>" class="ani_prefix width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="ani_min_length[]" value="<?php echo $item[0]['ani_min_length']; ?>" maxlength="2" class="ani_min_length width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="ani_max_length[]" value="<?php echo $item[0]['ani_max_length']; ?>" maxlength="2" class="ani_max_length width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="ani[]" value="<?php echo $item[0]['ani']; ?>" class="ani" />
                                    </td>
                                    <td>
                                        <input type="text" name="dnis_prefix[]" value="<?php echo $item[0]['dnis_prefix']; ?>" class="dnis_prefix width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="dnis_min_length[]" value="<?php echo $item[0]['dnis_min_length']; ?>" maxlength="2" class="dnis_min_length width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="dnis_max_length[]" value="<?php echo $item[0]['dnis_max_length']; ?>" maxlength="2" class="dnis_max_length width80" />
                                    </td>
                                    <td>
                                        <input type="text" name="dnis[]" value="<?php echo $item[0]['dnis']; ?>" class="dnis" />
                                    </td>
                                    <td>
                                        <a class="delete_replace_action" href="javascript:void(0)" url ="<?php echo $this->webroot; ?>prresource/gatewaygroups/delete_replace_action/<?php echo $item[0]['id']; ?>">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
                <?php echo $this->element('common/submit_div'); ?>
<!--                <div id="form_footer" class="button-groups center">-->
<!--                    <input type="submit" class="input in-submit btn btn-primary" value="--><?php //__('Submit')?><!--">-->
<!---->
<!--                    <input type="reset" class="input in-submit in-reset btn btn-default" value="--><?php //__('Reset')?><!--">-->
<!--                </div>-->
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(".change_type").live('change',function(){
            var $this = $(this);
            var change_type_value = $this.val();
            $this.closest('tr').find('input').attr('disabled',false);
            if (change_type_value == 0){
                $this.closest('tr').find("input[name*='dnis']").attr('disabled',true);
            }else if (change_type_value == 1){
                $this.closest('tr').find("input[name*='ani']").attr('disabled',true);
            }
        }).trigger('change');

        $("#myform").submit(function() {
            var re = true;
            $(".result").each(function() {
                var change_type = $(this).children().children(":input").eq(0).val();
                var ani_prefix = $(this).children().children(":input").eq(1).val();
                var ani_min = $(this).children().children(":input").eq(2).val();
                var ani_max = $(this).children().children(":input").eq(3).val();
                var change_to_ani = $(this).children().children(":input").eq(4).val();
                var dnis_prefix = $(this).children().children(":input").eq(5).val();
                var dnis_min = $(this).children().children(":input").eq(6).val();
                var dnis_max = $(this).children().children(":input").eq(7).val();
                var change_to_dnis = $(this).children().children(":input").eq(8).val();
//                if (change_type != 1)
//                {// only ANI OR Both 此时 change_to_ani 和 ani_prefix 不能为空
//                    if (!change_to_ani || !ani_prefix)
//                    {
//                        jGrowl_to_notyfy('<?php //__('type is ani false'); ?>//', {theme: 'jmsg-error'});
//                        re = false;
//                        return false;
//                    }
//                }
//                else if (change_type != 0)
//                {// only DNIS OR Both 此时 change_to_ani 和 ani_prefix 不能为空
//                    if (!change_to_dnis || !dnis_prefix)
//                    {
//                        jGrowl_to_notyfy('<?php //__('type is dnis false'); ?>//', {theme: 'jmsg-error'});
//                        re = false;
//                        return false;
//                    }
//                }
                if (/[^a-z0-9]/gi.test(change_to_ani)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain alphanumeric characters only', true), "ANI Prefix"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }

                var ani_prefix_length = change_to_ani.length;
                if (ani_prefix_length > 50)
                {
                    jGrowl_to_notyfy('<?php printf(__('[%s]less than[%u]', true), "ANI Prefix",50); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (!/^[0-9a-zA-Z\-\_\#]{0,16}$/.test(ani_prefix)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must be either empty or 1 - 16 characters or 0-9,a-z,A-Z,-,_,#', true), "Replace with ANI"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (/[\D]+/.test(ani_min)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain numeric characters only', true), "ANI Min Length"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (/[\D]+/.test(ani_max)) {
                    
                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain numeric characters only', true), "ANI Max Length"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }

                if (ani_max > 999999999)
                {
                    jGrowl_to_notyfy('<?php printf(__('[%s]less than[%u]', true), "ANI Max Length",10); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (ani_min && ani_max)
                {
                    if (parseInt(ani_min) > parseInt(ani_max))
                    {
                        jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), "ANI Max Length","ANI Min Length"); ?>', {theme: 'jmsg-error'});
                        re = false;
                        return false;
                    }
                }
                
//                dnis
                if (!/^[0-9a-zA-Z\-\_\#]{0,16}$/.test(change_to_dnis)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must be either empty or 1 - 16 characters or 0-9,a-z,A-Z,-,_,#', true), "DNIS Prefix"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }

                var dnis_prefix_length = change_to_dnis.length;
                if (dnis_prefix_length > 50)
                {
                    jGrowl_to_notyfy('<?php printf(__('[%s]less than[%u]', true), "DNIS Prefix",50); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (/[\D]+/.test(dnis_prefix)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain numeric characters only', true), "Replace with DNIS"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (/[\D]+/.test(dnis_min)) {

                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain numeric characters only', true), "DNIS Min Length"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (/[\D]+/.test(dnis_max)) {
                    
                    jGrowl_to_notyfy('<?php printf(__('[%s]must contain numeric characters only', true), "DNIS Max Length"); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }

                if (dnis_max > 999999999)
                {
                    jGrowl_to_notyfy('<?php printf(__('[%s]less than[%u]', true), "DNIS Max Length",10); ?>', {theme: 'jmsg-error'});
                    re = false;
                    return false;
                }
                if (dnis_min && dnis_max)
                {
                    if (parseInt(dnis_min) > parseInt(dnis_max))
                    {
                        jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), "DNIS Max Length","DNIS Min Length"); ?>', {theme: 'jmsg-error'});
                        re = false;
                        return false;
                    }
                }


            });
            return re;
        });

        var $clone = $('#clone').remove();
        var $add_replace_action = $('#add_replace_action');
        var $replace_actions = $('#replace_actions');
        var $delete_replace_action = $('.delete_replace_action');


        $add_replace_action.click(function() {
            $("tbody", $replace_actions).prepend($clone.clone(true).show());
//            $replace_actions.show();
            $('#clone').addClass('result');
        });

        $delete_replace_action.live('click', function() {

            var $this = $(this);
            var url = $(this).attr('url');
            bootbox.confirm('<?php __('delete replace action'); ?>', function(result) {
                if (result)
                {
                    $this.parent().parent().remove();
                    $("#myform").submit();
                }
            });

        });

    });
</script>