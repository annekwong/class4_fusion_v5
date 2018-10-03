<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Setting') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Item Log') ?>[<?php echo ( $lrn_item['LrnItem']['ip'] .':' .$lrn_item['LrnItem']['port']) ?>]</li>
</ul>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_btn btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>lrn_settings/items/<?php echo (base64_encode($lrn_item['LrnItem']['group_id'])) ?>" ><i></i>
        <?php __('Back')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div id="container">
                <?php
                $data = $p->getDataArray();
                ?>
                <?php
                if (count($data) == 0) {
                    ?>
                <div class="msg center"><?php echo __('no_data_found',true);?></div>
                <table class="list" id="mylist" style="display:none;">

                    <thead>
                        <tr>
                            <td><?php __('Time')?></td>
                            <td><?php __('PDD')?></td>
                            <td><?php __('State')?></td>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                <?php }else{ ?>
            <div id="container">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="mylist">
                    <thead>
                        <tr>
                            <th><?php __('Time')?></th>
                            <th><?php __('PDD')?></th>
                            <th><?php __('State')?></th>
                        </tr>
                    </thead>
                        <tbody>
                        <?php
                            foreach ($data as $item): 
                        ?>
                        <tr>
                            <td><?php echo $item[0]['time']; ?></td>
                            <td><?php echo $item[0]['pdd']; ?></td>
                            <td>
                                <?php 
                                    if ($item[0]['state'] == 1) :

                                ?>
                                    <i class="icon-check" style="color:red"></i>
                                <?php else: ?>
                                    <i class="icon-unchecked" style="color:red"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
                <div id="container">
                        <div class="bottom row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                            </div> 
                        </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
    