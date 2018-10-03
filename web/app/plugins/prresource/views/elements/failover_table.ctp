<table class="list list-form table  tableTools table-bordered  table-white"  id="list_table">
    <thead>
    <tr>
        <th rowspan='2'><?php echo __('Egress Return Code', true); ?></th>
        <th colspan='2'><?php echo __('System Default', true); ?></th>
        <th colspan='3'><?php echo __('User Defined', true); ?></th>
    </tr>
    <tr>
        <td><?php echo __('Route Type', true); ?></th>
        <th><?php echo __('Response', true); ?></th>
        <td><?php echo __('Route Type', true); ?></th>
        <th><?php echo __('Response', true); ?></th>
        <th><?php echo __('Reason', true); ?></th>
    </tr>
    </thead>

    <tbody class="rows" id="rows-ip">
           <?php foreach($data as $i => $item):?>
            <tr class="row-<?php echo $i % 2 + 1 ?>" id="row-<?php echo $i + 1 ?>">
                <td><?php echo $item[0]['from_sip_code'];?></td>
                <td><?php echo $failover_strategy[$item[0]['failover_strategy']];?></td>
                <td><?php echo $item[0]['to_sip_string'];?></td>
                <td>
                    <input type="hidden" value="<?php echo $resource_id;?>" name="accounts[<?php echo $i + 1 ?>][resource_id]" >
                    <input type="hidden" value="<?php echo !empty($item[0][$item[0]['from_sip_code']]) ? $item[0][$item[0]['from_sip_code']]['reponse_code'] : '';?>" name="accounts[<?php echo $i + 1 ?>][reponse_code]" >
                    <select rel='stop_return' name="accounts[<?php echo $i + 1 ?>][route_type]"   id="route_type0" class="netmask0 input in-select"   >
                        <?php foreach($failover_strategy as $key => $strategy):?>
                         <option value="<?php echo $key;?>" <?php if(!empty($item[0][$item[0]['from_sip_code']]) && $key == $item[0][$item[0]['from_sip_code']]['route_type']) echo 'selected';?>><?php echo $strategy;?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td>
                    <input type="text" value="<?php echo !empty($item[0][$item[0]['from_sip_code']]) ? $item[0][$item[0]['from_sip_code']]['return_code'] : '';?>" class='validate[required,custom[onlyNumberSp]] input in-text' name="accounts[<?php echo $i + 1 ?>][return_code]" id="ip-port-<?php echo $i + 1 ?>" check="code"  maxLength='3'
                    <?php if (!empty($item[0][$item[0]['from_sip_code']]) && $item[0][$item[0]['from_sip_code']]['route_type'] != 3) echo 'style="display:none;"'; ?>
                    />
                </td>
                <td>
                  <input type="text" value="<?php echo !empty($item[0][$item[0]['from_sip_code']]) ? $item[0][$item[0]['from_sip_code']]['return_string'] : '';?>"
                  <?php if (!empty($item[0][$item[0]['from_sip_code']]) && $item[0][$item[0]['from_sip_code']]['route_type'] != 3) echo 'style="display:none;"'; ?>
                  class="input in-text" name="accounts[<?php echo $i + 1 ?>][return_string]" id="ip-return_string-<?php echo $i + 1 ?>"  />
                <td>
            </tr>
           <?php endforeach; ?>
     </tbody>
    </table>



