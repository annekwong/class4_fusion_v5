
<?php
$data = $p->getDataArray();
?>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Delivery History') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Delivery History') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_back btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="#"   onclick="history.go(-1);"><i></i><?php echo __('goback', true); ?>    			
        </a>

    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo __('Send Date', true);
                    echo $appCommon->show_order('send_date', __(' ', true)); ?></th>
                        <th><?php echo __('Send To', true); ?></th>
                        <th><?php echo __('action', true); ?></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($data as $item): ?>
                        <tr>
                            <td><?php echo $item['Ratemailhistory']['send_date'] ?></td>
                            <td><?php echo $item['Ratemailhistory']['send_to'] ?></td>
                            <td>
                                <a title="<?php __('View detail')?>" href="<?php echo $this->webroot; ?>ratemailhistorys/detail/<?php echo $item['Ratemailhistory']['id'] ?>">
                                    <i class="icon-book"></i>
                                </a>
                                <a title="<?php __('Del')?>" href="<?php echo $this->webroot; ?>ratemailhistorys/delete/<?php echo $item['Ratemailhistory']['id'] ?>">
                                    <i class='icon-remove'></i>
                                </a>
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
        </div>
    </div>
</div>

