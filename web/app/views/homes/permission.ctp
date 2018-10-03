<?php
if (isset($_SESSION['file_permission']))
{
//    $file_permission = $_SESSION['file_permission'];
//    unset($_SESSION['file_permission']);
}
if (isset($_SESSION['license_date']))
{
    $license_dates = $_SESSION['license_date'];
    unset($_SESSION['license_date']);
}
?>
<div id="permission_dl" class="row-fluid">
    <table class="list table dynamicTable tableTools table-bordered  table-primary table-white">
        <?php
        if (isset($file_permission['read']))
        {
            ?>
            <tr>
                <th>The following file does not have read access:</th>
            </tr>
            <?php
            foreach ($file_permission['read'] as $read_item)
            {
                ?>
                <tr>
                    <td><?php echo $read_item; ?></td>
                </tr>

                <?php
            }
        }
        ?>
        <?php
        if (isset($file_permission['write']))
        {
            ?>
            <tr>
                <th>The following file does not have write access:</th>
            </tr>
            <?php
            foreach ($file_permission['write'] as $write_item)
            {
                ?>
                <tr>
                    <td><?php echo $write_item; ?></td>
                </tr>

                <?php
            }
        }
        ?>
        <?php
        if (isset($license_dates))
        {
            ?>
            <tr>
                <th>Check switch License:</th>
            </tr>
            <?php
            foreach ($license_dates['before7'] as $license_date)
            {
                ?>
                <tr>
                    <td>The Switch with Gateway Name
                        <font color="red"><?php echo $license_date['switch_name'] ?></font>
                        and IP 
                        <font color="red"><?php echo $license_date['ip'] . ":" . $license_date['port']; ?> </font>
                        will be expired on 
                        <font color="red"><?php echo $license_date['license_date'] ?></font>
                        .</td>
                </tr>

                <?php
            }
            
            foreach ($license_dates['expired'] as $license_date)
            {
                ?>
                <tr>
                    <td>The Switch with Gateway Name
                        <font color="red"><?php echo $license_date['switch_name'] ?></font>
                        and IP 
                        <font color="red"><?php echo $license_date['ip'] . ":" . $license_date['port']; ?> </font>
                        is expired.</td>
                </tr>

                <?php
            }
            
        }
        ?>

    </table>
</div>