<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/past_due_log">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>finances/past_due_log">
        <?php echo __('Past Due Notification Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Past Due Notification Log') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">

        <a href="javascript:history.go(-1)" class="btn btn-icon glyphicons btn-inverse circle_arrow_left">
            <i></i>&nbsp;<?php __('Back')?>         
        </a>

    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">



            <div id="container">
                <?php
                $data = $p->getDataArray();
                ?>
                <?php if (empty($data)):  ?>
                    <br style="clear:both" />
                    <h2 class="msg center"><?php echo __('There is no past due invoices.',true);?></h2>
                <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('send_time', __('Invoiced On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('carrier_name', __('Carrier', true)) ?></th>
                            <th><?php echo $appCommon->show_order('total_amount', __('Amount', true)) ?></th>
                            <th><?php echo $appCommon->show_order('due_date', __('Due Date', true)) ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item[0]['send_time']; ?></td>
                                <td><?php echo $item[0]['carrier_name']; ?></td>
                                <td><?php echo $item[0]['total_amount']; ?></td>
                                <td><?php echo $item[0]['due_date']; ?></td>
                                <td>
                                    <a href="<?php echo $this->webroot ?>finances/view_past_due_log/<?php echo $item[0]['id']; ?>" title="<?php __('View Email'); ?>">

                                        <i class="icon-list-alt"></i>
                                    </a>
                                    <a href="<?php echo $this->webroot ?>finances/resend_past_due/<?php echo $item[0]['id']; ?>" title="<?php __('Resend') ?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>