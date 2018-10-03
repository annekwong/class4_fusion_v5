<a id="addHost" onclick="return false;" href="#">
   <img width="10" height="10" src="<?php echo $this->webroot?>images/add-small.png"> <?php echo __('Add Host',true);?>
</a>
<table class="list list-form"  id="host_table" style="width:75%">
   <thead>
     <tr>
       <td style="width:20%">
          <span rel="helptip" class="helptip" id="ht-100002"><?php echo __('IP/FQDN',true);?></span>
          <span class="tooltip" id="ht-100002-tooltip">Name of an account in JeraSoft yht system (for statistics and reports)</span>
       </td>
       <?php if($$hel->isIngress(@$type)){?>
       <td  style="width:20%" style="display:none">
          <span rel="helptip" class="helptip" id="ht-100003"><?php echo __('Netmask',true);?></span>
          <span class="tooltip" id="ht-100003-tooltip">Gateway IP-adress. You can specify multiple adresses by dividing them with ";"</span>
       </td>
       <?php }?>
       <td  style="width:20%">
          <span rel="helptip" class="helptip" id="ht-100004"><?php echo __('port',true);?></span>
          <span class="tooltip" id="ht-100004-tooltip">Technical prefix, that is used to identify users, when multiple clients use same gateway</span>
       </td>
       <td  style="width:20%"" class="last"><?php echo __('Capacity',true);?></td>
       <td  style="width:20%" class="last">&nbsp;</td>
     </tr>
  </thead>
  <tbody class="rows" id="rows-ip">
     <?php if(isset($hosts) && is_array($hosts)){?>
       <?php foreach($hosts as $host){?>
       <?php
       		if( array_keys_value($host,'ip')){
       				$ip=array_keys_value($host,'ip');
       		}else{ 
       				$ip=array_keys_value($host,'fqdn');
       					}
       			 ?>
       <?php $ip=split("/",$ip)?>
          <tr  style="">
            <td style="width: 200px;" class="value">
            		<input type="text" style="width: 200px;" name="accounts[ip][]" id="ip" value="<?php echo array_keys_value($ip,0)?>">
            </td>
            <?php if($$hel->isIngress(@$type)){?>
            <td>
            		<?php echo $xform->input('need_register.',Array('value'=>array_keys_value($ip,1),'name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24)))?>
            </td>
        			<?php }?>
			        <td style="width: 350px;" class="value">
			            <input type="text" name="accounts[port][]" id="port" maxlength="16" value="<?php echo array_keys_value($host,'port')?>">
             </td>
             <td width="8%" class="last" style="text-align: center;">
                 <a title="<?php echo __('Capacity',true);?>" href="<?php echo $this->webroot?>gatewaygroups/add_host_time/<?php echo array_keys_value($host,'resource_ip_id')?>/<?php echo array_keys_value($host,'fqdn')?>">
                		<img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png">
                 </a>
             </td>
             <td style="width: 200px;" class="value last">
                	<a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();">
                    <i class="icon-remove"></i>
                  </a>
             </td>
           </tr>
         <?php }?>
         <?php }?>
           <tr id="mb" style="">
              <td style="width: 200px;" class="value">
                <input type="text" style="width: 200px;" name="accounts[ip][]" id="ip">
              </td>
              <?php if($$hel->isIngress(@$type)){?>
	            <td>
	            		<?php echo $xform->input('need_register.',Array('name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24)))?>
	            </td>
        			<?php }?>
              <td style="width: 350px;" class="value">
                <input type="text" name="accounts[port][]" id="port" maxlength="16">
              </td>
              <td width="8%" class="last" style="text-align: center;"></td>
              <td style="width: 200px;" class="value last">
                <a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();">
                   <i class="icon-remove"></i>
                </a>
              </td>
             </tr>
            </tbody>
           </table>
<script type="text/javascript">
jQuery(document).ready(function() {
    var mb = jQuery('#mb').remove();
    jQuery('#addHost').click(function () {
        mb.clone(true).appendTo('#host_table tbody');
        return false;
    });
    jQuery('input[id=port]').xkeyvalidate({type: 'Num'});
    jQuery('form[id^=Resource]').submit(function () {
        var re = true;
        if (jQuery('input[id=alias]:last').val() == '') {
            jQuery('input[id=alias]:last').jGrowlError('alias must not null!');
            re = false;
        }
        jQuery('input[id=port]').each(function () {
            if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                jQuery(this).jGrowlError('this is must nubmer');
                re = false;
            }
        });
        jQuery('input[id=ip]').each(function () {
            if (jQuery(this).val().indexOf('.') == -1 || jQuery(this).val().indexOf('/') != -1) {
                jQuery(this).jGrowlError('this is must Ip or Domain!');
                re = false;
            }
        });
        var arr = Array();
        jQuery('#host_table tr').each(function () {
            for (var i in arr) {
                if (jQuery(this).find('input[id=ip]').val() == arr[i].ip && jQuery(this).find('input[id=port]').val() == arr[i].port) {
                    jQuery.jGrowlError('Ip ' + arr[i].ip + " is Repeat!");
                    re = false;
                    return;
                }
            }
            if (jQuery(this).find('input[id=ip]').val() != '') {
                arr.push({
                    ip: jQuery(this).find('input[id=ip]').val(),
                    port: jQuery(this).find('input[id=port]').val()
                });
            }
        });
//        if (re) {
//            var arr = Array();
//            jQuery('#host_table tr').each(function () {
//                if (jQuery(this).find('#ip').size() > 0) {
//                    arr.push(jQuery(this).find('#ip').val() + '/' + jQuery(this).find('#ResourceNeedRegister').val());
//                }
//            });
//            arr = arr.join(',');
//            var data = jQuery.ajaxData("<?php //echo $this->webroot?>//ajaxvalidates/ip4r/noDomain?ip=" + arr);
//            data = '[' + data + ']';
//            data = eval(data);
//            data = data[0];
//            for (var i in data) {
//                if (data[i] == false) {
//                    var eq = parseInt(i) + 1;
//                    jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val() + '/' + jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val() + ' is not ip!');
//                    re = false;
//                }
//            }
//        }
        return re;
    });
});
</script>


