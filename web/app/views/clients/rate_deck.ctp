<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk') ?>[<?php echo $prefix_data['Resource']['alias']; ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rate Deck') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Deck') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>clients/product_list/<?php echo $this->params['pass'][1] ?>"><i></i>
        <?php echo __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div id="container">
                <?php if (count($this->data) == 0) :?>
                    <div  class="msg center"><h2><br /><?php echo __('no_data_found') ?></h2></div>
                <?php else : ?>
                    <div class="clearfix"></div>
                    <div class="widget-head">
                        <div class="row-fluid">
                            <?php if($prefix_data['Product']['product_name']): ?>
                                <div class="span3">
                                    <h4 class="heading">
                                        <?php __('Product Name'); ?>:
                                    </h4>
                                    <?php echo $prefix_data['Product']['product_name']; ?>
                                </div>
                                <div class="span3">
                                    <h4 class="heading">
                                        <?php __('Prefix'); ?>:
                                    </h4>
                                    <?php echo $prefix_data['ResourcePrefix']['tech_prefix']; ?>
                                </div>
                            <?php else: ?>
                                <div class="span4">
                                    <h4 class="heading">
                                        <?php __('Rate Table Name'); ?>:
                                    </h4>
                                    <?php echo $prefix_data['RateTable']['name']; ?>
                                </div>
                                <div class="span4">
                                    <h4 class="heading">
                                        <?php __('Prefix'); ?>:
                                    </h4>
                                    <?php echo $prefix_data['ResourcePrefix']['tech_prefix']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                        <tr>
<!--                            <th>--><?php //echo __('Update On'); ?><!--</th>-->
                            <th><?php echo __('Effective Date'); ?></th>
                            <th><?php echo __('Sent On'); ?></th>
                            <th><?php echo __('Sent To'); ?></th>
                            <th><?php echo __('Sent Status'); ?></th>
                            <th class="last"><?php __('Download')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
<!--                                <td></td>-->
                                <td><?php echo $item['RateSendLog']['effective_date'] ?></td>
                                <td><?php echo $item['RateSendLog']['create_time'] ?></td>
                                <td><?php echo $item['RateSendLogDetail']['send_to'] ?></td>
                                <td><?php echo $item['RateSendLogDetail']['status'] ? __('Success') : __('Failed'); ?></td>
                                <td>
                                    <?php if($item['RateSendLog']['file']): ?>
                                        <a target="_blank" href="<?php echo $this->webroot; ?>rates/download_send_rate_file?flg=<?php echo base64_encode($item['RateSendLog']['id']) ?>"><i class="icon-file-text"></i></a>
                                    <?php else: ?>
                                        --
                                    <?php  endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row-fluid separator">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {

    });
</script>
