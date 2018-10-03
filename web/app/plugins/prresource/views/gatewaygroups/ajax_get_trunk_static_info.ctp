<form method="post" id="add_form" action="<?php echo $this->webroot; ?>prresource/gatewaygroups/add_trunk_static/<?php echo $resource_id; ?>">
    <table>
        <tr>
            <td><?php __('Static Route Name')?></td>
        </tr>
        <tr>
            <td>
                <select name="static_route">
                    <?php foreach ($static_route as $static_route_item): ?>
                        <option value="<?php echo $static_route_item[0]['product_id']; ?>"
                                <?php if (!strcmp($static_route_item[0]['product_id'], $static_item_info['product_id'])): ?>selected="selected"<?php endif; ?>
                                ><?php echo $static_route_item[0]['name']; ?></option>
                            <?php endforeach; ?>
                </select>
                <input type="hidden" value="<?php echo $static_item_info['prefix']; ?>" name="prefix" />
            </td>
        </tr>
    </table>
</form>





