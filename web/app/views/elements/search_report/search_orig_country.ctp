
<td class="align_right padding-r10"><?php echo __('Country',true);?></td>
<td>
<?php if(isset($country_arr) && !empty($country_arr)){?>
<select id="query-country" style="width:220px;" name="query[country]" class="input in-select">
                        <?php foreach ($country_arr as $key => $country): ?>
                            <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php } else {?>
<input type="text" id="query-country" value="" name="query[country]" class="input" style="width:220px;"/>
<?php }?>
 <!--
  <a href="#" onclick="ss_country( _ss_ids_country)"><img width="9" height="9"
				
				class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>
                -->
            <?php echo $this->element('search_report/ss_clear_input_select');?>
