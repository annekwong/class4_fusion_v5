
<td><?php echo __('country',true);?>:</td>
<td><input type="text" id="query-country"
				ondblclick="ss_country( _ss_ids_country)"
				 value="" name="query[country]"
				class="input in-text"/>
  <!--
  <a href="#"onclick="ss_country( _ss_ids_country)"><img width="9" height="9" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>
  -->
  <a href="#"onclick="ss_clear('card', _ss_ids_country)"><img
				width="9" height="9"
				
				class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"/></a></td>
