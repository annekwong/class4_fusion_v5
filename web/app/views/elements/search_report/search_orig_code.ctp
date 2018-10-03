
<td class="align_right padding-r10">
  <?php __ ('Code');?>
  </td>
<td><input type="text" id="query-code"
           value=""	name="query[code]" class="input" style="width: 220px"/>
  <!--
  <a href="#" onclick="ss_code(undefined, _ss_ids_code)"><img width="9" height="9"
	 class="img-button"
	src="<?php
	echo $this->webroot?>images/search-small.png"/></a>-->
    <?php echo $this->element('search_report/ss_clear_input_select');?></td>
 