<form method="post">
    <table>
        <tr>
            <td><input type="checkbox" /></td>
            <td>
                <select name="static_route">
                    <?php foreach ($static_route as $static_route_item): ?>
                    <option value="<?php echo $static_route_item[0]['product_id']; ?>" ><?php echo $static_route_item[0]['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" name="prefix" id="prefix" value="<?php echo $static_item_info['prefix'] ?>" />
                <a style="cursor:help;" title="<?php __('trunk static route prefix notify'); ?>">
                    <i class="icon-question-sign"></i>
                </a>
            </td>
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="###" title="Save" proid = "<?php echo $static_item_info['product_id']; ?>">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
</form>





