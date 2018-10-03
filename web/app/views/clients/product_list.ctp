<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk') ?>[<?php echo $ingress_name; ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Product') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Product') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if($_SESSION['login_type'] != 3):?>
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);""><i></i>
    <?php echo __('Create New') ?>
    </a>
    <?php endif;?>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>clients/view_ingress"><i></i>
        <?php echo __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div id="container">
                <?php if (count($data) == 0) :?>
                    <div  class="msg center"><h2><br /><?php echo __('no_data_found') ?></h2></div>
                <?php else : ?>
                    <div class="clearfix"></div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                        <tr>
                            <th><?php echo __('Product'); ?></th>
                            <th><?php echo __('Prefix'); ?></th>
                            <th class="last"><?php __('Action')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td>
                                    <a target="_blank" title="Download Rate" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($item['RateTable']['rate_table_id']);?>" class="link_width">
                                        <?php echo $item['ProductRouteRateTable']['product_name'] ?>
                                    </a>
                                </td>
                                <td><?php echo $item['ResourcePrefix']['tech_prefix'] ?></td>
                                <td class="last">
                                    <a title="Download Rate" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($item['RateTable']['rate_table_id']);?>">
                                        <i class="icon-file-text"></i>
                                    </a>
                                    <a title="Rate Log" href="<?php echo $this->webroot; ?>clients/rate_deck/<?php echo base64_encode($item['ResourcePrefix']['id']) ?>/<?php echo $this->params['pass'][0]; ?>">
                                        <i class="icon-list"></i>
                                    </a>
                                    <?php if($item['ProductRouteRateTable']['id']): ?>
                                        <a title="delete" href="<?php echo $this->webroot; ?>clients/delete_product_by_trunk/<?php echo base64_encode($item['ResourcePrefix']['id']); ?>/<?php echo base64_encode($resource_id); ?>" onclick="myconfirm('<?php __('sure to delete'); ?>',this);return false;"><i class='icon-remove'></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();
            $('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>clients/product_add_panel/<?php echo $resource_id; ?>",
                action: "<?php echo $this->webroot ?>clients/product_add_panel/<?php echo $resource_id; ?>",
                removeCallback: function() {
                    if ($('table.list tr').size() == 1) {
                        $('table.list').hide();
                        $('.msg').show();
                    }
                }
            });
            $(this).parent().parent().show();
        });
    });

    window.onbeforeunload = function(e) {
        jQuery(".fakeloader").remove();
    };

</script>
