<form method="post">
    <table>
        <tr>
            <td><input type="checkbox" /></td>
            <td>
                <select name="ingress_id">
                    <?php foreach ($ingress_trunks as $item): ?>
                        <option value="<?php echo $item[0]['resource_id']; ?>" <?php if( $item[0]['resource_id'] == $edit_item[0][0]['ingress_id']) echo 'selected';?>><?php echo $item[0]['alias']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" name="max_cps" id="max_cps" value="<?php echo $edit_item[0][0]['max_cps']; ?>"/>
            </td>
            <td>
                <input type="text" name="max_cap" id="max_cap" value="<?php echo $edit_item[0][0]['max_cap']; ?>"/>
            </td>
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="###" title="Save" proid = "<?php echo $edit_item[0][0]['id']; ?>">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
</form>