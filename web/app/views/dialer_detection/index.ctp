<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>dialer_detection"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>dialer_detection"><?php echo __('Dialer Detection') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Dialer Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>dialer_detection/add"><i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('dialer_detection/tabs'); ?>
        </div>

        <div class="widget-body">
            <?php if (!count($data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th>
                            <input id="selectAll"  type="checkbox"  value=""/>
                            <!-- <?php echo $appCommon->show_order('id', __('ID', true)) ?> -->
                        </th>
                        <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                        <th><?php __('Ingress') ?></th>
                        <th><?php __('Frequency') ?></th>
                        <th><?php __('ANI counts') ?></th>
                        <th><?php __('Status') ?></th>
                        <th><?php __('Send Email') ?></th>
                        <th><?php __('Block ANI') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $data_item)
                    {
                        ?>
                        <tr>
                            <td><input type="checkbox" /></td>
                            <td><a href="<?php echo $this->webroot; ?>dialer_detection/add/<?php echo base64_encode($data_item[0]['id']) ?>" ><?php echo $data_item[0]['name'] ?></a></td>
                            <td>
                                <?php
                                    $explode = explode(',', $data_item[0]['trunk']);  
                                    if (count($explode) > 1) {
                                        $trunkList = '';
                                        foreach ($explode as $e) {
                                            $trunkList .= isset($trunk[$e]) ? $trunk[$e] . ',' : $e . ',';
                                        }
                                        $trunkList = rtrim($trunkList, ',');
                                        echo $trunkList;
                                    } else {
                                        echo isset($trunk[$data_item[0]['trunk']]) ? $trunk[$data_item[0]['trunk']] : $data_item[0]['trunk'];
                                    }
                                ?>
                            </td>
                            <td><?php echo $data_item[0]['ani_within_mins'] ?> min(s)</td>
                            <td><?php echo $data_item[0]['ani_scope'] ?></td>
                            <td><?php
                                if ($data_item[0]['action'])
                                {
                                    echo "Active";
                                }
                                else
                                {
                                    echo "Inactive";
                                }
                                ?></td>
                            <td><?php echo $data_item[0]['send_email'] ? 'TRUE' : 'FALSE'; ?></td>
                            <td><?php echo $data_item[0]['block_ani'] ? 'TRUE' : 'FALSE'; ?></td>
                            <td>
                                <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>dialer_detection/add/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <?php if ($data_item[0]['action']): ?>
                                    <a title="<?php __('Inactive') ?>" onclick="return myconfirm('<?php __('sure to inactive') ?>', this);"
                                       href="<?php echo $this->webroot; ?>dialer_detection/disable/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                        <i class="icon-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a title="<?php __('Active') ?>" onclick="return myconfirm('<?php __('sure to active') ?>', this);"
                                       href="<?php echo $this->webroot; ?>dialer_detection/enable/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>dialer_detection/delete_rule/<?php echo base64_encode($data_item[0]['id']); ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <script>
            $(document).ready(function(){
                $('#selectAll').on('click', function(){
                    $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
                });
            });

            $(document).on('DOMNodeInserted', function(){
                $('.icon-unchecked').parent().attr('title', 'Activate');
            });
        </script>