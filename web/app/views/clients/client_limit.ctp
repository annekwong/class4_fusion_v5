<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>clients/index"><?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Carrier') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Client Limit') ?></h4>
    <div class="clearfix"></div>
</div>

<div class="separator bottom"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a class="glyphicons list"  href="<?php echo $this->webroot; ?>clients/index">
                        <i></i>
                        <?php __('Client List') ?>
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons notes_2"  href="<?php echo $this->webroot; ?>clients/client_limit">
                        <i></i>
                        <?php __('Client Limit') ?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons compass"  href="<?php echo $this->webroot; ?>clients/ingress_host">
                        <i></i>
                        <?php __('Ingress Host')?>
                    </a>
                </li>
            </ul>
        </div>
        <?php $data = $p->getDataArray(); ?>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Type') ?>:</label>
                        <select  name="filter_client_type">
                            <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>><?php __('All') ?></option>
                            <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>><?php __('All Active Clients') ?></option>
                            <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>>All Inactive Clients</option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Name') ?>:</label>
                        <input type="text" name="search" id="search-_q" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <?php
            if (!count($data))
            {
                ?>
                <div>
                    <br /><h3 class="msg center"><?php echo __('no data found') ?></h3>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
            <form action="" method="post">
                <table class=" footable table table-striped table_page_num tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('View Trunk'); ?></th>
                            <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('allowed_credit', __('Credit Limit', true)) ?></th>
                            <th><?php echo $appCommon->show_order('call_limit', __('Port Limit', true)) ?></th>
                            <th><?php echo $appCommon->show_order('cps_limit', __('CPS Limit', true)) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $key => $data_item)
                        {
                            ?>
                            <tr>
                                <td><img   id="image<?php echo $key; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $key; ?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('View Trunk') ?>"/></td >
                                <td><?php echo $data_item[0]['name']; ?></td>
                                <td>
                                    <input type="hidden" name="data[<?php echo $key ?>][client_id]" value="<?php echo $data_item[0]['client_id']; ?>" />
                                    <input type="text" class="validate[custom[positiveNumber]]" name="data[<?php echo $key ?>][allowed_credit]" value="<?php echo intval(abs($data_item[0]['allowed_credit'])); ?>" />
                                </td>
                                <td>
                                    <input type="text" class="validate[custom[onlyNumberSp]]" name="data[<?php echo $key ?>][call_limit]" value="<?php echo $data_item[0]['call_limit']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="validate[custom[onlyNumberSp]]" name="data[<?php echo $key ?>][cps_limit]" value="<?php echo $data_item[0]['cps_limit']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div id="ipInfo<?php echo $key ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display:none;"> 
                                        <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                                            <tr>
                                                <th><?php echo __('Trunk Type', true); ?></th>
                                                <th><?php echo __('Trunk Name', true); ?></th>
                                                <th><?php echo __('Port Limit', true); ?></th>
                                                <th><?php echo __('CPS Limit', true); ?></th>
                                            </tr>
                                            <?php foreach ($data_item['resource'] as $resource_items): ?>
                                                <tr>
                                                    <td><?php if($resource_items['Gatewaygroup']['ingress']){echo "Ingress";}else{ echo "Egress";} ?></td>
                                                    <td><?php echo $resource_items['Gatewaygroup']['alias'] ?></td>
                                                    <td><?php echo $resource_items['Gatewaygroup']['capacity'] ?></td>
                                                    <td><?php echo $resource_items['Gatewaygroup']['cps_limit'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
        </div>
        <div class="clearfix"></div>
        <div class="center margin-bottom10">
            <input type="submit" value="Submit" class="input in-submit btn btn-primary">
            <input type="reset" value="Revert" class="btn btn-inverse">
        </div>
        </form>
        <div class="clearfix"></div>
    </div>
</div>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<script>
    $(document).on('DOMNodeInserted', function(){
        $('thead a[title="sort"]').attr('title', 'Sort');
    });
</script>