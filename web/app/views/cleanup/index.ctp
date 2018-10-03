<style>
    table input{width: 100px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Back-Up and Data Cleansing') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Back-Up and Data Cleansing') ?></h4>

</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div id="container">
<?php

    $res = array(
        1=>'Daily',
        2=>'Weekly',
        3=>'Monthly'
    );
?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('name', __('Data Type', true)) ?></th>
                            <th><?php echo $appCommon->show_order('backup_frequency', __('Backup Frequency', true)) ?></th>
                            <!--<th><?php echo $appCommon->show_order('data_size', __('Data Size(in days)', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('data_cleansing_frequency', __('Data Cleansing Frequency', true)) ?></th>
                            <!--<th><?php echo $appCommon->show_order('data_removal', __('Data Removal(in days)', true)) ?></th>
                            <th><?php echo $appCommon->show_order('ftp_server', __('FTP Server', true)) ?></th>
                            <th><?php echo $appCommon->show_order('ftp_user', __('User', true)) ?></th>
                            <th><?php __('Password')?></th>-->
                            <th><?php __('Active')?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($cleanups as $cleanup): ?>
                            <tr>
                                <td><?php echo $cleanup['Cleanup']['name']; ?></td>
                                <td><?php echo $cleanup['Cleanup']['backup_frequency']; ?></td>
                                <!--<td><?php echo $cleanup['Cleanup']['data_size']; ?></td>-->
                                <td><?php echo $cleanup['Cleanup']['data_cleansing_frequency']; ?></td>
                                <!--<td><?php echo $cleanup['Cleanup']['data_removal']; ?></td>
                                <td><?php echo $cleanup['Cleanup']['ftp_server']; ?></td>
                                <td><?php echo $cleanup['Cleanup']['ftp_user']; ?></td>
                                <td><?php echo $cleanup['Cleanup']['ftp_password']; ?></td>-->
                                <td>
                                    <a href="<?php echo $this->webroot ?>cleanup/change_status/<?php echo base64_encode($cleanup['Cleanup']['id']) ?>">
                                        <?php if ($cleanup['Cleanup']['actived']): ?>
                                            <i class="icon-check"></i>
                                        <?php else: ?>
                                            <i class="icon-unchecked"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                    <a title="<?php __('Edit')?>"  href="###" control="<?php echo $cleanup['Cleanup']['id'] ?>" class="edited_item">
                                        <i class="icon-edit"></i>
                                    </a>
                                </td>
                            </tr>            
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

            <script>
                jQuery(function() {

                    jQuery('a.edited_item').click(function() {
                        jQuery(this).parent().parent().trAdd({
                            action: '<?php echo $this->webroot ?>cleanup/edit_panel/' + jQuery(this).attr('control'),
                            ajax: '<?php echo $this->webroot ?>cleanup/edit_panel/' + jQuery(this).attr('control'),
                            saveType: 'edit'
                        });
                    });
                });
            </script>