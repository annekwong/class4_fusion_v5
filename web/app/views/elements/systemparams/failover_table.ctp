<table class="list list-form table  tableTools table-bordered  table-white"  id="list_table">
    <thead>
    <tr>
        <td><?php echo __('Code', true); ?></td>
        <td><?php echo __('Route Type', true); ?></td>
        <td><?php echo __('Response', true); ?></td>
    </tr>
    </thead>
    <?php
    if (strcmp($type, 'all'))
    {
        ?>
        <tbody class="rows" id="rows-ip">
        <?php
        $size = count($host);
        for ($i = 0; $i < $size; $i++)
        {
            ?>
            <tr class="row-<?php echo $i % 2 + 1 ?>" id="row-<?php echo $i + 1 ?>" style="">
                <td>
                    <input type="text" value="<?php echo $host[$i][0]['from_sip_code']; ?>" class='validate[required,custom[onlyNumberSp]] input in-text' name="accounts[<?php echo $i + 1 ?>][from_sip_code]" id="ip-port-<?php echo $i + 1 ?>" check="code"  maxLength='3'>
                </td>
                <td>
                    <input type="hidden" name="accounts[<?php echo $i + 1 ?>][id]" id="ip-id-<?php echo $i + 1 ?>" value="<?php echo $host[$i][0]['id'] ?>" class="input in-hidden">
                    <?php
                    $ii = $i + 1;
                    $t = array('1' => __('Fail to Next Host',true), '2' => __('Fail to Next Trunk',true), '3' => __('Stop',true));
                    echo $form->input('client_id', array('options' => $t, 'rel' => 'stop_return', 'id' => "ip-route_type-$ii", 'name' => "accounts[$ii][failover_strategy]", 'selected' => $host[$i][0]['failover_strategy'],
                        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                    ?>
                </td>
                <td >
                    <input type="text" value="<?php echo $host[$i][0]['to_sip_code']; ?>" style="<?php
                    if ($host[$i][0]['failover_strategy'] != 3)
                    {
                        echo "display:none";
                    }
                    ?>"  class="input in-text validate[required,custom[onlyNumberSp]]" name="accounts[<?php echo $i + 1 ?>][to_sip_code]" id="ip-return_code-<?php echo $i + 1 ?>" check="Num" maxLength='3'>
                    <input type="text" value="<?php echo $host[$i][0]['to_sip_string']; ?>"  style="<?php
                    if ($host[$i][0]['failover_strategy'] != 3)
                    {
                        echo "display:none";
                    }
                    ?>"  class="input in-text" name="accounts[<?php echo $i + 1 ?>][to_sip_string]" id="ip-return_string-<?php echo $i + 1 ?>"  />
                </td>

            </tr>
        <?php } ?>
        </tbody>
        <tbody>
        <tr style="display:none;" id="tpl-ip" class="  row-2">
            <td>
                <input type="text" name="_accounts[%n][from_sip_code]" class="input in-text"  maxLength="3" check="code" value="">
            </td>
            <td>
                <select rel='stop_return'    name="_accounts[%n][failover_strategy]"   id="route_type0" class="netmask0 input in-select"   >
                    <?php
                    if ($type == "ingress")
                    {
                        ?>
                        <option value="3"><?php __('Stop')?></option>
                        <?php
                    }
                    else
                    {
                        ?>
                        <option value="1"><?php __('Fail to Next Host')?></option>
                        <option value="2"><?php __('Fail to Next Trunk')?></option>
                        <option value="3"><?php __('Stop')?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td >
                <input type="text"   style="display: none;" name="_accounts[%n][[to_sip_code]" class="input in-text" check="Num" maxLength="3" value="">
                <input type="text"  style="display: none;" name="_accounts[%n][to_sip_string]" class="input in-text"  value="">
            </td>
            <td>
                <a href="#" id="tpl-delete-row"  >
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        </tbody>
        <?php
    }
    else
    {
        ?>
        <tbody class="rows" id="rows-ip">
        <?php
        $size = count($host);
        for ($i = 0; $i < $size; $i++)
        {
            ?>
            <tr class="row-<?php echo $i % 2 + 1 ?>" id="row-<?php echo $i + 1 ?>" style="">
                <td>
                    <?php echo $host[$i][0]['from_sip_code']; ?>
                </td>
                <td>
                    <input type="hidden" name="accounts[<?php echo $i + 1 ?>][id]" id="ip-id-<?php echo $i + 1 ?>" value="<?php echo $host[$i][0]['id'] ?>" class="input in-hidden">
                    <?php
                    $ii = $i + 1;
                    $t = array('1' => __('Fail to Next Host',true), '2' => __('Fail to Next Trunk',true), '3' => __('Stop',true));
                    $t_type = intval($host[$i][0]['failover_strategy']);
                    echo $t[$t_type];
                    ?>
                </td>
                <td >
                    <?php
                    if ($host[$i][0]['failover_strategy'] == 3)
                    {
                        echo $host[$i][0]['to_sip_string'];
//                                            echo $host[$i][0]['to_sip_code'] . "&nbsp&nbsp&nbsp&nbsp" . $host[$i][0]['to_sip_string'];
                    }
                    ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
        <tbody>
        <tr style="display:none;" id="tpl-ip" class="  row-2">
            <td>
                <input type="text" name="_accounts[%n][from_sip_code]" class="input in-text"  maxLength="16" check="code" value="">
            </td>
            <td>
                <select rel='stop_return'    name="_accounts[%n][failover_strategy]"   id="route_type0" class="netmask0 input in-select"   >
                    <?php
                    if ($type == "ingress")
                    {
                        ?>
                        <option value="3"><?php __('Stop')?></option>
                        <?php
                    }
                    else
                    {
                        ?>
                        <option value="1"><?php __('Fail to Next Host')?></option>
                        <option value="2"><?php __('Fail to Next Trunk')?></option>
                        <option value="3"><?php __('Stop')?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td >
                <input type="text"   style="display: none;" name="_accounts[%n][[to_sip_code]" class="input in-text" check="Num" maxLength="16" value="">
                <input type="text"  style="display: none;" name="_accounts[%n][to_sip_string]" class="input in-text"  value="">
            </td>

        </tr>
        </tbody>
    <?php } ?>
</table>