<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<?php 			
    $mydata =$p->getDataArray();
    $loop = count($mydata); 
    
    ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rule') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rule') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot?>alerts/add_rules"><i></i> Create New</a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot?>alerts/delete_all');" href="###"><i></i> Delete All</a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('ruleId','<?php echo $this->webroot?>alerts/delete_selected','rate table');" href="###"><i></i> Delete Seleted</a>
        <?php  } ?>
        <?php if (isset($edit_return)) {?>
        <a href="<?php echo $this->webroot;?>alerts/rule" class="link_back btn btn-default btn-icon glyphicons left_arrow"><i></i> Back</a>
        <?php }?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

    <ul class="tabs">
        <li class="active">
            <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rule">
                <i></i><?php __('Rule') ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js tag" href="<?php echo $this->webroot; ?>alerts/action">
                <i></i><?php __('Action') ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js projector" href="<?php echo $this->webroot; ?>alerts/condition">
                <i></i><?php __('Condition') ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_ani">
                <i></i><?php __('Block') ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <i></i><?php __('Trouble Tickets') ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <i></i><?php __('Trouble Tickets Mail Template') ?>			
            </a>
        </li>
    </ul>    
        </div>
        <div class="widget-body">


    <?php 
    if(empty($mydata)){
    ?>
    <h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
    <?php }else{

    ?>
    <div class="separator bottom row-fluid">
    <div class="pagination pagination-large pagination-right margin-none">
        <?php echo $this->element('page'); ?>
    </div> 
</div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

        <thead>
            <tr>

                <th class="footable-first-column expand" data-class="expand" ><?php if($_SESSION['login_type']=='1'){?>
                    <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this,'ruleId');" value=""/>
                    <?php }?></th>
                <th ><?php echo __('Rule Name');?> </th>
                <th > <?php echo __('Condition'); ?>  </th>
                <th > <?php echo __('Action'); ?>  </th>
                <th colspan="4"><?php echo __('Monitor Target'); ?></th>
                <th colspan="2"><?php echo __('Statistics Collection') ?></th>
                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;" > <?php echo __('Orig/Term'); ?>  </th>
                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"> <?php echo __('Last Run'); ?>  </th>
                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"> <?php echo __('Next Run'); ?>  </th>
                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"> <?php echo __('Update By'); ?>  </th>
                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"> <?php echo __('Update At'); ?>  </th>
                <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
                <th rowspan="2" data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo __('action',true);?></th>
                <?php }?>

            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php echo __('Trunk'); ?></th>
                <th><?php echo __('Host'); ?></th>
                <th><?php echo __('SRC DNIS'); ?></th>
                <th><?php echo __('DEST DNIS'); ?></th>
                <th><?php echo __('Sample Size'); ?></th>
                <th><?php echo __('Frequency'); ?></th>
                
            </tr>
        </thead>
        <tbody  id="ruleId">
            <?php 
            $week_arr = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');		 	
            for ($i=0;$i<$loop;$i++){
            ?>
            <tr  class="footable-first-column expand row-1" data-class="expand">

                <td style="text-align:center"><?php if($_SESSION['login_type']=='1'){?><input class="select" type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/>
                    <?php }?></td>

                <td align="center">

                    <a title="" href="<?php echo $this->webroot?>alerts/add_rule/<?php echo $mydata[$i][0]['id']?>">
                        <?php echo $mydata[$i][0]['name'];?>
                    </a>

                </td>

                <td class="condition" style="cursor:pointer;"><?php echo isset($name_join_arr['condition'][$mydata[$i][0]['alert_condition_id']]) ? $name_join_arr['condition'][$mydata[$i][0]['alert_condition_id']] : ''; ?></td>
                <td class="action" style="cursor:pointer;"><?php echo isset($name_join_arr['action'][$mydata[$i][0]['alert_action_id']]) ? $name_join_arr['action'][$mydata[$i][0]['alert_action_id']] : ''; ?></td>

                <?php
                /*
                <td > <?php echo $mydata[$i][0]['is_origin']?'Orig':'Term'; ?>  </td>
                * 
                */?>
                <td > <?php 

                    if(!empty($name_join_arr['resource'][$mydata[$i][0]['res_id']])){
                    echo $name_join_arr['resource'][$mydata[$i][0]['res_id']];
                    }


                    ?>  </td>

                <td > <?php echo $mydata[$i][0]['host_id']==0?'All':$mydata[$i][0]['host_id']; ?>  </td>
                <?php
                if($mydata[$i][0]['monitor_type'] == 0):
                ?>
                <td> 
                    <?php 
                    if ($mydata[$i][0]['apply_type'] == 0)
                    {
                        echo 'Apply To All';
                    } 
                    else if($mydata[$i][0]['apply_type'] == 1)
                    {
                        echo $mydata[$i][0]['ani']; 
                    }
                    else 
                    {
                    echo '<s>' . $mydata[$i][0]['ani'] . '</s>'; 
                    }
                    ?>  
                </td>
                <td> <?php echo $mydata[$i][0]['dnis']; ?>  </td>
                <?php
                else:
                ?>
                <td> <?php echo $mydata[$i][0]['source_code_name']; ?> </td>
                <td> 
                    <a href="###" detail="<?php echo $mydata[$i][0]['destination_code_name']; ?>">
                        <?php echo substr($mydata[$i][0]['destination_code_name'], 0, 10); ?>
                    </a>
                </td>
                <?php
                endif;
                ?>
                <td><?php echo $mydata[$i][0]['sample_size']; ?>  </td>
                <td> 
                    <?php 
                    if ($mydata[$i][0]['freq_type'] == 1)
                    {
                    echo 'every ' . $mydata[$i][0]['freq_value'] . ' minute(s)'; 
                    }
                    elseif ($mydata[$i][0]['freq_type'] == 2)
                    {
                    $week_time_arr = explode('!', $mydata[$i][0]['weekday_time']);
                    echo 'every '; 
                    $arr_week = explode(',', $week_time_arr[0]);
                    $arr_time = explode(',', $week_time_arr[1]);
                    foreach($arr_week as $key => $item) {
                    echo $week_arr[$item] . '(' . $arr_time[$key] . ')&nbsp;';
                    }
                    }
                    else
                    {
                    echo "Never";
                    }
                    ?>  
                </td>
                <td data-hide="phone,tablet"  style="display: table-cell;">
                    <?php
                    if($mydata[$i][0]['is_origin']) {
                    echo 'Orig';
                    } else {
                    echo 'Term';
                    }/* elseif ($mydata[$i][0]['is_origin'] == 2) {
                    echo 'Both';
                    }*/
                    ?>
                </td>

                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['last_runtime']; ?>  </td>
                <td data-hide="phone,tablet"  style="display: table-cell;"> <?php echo $mydata[$i][0]['next_runtime']; ?>  </td>
                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_by']; ?> </td>
                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_at']; ?> </td>
                <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
                <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                    <?php 
                    if (Configure::read('system.type') == 2):
                    ?>
                    <a title="Trigger" href="<?php echo $this->webroot?>alerts/rule_trigger/<?php echo  $mydata[$i][0]['id']?>" >
                        <i class="icon-fighter-jet"></i>
                    </a>
                    <?php endif; ?>
                    <a title="Edit" href="<?php echo $this->webroot?>alerts/add_rule/<?php echo  $mydata[$i][0]['id']?>" >
                        <i class="icon-edit"></i>
                    </a> 
                    <?php if($mydata[$i][0]['status'] == 1): ?>
                    <a title="Stop" href="<?php echo $this->webroot?>alerts/rule_status/<?php echo  $mydata[$i][0]['id']?>/0" >
                        <i class="icon-stop"></i>
                    </a> 
                    <?php elseif($mydata[$i][0]['status'] == 0): ?>
                    <a title="Resume" href="<?php echo $this->webroot?>alerts/rule_status/<?php echo  $mydata[$i][0]['id']?>/1" >
                        <i class="icon-play-circle"></i>
                    </a> 
                    <?php endif; ?>
                    <a href='###' url="<?php echo $this->webroot?>alerts/delete_alert_rule/<?php echo $mydata[$i][0]['id'] ?>" hit='<?php echo $mydata[$i][0]['name']?>' title="Delete" class="delete_rule">
                        <i class="icon-remove"></i>

                    </a> 
                </td>
                <?php }?>
            </tr>
            <?php }?>
        <span style="display: none;"><a  href="" title="Delete" id="delete_rule"></span>
        </tbody>
    </table>
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
</div>
<div>
   

    <?php }?>
</div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        
        $(".delete_rule").click(function(){
            var name = $(this).attr('hit');
            var obj = $("#delete_rule");
            obj.attr('href',$(this).attr('url'));
            myconfirm('Delete rule'+ name +'?',obj);
        });
        
        $('.condition').hover(function(e){
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>alerts/get_condition/' + condition_id,
                'type' : 'GET',
                'dataType' : 'json',
                'success' : function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left' : xx,
                        'top' : yy+200,
                        'opacity' : 1
                    });
                    $ul.addClass('tooltips');
                    $ul.append('<li>ACD:' + data[0][0]['acd'] + '</li>');
                    $ul.append('<li>ASR:' + data[0][0]['asr'] + '</li>');
                    $ul.append('<li>Margin:' + data[0][0]['margin'] + '</li>');
                    $('body').append($ul);
              
                }
            });
        }, function(e){
            $('.tooltips').remove();
        });
    
    
        $('.action').hover(function(e){
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>alerts/get_action/' + condition_id,
                'type' : 'GET',
                'dataType' : 'json',
                'success' : function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left' : xx,
                        'top' : yy+200,
                        'opacity' : 1
                    });
                    $ul.addClass('tooltips');
                    var arr = data[0][0]['content'].split(',');
                    for(var v in arr) {
                        $ul.append('<li>' + arr[v]+ '</li>');
                    }
                    //$ul = "<div class='tooltips'>"+$ul+"</div>";
                    $('body').append($ul);
            
                }
            });
        }, function(e){
            $('.tooltips').remove();
        });
    
    });
</script>