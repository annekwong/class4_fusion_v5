<tr id="output-sub">
    <td></td>
       
    <td>
        <input type="checkbox"   <?php
         if(isset($_GET['query']['output_subgroups'])){
	        	if(!empty($_GET['query']['output_subgroups'])){ 
	        		echo "checked='checked'  value='true'"; 
	        	}else{
	        			echo "value='false'";
	        				}
         }else{
         	echo "value='false'";
         				}?>
        onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"
        id="query-output_subgroups" name="query[output_subgroups]" class="input in-checkbox">      
        <label for="query-output_subgroups"><?php __('Show subgroups')?></label>
    </td>
    <td style="width:1px;"></td>
    <td></td>
    <td>
        <input type="checkbox" id="query-output_subtotals"
        <?php
         if(isset($_GET['query']['output_subtotals'])){
	        	if(!empty($_GET['query']['output_subtotals'])){ 
	        		echo "checked='checked'  value='true'"; 
	        	}else{
	        			echo "value='false'";
	        				}
         }else{
         	echo "value='false'";
         				}?>
         name="query[output_subtotals]" class="input in-checkbox">       <?php echo __('Show subtotals',true);?>
    </td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
