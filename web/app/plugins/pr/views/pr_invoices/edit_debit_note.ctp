<?php echo $form->create('debit', Array('action' => 'add', 'method' => 'post')) ?>
    <table>
        <tr>
            <td>

            </td>
            <td></td>
            <td>
                <?php
                echo $form->input('amount', array('label' => false, 'div' => false, 'class' => 'amount', 'type' => 'text', 'class' => 'input-small,validate[required,custom[number]]', 'value' => number_format($data[0][0]['amount'], 2)));
                ?>
            </td>
            <td>
                <?php
                echo $form->input('description', array('label' => false, 'div' => false, 'class' => 'description', 'type' => 'text', 'class' => 'input-small', 'value' => $data[0][0]['description']));
                ?>
            </td>
            <td  data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                <a title="Save" id="save" href="javascript:void(0)">
                    <i class="icon-save"></i>
                </a>
                <a title="Exit" id="delete"  href="javascript:void(0)">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
<?php echo $form->end() ?>
