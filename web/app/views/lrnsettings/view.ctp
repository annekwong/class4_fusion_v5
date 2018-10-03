<style type="text/css" >
    
    #title-menu li{
          margin-left: 5px;
          float: left;
    }
    
    #title-menu{
          line-height: 25px; 
          list-style: none; 
          float: right;
    }
    
    
    
</style>


<?php if (isset($error) && false): ?>
    <div style="padding:20px;font-size:16px;color:red;text-align:center">
        <?php echo $error; ?>
    </div>
    <?php return ?>
<?php endif ?>


<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Setting') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Setting', true); ?></h4>

    <div class="clearfix"></div>
</div>

<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">

            <div class="clearfix"></div>
            <div>
                    <table class="form list table dynamicTable tableTools table-bordered  table-white" style="margin-left:10%;width:70%" >
                        <tbody>
                            <tr>
                                <td style="width:15%" class="align_right"><?php echo __('Primary IP', true); ?> </td>
                                <td style="width:35%">
                                    <input type="text" id="ip1" value="<?php
                                    if (isset($post[0][0]['ip1'])) {
                                        echo $post[0][0]['ip1'];
                                    }
                                    ?>" name="ip1" class="input in-text validate[required,custom[ipv4]]">
                                </td>
                                <td style="width:15%" class="align_right"><?php echo __('Secondary IP', true); ?> </td>
                                <td style="width:35%">
                                    <input type="text" id="ip2" value="<?php
                                    if (isset($post[0][0]['ip2'])) {
                                        echo $post[0][0]['ip2'];
                                    }
                                    ?>" name="ip2" class="input in-text validate[required,custom[ipv4]]">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php echo __('Primary Port', true); ?> </td>
                                <td><input  type="text" id="port1" 
                                                           value="<?php
                                                           if (isset($post[0][0]['port1'])) {
                                                               echo $post[0][0]['port1'];
                                                           }
                                                           ?>" 
                                                           name="port1" class="input in-text validate[required,custom[onlyNumberSp]]"></td>

                                <td class="align_right"><?php echo __('Secondary Port', true); ?> </td>
                                <td>
                                    <input  type="text" id="port2" 
                                            value="<?php
                                            if (isset($post[0][0]['port2'])) {
                                                echo $post[0][0]['port2'];
                                            }
                                            ?>" 
                                            name="port2" class="input in-text validate[required,custom[onlyNumberSp]]">
                                </td>
                            </tr>


                            <tr>
                                <td class="align_right"><?php echo __('Timeout ms', true); ?> </td>
                                <td>
                                    <input  type="text" id="timeout1" 

                                            value="<?php
                                            if (isset($post[0][0]['timeout1'])) {
                                                echo $post[0][0]['timeout1'];
                                            }
                                            ?>" 
                                            name="timeout1" class="input in-text validate[required,custom[onlyNumberSp]]">
                                </td>
                                <td class="align_right"><?php echo __('Retries', true); ?> </td>
                                <td>
                                    <input type="text" id="timeout2" 
                                           value="<?php
                                           if (isset($post[0][0]['timeout2'])) {
                                               echo $post[0][0]['timeout2'];
                                           }
                                           ?>" 
                                           name="timeout2" class="input in-text validate[required,custom[onlyNumberSp]]">
                                </td>
                            </tr>
                            <?php if ($_SESSION['role_menu']['Configuration']['lrnsettings']['model_w']) { ?>
                                <tr>
                                    <td colspan="4" class="button-groups center">
                                        <input type="button" class="btn btn-primary input in-submit" value="<?php echo __('submit') ?>" onclick="javascript:postLimit();
                                        return false;">

                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>



                <div style="margin: 0 auto;">

                    <ul id="title-menu">
                        <li>
                            <?php __('Dynamic Time out')?>:
                        </li>
                        <li>
                            <?php if ($dynamic_max_timeout): ?>
                                <a href="<?php echo $this->webroot ?>lrnsettings/doaction/dynamic_max_timeout/0">
                                    <button class="btn btn-primary"><?php __('Inactive')?></button>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $this->webroot ?>lrnsettings/doaction/dynamic_max_timeout/1">
                                    <button class="btn btn-primary"><?php __('Active')?></button>
                                </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <?php __('Dynamic Filter')?>:
                        </li>
                        <li>
                            <?php if ($dynamic_filter): ?>
                                <a href="<?php echo $this->webroot ?>lrnsettings/doaction/dynamic_filter/0">
                                    <button class="btn btn-primary"><?php __('Inactive')?></button>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $this->webroot ?>lrnsettings/doaction/dynamic_filter/1">
                                    <button class="btn btn-primary"><?php __('Active')?></button>
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                        <tr>
                            <th><?php __('LRN Server') ?></th>
                            <th><?php __('Staus'); ?></th>
                            <th><?php __('Current Response Time'); ?></th>
                            <th><?php __('Max Response Time') ?></th>
                            <th><?php __('Dynamic Time out') ?></th>
                            <th><?php __('Dynamic Filter') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lrn_infos as $lrn_info): ?>
                            <tr>
                                <td><?php echo $lrn_info['server'] ?></td>
                                <td><?php echo $lrn_info['status'] ?></td>
                                <td><?php echo $lrn_info['response_time'] ?>ms</td>
                                <td><?php echo $lrn_info['max_response_time'] ?>ms</td>
                                <td><?php echo $lrn_info['dynamic_timeout'] ?></td>
                                <td><?php echo $lrn_info['dynamic_filter'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <div id="lrn_testing" style="text-align:center;">
                    <?php __('Timeout') ?>:
                    <input type="text" id="timeout" value="3000" /><?php __('ms')?> 
                    <input type="button"  class="btn btn-primary margin-bottom10" value="<?php __('Test')?>" id="test_lrn_btn" />
                    &nbsp;&nbsp;
                    <img src="<?php echo $this->webroot ?>images/progress.gif" id="progress_img" style="display:none;" />
                </div>



                <table id="lrn_test_result" class="list" style="display:none;">
                    <thead>
                        <tr>
                            <th><?php __('LRN Server') ?></th>
                            <th><?php __('Response Time'); ?></th>
                            <th><?php __('Result') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function postLimit() {



        var ip1 = $('#ip1').val();
        var ip2 = $('#ip2').val();
        var port1 = $('#port1').val();
        var port2 = $('#port2').val();
        var timeout1 = $('#timeout1').val();
        var timeout2 = $('#timeout2').val();

        var pattern = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
        var pattern1 = /^\d{1,6}$/;

        if (!pattern.test(ip1)) {
            showMessages("[{'field':'#ip1','code':'101','msg':'<?php echo __('inputip', true) ?>'}]");

            return false;
        }


        if (!pattern1.test(port1)) {
            showMessages("[{'field':'#ip2','code':'101','msg':'<?php echo __('inputport', true) ?>'}]");
            return false;
        }



        if (!pattern1.test(timeout1)) {
            showMessages("[{'field':'#ip2','code':'101','msg':'<?php echo __('timeoutinvalid', true) ?>'}]");

            return false;
        }



        $.post("<?php echo $this->webroot ?>lrnsettings/ajax_update.json",
                {ip1: ip1,
                    ip2: ip2,
                    port1: port1,
                    port2: port2,
                    timeout1: timeout1,
                    timeout2: timeout2
                },
        function(text) {

            showMessages("[{'code':'201','msg':'<?php echo __('lrnsucc', true) ?>'}]");
        },
                'json');



    }

</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#ip1,#ip2').xkeyvalidate({type: 'checkIp'})
    })
</script>

<script type="text/javascript">
    $(function() {
        var $lrn_test_result = $('#lrn_test_result');
        var $lrn_test_result_tbody = $("tbody", $lrn_test_result);
        var okimg = "<img src='<?php echo $this->webroot ?>images/flag-1.png'>";
        var nookimg = "<img src='<?php echo $this->webroot ?>images/flag-0.png'>";

        $('#test_lrn_btn').click(function() {
            var timeout = $('#timeout').val();
            $lrn_test_result_tbody.empty()
            $.ajax({
                'url': '<?php echo $this->webroot; ?>lrnsettings/testing',
                'type': 'POST',
                'dataType': 'json',
                'data': {'timeout': timeout},
                'beforeSend': function() {
                    $('#progress_img').show();
                },
                'success': function(data) {
                    $('#progress_img').hide();
                    $lrn_test_result.show();
                    $.each(data, function(index, item) {
                        var $tr = $('<tr>');
                        $tr.append("<td>" + item['lrn_server'] + "</td>");
                        $tr.append("<td>" + item['execute_time'].toFixed(5) + "</td>");
                        if (item['is_ok']) {
                            var show_work = "<a title='It works'>" + okimg + "</a>";
                            $tr.append("<td>" + show_work + "</td>");
                        }
                        else
                        {
                            var show_error = '';
                            if (item['is_timeout'])
                                show_error = "<a title='Timeout'>" + nookimg + "</a>";
                            else
                                show_error = "<a title='Out of work'>" + nookimg + "</a>";
                            $tr.append("<td>" + show_error + "</td>");
                        }
                        $lrn_test_result_tbody.append($tr);
                    });
                    $lrn_test_result.show();
                }
            });
        });

        $("#toggle_btn").toggle(function() {
            $("#sip_pannel").slideUp();
            $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_plus.png");
        }, function() {
            $("#sip_pannel").slideDown();
            $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_minus.png");
        });
    });
</script>

