<tr id="group_by">
        <td colspan="8" ><div class="group_by">
          <?php echo __('Group By',true);?><img src="<?php echo $this->webroot?>images/bullet_toggle_plus.png"></div>
          </td>
          
      </tr>
      <tr class="group_by_list">
        <td class="label"><?php echo __('Group By',true);?> #1:</td>
        <td class="value"colspan="2"><?php
    				$groupby=array(
					'orig_client_name'=>'ORIG Carrier',
					'ingress_alias'=>'Ingress Trunk',
		
					'orig_code_name'=>'ORIG Code Name',
					'orig_code'=>'ORIG Code',
    			'orig_country'=>'ORIG Country',  
    				'orig_rate'=>'ORIG Rate',  
					'ingress_host'=>'Ingress Host',
					''=>'',
    				
    				
					'term_client_name'=>'TERM Carrier',
					'egress_alias'=>'Egress Trunk',
					'term_code_name'=>'TERM Code Name',
					'term_code'=>'TERM Code',
    				'term_country'=>'TERM Country',
    				'term_rate'=>'TERM Rate',    
					'egress_host'=>'Egress  Host',
				'termination_source_host_name'=>'Switch IP '
					
					);
    	
    	

    		echo $form->input('group_by1',
 				array('name'=>'group_by[0]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
 			?></td>
        <td class="label"><?php echo __('Group By',true);?> #2:</td>
        <td class="value"colspan="2"><?php
    			echo $form->input('group_by2',
 					array('name'=>'group_by[1]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
 				?></td>
        <td class="label"><?php echo __('Group By',true);?> #3:</td>
        <td class="value"><?php
    			echo $form->input('group_by3',array('name'=>'group_by[2]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    			?></td>
      </tr>
      <tr class="group_by_list">
        <td class="label"><?php echo __('Group By',true);?> #4:</td>
        <td class="value"colspan="2"><?php
	    		echo $form->input('group_by4',array('name'=>'group_by[3]','options'=>$groupby,'empty'=>'  '      ,   'label'=>false ,'div'=>false,'type'=>'select'));
	    		?></td>
        <td class="label"><?php echo __('Group By',true);?> #5:</td>
        <td class="value" colspan="2"><?php
    		echo $form->input('group_by5',array('name'=>'group_by[4]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
        <td class="label"><?php echo __('Group By',true);?> #6:</td>
        <td class="value"><?php
    		echo $form->input('group_by6',array('name'=>'group_by[5]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
      </tr>
      
      
      <script type="text/javascript">
	  //这个已经写到bb-functions.js里面
	  /*
$(document).ready(function(){
	var group_by = $('#group_by');
	var group_by_list = $('.group_by_list');
	group_by_list.hide();
	group_by.css('cursor','pointer').toggle(function(){
		$('img', $(this)).attr('src', '<?php echo $this->webroot?>images/bullet_toggle_minus.png');
		group_by_list.show();
	}, function() {
		$('img', $(this)).attr('src', '<?php echo $this->webroot?>images/bullet_toggle_plus.png');
		group_by_list.hide();
	});
	
}); 
*/
</script>