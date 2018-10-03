<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/failover">
            <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/failover/<?= $type ?>">
            <?php echo __($titie_name) ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __($titie_name) ?></h4>
</div>
<div class="separator bottom"></div>

<div class="buttons pull-right newpadding">
    <!--    <a class="btn btn-primary btn-icon glyphicons circle_plus" onclick="addItem('ip');
                return false;" href="###"><i></i> Create New</a>-->
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <?php //die(var_dump($host)); ?>
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if (!strcmp($type, 'all')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons settings" href="<?php echo $this->webroot ?>systemparams/failover/all">
                        <i></i>
                        <?php __('System Default')?>
                    </a>
                </li>
                <li <?php if (!strcmp($type, 'orig')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons left_arrow" href="<?php echo $this->webroot ?>systemparams/failover/orig">
                        <i></i>
                        <?php __('User-Defined Origination')?>
                    </a>
                </li>
                <li <?php if (!strcmp($type, 'term')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons right_arrow" href="<?php echo $this->webroot ?>systemparams/failover/term">
                        <i></i>
                        <?php __('User-Defined Termination')?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <form  action="<?php echo $this->webroot ?>systemparams/add_rule_post/<?php echo $type; ?>"   id="hostForm"  method="post">
                <input type="hidden" name="is_all_trunk" id="is_all_trunk" value="" />
                <input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
                <?php echo $this->element('systemparams/failover_table');?>
                <?php
                if (strcmp($type, 'all'))
                {
                    ?>
                    <div id="form_footer" class="button-groups center">
                        <input type="button" id="form_submit" value="<?php echo __('submit', true); ?>" class="input in-submit btn btn-primary">

                        <a title="" id="reset-btn" class="btn btn-cancel btn-default"
                           href='javascript:void(0);'>
                            <?php __('Revert')?>
                        </a>
                    </div>
                <?php } ?>
            </form>
            <?php
            $size = count($host);
            ?>
            <script type="text/javascript" language="JavaScript">
                var lastId = '<?php echo $size; ?>';
                var dr_groups = [];
                function addItem(type, row)
                {
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
                    for (k in row) {
                        if (row[k] == null) {
                            row[k] = '';
                        }
                    }
                    var tRow = $('#tpl-' + type).clone(true);//澶嶅埗鍑嗗濂界殑琛?
                    tRow.attr('id', 'row-' + lastId).show();//璁剧疆鏄剧ず
                    tRow.find('input,select').each(function() {
                        var el = $(this);//褰撳墠琛ㄥ崟鍏冪礌
                        var name = $(this).attr('name').substring(1).replace('%n', lastId);//璁剧疆鍚嶅瓧(灏嗗悕瀛椾腑鐨?n鏇挎崲涓簂astId)  accounts[6][id]
                        var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);  //id
                        el.attr('id', type + '-' + field + '-' + lastId);//璁剧疆id  ip-id-6
                        //el.addClass('validate[required,custom[onlyNumberSp]]');
                        el.attr('name', name);
                        el.attr('style', $(this).attr('style'));
                        if (el.attr('type') == 'checkbox') {
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

                    $("#form_submit").click(function() {
                        bootbox.confirm('All of The Trunks will be re-configured?', function(result)
                        {
                            if (result)
                            {
                                $("#is_all_trunk").val('1');
                                jQuery('#hostForm').submit();
                            }
                        });
                    });


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
                        var diff_arr = minus(arr);
                        if (arr.length != arr2.length) {
                            $('#list_table').find('input[id^=ip-reponse_code]').each(function() {
                                flag = false;
                            });
                            if (null_flag == true) {
                                jGrowl_to_notyfy('The return code ' + diff_arr + ' is already defined.', {theme: 'jmsg-error'});
                                return false;
                            }
                        }
                        if (!error_flag) {
                            jGrowl_to_notyfy('Code, must be whole number!', {theme: 'jmsg-error'});
                            return false;
                        }
                        if (null_flag == false) {
                            jGrowl_to_notyfy('Reponse Code  is  not null.', {theme: 'jmsg-error'});
                            return false;
                        }
                        if (null_flag == true && flag == true && error_flag) {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    });
                });
                function minus(arr)
                {
                    var same_arr = [];
                    var diff_arr = [];
                    for (var i = 0, l = arr.length; i < l; ++i)
                    {
                        if ($.inArray(arr[i], same_arr) === -1)
                        {
                            same_arr.push(arr[i]);
                        }
                        else
                        {
                            diff_arr.push(arr[i]);
                        }
                    }
                    return diff_arr.toString();
                }

            </script>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#reset-btn").click(function () {
            $.post("<?php echo $this->webroot; ?>systemparams/reset_failover/<?php echo $type; ?>", {}, function (data) {
                if(data !== 0) {
                    $("#list_table").remove();
                    $("#hostForm").prepend(data);
                }
            });
        });
    });
</script>