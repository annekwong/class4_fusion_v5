<?php $form->create('Product') ?>
<table>
    <tr>
        <td><?php echo $xform->input('product_id', Array('type' => 'hidden', 'name' => 'id')) ?></td>
        <td>
            <?php echo $xform->input('name', Array('name' => 'name', 'style' => 'width:100px')) ?>
        </td>
        <td>
            <?php echo $xform->input('code_type', Array('options' => Array('0' => 'By Code', '1' => 'By Code Name'), 'style' => 'width:150px', 'onchange' => 'changeCodeType(this)')) ?>
        </td>
        <td>
            <?php
            $codeDecks = array("0" => "");
            if (!empty($code_decks)) {
                foreach ($code_decks as $code_deck) {
                    $codeDecks[$code_deck[0]['code_deck_id']] = $code_deck[0]['name'];
                }
            }
            echo $xform->input('code_deck_id', Array('options' => $codeDecks, 'style' => 'width:90px'));
            ?>

        </td>
        <td>
            <?php echo $xform->input('route_lrn', Array('options' => Array('0' => __('DNIS', true), '1' => __('LRN', true)), 'style' => 'width:75px')) ?>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <a title="Save" href="#%20" id="save">
                <i class="icon-save"></i>
            </a>
            <a title="Exit" href="#%20" style="margin-left: 20px;" id="delete">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php $form->end() ?>
