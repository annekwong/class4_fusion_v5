<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Registration Log') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Registration Log') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
       
         <div class="filter-bar">

            <form method="get">
                <!-- Filter -->
                <div>
                    <label>:</label>
                    <input type="text" id="search-_q" class="in-search input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                </div>
                <!-- // Filter END -->
                

                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->

                
            </form>
        </div>
        <div class="widget-body">
            <?php
            $mydata = $p->getDataArray();
            if (!count($mydata))
            {
                ?>
                <div class="center msg">
                    <h3><?php __('data not found') ?></h3>
                </div>
                <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('username', __('Sip Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('time', __('Date', true)) ?></th>
                            <th><?php echo $appCommon->show_order('from_ip', __('From IP', true)) ?></th>
                            <th><?php echo $appCommon->show_order('to_ip', __('To IP', true)) ?></th>
                            <th><?php echo $appCommon->show_order('state', __('Status', true)) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($mydata as $item)
                        {
                            ?>
                            <tr>
                                <td><?php echo $item[0]['username']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$item[0]['time']); ?></td>
                                <td><?php echo $item[0]['from_ip']; ?></td>
                                <td><?php echo $item[0]['to_ip']; ?></td>
                                <td><?php echo $item[0]['state'] ? "registered":"unregistered"; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
</script>