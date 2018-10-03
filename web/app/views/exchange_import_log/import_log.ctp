<style type="text/css">
            .list thead{
                background:url("") repeat scroll 0 0 transparent;
            }
            .list thead td:hover{
                background-image:url("");
            }
        </style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Exchange Import Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Exchange Import Log</h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">
 <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" >
                <thead>
                    <tr>
                       <th rowspan="2"><?php echo $appCommon->show_order('upload_file_name', __('File Name', true)) ?></th>
                       <th rowspan="2"><?php echo $appCommon->show_order('status', __('Status', true)) ?></th>
                       <th colspan="5">Records</th>
                       <th rowspan="2">Method</th>
                       <th rowspan="2"><?php echo $appCommon->show_order('time', __('Upload Time', true)) ?></th>
                       <!--<th rowspan="2">Finished Time</th>-->
                       <th rowspan="2">Upload File</th>
                       <th rowspan="2">Error File</th>
                    </tr>
                    
                    <tr>
                       <th>Delete</th>
                       <th>Update</th>
                       <th>Insert</th>
                       <th>Error</th>
                       <th>Reimport</th>
                    </tr>
                </thead>
        <tbody>
            <?php foreach($data as $item): ?>
                    <tr>
                        <td><?=$item[0]['upload_file_name']?></td>
                        <td><?=$item[0]['status']?></td>
                        <td><?=$item[0]['delete_queue']?></td>
                        <td><?=$item[0]['update_queue']?></td>
                        <td><?=$item[0]['insert_queue']?></td>
                        
                        <td><?=$item[0]['error_counter']?></td>
                        <td><?=$item[0]['reimport_counter']?></td>
                        <td>
                            <?php
                                if($item[0]['method'] == 0){
                                    echo "Ignore";
                                }else if($item[0]['method'] == 1){
                                    echo "Delete Existing Records";
                                }else if($item[0]['method'] == 2){
                                    echo "Update Existing Records";
                                }
                            ?>
                        </td>
                        <td><?=$item[0]['time']?></td>
                        <!--<td><?=$log['time']?></td>-->
                        <td>
                            <a href="<?php  echo $this->webroot.'exchange_import_log/download?file='. urlencode($item[0]['local_file'])?>">Export</a>
                        </td>
                        <td>
                            <a href="<?php  echo $this->webroot.'exchange_import_log/download?file='. urlencode($item[0]['error_log_file'])?>">Export</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
    <?php }?>
</div>
</div>
</div>
</div>




