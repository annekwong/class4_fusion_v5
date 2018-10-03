<div class="row-fluid">
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <tr>
            <th><?php echo $type; ?></th>
            <td>
                <select name="detail" multiple="multiple">
                    <?php foreach ($list as $value){ ?>
                    <option><?php echo $value[0]['name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        
    </table>
</div>