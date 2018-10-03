<div id="title">
    <h1><?php __('System')?>&gt;&gt;<?php echo __('VoIPGateway')?></h1>
</div>
<div id="container">
    <center>
        <form  action=""   method="post">
            <input type="hidden" id="resource_ip_id" value="true" name="is_post" class="input in-hidden">
            <fieldset>
                <legend>
                    <?php  if ($_SESSION['role_menu']['Switch']['gatewaygroups:add_server']['model_w']) {?>
                    <a onclick="jQuery('#mb tr:nth-child(1)').clone(true).appendTo('table.list');return false;" href="#">
                        <img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('add')?></a>
                    <?php }?>
                </legend>
                <table class="list list-form"   id="list_table">
                    <thead>
                        <tr>
                            <td>
                                <?php __('Name')?>
                            </td>
                            <td width="33%">
                                <span rel="helptip" class="helptip" id="ht-100003"><?php echo __('IP Address',true);?></span>
                                <span class="tooltip" id="ht-100003-tooltip"><?php __("Gateway IP-adress. You can specify multiple adresses by dividing them with ';'")?></span>
                            </td>
                            <td width="33%"><?php echo __('port',true);?></td>
                            <td><?php echo __('Info IP',true);?></td>
                            <td><?php echo __('Info Port',true);?></td>
                            <td><?php echo __('Sip Capture IP',true);?></td>
                            <td><?php echo __('Sip Capture Port',true);?></td>
                            <td><?php echo __('Sip Capture Path',true);?></td>
                            <?php  if ($_SESSION['role_menu']['Switch']['gatewaygroups:add_server']['model_w']) {?>
                            <td width="33%" class="last">&nbsp;<?php echo __('action',true);?>&nbsp;</td>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody class="rows" id="rows-ip">
                        <?php  $size=count($host);
                        for($i=0;$i<$size;$i++){?>
                        <tr>
                            <td>
                                <input type="text" name="accounts[name][]" value="<?php echo $host[$i]['ServerPlatform']['name']?>"/>
                            </td>
                            <td style="width: 300px;" class="value">
                                <input type="text" value="<?php echo $host[$i]['ServerPlatform']['ip'];?>" class="input in-text ServerPlatformIp" name="accounts[ip][]" id="ip-cps-<?php echo $i+1?>" check="noEmpty/checkIp">
                            </td>
                            <td><input type="text" name="accounts[port][]" value="<?php echo $host[$i]['ServerPlatform']['port']?>"/></td>
                            <td><input type="text" name="accounts[info_ip][]" value="<?php echo $host[$i]['ServerPlatform']['info_ip']?>"/></td>
                            <td><input type="text" name="accounts[info_port][]" value="<?php echo $host[$i]['ServerPlatform']['info_port']?>"/></td>
                            <td><input type="text" name="accounts[sip_ip][]" value="<?php echo $host[$i]['ServerPlatform']['sip_ip']?>"/></td>
                            <td><input type="text" name="accounts[sip_port][]" value="<?php echo $host[$i]['ServerPlatform']['sip_port']?>"/></td>
                            <td><input type="text" name="accounts[sip_path][]" value="<?php echo $host[$i]['ServerPlatform']['sip_path']?>"/></td>
                            <?php  if ($_SESSION['role_menu']['Switch']['gatewaygroups:add_server']['model_w']) {?>
                            <td class="value last"  >
                                <a href="<?php echo $this->webroot ?>switch_profiler/index/<?php echo $host[$i]['ServerPlatform']['server_id']?>" title="Switch Profile">
                                    <img width="16" height="16" src="<?php echo $this->webroot?>images/switch_profile.png">
                                </a>
                                <a href="#" rel="delete" onclick="jQuery(this).parent().parent().remove();return false;">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                            <?php }?>
                        </tr>
                        <?php }?> 
                    </tbody>
                </table>
            </fieldset>
            <?php  if ($_SESSION['role_menu']['Switch']['gatewaygroups:add_server']['model_w']) {?>
            <div id="form_footer">
                <input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
                <input type="button" value="<?php echo __('reset')?>"    class="input in-submit">
            </div>
            <?php }?>
        </form>
        <table id="mb" style="display:none;">
            <tr  id="tpl-ip">
                <td>
                    <input type="text" name="accounts[name][]">
                </td>
                <td class="value"  style="width: 300px;">
                    <input type="text" name="accounts[ip][]" class="input in-text ServerPlatformIp" check="noEmpty/checkIp">
                </td>
                <td><input type="text" name="accounts[port][]"/></td>
                <td><input type="text" name="accounts[info_ip][]" /></td>
                <td><input type="text" name="accounts[info_port][]" /></td>
                <td><input type="text" name="accounts[sip_ip][]" /></td>
                <td><input type="text" name="accounts[sip_port][]" /></td>
                <td><input type="text" name="accounts[sip_path][]" /></td>
                <td class="value last"  style="width: 200px;">
                    <a rel="delete" href="#" onclick="jQuery(this).parent().parent().remove();return false">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('form').submit(function(){
                    var flag=true;
                    var arr = new Array();
                    $('#list_table').find('input[id^=ip-cps]').each(function (){
                        arr.push($(this).val());
                    });
                    var arr2=$.uniqueArray(arr);
                    if(arr.length!=arr2.length){
                        $('#list_table').find('input[id^=ip-cps]').each(function (){
                            flag=false;
                        });
                        jGrowl_to_notyfy('IP Address  Happen  Repeat.',{theme:'jmsg-error'});
                    }
                    return flag;
                });  
            });
            jQuery('form').find('input[check]').xkeyvalidate({type:'split'});
        </script>
    </center>
</div>













