<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit')?> <?php echo  ($type == 'ingress') ? "Ingress" : "Egress" ; ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Allowed Send－To IP')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Allowed Send－To IP')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]</h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

	<?php if ($type == 'ingress'): ?>
	<?php echo  $this->element('ingress_tab',array('active_tab'=>'allowed_send_to_ip'));?>
	<?php else: ?>
	<?php echo  $this->element('egress_tab',array('active_tab'=>'allowed_send_to_ip'));?>
	<?php endif; ?>
        </div>
        <div class="widget-body">
	
            <?php echo $form->create ('Gatewaygroup', array ('url' => '/' . $this->params['url']['url']));?>
                
            <div id="support_panel" style="text-align:center;padding:20px;">
                 
                <table class=" footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded table-condensed">
                    <thead>
                        <tr>
                            <th style="width:50%;">VoIP Gateway</th>
                            <th style="width:50%;">IP</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                    
                    <?php
                    
                        if(empty($results)){
                    ?>
                    <tr>
                        <td>
                            <select onchange="get_ip(this);">
                                <option value="0"></option>
                                <?php
                                    foreach($options as $val){
                                ?>
                                <option value="<?php echo $val[0]['id']?>"><?php echo $val[0]['name']?></option>
                                <?php        
                                    }
                                ?>
                            </select>
                        </td>
                        <td></td>
<!--                        <td>-->
<!--                            <input class="btn btn-primary test_in" type="button" value="Add" onclick="add(this);">-->
<!--                        </td>-->
                    </tr>
                    
                    <?php        
                        }else{
                    ?>
                    
                    <tr>
                        <td>
                            <select onchange="get_ip(this);">
                                <option value="0"></option>
                                <?php
                                foreach($options as $val){
                                    if($val[0]['id'] == $results[0][0]['voip_gateway_id']){
                                ?>
                                    <option selected value="<?php echo $val[0]['id']?>"><?php echo $val[0]['name']?></option>
                                <?php        
                                    }else{
                                ?>
                                <option value="<?php echo $val[0]['id']?>"><?php echo $val[0]['name']?></option>
                                <?php        
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="hidden" value="<?php echo $results[0][0]['id'];?>"></td>
<!--                        <td>-->
<!--                            <input class="btn btn-primary test_in" type="button" value="Add" onclick="add(this);">-->
<!--                        </td>-->
                    </tr>      

                    <?php
                        foreach($results as $key=>$res){
                            if($key == 0){
                                continue;
                            }
                    ?>
                    
                            <tr>
                                <td>
                                    <select onchange="get_ip(this);">
                                        <?php
                                        foreach($options as $val){
                                            if($val[0]['id'] == $res[0]['voip_gateway_id']){
                                        ?>
                                            <option selected value="<?php echo $val[0]['id']?>"><?php echo $val[0]['name']?></option>
                                        <?php        
                                            }else{
                                        ?>
                                        <option value="<?php echo $val[0]['id']?>"><?php echo $val[0]['name']?></option>
                                        <?php        
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="hidden" value="<?php echo $res[0]['id'];?>"></td>
                                <td>
                                    <input class="btn btn-primary test_in" type="button" value="Remove" onclick="to_remove(this);">
                                </td>
                            </tr>  
                    
                    <?php        
                        }
                    ?>
                    
                    <?php        
                        }
                    ?>
                    
                </table>
            
            <div class="button-groups">
                <input type="submit" class="btn btn-primary" value="<?php __('Submit')?>" />
            </div>
        </div>
        <?php echo $form->end(); ?>
</div>
    </div>
</div>

<script>
    function get_ip(obj){
        var voip_id = $(obj).val();
        var id = $(obj).parents('td').eq(0).next().find('input').val();
        $.ajax({
            'url':'<?php echo $this->webroot."prresource/gatewaygroups/get_ip"?>',
            'type':'post',
            'dataType':'html',
            'data':{'id':voip_id,'ip_id':id},
            'success':function(data){
                $(obj).parents('td').eq(0).next().html(data);
            }
        });
    }

    function add(obj){
        var tr = $(obj).parents('tr').eq(0);
        $(obj).parents('tr').eq(0).after("<tr><td><select onchange='get_ip(this);'><?php foreach($options as $val){ ?> <option value='<?php echo $val[0]['id']?>'><?php echo $val[0]['name']?></option> <?php    } ?> </select></td><td></td><td><input class='btn btn-primary test_in' type='button' value='Remove' onclick='to_remove(this);'></td></tr>");
        
        get_ip($(obj).parents('tr').eq(0).next().find('select').get(0));
    }
    
    function to_remove(obj){
        $(obj).parents('tr').eq(0).remove();
    }
    
    $(function(){
        $.each($("table").find('select'),function(index,content){
            get_ip(content);
        });
    });
    

</script>