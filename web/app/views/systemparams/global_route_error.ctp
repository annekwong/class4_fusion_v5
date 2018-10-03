<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/global_route_error">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/global_route_error">
        <?php echo __('Default Error Response') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Default Error Response') ?></h4>
</div>
<div class="separator bottom"></div>

<!--<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" onclick="addItem('ip');
            return false;" href="###"><i></i> Create New</a>
</div>-->
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <form  action="<?php echo $this->webroot ?>systemparams/add_error_post"   id="hostForm"  method="post">

                <table class="list list-form table  tableTools table-bordered  table-white table-primary"  id="list_table">
                    <thead>
                        <tr>
                            <th><?php echo __('Error Code', true); ?></th>
                            <th><?php echo __('Error Description', true); ?></th>
                            <th><?php echo __('Default Response', true); ?></th>
                            <th><?php echo __('User-defined Response', true); ?></th>
                        </tr>
                    </thead>
                    <tbody class="rows" id="rows-ip">
                        <?php
                        $size = count($host);
                        for ($i = 0; $i < $size; $i++)
                        {
                            ?>
                            <tr class="row-<?php echo $i % 2 + 1 ?>" id="row-<?php echo $i + 1 ?>" style="">
                                <td>
                                    <input type="hidden" name="accounts[<?php echo $i + 1 ?>][id]" id="ip-id-<?php echo $i + 1 ?>" value="<?php echo $host[$i][0]['id'] ?>" class="input in-hidden">
                                    <?php echo $host[$i][0]['error_code']; ?>
                                </td>
                                <td>
                                    <?php echo ucwords($host[$i][0]['error_description']); ?>
                                </td>
                                <td>
                                    <?php echo $host[$i][0]['default_to_sip_code']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $host[$i][0]['default_to_sip_string']; ?>
                                </td>
                                <td >
                                    <input type="text" value="<?php echo $host[$i][0]['to_sip_code']; ?>"  class="input in-text validate[custom[onlyNumberSp]]" name="accounts[<?php echo $i + 1 ?>][to_sip_code]" id="ip-return_code-<?php echo $i + 1 ?>" check="Num" maxLength='16'>
                                    <input type="text" value="<?php echo $host[$i][0]['to_sip_string']; ?>"  class="input in-text" name="accounts[<?php echo $i + 1 ?>][to_sip_string]" id="ip-return_string-<?php echo $i + 1 ?>"  />
                                </td>

                            </tr>
                        <?php } ?> 
                    </tbody>

                </table>
                <div id="form_footer" class="button-groups center separator">
                    <input type="submit" value="<?php echo __('submit', true); ?>" class="input in-submit btn btn-primary">

                    <a title="" onclick="return myconfirm('Are you sure to reset it?', this)" class="btn btn-cancel"
                       href='<?php echo $this->webroot ?>systemparams/reset_failover_error'>
                           <?php __('Revert') ?>
                    </a>
                </div>
            </form>
            <script type="text/javascript" language="JavaScript">
                var lastId = '<?php echo $size; ?>';
                var dr_groups = [];
//                function addItem(type, row)
//                {
//                    lastId++;
//                    if (!row || !row['id']) {
//                        row = {
//                            'auth_type': type,
//                            'name': 'account_' + lastId,
//                            'proxy_mode': '',
//                            'orig_enabled': 1,
//                            'term_enabled': 1
//                        };
//                    }
//                    for (k in row) {
//                        if (row[k] == null) {
//                            row[k] = '';
//                        }
//                    }
//                    var tRow = $('#tpl-' + type).clone(true);//澶嶅埗鍑嗗濂界殑琛?
//                    tRow.attr('id', 'row-' + lastId).show();//璁剧疆鏄剧ず
//                    tRow.find('input,select').each(function() {
//                        var el = $(this);//褰撳墠琛ㄥ崟鍏冪礌
//                        var name = $(this).attr('name').substring(1).replace('%n', lastId);//璁剧疆鍚嶅瓧(灏嗗悕瀛椾腑鐨?n鏇挎崲涓簂astId)  accounts[6][id]
//                        var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);  //id
//                        el.attr('id', type + '-' + field + '-' + lastId);//璁剧疆id  ip-id-6
//                        //el.addClass('validate[required,custom[onlyNumberSp]]');
//                        el.attr('name', name);
//                        el.attr('style', $(this).attr('style'));
//                        if (el.attr('type') == 'checkbox') {
//                            if (field == 'need_register') {
//                                el.click(function() {
//                                    if ($(this).attr("checked") == true) {
//                                        $(this).attr("value", 'true');
//                                    } else {
//                                        $(this).attr("value", 'false');
//                                    }
//                                });
//                            }
//                            if (typeof (row[field]) == 'object') {
//                                el.attr('checked', jQuery.inArray(1 * el.attr('value'), row[field]) != -1 ? 'checked' : '');
//                                el.attr('name', el.attr('name') + '[]');
//                            } else {
//                                el.attr('checked', row[field] ? 'checked' : '');
//                            }
//                        } else {
//                            el.val(row[field]);
//                        }
//                    });
//                    tRow.find('a[rel=delete]').click(function() {
//                        $(this).closest('tr').remove(); //鎵惧埌浠栨渶闈犺繎鐨則r鍒犻櫎涔?
//                        return false;
//                    });
//                    buildParams(tRow);
//                    if (row['id']) {
//                        tRow.appendTo($('#rows-' + type));
//                    } else {
//                        tRow.prependTo($('#rows-' + type));//<tbody class="rows" id="rows-ip">  灏唗r鍔犲叆tbody
//                    }
//                    if (!row['id']) {
//                        initForms(tRow);
//                        initList();
//                    }
//                }
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
                            if (dr_group != '') {
                                dr_group += ', ';
                            }
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
                    if (div.is(':visible')) {
                        vis = 1;
                    }
                    hideParams();
                    if (!vis) {
                        div.attr('id', 'tooltip').show();
                    }
                    return false;
                });
                $('.rows').find('select[rel*=stop]').live('change', function() {
                    var type = $(this).val();
                    if (type == 3) {
                        $(this).closest('tr').find('input[name*=return_code]').show();
                        $(this).closest('tr').find('input[name*=return_string]').show();
                        $(this).closest('tr').find('input[name*=to_sip_code]').show();
                        $(this).closest('tr').find('input[name*=to_sip_string]').show();
                    } else {
                        $(this).closest('tr').find('input[name*=return_code]').hide();
                        $(this).closest('tr').find('input[name*=return_string]').hide();
                        $(this).closest('tr').find('input[name*=to_sip_code]').hide();
                        $(this).closest('tr').find('input[name*=to_sip_string]').hide();
                    }
                });
                $('.rows #tpl-delete-row').live('click', function() {
                    $this = $(this);
                    bootbox.confirm("Are you sure to delete rule  " + jQuery(this).attr('value') + "?", function(result) {
                        if (result)
                        {
                            var del_rate_id = $this.attr('del_id');
                            if (del_rate_id != null && del_rate_id != '') {
                                var del_val = $('#delete_rate_id').val() + "," + del_rate_id;
                                $('#delete_rate_id').val(del_val);
                            }
                            $this.closest('tr').remove();
                            //璁板綍鍒犻櫎鐨刬d
                            return false;
                        }

                    });

                });
                $(window).click(hideParams);
            </script>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('input[check=Num]').xkeyvalidate({type: 'Num'});
                    jQuery('input[check=code]').xkeyvalidate({type: 'code'});
                    jQuery('#hostForm').submit(function() {
                        var null_flag = true;
                        var flag = true;
                        var error_flag = true;
                        var arr = new Array();
                        $('#list_table').find('input[check=code]:visible').each(function() {
                            if ($(this).val() == null || $(this).val() == '') {
                                null_flag = false;
                                $(this).attr('class', 'invalid');
                            }
                            arr.push($(this).val());
                            if (/\D/.test(jQuery(this).val())) {
                                jQuery(this).addClass('invalid');
                                error_flag = false;
                            }




                        });
                        var arr2 = $.uniqueArray(arr);
                        if (arr.length != arr2.length) {
                            $('#list_table').find('input[id^=ip-reponse_code]').each(function() {
                                flag = false;
                            });
                            if (null_flag == true) {
                                jGrowl_to_notyfy('Reponse Code  happen  repeat.', {theme: 'jmsg-error'});
                            }
                        }
                        if (!error_flag) {
                            jGrowl_to_notyfy('Code, must be whole number!', {theme: 'jmsg-error'});
                        }
                        if (null_flag == false) {
                            jGrowl_to_notyfy('Reponse Code  is  not null.', {theme: 'jmsg-error'});
                        }
                        if (null_flag == true && flag == true && error_flag) {
                            return true;
                        } else {
                            return false;
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>
