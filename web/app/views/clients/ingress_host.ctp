<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>clients/index">
        <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Ingress Host') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Ingress Host') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a class="glyphicons list"  href="<?php echo $this->webroot; ?>clients/index">
                        <i></i>
                        <?php __('Client List')?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons notes_2"  href="<?php echo $this->webroot; ?>clients/client_limit">
                        <i></i>
                        <?php __('Client Limit')?>
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons compass"  href="<?php echo $this->webroot; ?>clients/ingress_host">
                        <i></i>
                        <?php __('Ingress Host')?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Status')?>:</label>
                        <select  name="filter_status">
                            <option value="0" <?php echo $common->set_get_select('filter_status', 0) ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('filter_status', 1, TRUE) ?>><?php __('Active')?></option>
                            <option value="2" <?php echo $common->set_get_select('filter_status', 2) ?>>Inactive</option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier Name')?>:</label>
                        <input type="text" name="client_name" value="<?php echo isset($_GET['client_name']) ? $_GET['client_name'] : '' ?>" />
                    </div>
                    <div>
                        <label><?php __('Resource Name')?>:</label>
                        <input type="text" name="resource_name" value="<?php echo isset($_GET['resource_name']) ? $_GET['resource_name'] : '' ?>" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <!-- Table -->
            <div class="overflow_x">
                <table class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <!-- Table heading -->
                    <thead>
                    <tr>
                        <th rowspan="2"><?php echo $appCommon->show_order('ResourceIp.ip', __('Resource IP', true)) ?></th>
                        <th rowspan="2"><?php __('Carrier Name')?></th>
                        <th rowspan="2"><?php __('Resource Name')?></th>
                        <th rowspan="2"><?php __('Status')?></th>
                        <th colspan="2"><?php __('Carrier Limit')?></th>
                        <th colspan="2"><?php __('Resource Limit')?></th>
                        <th colspan="2"><?php __('Host Limit')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Call Limit')?></th>
                        <th><?php __('CPS Limit')?></th>

                        `               <th><?php __('Call Limit')?></th>
                        <th><?php __('CPS Limit')?></th>

                        <th><?php __('Call Limit')?></th>
                        <th><?php __('CPS Limit')?></th>


                    </tr>
                    </thead>
                    <!-- // Table heading END -->

                    <!-- Table body -->
                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td>
                                <?php echo $item['ResourceIp']['ip'] . ':' . $item['ResourceIp']['port']?>
                            </td>
                            <td>
                                <?php echo $item['client']['name']?>
                            </td>
                            <td>
                                <?php echo $item['resource']['alias']?>
                            </td>
                            <td>
                                <?php echo $item['resource']['active'] ? 'Active' : 'Inactive'?>
                            </td>


                            <td>
                                <?php echo $item['client']['call_limit'] ? $item['client']['call_limit'] : 'NO LIMITED' ?>
                            </td>
                            <td>
                                <?php echo $item['client']['cps_limit'] ? $item['client']['cps_limit'] : 'NO LIMITED'?>
                            </td>
                            <td>
                                <?php echo $item['resource_ip_limit']['capacity'] ? $item['resource_ip_limit']['capacity'] : 'NO LIMITED'?>
                            </td>
                            <td>
                                <?php echo $item['resource_ip_limit']['cps'] ? $item['resource_ip_limit']['cps'] : 'NO LIMITED'?>
                            </td>
                            <td>
                                <?php echo $item['resource']['capacity'] ? $item['resource']['capacity'] : 'NO LIMITED'?>
                            </td>
                            <td>
                                <?php echo $item['resource']['cps_limit'] ? $item['resource']['cps_limit'] : 'NO LIMITED'?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                    <!-- // Table body END -->

                </table>
                <!-- // Table END -->
            </div>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>
<script>
    $(document).on('DOMNodeInserted', function(){
        $('thead a[title="sort"]').attr('title', 'Sort');
    });
</script>
