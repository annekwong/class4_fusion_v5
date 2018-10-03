
<td class="align_right padding-r10">
  <?php __('RateTable')?></td>
<td >
<!--    <input type="text" id="query-id_rates_name_term" ondblclick="ss_rate_term(_ss_ids_rate_term)" value="" name="query[rate_name_term]" class="input in-text"/>
 <a href="#" onclick="ss_rate_term(_ss_ids_rate_term)" > <img width="9" height="9" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>-->
    <input type="text" id="query-id_rates_name_term" name="query[rate_name_term]" class="input in-text" style="width:220px;"/>
<!--<select name="query[rate_name_term]" id="query-id_rates_name_term" >
            <option></option>
            <?php foreach ($all_rate_table as $rate_name){ ?>
            <option value="<?php echo $rate_name; ?>"><?php echo $rate_name; ?></option>
            <?php } ?>
        </select>-->
 <a href="#"  onclick="ss_clear('card', _ss_ids_rate_term)"> 
  <i class="icon-remove"></i></a></td>
