<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Roles') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Roles</h4>
    <div class="buttons pull-right">

        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>exchangesysrolepris/add_sysrolepri/<?php echo $type ?>"><i></i> Create New</a>
        <a class="btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot ?>exchangesysrolepris/view_sysrolepri/<?php echo $type; ?>">

            &nbsp;<i></i><?php echo __('gobackall') ?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul>
                <li <?php if (!strcmp($type, 'agent')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangesysrolepris/view_sysrolepri/agent"><i></i>Agent Role</a></li>
                <li <?php if (!strcmp($type, 'partition')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangesysrolepris/view_sysrolepri/partition"><i></i>Partition Role</a></li>
                <li <?php if (!strcmp($type, 'exchange')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangesysrolepris/view_sysrolepri/exchange"><i></i>Exchange Role</a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">

                <form action="<?php echo $this->webroot ?>exchangesysrolepris/view_sysrolepri/<?php echo $type; ?>" method="get">
                    <!-- Filter -->
                    <div>
                        <label>Search:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->


                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn">Query</button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>


            <div id="container">




                <?php
                $mydata = $p->getDataArray();
                $loop = count($mydata);
                if (empty($mydata)) {
                    ?>
                    <div class="msg"><?php echo __('no_data_found', true); ?></div>
                <?php } else {
                    ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                        <thead>
                            <tr>
                                <th ><?php echo $appCommon->show_order('role_name', __('RolesName', true)) ?> </th>
                        <!-- <th > <?php echo $appCommon->show_order('active', __('Rellerusable', true)) ?>  </th>
                                -->
                                <th > <?php echo $appCommon->show_order('role_cnt', __('usercount', true)) ?>	</th>
                                <?php if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) { ?><th  class="last"><?php echo __('action') ?></th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < $loop; $i++) {
                                ?>
                                <tr class="row-1">
                                    <td align="center">
                                        <a style="width:80%;display:block" title="<?php __('viewfunc') ?>" href="<?php echo $this->webroot ?>exchangesysrolepris/add_sysrolepri/<?php echo $type . '/' . $mydata[$i][0]['role_id'] ?>" class="link_width">
                                            <?php echo $mydata[$i][0]['role_name'] ?>
                                        </a>
                                    </td>
                                    <!--
                              <td>
                                    <?php
                                    if (!empty($mydata[$i][0]['active'])) {
                                        echo 'active';
                                    } else {
                                        echo 'disable';
                                    }
                                    ?>
                                  </td>
                                    -->
                                    <td>
                                        <?php
                                        if (empty($mydata[$i][0]['role_users'])) {
                                            echo 0;
                                        } else {
                                            echo $mydata[$i][0]['role_users'];
                                        }
                                        ?>
                                    </td>
                                    <?php if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) { ?>
                                        <td class="last"  >		      

                                            <a   title="<?php echo __('editrole') ?>"  href="<?php echo $this->webroot ?>exchangesysrolepris/add_sysrolepri/<?php echo $type . '/' . $mydata[$i][0]['role_id'] ?>">
                                                <i class="icon-edit"></i>

                                            </a>
                                            <?php if (empty($mydata[$i][0]['role_users'])) { ?>
                                                <a title="<?php echo __('del') ?>" onclick="return confirm('Are you sure to delete, roles <?php echo $mydata[$i][0]['role_name'] ?> ? ');" href="<?php echo $this->webroot ?>exchangesysrolepris/del/<?php echo $mydata[$i][0]['role_id'] ?>/<?php echo $mydata[$i][0]['role_name'] ?>/<?php echo $type; ?>">
                                                    <i class="icon-remove"></i>
                                                </a>
                                            <?php } else if ($mydata[$i][0]['role_users'] >= 1) { ?>

                                                <a  onclick="return alert('<?php echo __('Are you sure to delete, roles %s',false, $mydata[$i][0]['role_name']) ?>');" href="<?php echo $this->webroot ?>exchangesysrolepris/del/<?php echo $mydata[$i][0]['role_id'] ?>/<?php echo $mydata[$i][0]['role_name'] ?>/<?php echo $type; ?>">
                                                    <i class="icon-remove"></i>
                                                </a>
                                            <?php } else if ($mydata[$i][0]['role_users'] > 1) { ?>

                                            <?php } ?>

                                        </td><?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php } ?>
            </div>

