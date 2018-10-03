<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Table') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li><a href="<?php echo $this->webroot ?>clientrates/view_rate_egress" class="glyphicons right_arrow">
                        <?php __('Egress Rate') ?>
                        <i></i>
                    </a>
                </li>
                <li class="active"><a href="<?php echo $this->webroot ?>clientrates/view_rate_ingress" class="glyphicons left_arrow">
                        <?php __('Ingress Rate') ?>
                        <i></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">

            <?php
            $data = $p->getDataArray();
            if (!empty($data)):
                ?>
                <table id="mytable" class="list footable table table-striped table_page_num tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo __('Tech Prefix', true); ?></th>
                    <tr>
                    </thead>

                    <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><a href="<?php echo $this->webroot; ?>clientrates/view_rate_detail/<?php echo base64_encode($item[0]['rate_table_id']) ?>"><?php echo $item[0]['tech_prefix'] == '' ? 'None' : $item[0]['tech_prefix'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php else: ?>
                <br />
                <h2 style="text-align:center"><?php echo __('no_data_found', true); ?></h2>
            <?php endif; ?>
        </div>

