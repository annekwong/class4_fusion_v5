<?php echo $form->create('ProductRouteRateTable')?>
<table>
    <tr>
        <td>
            <select name="product" class="product_name" >
                <?php foreach ($product_lists as $product_id => $product_list): ?>
                    <option value="<?php echo $product_id; ?>" prefix="<?php echo $product_list['tech_prefix']; ?>">
                        <?php echo $product_list['product_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td></td>
        <td class="last">
            <a id="save" href="javascript:void(0)" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" title="Exit">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>

<script type="text/javascript">
    $(function(){
        $(".product_name").change(function(){
            var prefix = $(this).find("option:selected").attr('prefix');
            console.log(prefix);
            $(this).parent().next().html(prefix);
        }).trigger('change');
    });
</script>
