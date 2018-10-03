<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('History Report Delivery') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('History Report Delivery') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>reports/report_delivery">
            <i></i>
            <?php __('Back') ?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $data = $p->getDataArray();
            ?>
            
            <?php
            if (count($data) == 0) {
                ?>
                <div class="msg"><?php echo __('no_data_found') ?></div>
                <?php
            } else {
                ?>

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <th>Time</th>
                    <th>Email to</th>
                    <th>Report</th>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value) { ?>
                            <tr>    
                                <td><?php echo $value[0]['sent_time'] ?></td>
                                <td><?php echo $value[0]['email_to'] ?></td>
                                <td><a href="<?php echo $this->webroot; ?>reports/download_report_delivery/<?php echo $value[0]['id']; ?>"><?php __('Report') ?></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
        </div>
    </div>
</div>







