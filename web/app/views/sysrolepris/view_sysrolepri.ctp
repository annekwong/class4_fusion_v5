<?php
$disable = false;
if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
{
     $disable = true;
}
?>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>sysrolepris/view_sysrolepri">
    <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>sysrolepris/view_sysrolepri">
        <?php echo __('Roles'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Roles'); ?></h4>

</div>
<div class="separator bottom"></div>
<?php if(!$disable) {?>
<div class="buttons pull-right newpadding" <?php echo $disable; ?>>
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>sysrolepris/add_sysrolepri"><i></i> <?php __('Create new'); ?></a>
</div>
<div class="clearfix"></div>
<?php }?>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="myform1">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Role') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search') ?>" value="Search" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata))
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php
            }
            else
            {
            ?>
            <div class="clearfix"></div>
            <!--<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">-->
            <table class="dynamicTable tableTools table table-striped table-bordered   table-white table-primary" >
                <thead>
                <tr>
                    <!--<th><?php echo 1; ?></th>-->
                    <th><?php echo $appCommon->show_order('role_name', __('RolesName', true)) ?> </th>
                    <!-- <td > <?php echo $appCommon->show_order('active', __('Rellerusable', true)) ?>  </td>
                            -->
                    <th> <?php echo $appCommon->show_order('role_users', __('usercount', true)) ?>	</th>
                    <?php
                    if ($_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
                    {
                        ?><th><?php echo __('action') ?></th><?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i = 0; $i < $loop; $i++)
                {
                    ?>
                    <tr class="row-1">
                        <td align="center">
                            <?php if (strcmp($mydata[$i][0]['role_name'], 'admin')): ?>
                                <a title="<?php __('viewfunc') ?>"  href="<?php echo $this->webroot ?>sysrolepris/add_sysrolepri/<?php echo base64_encode($mydata[$i][0]['role_id']) ?>" class="link_width">
                                    <?php echo $mydata[$i][0]['role_name'] ?>
                                </a>
                            <?php else: ?>
                                <?php echo $mydata[$i][0]['role_name'] ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            if (empty($mydata[$i][0]['role_users'])): ?>
                                0
                            <?php else: ?>
                                <?php
                                if (!strcmp($mydata[$i][0]['role_name'], $dnl_role_name))
                                    $mydata[$i][0]['role_users'] --;
                                ?>
                                <a style="display: block" href="<?php echo $this->webroot; ?>users/index?role_id=<?php echo $mydata[$i][0]['role_id']; ?>" target="_block"><?php echo $mydata[$i][0]['role_users']; ?></a>

                            <?php endif; ?>
                        </td>
                        <?php
                        if ($_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
                        {
                            ?>
                            <td class="last"  >
                            <?php if (strcmp($mydata[$i][0]['role_name'], 'admin')): ?>
                            <a   title="<?php echo __('editrole') ?>"  href="<?php echo $this->webroot ?>sysrolepris/add_sysrolepri/<?php echo base64_encode($mydata[$i][0]['role_id']) ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <?php
                            if (empty($mydata[$i][0]['role_users']))
                            {
                                ?>
                                <a title="<?php echo __('del') ?>" onclick="return myconfirm('Are you sure to delete it?', this);" href="<?php echo $this->webroot ?>sysrolepris/del/<?php echo base64_encode($mydata[$i][0]['role_id']) ?>/<?php echo $mydata[$i][0]['role_name'] ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            <?php
                            }
                            else if ($mydata[$i][0]['role_users'] >= 1)
                            {
                                ?>

                                <a  onclick="return myconfirm('Are you sure to delete it?', this);" href="<?php echo $this->webroot ?>sysrolepris/del/<?php echo base64_encode($mydata[$i][0]['role_id']) ?>/<?php echo $mydata[$i][0]['role_name'] ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            <?php
                            }
                            else if ($mydata[$i][0]['role_users'] > 1)
                            {
                                ?>

                            <?php } ?>
                        <?php endif; ?>
                            </td><?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="separator"></div>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/css/TableTools.css" rel="stylesheet" />
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/ColVis/media/css/ColVis.css" rel="stylesheet" />
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/ColVis/media/js/ColVis.min.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
