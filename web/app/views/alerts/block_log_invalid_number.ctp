<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Invalid Number Detection', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Block', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Block Log', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<?php
$mydata = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <ul class="tabs">
                <li>
                    <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/invalid_number">
                        <i></i><?php __('Invalid Number Detection'); ?>			
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_log_invalid_number">
                        <i></i><?php __('Block'); ?>			
                    </a>
                </li>
                
            </ul>    
        </div>
        <div class="widget-body">

            <?php   
            if (!count($mydata))
            {
                ?>
                <div class="msg center">
                    <br />
                    <h3><?php echo __('no_data_found') ?></h3>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('block_on', __('Blocked On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('block_by', __('Rule Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('code_detail', __('Ingress Counts', true)) ?></th>
                            <th><?php echo $appCommon->show_order('re_enable_time', __('action', true)) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mydata as $data_item)
                        {
                            ?>
                            <tr>
                                <td><?php echo $data_item[0]['block_on']; ?></td>
                                <td><?php echo $data_item[0]['block_by']; ?></td>
                                <td><a href="javascript:void(0);" onclick="get_all(<?=$data_item[0]['log_id']?>,this)" ><?php echo $data_item[0]['ingress_count']; ?></a></td>
                                <td>
                                    <a class="delete_client"   href="<?php echo $this->webroot.'alerts/unblock/'.$data_item[0]['log_id']?>"  title="<?php __('Unblock')?>">
                                            <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
<?php } ?>
        </div>
    </div>
    
    <script>
    
     function get_all(log_id,obj){
	if($(obj).parents('tr').eq(0).next().find('table').length==0){
                        $.ajax({
                            'url':"<?php echo $this->webroot;?>alerts/get_result",
                            'type':'post',
                            'dataType':'html',
                            'data':{'log_id':log_id},
                            'beforeSend':function(){
                                
                            },
                            'success':function(data){
                                $(obj).parents('tr').eq(0).after("<tr><td colspan='4'>"+data+"</td></tr>");
                            }
                        });
	}else{
                        $(obj).parents('tr').eq(0).next().remove();
	}
            }
    
    
    </script>