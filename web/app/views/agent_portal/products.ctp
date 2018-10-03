<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Agent Portal') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Client') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Products') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Products') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $count = count($data);
            if (!$count):
                ?>
                <h2 class="msg center"><br /><?php __('No data found'); ?></h2>
            <?php else: ?>
                <table class="list footable table table-striped table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php __('Product Name'); ?></th>
                        <th><?php __('Scope'); ?></th>
                        <th><?php __('Type'); ?></th>
                        <th><?php __('Used By'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $items)
                    {
                        ?>
                        <tr>
                            <td><?php echo $items['ProductRoute']['product_name'] ?></td>
                            <td><?php echo $items['ProductRoute']['is_private'] ? 'Private' : 'Public' ?></td>
                            <td><?php echo $jur_types[$items['RateTable']['jur_type']] ?></td>
                            <td><?php echo $items['count'] ?></td>
                            <td>
                                 <a target="_blank" href="<?php echo $this->webroot?>agent_portal/download_rate/<?php echo base64_encode($items['RateTable']['rate_table_id']);?>" title="<?php __('Download Rate'); ?>">
                                     <i class="icon-download"></i>
                                 </a>
                                 <a href="<?php echo $this->webroot?>agent_portal/view_rate/<?php echo base64_encode($items['RateTable']['rate_table_id']);?>" title="View Rate">
                                     <i class="icon-list"></i>
                                 </a>
                                <a href="#myModalShareRate" class="share_rate" data-toggle="modal" data-id="<?php echo base64_encode($items['RateTable']['rate_table_id']); ?>" title="<?php __('Share Rate'); ?>">
                                    <i class="icon-share"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div id="myModalShareRate" class="modal hide" style="width:350px;margin-left:-175px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Share Rate'); ?></h3>
    </div>
    <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('URL')?>:</td>
                    <td>
                        <input style="margin-bottom:0;" class="width220 copy_text" data-value = "<?php echo $url;?>agent_portal/downloadSharedRate/" value="" type="text" readonly>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary copy" value="<?php __('Copy'); ?>">
            <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default close-btn"><?php __('Close'); ?></a>
        </div>
</div>

<script >
$(document).ready(function(){
   $(".copy").on('click', function(){
       $(".copy_text").select();
       document.execCommand('copy');
       jGrowl_to_notyfy('<?php __('Copied Successfully.'); ?>', {theme: 'jmsg-success'});
       $('#myModalShareRate .close-btn').click();
   });
   $(".share_rate").on('click', function(){
      let table_id = $(this).attr('data-id');
      $('#myModalShareRate').find('.copy_text').val($('#myModalShareRate').find('.copy_text').attr('data-value') + table_id);
   });
});

</script >