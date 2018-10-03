
<td><?php __('code_name')?>:</td>
<td><input type="text" id="query-code_name_term"
				ondblclick="ss_code_term(undefined, _ss_ids_code_name_term)"
				 value="" name="query[code_name_term]"
				class="input in-text">
 <!--
  <a href="#" onclick="ss_code_term(undefined, _ss_ids_code_name_term)"><img width="9" height="9"
				
				class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>
               -->
  <a href="#" onclick="ss_clear('card', _ss_ids_code_name_term)"> <img
				width="9" height="9"
				
				class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"/></a></td>
<td>
  <?php __('code')?>
  :</td>
<td>
<input type="text" id="query-code_term" ondblclick="ss_code_term(undefined, _ss_ids_code_term)"
 value="" name="query[code_term]" class="input in-text"/>
<!--
  <a href="#"  onclick="ss_code_term(undefined, _ss_ids_code_term)" ><img width="9" height="9"class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/></a>
  -->
  <a href="#"  onclick="ss_clear('card', _ss_ids_code_term)"> 
  <img width="9" height="9" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"/></a></td>
