<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Check Route') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php __('Client List') ?></h1>
    
</div>
<div class="separator bottom"></div>
    <div class="clearfix"></div>
<?php
$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
$data = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                    <label><?php __('Create Time') ?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php
                           if (isset($get_data['time']))
                           {
                               echo $get_data['time'];
                           }
                           ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                    ~
                    <input id="end_date" class="input in-text wdate " value="<?php
                           if (isset($get_data['end_time']))
                           {
                               echo $get_data['end_time'];
                           }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">

                </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <!-- Table -->
            <table id="list" class=" footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <!-- Table heading -->
                <thead>
                    <tr>
                        <th class="center"><?php echo $appCommon->show_order('start_time', __('Create On', true)) ?></th>
                        <th class="center"><?php __('Completed Time')?></th>
                        <th class="center"><?php __('ani') ?></th>
                        <th class="center"><?php __('dnis') ?></th>
                        <th class="center"><?php __('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?=str_ireplace(" ", '<br/>', $item[0]['start_time']);?></td>
                            <td><?=str_ireplace(" ", '<br/>', $item[0]['end_time']);?></td>
                            <td>
                               <?=str_ireplace(" ", '<br/>', $item[0]['ani']);?>
                            </td>
                            <td >
                               <?=str_ireplace(" ", '<br/>', $item[0]['dnis']);?>
                            </td>
                            <td>
                                <img onclick="getResultAdmin('<?=$item[0]['call_id']?>','<?=$item[0]['end_time']?>',this)"    src="<?php echo $this->webroot.'images/+.gif'; ?>" title="View Cdr"/>
                                <a target="_bank" href="<?php echo $this->webroot.'check_route/get_sip/'.base64_encode($item[0]['id'])?>"><img src="<?php echo $this->webroot ?>images/html.png" /></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
                <!-- // Table body END -->

            </table>
            <!-- // Table END -->
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<script type="text/javascript">
    
    $(function(){
        
        if($("#list").find('img[name=noresult]').size() != 0){
            //getdata();
            var interval_time = 3000;
            window.setInterval("getdata()", interval_time);
        }
    });
    
    
    function getdata(){
        var load = $("#list").find('img[name=noresult]');
            $.each(load, function (index,content){
                var id = $(content).attr('image_id');
                $.post("<?php echo $this->webroot ?>check_route/get_result", {cdr_id:id},
                    function (data){
                        if(data == 0){
                            
                        }else if(data == 1){
                            
                        }else if(data == 'noResult'){
                            
                        }else{
                            $.each(eval(data), function(index1,content1){
                                tr = $(content).parents('tr').eq(0);
                                tr.find('td').eq(2).html(content1['end_time']);
                                tr.find('td').eq(0).html("<a href=\"<?php echo  $this->webroot."check_route/showalldetail";?>/"+id+"\"><img    src=\"<?php echo $this->webroot.'images/check_ok.png'; ?>\" title=\"View Result\"/></a>");
                            });
                        }
                    }
                );
            });
    }
    
  function delRate(id,obj){
           if(confirm("Are you sure to delete!")){
               
                var url="<?php echo $this->webroot?>check_route/del_cdr/"+id;
                var data=jQuery.ajaxData(url);
                if(data.indexOf('true')==-1){
                        jQuery.jGrowlError('delete fail!');
                }else{
                       // $waiting.remove();
                        jQuery.jGrowlSuccess('The Record is removed successfully.');
                        $(obj).parents('tr').remove();
                }
               
               //location = "<?php echo $this->webroot ?>cdr_reconciliation/del_cdr/"+id;
           }
        }
        
        
         function getResultAdmin(id,time,obj){
         
	if($(obj).parents('tr').eq(0).next().find('table').length==0){
            
                        
            
                        $.ajax({
                            'url':"<?php echo $this->webroot;?>check_route/get_result",
                            'type':'post',
                            'dataType':'html',
                            'data':{'call_id':id,'time':time},
                            'beforeSend':function(){
                                
                            },
                            'success':function(data){
                                
                                $(obj).parents('tr').eq(0).after("<tr><td colspan='6'>"+data+"</td></tr>");
                                obj.src = "<?php echo $this->webroot ?>images/-.gif";
                            }
                        });
	}else{
                        $(obj).parents('tr').eq(0).next().remove();
                        obj.src = "<?php echo $this->webroot ?>images/+.gif";
	}
        
         
        }
    
</script>
