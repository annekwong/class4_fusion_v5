
<fieldset class="query-box">
  <div class="search_title"><img src="<?php echo $this->webroot?>images/search_title_icon.png" />&nbsp;
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?> <?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/realcdrreports/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?> <?php echo $this->element('search_report/search_hide_input');?>
  <table  class="form" style="width:100%;">
    <tbody>
    	<tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      <tr >
        <?php 	echo $this->element('search_report/orig_carrier_select');?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/term_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <td valign="top" rowspan="7" colspan="2" style="padding-left: 10px; width:25%;" ><div align="left"><?php echo __('Show Fields',true);?>:</div>
          <?php
function mapfun($item) {
    return __($item, true);
}
$temp = array_map('mapfun', $cdr_field);
$temp['term_country'] = "term_country";
asort($temp,SORT_STRING);

							echo $form->select('Cdr.field', $temp , $show_field_array,array('id'=>'query-fields',  'style'=>'width: 99%; height: 250px;', 'name'=>'query[fields]',
							'type' => 'select', 'multiple' => true),false);
	?>
        
        
        </td>
      </tr>
      <tr > 
      <td class="label"><?php echo __('Ingress',true);?>:</td>
        <td class="value"><?php 
   			echo $form->input('ingress_alias',array('options'=>$ingress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
   				?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      <td class="in-out_bound">&nbsp;</td>
      <td class="label"> <?php echo __('Egress',true);?>:</td>
        <td class="value"><?php echo $form->input('egress_alias',
		 		array('options'=>$egress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      <td class="in-out_bound">&nbsp;</td>

      </tr>
      
      <?php 
      /*
      <tr>
      <?php echo $this->element('search_report/search_orig_country');?>
      <td class="in-out_bound">&nbsp;</td>
      <?php echo $this->element('search_report/search_term_country');?>
      <td class="in-out_bound">&nbsp;</td>
		
      </tr>
      <tr>
      <?php echo $this->element('search_report/search_orig_code_name'); ?>
      <td class="in-out_bound">&nbsp;</td>
      <?php echo $this->element('search_report/search_term_code_name');?>
      <td class="in-out_bound">&nbsp;</td>
      </tr>
       <tr>
      <?php echo $this->element('search_report/search_orig_code'); ?>
      <td class="in-out_bound">&nbsp;</td>
      <?php echo $this->element('search_report/search_term_code');?>
      <td class="in-out_bound">&nbsp;</td>
      
      </tr>
       * 
       * 
       */?>
      <tr>
          <td class="label">
              <?php echo __('Ingress Host',true);?>:
          </td>
          <td class="value">
              <input type="text" name="ingress_host" /><?php echo $this->element('search_report/ss_clear_input_select');?>
          </td>
          <td class="in-out_bound">&nbsp;</td>
          <td class="label">
              <?php __('Egress Host')?>:
          </td>
          <td class="value">
              <input type="text" name="egress_host" /><?php echo $this->element('search_report/ss_clear_input_select');?>
          </td>
          <td class="in-out_bound">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"> <?php echo __('Class4-server',true);?>:</td>
        <td class="value">
              <?php if(count($server) > 1): ?>
              <?php
              
		 			echo $form->input('server_ip',
		 			array('options'=>$server,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
		 		?><?php echo $this->element('search_report/ss_clear_input_select');?>
            
              <?php endif;?>
        </td>
                <td class="in-out_bound">&nbsp;</td>
        <td class="label"><span><?php echo __('output',true);?>:</span></td>
        <td class="value"><select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
            <option value="web"><?php __('Web')?></option>
            <?php  if ($_SESSION['role_menu']['Statistics']['realcdrreports']['model_x']) {?>
            <option value="csv"><?php __('Excel CSV')?></option>
            <option value="xls"><?php __('Excel XLS')?></option>
            <?php }?>
            <!--<option value="delayed">Delayed CSV</option>
	   -->
          </select></td>
          <td class="in-out_bound">&nbsp;</td>
          </tr>
          <tr>
            <td class="label"><?php echo __('dnis',true);?> :</td>
            <td class="value"><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="label"><?php echo __('ani',true);?>:</td>
            <td class="value"><input type="text" id="query-src_number" value="" name="query[src_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
      </tr>
      <tr>
          <td class="label"></td>
            <td class="value"></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="label"><?php echo __('Currency',true);?>:</td>
            <td class="value">
                <select id="currency" name="currency">
                    <option></option>
                    <?php foreach($currency as $cur): ?>
                    <option value="<?php echo $cur[0]['currency_id']; ?>" <?php if(isset($_GET['currency']) && $_GET['currency'] == $cur[0]['currency_id']) echo 'selected' ?>><?php echo $cur[0]['code']; ?></option>
                    <?php endforeach;?>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
      </tr>
      
    </tbody>
  </table>
  <div id="form_footer"><input type="button" value="<?php echo __('Refresh',true);?>"   onclick="update_report();" class="input in-submit"/>&nbsp;&nbsp;<input type="submit" value="<?php echo __('query',true);?>" class="input in-submit"/></div>
  <?php echo $form->end();?>
</fieldset>
