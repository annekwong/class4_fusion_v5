<form method="post" id="update_in_form">
<input type="hidden" name="rate_table_id" value="<?php echo $rate_table_id; ?>" />
<table class="table table-striped table-bordered  table-white table-primary">
    <thead></thead>
    <tr>
        <td class="right"><?php __('Country Code')?></td>
        <td><input type="text" name="jurisdiction_prefix" value="<?php 
            if(isset($data['jurisdiction_prefix'])){
                echo $data['jurisdiction_prefix'];
            }else{
                echo '';
            }
        ?>" /></td>
    </tr>
    <tr>
        <td class="right"><?php __('Max Code Length W/O Country Code')?></td>
        <td><input type="text"  name="noprefix_max_length" value="<?php
            if(isset($data['noprefix_max_length'])){
                echo $data['noprefix_max_length'];
            }else{
                echo 10;
            }
        ?>" /></td>
    </tr>
    <tr>
        <td class="right"><?php __('Min Code Length W/O Country Code')?></td>
        <td><input type="text" name="noprefix_min_length" value="<?php
            if(isset($data['noprefix_min_length'])){
                echo $data['noprefix_min_length'];
            }else{
                echo 10;
            }
        ?>" /></td>
    <tr>
    <tr>
        <td class="right"><?php __('Max Code Length With Country Code')?></td>
        <td><input type="text" name="prefix_max_length" value="<?php if(isset($data['prefix_max_length'])){
                echo $data['prefix_max_length'];
            }else{
                echo 11;
            } ?>" /></td>
    <tr>
    <tr>
        <td class="right"><?php __('Min Code Length With Country Code')?></td>
        <td><input type="text" name="prefix_min_length" value="<?php if(isset($data['prefix_min_length'])){
                echo $data['prefix_min_length'];
            }else{
                echo 11;
            } ?>" /></td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <input class="btn btn-primary" type="button" id="update_in" value="<?php __('Update')?>" />
        </td>
    </tr>
</table>
</form>
<script>
$('#update_in_form').find('input').on('keyup', function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
});
</script>