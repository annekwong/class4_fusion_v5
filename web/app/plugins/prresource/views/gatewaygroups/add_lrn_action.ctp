<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier', true); ?>[<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Ingress Trunk', true); ?><?php echo $this->element('title_name', array('name' => $name)); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('LRN Digit Modifier'); ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('LRN Digit Modifier'); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("ingress_tab", array('active_tab' => 'lrn_action')) ?>
        </div>
        <div class="widget-body">
            <form action="<?php echo $this->webroot ?>prresource/gatewaygroups/add_lrn_action_post/<?php
            if (!empty($resource_id))
            {
                echo base64_encode($resource_id);
            }
            else
            {
                echo '';
            }
            ?>?gress=ingress"   method="post"  id="trans_form">
                        <?php echo $appGetewaygroup->echo_resource_hidden($resource_id, $gress); ?>
                <fieldset>
                    <legend>
                           <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                           {
                               ?>
                            <a onclick="addItem('ip');
                                    return false;" href="###" class="btn btn-primary btn-icon glyphicons circle_plus">
                                <i></i> <?php __('Add LRN  Action')?></a><?php } ?></legend>

                    <?php
                    $size = count($host);
                    if ($size == 0)
                    {
                        ?>
                        <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                           <?php } ?>

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"   id="list_table"  style="width: 100%;<?php
                           if ($size == 0)
                           {
                               echo 'display:none';
                           }
                           ?>"  >
                        <thead>
                            <tr>
                                <th><?php echo __('Modify DNIS', true); ?></th>
                                <th><?php __('action') ?></th>
                                <th><?php echo __('Match Prefix', true); ?></th>
                                <th><?php echo __('Prefix to Append', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                            {
                                ?><th><?php echo __('action', true); ?></th>
<?php } ?>
                            </tr>
                        </thead>
                        <tbody class="rows" id="rows-ip">
                                    <?php for ($i = 0; $i < $size; $i++)
                                    {
                                        ?>
                                        <?php $iadd = $i + 1 ?>
                                <tr class="row-<?php echo $i % 2 + 1 ?>" id="row-<?php echo $i + 1 ?>" style="">
                                    <td style="width: 200px;" class="value">
                                        <input type="hidden" name="accounts[<?php echo $i + 1 ?>][id]" id="ip-id-<?php echo $i + 1 ?>" value="<?php echo $host[$i][0]['id'] ?>" class="input in-hidden">
    <?php
    $ii = $i + 1;

    echo $form->input('client_id', array('options' => array('1' => 'Before Send to LRN Server', '2' => 'After Receive from LRN Server'), 'id' => "ip-direction-$ii", 'name' => "accounts[$ii][direction]", 'selected' => $host[$i][0]['direction'],
        'style' => '205px;',
        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
    ?>
                                    </td>


                                    <td style="width: 350px;" class="value">
    <?php
    $t = array('1' => __('AddPrefix', true), '3' => __('DelPrefix', true), '2' => __('Addsuffix', true), '4' => __('Delsuffix', true));
    echo $form->input('client_id', array('options' => $t, 'id' => "ip-action-$ii", 'name' => "accounts[$ii][action]", 'selected' => $host[$i][0]['action'],
        'style' => '205px;', 'onchange' => 'PrefixChange(this)',
        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
    ?>
                                    </td>
                                    <td style="width: 300px;" class="value"><input type="text"   value="<?php echo $host[$i][0]['dnis'] ?>"  class="input in-text"  name="accounts[<?php echo $i + 1 ?>][dnis]" id="ip-dnis-<?php echo $i + 1 ?>"   mycheck="num_code"  ></td>


                                    <td style="width: 300px;" class="value">

                                        <?php
                                        if ($host[$i][0]['action'] == 1 || $host[$i][0]['action'] == 2)
                                        {
                                            ?>
                                            <input type="text"  mycheck="num_code"   value="<?php echo $host[$i][0]['digits'] ?>"  class="input in-text" name="accounts[<?php echo $i + 1 ?>][digits]" id="ip-digits-<?php echo $i + 1 ?>" >
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                            <select  name="accounts[<?php echo $i + 1 ?>][digits]" id="ip-digits-<?php echo $i + 1 ?>"> 


                                                <?php
                                                for ($k = 1; $k < 11; $k++)
                                                {
                                                    if ($k == $host[$i][0]['digits'])
                                                    {
                                                        ?><option value="<?php echo $k ?>" selected ><?php echo $k ?></option><?php
                                                }
                                                else
                                                {
                                                    ?><option value="<?php echo $k ?>" ><?php echo $k ?></option><?php
                                                }
                                            }
                                            ?>
                                            </select>
                                        <?php
                                    }
                                    ?>

                                    </td>



    <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
    {
        ?>
                                        <td style="width: 200px;" class="value last">
                                            <a href="#" rel="delete"  onclick="del_lrn_action(this)">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td> 
                                        <?php } ?>
                                </tr>



                                    <?php } ?> 
                                    <?php //  鐢╥d="tpl-ip"琛ㄧず  鍑嗗澶嶅埗鐨刪ang  ?>
                            <tr  style="display:none" id="tpl-ip" class="  row-2">
                                <td class="value"  style="width: 200px;">
                                    <?php
                                    echo $form->input('client_id', array('options' => array('1' => 'Before Send to LRN Server', '2' => 'After Receive from LRN Server'), 'name' => "_accounts[%n][direction]", 'style' => '205px;',
                                        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                    ?>
                                </td>
                                <td style="width: 350px;" class="value">
<?php
$t = array('1' => __('AddPrefix', true), '3' => __('DelPrefix', true), '2' => __('Addsuffix', true), '4' => __('Delsuffix', true));
echo $form->input('client_id', array('options' => $t, 'name' => "_accounts[%n][action]", 'onchange' => "PrefixChange(this)",
    'style' => '205px;',
    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
?>
                                </td>
                                <td style="width: 300px;" class="value"><input type="text"  class="input in-text" name="_accounts[%n][dnis]"  mycheck="num_code"  ></td>
                                <td class="value"  style="width: 300px;">
                                    <input id="digits" type="text" name="_accounts[%n][digits]" class="input in-text" mycheck="num_code">

                                </td>

<?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
{
    ?> <td class="value last"><a rel="delete" href="###"><i class="icon-remove"></i></a></td>
<?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
<?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
{
    ?>
                    <div id="form_footer" class="buttons-group center">

                        <input type="submit" value="<?php __('submit') ?>"  class="input in-submit btn btn-primary">
                        <input type="reset" value="<?php echo __('reset') ?>"    class="input in-submit btn btn-default">
                    </div><?php } ?>
            </form>

            <script type="text/javascript" language="JavaScript">
                //<![CDATA[
                var pro_arr = new Array();
                function check_sel()
                {
                    var ret = true;
                    $("#rows-ip tr").each(function(i) {
                        if ($("#ip-time_profile_id-" + (i + 1)).val() == undefined)
                        {
                        }
                        else
                        {
                            var this_selval = $("#ip-time_profile_id-" + (i + 1)).val();
                            $.grep(pro_arr, function(val, key) {
                                //杩囨护鍑芥暟鏈変袱涓弬鏁?绗竴涓负褰撳墠鍏冪礌,绗簩涓负鍏冪礌绱㈠紩
                                if (val == this_selval) {
                                    $("#ip-time_profile_id-" + (i + 1)).addClass('invalid');
                                    $("#ip-time_profile_id-" + (key + 1)).addClass('invalid');
                                    alert('Time profile');
                                    ret = false;
                                }
                            });
                            pro_arr.push(this_selval);
                            //alert($("#ip-time_profile_id-"+(i+1)).val());
                        }
                    });
                    return ret;
                }


                jQuery(document).ready(function() {
                    jQuery('#trans_form').submit(function() {
                        var flag = true;
                        var arr = new Array();
                        var isRepeat = false;
                        
                        jQuery('input[mycheck=num_code]:visible').each(function() {
                            var code = jQuery(this).val();
                            if (code != '') {
                                if (/[^0-9\+\*\#\.\-]/.test(code)) {
                                    jGrowl_to_notyfy('Code, must be number or +-*#! ', {theme: 'jmsg-error'});
                                    flag = false;
                                }
                            }
                        });
                        jQuery('input[id*=ip-number_length]:visible').each(function() {
                            var num_length = jQuery(this).val();
                            if (/\D/.test(num_length)) {
                                jGrowl_to_notyfy('Number Length, must be whole number!', {theme: 'jmsg-error'});
                                flag = false;
                            }

                        });


                        //   jQuery('#digits').val();

                        if (jQuery('#digits').val() != '') {
                            if (/[^0-9\+\*\#\.\-]/.test(jQuery('#digits').val())) {
                                jGrowl_to_notyfy('Chars to Add, must be number or +-*#! ', {theme: 'jmsg-error'});
                                flag = false;
                            }
                        }
                        jQuery('input[id*=ip-digits-]').each(function() {
                            var code_to_add = jQuery(this).val();
                            if (code_to_add != '') {
                                if (/[^0-9\+\*\#\.\-]/.test(code_to_add)) {
                                    jGrowl_to_notyfy('Chars to Add, must be whole number! ', {theme: 'jmsg-error'});
                                    flag = false;
                                }
                            }

                        })


                        jQuery('#rows-ip').find('tr').each(function() {
                            var time_profile_id = jQuery(this).find('select[id^=ip-time_profile_id]').val();
                            var type = jQuery(this).find('select[id^=ip-type]').val();
                            var dnis = jQuery(this).find('select[id^=ip-dnis]').val();
                            for (var i in arr) {
                                if (arr[i].time_profile_id == time_profile_id && arr[i].type == type && arr[i].dnis == dnis) {
                                    //isRepeat=true;
                                    break;
                                }



                            }
                            arr.push({time_profile_id: time_profile_id, type: type, dnis: dnis});
                        });
                        if (isRepeat) {
                            jGrowl_to_notyfy('Time Profile  Happen  Repeat.', {theme: 'jmsg-error'});
                            flag = false;
                        }
                        return flag;
                    });
                });
                var lastId = '<?php echo $size; ?>';
                var dr_groups = [];
                function addItem(type, row)
                {

                    jQuery('.msg').hide();
                    jQuery('#list_table').show();

                    lastId++;
                    if (!row || !row['id']) {
                        row = {
                            'auth_type': type,
                            'name': 'account_' + lastId,
                            'proxy_mode': '',
                            'orig_enabled': 1,
                            'term_enabled': 1
                        };
                    }
                    // fix row values
                    for (k in row) {
                        if (row[k] == null)
                            row[k] = '';
                    }
                    // prepare row
                    var tRow = $('#tpl-' + type).clone(true);//澶嶅埗鍑嗗濂界殑琛?
                    tRow.attr('id', 'row-' + lastId).show();//璁剧疆鏄剧ず

                    // set names / values寰幆琛屽唴鐨勬瘡涓〃鍗曞厓绱?
                    tRow.find('input,select').each(function() {
                        var el = $(this);//褰撳墠琛ㄥ崟鍏冪礌
                        //鍑嗗琛岀殑鍚嶅瓧  _accounts[%n][id]  鏇挎崲涓篴ccounts[6][id]
                        var name = $(this).attr('name').substring(1).replace('%n', lastId);//璁剧疆鍚嶅瓧(灏嗗悕瀛椾腑鐨?n鏇挎崲涓簂astId)  accounts[6][id]
                        var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);  //id
                        el.attr('id', type + '-' + field + '-' + lastId);//璁剧疆id  ip-id-6
                        el.attr('name', name);

                        //        瀵筩heckbox鐨勫鐞?
                        if (el.attr('type') == 'checkbox') {
                            //缁檆heckbox娉ㄥ唽浜嬩欢
                            if (field == 'need_register') {
                                el.click(function() {
                                    if ($(this).attr("checked") == true) {
                                        $(this).attr("value", 'true');
                                    } else {
                                        $(this).attr("value", 'false');
                                    }
                                });
                            }


                            if (typeof (row[field]) == 'object') {
                                el.attr('checked', jQuery.inArray(1 * el.attr('value'), row[field]) != -1 ? 'checked' : '');
                                el.attr('name', el.attr('name') + '[]');
                            } else {
                                el.attr('checked', row[field] ? 'checked' : '');
                            }
                        } else {
                            el.val(row[field]);
                        }
                    });
                    // remove of the row  缁欏垹闄ょ敤鐨勫浘鐗囬摼鎺ユ敞鍐屼簨浠?
                    tRow.find('a[rel=delete]').click(function() {
                        $(this).closest('tr').remove(); //鎵惧埌浠栨渶闈犺繎鐨則r鍒犻櫎涔?
                        return false;
                    });
                    buildParams(tRow);
                    if (row['id']) {
                        tRow.appendTo($('#rows-' + type));
                    } else {
                        tRow.prependTo($('#rows-' + type));//<tbody class="rows" id="rows-ip">  灏唗r鍔犲叆tbody
                    }
                    // styles
                    if (!row['id']) {
                        initForms(tRow);
                        initList();
                    }
                }

                function buildParams(row)
                {
                    var s = '';

                    if (row.find('input[name*=orig_capacity]').val()) {
                        s += ' / OC: ' + row.find('input[name*=orig_capacity]').val();
                    }
                    if (row.find('input[name*=term_capacity]').val())
                        s += ' / TC: ' + row.find('input[name*=term_capacity]').val();
                    if (row.find('select[name*=protocol]').val())
                        s += ' / ' + row.find('select[name*=protocol] :selected').text();
                    if (row.find('input[name*=proxy_mode]').val())
                        s += ' / P: ' + row.find('input[name*=proxy_mode]').val();
                    if (row.find('select[name*=id_dr_plans]').val())
                        s += '<br/>RP: ' + row.find('select[name*=id_dr_plans] :selected').text();
                    if (row.find('select[name*=orig_rate_table]').val())
                        s += '<br/>Orig RT: ' + row.find('select[name*=orig_rate_table] :selected').text();
                    if (row.find('select[name*=term_rate_table]').val())
                        s += '<br/>Term RT: ' + row.find('select[name*=term_rate_table] :selected').text();

                    var dr_group = '';
                    row.find('input[name*=dr_groups]').each(function() {
                        if ($(this).attr('checked')) {
                            if (dr_group != '')
                                dr_group += ', ';
                            dr_group += dr_groups[$(this).val()];
                        }
                    });
                    if (dr_group != '') {
                        s += '<br/>G: ' + dr_group;
                    }

                    if (s.substring(0, 3) == ' / ') {
                        s = s.substring(3);
                    }
                    if (s.substring(0, 5) == '<br/>') {
                        s = s.substring(5);
                    }
                    if (!s) {
                        s = '&mdash; &raquo;';
                    }

                    row.find('#tpl-params-text').html(s);
                    return s;
                }
                function hideParams()
                {
                    $('.rows div.params-block:visible').hide().attr('id', '').each(function() {
                        buildParams($(this).parent().parent());
                    });
                }

                //live event handlers
                $('.rows #tpl-params-block div').live('click', function(e) {
                    e.stopPropagation();
                });
                $('.rows #tpl-params-block div a').live('click', function() {
                    hideParams();
                    return false;
                });
                $('.rows #tpl-params-link').live('click', function() {
                    var vis = 0;
                    var div = $(this).parent().find('div');
                    if (div.is(':visible'))
                        vis = 1;
                    hideParams();
                    if (!vis) {
                        div.attr('id', 'tooltip').show();
                    }
                    return false;
                });
                $('.rows #tpl-delete-row').live('click', function() {
                    $(this).closest('tr').remove();
                    return false;
                });
                $(window).click(hideParams);



                //]]>
            </script>
        </div>
    </div>
</div>
<script type="text/javascript">
<!--

    jQuery.xkeyvalidatesfuns.MyNum = function(that, obj) {
        var value = jQuery(that).val();
        var re = true;
        while (/[^-\.\#\+\*\d]/g.test(value))
        {
            value = value.replace(/[^-\.\#\+\*\d]/g, '');
            re = false;
        }
        jQuery(that).val(value);
        return re;
    }
    jQuery.fn.xkeyvalidatesfuns.MyNum = function(that, obj) {
        jQuery(that).each(function() {
            jQuery(this).keyup(function(e) {
                if (e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40) {
                    return;
                }
                jQuery.xkeyvalidatesfuns.MyNum(jQuery(this), obj);
            });
        });
    }

    jQuery(document).ready(function() {
        jQuery('input[check=Num]').xkeyvalidate({type: 'Num'}).attr('maxLength', '8')

    });

    jQuery(document).ready(function() {
        jQuery('input[check=MyNum]').xkeyvalidate({type: 'MyNum'}).attr('maxLength', '8')

    });

    jQuery(document).ready(function() {
        jQuery('#checkForm').submit(function() {
            te = true;
            re = true;
            jQuery('input[check=Num]').map(function() {
                if (/\D/.test(jQuery(this).val())) {
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('must contain numeric characters only.', {theme: 'jmsg-error'});
                    te = false;
                }
            });
            jQuery('input[check=MyNum]').map(function() {
                if (!/[^-\.\#\+\*\d]/g.test(jQuery(this).val())) {
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('must contain numeric characters only.', {theme: 'jmsg-error'});
                    te = false;
                }
            });
            re = check_sel();
            if (re == false)
            {
                te = false;
            }
            return te;
        });
    })



    function PrefixChange(obj) {
        if ($(obj).val() == '1' || $(obj).val() == '2') {
            var Fixname = $(obj).parent().next().next().children().attr('name');
            var Fixid = $(obj).parent().next().next().children().attr('id');
            $(obj).parent().next().next().html('');
            $(obj).parent().next().next().append("<input id='" + Fixid + "' class='input in-text in-input' mycheck='num_code' type='text' name='" + Fixname + "'>");
        } else {
            var Fixname = $(obj).parent().next().next().find('input').attr('name');
            var Fixid = $(obj).parent().next().next().find('input').attr('id');
            $(obj).parent().next().next().html('');
            $(obj).parent().next().next().append("<select id='" + Fixid + "' class='input in-select select' name='" + Fixname + "'><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option></select>");
        }
    }


    function del_lrn_action(obj) {
        bootbox.confirm('Are you sure to delete the action ?', function(result) {
            if (result) {
                var actionId = $($(obj).parent().parent().find('td')[0]).find('input').val();
                location = "<?php echo $this->webroot ?>prresource/gatewaygroups/del_lrn_action_post/" + $("#resource_id").attr('value') + "/" + actionId;
            }

        });

    }

//-->
</script>

