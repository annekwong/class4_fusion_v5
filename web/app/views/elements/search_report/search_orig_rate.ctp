    <td class="align_right padding-r10"><?php __('RateTable')?></td>
    <td >
<!--        <input type="text" id="query-id_rates_name" ondblclick="ss_rate(_ss_ids_rate)" value="" name="query[rate_name]" class="input in-text"/>        
        <a href="#" onclick="ss_rate(_ss_ids_rate)" ><img width="9" height="9" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>-->

        <input value="" type="text" id="query-id_rates_name" name="query[rate_name]" class="input in-text" style="width:220px;"/>        
<!--        <select name="query[rate_name]" id="query-id_rates_name" >
            <option></option>
            <?php foreach ($all_rate_table as $rate_name){ ?>
            <option value="<?php echo $rate_name; ?>"><?php echo $rate_name; ?></option>
            <?php } ?>
        </select>-->
            <?php echo $this->element('search_report/ss_clear_input_select');?>
    </td>