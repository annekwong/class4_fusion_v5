<form id="assign_product" style="padding: 0 20px;">
    <table  class="form footable table dynamicTable tableTools table-bordered  table-white default footable-loaded">
            <colgroup>
                <col width="100%">
            </colgroup>
            <tr class="assign">
                <td>
                     <input type="hidden" name="agent_id" value = "<?php echo $agent_id;?>">
                     <select name="products[]" id ="products" multiple="multiple" >
                             <option value=""></option>
                         <?php foreach ($products as $id => $product): ?>
                             <option value="<?php echo $id; ?>" <?php if(in_array($id, $assigned_products)) echo 'selected'?>><?php echo $product; ?></option>
                         <?php endforeach; ?>
                     </select>
                </td>
            </tr>
    </table>
</form>
<script>
     $('#products').multiSelect({
          selectableHeader: "<div class='custom-header'>All Products</div>",
          selectionHeader: "<div class='custom-header'>Assigned Products</div>",
     });
</script>