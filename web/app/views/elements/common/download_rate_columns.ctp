<?php if(isset($schema) && is_array($schema)):?>

<?php
$def_infex = 0;
$index = count($default_fields);
?>
<table>
	<?php foreach($default_fields as $field_name):?>
            <tr>
                <td><?php echo __('Column',true);?> #<?php echo $index ?>:</td>
                <td style="text-align:left;"><?php echo $appDownload->display_field_select($schema,$field_name) ?></td>
            </tr>
            <?php $index = $def_infex + 1;?>
    <?php endforeach;?>
    <?php foreach($schema as $field_name => $t):?>
        <?php if(!array_key_exists($field_name)):?>
            <tr>
                <td><?php echo __('Column',true);?> #<?php echo $index ?>:</td>
                <td style="text-align:left;"><?php echo $appDownload->display_field_select($schema,'') ?></td>
            </tr>
            <?php $index = $index + 1;?>
        <?php endif;?>
    <?php endforeach;?>
</table>
<?php endif;?>

