<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clients/rate_summary">
        <?php echo __('Management', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clients/rate_summary">
        <?php __('Client Rate Summary'); ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Client Rate Summary') ?></h4>
</div>
<?php if(!empty($this->data)): ?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons file_export" target="_blank" href="<?php echo $this->webroot; ?>clients/export_rate_summary/<?php echo isset($this->params['url']['search_name']) ? base64_encode($this->params['url']['search_name']): '';?>">
        <i></i>
        <?php __('Export'); ?>
    </a>
</div>
<?php endif;?>
<div class="separator"></div>
<div class="innerLR">
    <div class="widget widget-heading-simple widget-body-white">
        <div class="filter-bar">
            <form action="" method="get">
                <div>
                    <label><?php __('Name') ?>:</label>
                    <input type="text" name="search_name" value="<?php echo $appCommon->_get('search_name');?>"
                           placeholder="<?php __('Carrier') ?>,<?php __('Ingress') ?> <?php __('or') ?> <?php __('Rate table') ?>"
                        />
                </div>
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
            </form>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if(empty($this->data)): ?>
                <h2 class="msg center"><br /><?php __('No data found'); ?></h2>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('Client.name', __('Carrier Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Resource.alias', __('Ingress Trunk Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('ResourcePrefix.tech_prefix', __('Prefix', true)) ?></th>
                        <th><?php echo $appCommon->show_order('RateTable.name', __('Rate Table Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Client.rate_email', __('Rate Email', true)) ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['Client']['name']; ?></td>
                            <td><?php echo $item['Resource']['alias']; ?></td>
                            <td><?php echo $item['ResourcePrefix']['tech_prefix']; ?></td>
                            <td>
                                <a href="<?php echo $this->webroot; ?>clientrates/view/<?php echo base64_encode($item['RateTable']['rate_table_id']) ?>" title="View Rates">
                                    <?php echo $item['RateTable']['name']; ?>
                                </a>
                            </td>
                            <td><?php echo $item['Client']['rate_email']; ?></td>
                            <td>
                                <?php if($item['Client']['rate_email']): ?>
                                    <a title="<?php __('Send Rate')?>" href="<?php echo $this->webroot; ?>rates/send_rate/<?php echo base64_encode($item['RateTable']['rate_table_id']) ?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if($item['RateTable']['rate_table_id']): ?>
                                   <a target="_blank" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($item['RateTable']['rate_table_id']);?>"
                                   title="<?php __('Download Rate'); ?>" >
                                    <i class="icon-file"></i></a>
                                <?php endif; ?>
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
                <div class="clearfix"></div>
            <?php endif;?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
    });
    $(document).on('DOMNodeInserted', function(){
        $('thead a[title="sort"]').attr('title', 'Sort');
    });
</script>