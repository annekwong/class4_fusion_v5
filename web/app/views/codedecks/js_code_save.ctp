<?php echo $form->create('Codedeck') ?>
<table>
    <tr>
        <td><input type="hidden" name="code_deck_id" id="code_deck_id" value="<?php echo $code[0][0]['code_deck_id'] ?>" />
            <input type="hidden" name="code_id" id="code_id" value="<?php echo $code[0][0]['code_id'] ?>" /></td>
        <td><?php echo $form->input('code', Array('div' => false, 'label' => false, 'id' => 'code', 'name' => 'code', 'value' => $code[0][0]['code'])) ?></td>
        <td><?php echo $form->input('name', array('div' => false, 'label' => false, 'id' => 'name', 'name' => 'name', 'value' => $code[0][0]['name'])) ?></td>
        <td><?php echo $form->input('country', array('div' => false, 'label' => false, 'id' => 'Country', 'name' => 'country', 'value' => isset($search_value) ? $search_value : $code[0][0]['country'])) ?></td>
        <td>
            <a title="Save" id="save" href="<?php echo $this->webroot ?>codedecks/add_code" onclick="return false">
                <i class="icon-save"></i>
            </a>
            <a title="Exit" id="delete"  href="">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>


