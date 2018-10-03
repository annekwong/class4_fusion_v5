<?php
$encodedWord = base64_encode('DID');
?>
<form action="">
    <table>
        <tr>
            <td><?php echo $data['DidBillingRel']['did']; ?></td>
            <td>
                <select name="trunk" id="">
                    <?php foreach ($trunks as $trunk): ?>
                        <option value="<?php echo $trunk['Resource']['resource_id']; ?>" <?php if (explode("_{$encodedWord}_", $data['Resource']['alias'])[0] == $trunk['Resource']['alias']) echo 'selected' ?>><?php echo $trunk['Resource']['alias']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?php echo $data['DidBillingRel']['type']; ?></td>
<!--            <td>--><?php //echo $data['Code']['country']; ?><!--</td>-->
<!--            <td>--><?php //echo $data['Code']['state']; ?><!--</td>-->
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="###" title="Save">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
</form>