
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js')?> <?php echo $this->element('search_report/search_js_show')?> <?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/locationreports/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?> <?php echo $this->element('search_report/search_hide_input')?>
  <table style="width:100%" class="form">
    <tbody>
        <?php echo $this->element('report/form_period',array('group_time'=>true, 'gettype'=>'<select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
        <option selected="selected" value="web">
            web
        </option>
        <option value="csv">Excel CSV</option>
        <option value="xls">Excel XLS</option>
    </select>'))?>
      
      <tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      <tr> 
      <?php echo $this->element('search_report/orig_carrier_select'); ?> 
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/term_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"> Class4-server:</td>
        <td class="value"><?php if(count($server) > 1): ?><?php echo $form->input('server_ip',array('options'=>$server,'empty'=>'    ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?><?php endif;?></td>
      </tr>
      
      <tr> 
      <td class="label"><?php __('ingress')?>
          :</td>
        <td class="value"><?php 
   		echo $form->input('ingress_alias',
				array('options'=>$ingress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select','onchange'=>'getTechPrefix(this);'));
			?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php __('egress')?></td>
        <td class="value"><?php echo $form->input('egress_alias',array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        
        
        <?php  if ($_SESSION['role_menu']['Statistics']['locationreports']['model_x']) {?>
     
      
        <td class="label">
         </td>
        <td class="value"></td>
        
      </tr>
      <?php }else{?>
      <td class="label">&nbsp;</td>
        <td class="value">&nbsp;</td>
        <?php }?>
      
      </tr>
      
      
      
      
       <tr>
          
        <td lass="label">Tech Prefix</td>
          <td class="value">
              <select name ="route_prefix" id="CdrRoutePrefix">
                   <option value="">
                       <?php __('All')?>
                   </option>
                   <?php
                        if(!empty($tech_perfix)){
                            foreach($tech_perfix as $te){
                                if($_GET['route_prefix'] == $te[0]['tech_prefix']){
                                    echo "<option selected value='".$te[0]['tech_prefix']."'>".$te[0]['tech_prefix']."</option>";
                                }else{
                                    echo "<option value='".$te[0]['tech_prefix']."'>".$te[0]['tech_prefix']."</option>";
                                }
                            }
                        }
                       
                   ?>   
              </select>
              
                    
          </td>
          <td class="in-out_bound">&nbsp;</td>
          <td class="label"></td>
          <td class="value">&nbsp;</td>
          <td class="in-out_bound">&nbsp;</td>
      </tr>
      
      
      <tr> 
      <?php echo $this->element('search_report/search_orig_country')?>
        <td class="in-out_bound">&nbsp;</td>
       <?php echo $this->element('search_report/search_term_country')?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value">&nbsp;</td>
      </tr>
      
      
      <tr> 
      <?php echo $this->element('search_report/search_orig_code_name');?>
        <td class="in-out_bound">&nbsp;</td>
       <?php echo $this->element('search_report/search_term_code_name');?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value">&nbsp;</td>
      </tr>
      
      
      <tr> 
      <?php echo $this->element('search_report/search_orig_code');?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code');?>
        <td class="in-out_bound">&nbsp;</td>
        
        
        <td class="label">&nbsp;</td>
        <td class="value">&nbsp;</td>
      </tr>

     
     
      
      <!--
      <tr>

  			    <td class="label">Duration:</td>
			    <td class="value"><select id="query-duration" name="query[duration]" class="input in-select">
			    <option    value=""  selected="selected">all</option>
			    <option  value="nonzero">non-zero</option><option value="zero">zero</option></select></td>
</tr>
-->
     
      
      <?php echo $this->element('report/group_by');?>
      <!--
      <tr>
        <td class="label">Group By #1:</td>
        <td class="value" ><?php
					$groupby=array(
					
				'orig_country'=>'ORIG Country'  ,
					'orig_client_name'=>'ORIG Carrier	',
					'ingress_alias'=>'Ingress Trunk',
		
					'orig_code_name'=>'ORIG Code Name',
					'orig_code'=>'ORIG Code',
					'ingress_host'=>'Ingress Host',
					''=>'',
					'term_client_name'=>'TERM Carrier	',
					'egress_alias'=>'Egress Trunk',
					'term_code_name'=>'TERM Code Name',
					'term_code'=>'TERM Code',
					'egress_host'=>'Egress  Host',
				'termination_source_host_name'=>'Switch IP '
					
					);
    			echo $form->input('group_by1',array('name'=>'group_by[0]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
        <td class="label">Group By #2:</td>
        <td class="value"><?php
    		echo $form->input('group_by2',array('name'=>'group_by[1]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
        <td class="label" style="width:10%">Group By #3:</td>
        <td class="value" style="width:20%"><?php
    		echo $form->input('group_by3',array('name'=>'group_by[2]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
      </tr>
      <tr>
        <td class="label">Group By #4:</td>
        <td class="value"><?php
    		echo $form->input('group_by4',
 				array('name'=>'group_by[3]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
 			?></td>
        <td class="label">Group By #5:</td>
        <td class="value"><?php
	    		echo $form->input('group_by5',array('name'=>'group_by[4]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
	    		?></td>
        <td class="label">Group By #6:</td>
        <td class="value"><?php
    		echo $form->input('group_by6',array('name'=>'group_by[5]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
    		?></td>
      </tr>
      -->
      <?php echo $this->element('search_report/output_sub');?>
    </tbody>
  </table>
  <?php echo $form->end();?>
</fieldset>


<script type="text/javascript">
    function getTechPrefix(obj){
        $("#CdrRoutePrefix").empty();
        $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if($(obj).val() != '0'){
            $.post("<?php echo $this->webroot?>cdrreports/getTechPerfix", {ingId:$(obj).val()}, 
            function(data){
                
                $.each(eval(data),
                    function (index,content){
                       $("#CdrRoutePrefix").append("<option value='"+content[0]['tech_prefix']+"'>"+content[0]['tech_prefix']+"</option>");
                    }
                );
            });
            
        }
        
        
        
    }
</script>

