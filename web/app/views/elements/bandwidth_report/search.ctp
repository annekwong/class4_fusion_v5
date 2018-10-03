
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
<div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" /><?php __('search')?></div>
<?php echo $this->element('search_report/search_js');?>

<?php echo $this->element('search_report/search_hide_input');?>
<table class="form">
	<tbody>
	
	
	
		<?php echo $this->element('report/form_period',array('group_time'=>true, 'gettype'=>'<select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
    <option selected="selected" value="web">Web</option>
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
		
		
        <td class="label"> <?php echo __('Switch IP',true);?> :</td>
        <td class="value">
            <?php if(count($server) > 1): ?>
            <?php echo $form->input('server_ip',
            array('options'=>$server,'empty'=>'    ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?><?php endif;?>
        </td>
      </tr>
      
      
      <tr> 
	  <td class="label"><?php __('ingress')?><span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table.')?><br><br><?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.')?></span>:</td>
    <td class="value">
      <?php echo $form->input('ingress_alias',array('options'=>$ingress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?>
    </td>
    <td class="in-out_bound">&nbsp;</td>
    <td class="label"> <?php __('egress')?>:</td>
    <td class="value">
		 		<?php echo $form->input('egress_alias',array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?>
    </td>
        <td class="in-out_bound">&nbsp;</td>

        <td class="label"><?php __('type')?>:</td>
        <td class="value">
                    <?php 
                            $type=array(''=>__('all',true),'orig'=>__('origination',true),'term'=>__('termination',true));
                            echo $form->input('report_type',
                            array('options'=>$type,'label'=>false ,'div'=>false,'type'=>'select'));
                     ?><?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
      </tr>

	<tr> 
	  <?php echo $this->element('search_report/search_orig_country'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_country'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		
       <td class="label"><?php __('currency')?>:</td>
        <td class="value">
            <?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'   ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
      </tr>
      
      
      <tr> 
	  <?php echo $this->element('search_report/search_orig_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		
       <td class="label"></td>
    <td class="value">
    </td>
           
        </td>
      </tr>

	<tr> 
	  <?php echo $this->element('search_report/search_orig_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		
       <td class="label"></td>
        <td class="value">
           
        </td>
      </tr>
<?php echo $this->element('report/group_by');?>

</tbody>
</table>
</fieldset>