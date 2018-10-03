<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Modules') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Modules</h4>
    <div class="buttons pull-right">
        <?php if ($_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w']) { ?>
            <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>exchangesysmodules/add_sysmodule"><i></i> Create New</a>
        <?php } ?> 

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">

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
                            <th ><?php echo __('Module Name', true); ?></th>
                            <th>Type</th>
                            <th class="last"><?php echo __('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $type = array('0' => 'Exchange', '1' => 'Agent', '2' => 'Partition');
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr class="row-1">
                                <td align="center"><a title="<?php echo "View sub-module" ?>"  href="<?php echo $this->webroot ?>exchangesyspris/view_syspri/<?php echo $mydata[$i][0]['id'] ?>"><?php echo $mydata[$i][0]['module_name'] ?> </a></td>
                                <td>
                                    <?php echo $type[$mydata[$i][0]['type']] ?>
                                </td>
                                <td class="last">
                                    <?php if ($_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w']) { ?>
                                        <a title="<?php echo __('editmodule') ?>"  href="<?php echo $this->webroot ?>exchangesysmodules/edit_sysmodule/<?php echo $mydata[$i][0]['id'] ?>"> <i class="icon-edit"></i> </a>

                                        <a title="<?php echo __('del') ?>" onClick="return confirm('Are you sure to delete,this module <?php echo $mydata[$i][0]['module_name'] ?> ? ');" href="<?php echo $this->webroot ?>exchangesysmodules/del/<?php echo $mydata[$i][0]['id'] ?>/<?php echo $mydata[$i][0]['module_name'] ?>"> <i class="icon-remove"></i> </a>
                                    <?php } ?>
                                    <a title="<?php echo "View sub-module" ?>"  href="<?php echo $this->webroot ?>exchangesyspris/view_syspri/<?php echo $mydata[$i][0]['id'] ?>"> <i class="icon-arrow-right"></i> </a>
                                </td>
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
    </div>
</div>
