<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdr_reconciliation">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdr_reconciliation">
        <?php echo __('CDR Reconciliation') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Client List') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <!--<a class="btn btn-primary btn-icon glyphicons file_import" href="<?php echo $this->webroot ?>uploads/carrier"><i></i>Import</a>-->
        <?php
            if($_SESSION['role_menu']['Tools']['cdr_reconciliation']['model_w']){
        ?>
            <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>cdr_reconciliation/add"><i></i> <?php __('Create New') ?></a>
        <?php 
            }
        ?>
    </div>
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
                        <th><input type="checkbox" class="checkAll"></th>
                        <th class="center"><?php echo $appCommon->show_order('create_time', __('Create On', true)) ?></th>
                        <th class="center"><?php echo $appCommon->show_order('finish_time', __('Completed Time', true)) ?></th>
                        <th class="center"><?php __('Compare Type') ?></th>
                        <th class="center"><?php echo $appCommon->show_order('source_filename', __('Source File', true)) ?></th>
                        <th class="center"><?php echo $appCommon->show_order('diff_filename', __('Diff File', true)) ?></th>
                        <th class="center"><?php __('Result') ?></th>
                        <th class="center"><?php __('File of Result') ?></th>
                        <th class="center"><?php __('Action') ?></th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr >
                            <td>
                                <input type="checkbox" name="cdr_id" id="<?=$item[0]['id']?>">
                            </td>
                            <td><?=str_ireplace(" ", '<br/>', $item[0]['create_time']);?></td>
                        <td><?=str_ireplace(" ", '<br/>', $item[0]['finish_time']);?></td>
                        <td>
                            <?php
                                if($item[0]['compare_type'] == 0){
                                    echo 'US LRN Non-Jurisdiction';
                                }else if($item[0]['compare_type'] == 1){
                                    echo 'US LRN Jurisdiction';
                                }else if($item[0]['compare_type'] == 2){
                                    echo 'US DNIS';
                                }else{
                                    echo 'International A-Z';
                                }
                            ?>
                        </td>
                        <td >
                            <?php
                                $files = explode("/",$item[0]['source_filename']);
                            ?>
                            <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/1">
                            <?php echo substr($files[count($files) - 1], 0,10);?>
                            </a>
                        </td>
                        <td >
                            <?php
                                $files = explode("/",$item[0]['diff_filename']);
                            ?>
                            <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/2">
                            <?php echo substr($files[count($files) - 1], 0,10);?>
                            </a>
                        </td>
                      
                        <td>
                            <img   id="image_<?=$item[0]['id']?>"  onclick="getResultAdmin(<?=$item[0]['id']?>,this)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="View Result"/>
                        </td>
                        
                        
                        <td>
                            <?php
                                //var_dump($item[0]['status']);
                                if($item[0]['status'] == 2){
                            ?>
                                
                             <?php
                                    $files = explode("/",$item[0]['match_cdr_file']);
                                ?>
                                        <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/3">
                                <?php
                                    echo "Download Matched CDRs";
                                ?>
                                </a>
                                <br/>
                                <!--Mismatched Cdr File:-->
                                <?php
                                        $files = explode("/",$item[0]['mismatch_cdr_file']);
                                ?>
                                        <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/4">
                                <?php       
                                        echo 'Download Unmatched CDRs'; 
                                ?>
                                </a>
                                <br/>
                                <!--Left Right Cdr File:-->
                                <?php

                                        $files = explode("/",$item[0]['left_right_cdr_file']);
                                ?>
                                        <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/5">
                                <?php
                                        //echo $files[count($files) - 1];
                                        echo "Download Line-By-Line Comparison";
                                ?>
                                </a>
                                <br/>
                                <!--Aggregated Analysis File:-->
                                <?php
                                        $files = explode("/",$item[0]['aggregated_analysis_file']);
                                ?>
                                    <a title='<?=$files[count($files) - 1]?>' href="<?php echo $this->webroot ?>cdr_reconciliation/download/<?=$item[0]['id']?>/6">
                                <?php
                                        echo "Download Comparison Summary";
                                ?>
                                </a>
                            
                            
                            <?php        
                                }else if($item[0]['status'] == -1){
                            ?><lable style='font-weight:800;color:rgb(255, 109, 6)'>failed open file!</lable><?php        
                                }else if($item[0]['status'] == -2){
                            ?><lable style='font-weight:800;color:rgb(255, 109, 6)'>Invalid File Format !</lable><?php        
                                }else if($item[0]['status'] == -3){
                            ?><lable style='font-weight:800;color:rgb(255, 109, 6)'>unkown error</lable><?php
                                }else{
                            ?>
                                <img src="<?php echo $this->webroot ?>images/loading.gif" name="noresult" id="<?=$item[0]['id']?>" />
                            <?php        
                                }
                            ?>
                        </td>
                        
                        <td>
                                <a href="javascript:void(0);" onclick="delRate(<?=$item[0]['id']?>,this)" title="<?php __('Delete')?>">
                                    <i class="icon-remove"></i>
                                </a>
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

        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });
    
    
    function getdata(){
        var load = $("#list").find('img[name=noresult]');
            $.each(load, function (index,content){
                $.post("<?php echo $this->webroot ?>cdr_reconciliation/get_result", {cdr_id:content.id},
                    function (data){
                        if(data == 0){
                            
                        }else if(data == 1){
                            
                        }else if(data == -1){
                            td = $(content).parent();
                            td.html("<lable style='font-weight:800;color:rgb(255, 109, 6)'>failed open file!</lable>");
                        }else if(data == -2){
                            td = $(content).parent();
                            td.html("<lable style='font-weight:800;color:rgb(255, 109, 6)'>Invalid File Format !</lable>");
                        }else if(data == -3){
                            td = $(content).parent();
                            td.html("<lable style='font-weight:800;color:rgb(255, 109, 6)'>unkown error</lable>");
                        }else if(data == 'noResult'){
                            
                        }else{
                            $.each(eval(data), function(index1,content1){
                                td = $(content).parent();
                                complete_time =  td.prev().prev().prev().prev().prev().html(content1['finish_time']);
                                td.html('');
                                //alert(content1['match_cdr_file']);
                                td.append("<a href='<?php echo $this->webroot ?>cdr_reconciliation/download/"+content.id+"/3'>Download Matched CDRs</a><br/>");
                                td.append("<a href='<?php echo $this->webroot ?>cdr_reconciliation/download/"+content.id+"/4'>Download Unmatched CDRs</a><br/>");
                                td.append("<a href='<?php echo $this->webroot ?>cdr_reconciliation/download/"+content.id+"/5'>Download Line-By-Line Comparison</a><br/>");
                                td.append("<a href='<?php echo $this->webroot ?>cdr_reconciliation/download/"+content.id+"/6'>Download Comparison Summary</a><br/>");
                            });
                        }
                    }
                );
            });
    }
    
    function delRate(id,obj){
           if(confirm("Are you sure to delete!")){
               
                var url="<?php echo $this->webroot?>cdr_reconciliation/del_cdr/"+id;
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
    
         function getResultAdmin(id,obj){
         
            $.post("<?php echo $this->webroot ?>cdr_reconciliation/getResult", {cdr_id :id}, 
                function(data){
                    if(obj.src.indexOf('+.gif')>=0){
                        obj.src = "<?php echo $this->webroot ?>/images/-.gif";
                        $(obj).parent().parent().after("<tr><td class='footable-last-column footable-first-column' colspan='9'></td></tr>");
                        source_tr = $(obj).parents('tr').eq(0).next()[0];
                        $.each(data, function(index,content){
                            //alert(content['source_filename']);
                            $(source_tr).find('td').eq(0).append("<div class=' jsp_resourceNew_style_2' style='padding:5px;'> <table class='list table dynamicTable tableTools table-bordered  table-white'><tr><th></th><th>Source File</th><th>Diff File</th><th>Difference</th><th>%Diff</th></tr>\n\
<tr><td>CDR Counts</td><td>"+content['sf_count']+"</td><td>"+content['df_count']+"</td><td>"+(content['sf_count']-content['df_count'])+"</td><td>"+content['cdr_dif']+"</td></tr>\n\
<tr><td>Non-Zero CDR Counts</td><td>"+content['sf_nozero_count']+"</td><td>"+content['df_nozero_count']+"</td><td>"+(content['sf_nozero_count']-content['df_nozero_count'])+"</td><td>"+content['non_dif']+"</td></tr>\n\
<tr><td>Total Duration</td><td>"+content['sf_total_duration']+"</td><td>"+content['df_total_duration']+"</td><td>"+(content['sf_total_duration']-content['df_total_duration'])+"</td><td>"+content['total_dif']+"</td></tr>\n\
<tr><td>Cost</td><td>"+content['sf_total_cost']+"</td><td>"+content['df_total_cost']+"</td><td>"+(content['sf_total_cost_df_total_cost'])+"</td><td>"+content['cost_dif']+"</td></tr>\n\
<tr><td>Avg Rate</td><td>"+(content['avg_sorce'])+"</td><td>"+(content['avg_diff'])+"</td><td>"+content['avg_difff']+"</td><td>"+content['avg_dif']+"</td></tr>\n\
<tr><td>Mismatched Count</td><td>"+content['sf_mismatch_count']+"</td><td>"+content['df_mismatch_count']+"</td><td>"+(content['sf_mismatch_count']-content['df_mismatch_count'])+"</td><td>"+content['mis_dif']+"</td></tr>                    \n\
<tr><td>Matched CDR Count</td><td>"+content['sf_match_count']+"</td><td>"+content['df_match_count']+"</td><td>"+(content['sf_match_count']-content['df_match_count'])+"</td><td>"+content['mat_dif']+"</td></tr>\n\
</table>");
                        });
                        
                    }else{
                        obj.src = "<?php echo $this->webroot ?>/images/+.gif";
                        $(obj).parent().parent().next().remove();
                    }
                },'json'
            );
        }
    
</script>
