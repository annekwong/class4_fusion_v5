<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Modules', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Modules', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) { ?>
            <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>syspris/add_syspri/<?php echo $module_id = array_keys_value($this->params, 'pass.0') ?>"><i></i> <?php __('Create new'); ?></a>
            <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>sysmodules/view_sysmodule"><i></i> <?php __('Back'); ?></a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)) {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php } else {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                        <tr>
                            <th ><?php echo $appCommon->show_order('pri_name', __('Privilege Name', true)) ?></th>
                            <th ><?php echo __('Privilege List Value', true); ?></th>
                            <th ><?php echo $appCommon->show_order('flag', __('Flag', true)) ?></th>
                            <th><?php echo __('Module List Url', true); ?></th>
                            <th ><?php echo __('Parent Module', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Configuration']['syspris']['model_w']) { ?>
                                <th  class="last"><?php echo __('action') ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr class="row-1">
                                <td align="center"><a style="width:80%;" title="" href="<?php echo $this->webroot ?>syspris/edit_syspri/<?php echo base64_encode($mydata[$i][0]['module_id']) ?>/<?php echo base64_encode($mydata[$i][0]['id']); ?>" class="link_width"> <?php echo $mydata[$i][0]['pri_name'] ?> </a></td>
                                <td><?php echo $mydata[$i][0]['pri_val'] ?></td>
                                <td><?php if ($mydata[$i][0]['flag'] == 1) {
                        echo 'True';
                    } else {
                        echo 'False';   
                    } ?></td>
                                <td><?php echo $mydata[$i][0]['pri_url'] ?></td>
                                <td><a href="<?php echo $this->webroot ?>sysmodules/view_sysmodule"><?php echo $mydata[$i][0]['module_name'] ?></a></td>
        <?php if ($_SESSION['role_menu']['Configuration']['syspris']['model_w']) { ?>
                                    <td class="last">

                                        <a   title="<?php echo __('editrole') ?>"  href="<?php echo $this->webroot ?>syspris/edit_syspri/<?php echo base64_encode($mydata[$i][0]['module_id']) ?>/<?php echo base64_encode($mydata[$i][0]['id']); ?>"> <i class="icon-edit"></i> </a>

                                        <a title="<?php echo __('del') ?>" onClick="return myconfirm('Are you sure to delete it?', this);" href="<?php echo $this->webroot ?>syspris/del_syspri/<?php echo base64_encode($mydata[$i][0]['module_id']) ?>/<?php echo base64_encode($mydata[$i][0]['id']); ?>"> <i class="icon-remove"></i> </a>


                                    </td> <?php } ?>
                            </tr>
    <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
            </div>
            <div>

<?php } ?>
        </div>
    </div>
</div>
