

<p class="separator text-center"><i class="icon-table icon-3x"></i></p>



<div class="buttons">
    <a id="addHost" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php echo __('Add Host', true); ?> </a>
</div>
<table class="list list-form table table-condensed"  id="host_table">
    <thead>
        <tr>
          <th><span rel="helptip" class="helptip" id="ht-100002" title="Name of an account in JeraSoft yht system (for statistics and reports)"><?php echo __('IP/FQDN', true); ?></span><!-- <span class="tooltip" id="ht-100002-tooltip"</span>--></th>
         <th><span rel="helptip" class="helptip" id="ht-100004" title="Technical prefix, that is used to identify users, when multiple clients use same gateway"><?php echo __('Port', true); ?></span><!-- <span class="tooltip" id="ht-100004-tooltip"></span>--></th>
            <th><?php echo __('Capacity', true); ?></th>
            <th class="last">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr class="rows" id="rows">
            <td><input type="text" name="ip[]" id="ip" /></td>
            <td><input type="text" name="port[]" id="port" maxlength="16" /></td>
            <td></td>
            <td><a href="#" title="delete" rel="delete"> <i class='icon-remove'></i> </a></td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">

    jQuery(document).ready(function() {
        $row = $('#rows').remove();
        jQuery(function($) {
            $('#addHost').click(function() {
                $row.clone(true).appendTo('#host_table tbody').show();
                return false;
            });
        });

        jQuery('input[id=port]').xkeyvalidate({type: 'Num'});
        jQuery('form[id=ClientAddegressForm]').submit(function() {
            var re = true;

            if (jQuery('input[id=alias]').val() == '') {
                jQuery('input[id=alias]').jGrowlError('Name is required');
                re = false;
            }
            //if($('#alias').val()==''){
            //	jQuery($('#alias')).jGrowlError('Egress Name is required');
            //	re=false;
            // }
            jQuery('input[id=port]').each(function() {
                if (jQuery(this).val() == '') {
                    jQuery(jQuery(this)).jGrowlError('Port is required');
                    re = false;
                }
                if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                    jQuery(this).jGrowlError('Port,must be whole number! ');
                    re = false;
                }
            });

            jQuery('input[id=ip]').each(function() {

                var arr = jQuery(this).val().split('.');

                for (var i = 0; i < arr.length; i++) {
                    if (isNaN(arr[i]) || arr[i] > 255 || ((arr.length - 1) != 3)) {
                        jQuery(this).jGrowlError('Invalid IP Address.');
                        re = false;
                        break;
                    }
                }

                if (jQuery(this).val().indexOf('.') == -1 || jQuery(this).val().indexOf('/') != -1) {
                    jQuery(this).jGrowlError('Invalid IP Address.');
                    re = false;
                }


            });

            var arr = Array();

            jQuery('#host_table tr').each(function() {
                for (var i in arr) {
                    if (jQuery(this).find('input[id=ip]').val() == arr[i].ip && jQuery(this).find('input[id=port]').val() == arr[i].port) {
                        jQuery.jGrowlError('Ip ' + arr[i].ip + " is Repeat!");
                        re = false;
                        return;
                    }
                }
                if (jQuery(this).find('input[id=ip]').val() != '') {
                    arr.push({ip: jQuery(this).find('input[id=ip]').val(), port: jQuery(this).find('input[id=port]').val()});
                }
            });

            if (re) {
                var arr = Array();
                jQuery('#host_table tr').each(function() {
                    if (jQuery(this).find('#ip').size() > 0) {
                        arr.push(jQuery(this).find('#ip').val() + '/' + jQuery(this).find('#GatewaygroupNeedRegister').val());
                    }
                });

                arr = arr.join(',');
                var data = jQuery.ajaxData("<?php echo $this->webroot ?>ajaxvalidates/ip4r/noDomain?ip=" + arr);
                data = '[' + data + ']';
                data = eval(data);
                data = data[0];
                for (var i in data) {
                    if (data[i] == false) {
                        var eq = parseInt(i) + 1;
                        jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val() + '/' + jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val() + ' is not ip!');
                        re = false;
                    }
                }
            }
            return re;
        });
    });
</script>

