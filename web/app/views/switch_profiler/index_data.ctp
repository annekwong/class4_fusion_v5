<style>

    table.table-black {
        border: 1px solid #ccc;
    }

    input.no-input-style{
        border: none;
        cursor: default;
    }
    .require_auth input {
        margin: 0 10px 0 3px;
    }
</style>

<table class="list sub-table footable table table-striped tableTools table-bordered table-primary table-black">
    <thead>
    <tr>
        <th class="background-primary"><?php __('Profile Name') ?></th>
        <!--                        <th>--><?php //__('Profile Status') ?><!--</th>-->
        <th class="background-primary"><?php __('SIP IP') ?></th>
        <th class="background-primary"><?php __('SIP Port') ?></th>
        <!--th><?php __('Proxy IP') ?></th>
        <th class="background-primary"><?php __('Proxy Port') ?></th-->
        <th class="background-primary"><?php __('Status') ?></th>
        <th class="background-primary"><?php __('CPS') ?></th>
        <th class="background-primary"><?php __('CAP') ?></th>
        <th class="background-primary"><?php __('Action') ?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($this->data as $item): ?>
        <tr>
            <td><?php echo $item['SwitchProfile']['profile_name']; ?></td>
            <!--                            <td>--><?php //echo $status[$item['SwitchProfile']['profile_status']]; ?><!--</td>-->
            <td><?php echo $item['SwitchProfile']['sip_ip']; ?></td>
            <td><?php echo $item['SwitchProfile']['sip_port']; ?></td>

            <!--td><?php echo $item['SwitchProfile']['proxy_ip']; ?></td>
            <td><?php echo $item['SwitchProfile']['proxy_port']; ?></td-->
            <td><?php echo $item['SwitchProfile']['status']; ?></td>
            <td><?php echo $item['SwitchProfile']['cps']; ?></td>
            <td><?php echo $item['SwitchProfile']['cap']; ?></td>
            <td>
                <?php if ($item['SwitchProfile']['default_register']): ?>
                    <i class="icon-check"></i>
                <?php else: ?>
                    <a title="<?php __('Start') ?>" href="<?php echo $this->webroot; ?>switch_profiler/set_default_register/<?php echo $server_id . '/' . $item['SwitchProfile']['id'] ?>">
                        <i class="icon-unchecked"></i>
                    </a>
                <?php endif; ?>

                <a title="<?php __('Edit') ?>" class="edit_sub_item" onclick="editSubItem(this);" href="javascript:void(0);" control="<?php echo $item['SwitchProfile']['id'] ?>" >
                    <i class="icon-edit"></i>
                </a>

                <a title="<?php __('Delete') ?>" onclick="bootbox.confirm('Are you sure to delete the item?', function (result) {
                    let url = '<?php echo $this->webroot; ?>switch_profiler/delete/<?php echo $server_id . '/' . $item['SwitchProfile']['id'] ?>';
                if (result) {
                    window.location.href = url;
                }
            });" class="delete" href="javascript:void(0);">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="15">
                <span><?php __('Require Authenication') ?>:<?php echo $item['SwitchProfile']['auth_register'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('RPID') ?>:<?php echo $item['SwitchProfile']['support_rpid'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('OLI') ?>:<?php echo $item['SwitchProfile']['support_oli'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('PRIV') ?>:<?php echo $item['SwitchProfile']['support_priv'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('DIV') ?>:<?php echo $item['SwitchProfile']['support_div'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('PAID') ?>:<?php echo $item['SwitchProfile']['support_paid'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('PCI') ?>:<?php echo $item['SwitchProfile']['support_pci'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('X LRN') ?>:<?php echo $item['SwitchProfile']['support_x_lrn'] ? 'Yes' : 'No'; ?></span>
                <span><?php __('X Header') ?>:<?php echo $item['SwitchProfile']['support_x_header'] ? 'Yes' : 'No'; ?></span>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
