<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier Invoice History', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Carrier Invoice History', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $back_url; ?>">
                <i></i>
            <?php echo __('goback')?>
            </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Invoice Date')?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['Invoice']['invoice_time']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>

    </div>
</div>