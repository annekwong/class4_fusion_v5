
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?> <?php echo $this->element('search_report/search_hide_input');?>
  <table class="form" style="width:100%">

    <tbody>
      <?php echo $this->element('report/form_period',array('group_time'=>true))?>
      
      
      <tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      <tr> <?php echo $this->element('search_report/orig_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/term_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label" align="right"> <?php __('Switch IP')?> :</td>
        <td class="value"  align="left"><?php echo $form->input('server_ip',
 		array('options'=>$server,'empty'=>'    ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      </tr>
      <tr>
        <td class="label" align="right"><?php __('ingress')?>
          : <span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br>
          <br>
          <?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span></td>
        <td class="value"  align="left"><?php echo $form->input('ingress_alias',array('options'=>$ingress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label" align="right"><?php __('egress')?>
          :</td>
        <td class="value"  align="left"><?php echo $form->input('egress_alias',array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <?php  if ($_SESSION['role_menu']['Statistics']['clientsummarystatis']['model_x']) {?>
        <td class="label"><?php __('output')?>
          :</td>
        <td class="value"><select id="query-output" onChange="repaintOutput();" name="query[output]" class="input in-select">
            <option selected="selected" value="web">
            <?php __('web')?>
            </option>
            <option value="csv"><?php __('Excel CSV')?></option>
            <option value="xls"><?php __('Excel XLS')?></option>
            <!--<option value="delayed">Delayed CSV</option>
    -->
          </select></td>
        <?php }?>
      </tr>
      <tr> <?php echo $this->element('search_report/search_orig_country')?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_country')?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"></td>
        <td class="value"></td>
        
        <!--
        <td class="label" align="right"><?php __('type')?>
          :</td>
        <td class="value"  align="left"><?php 
				 		$type=array(''=>__('all',true),'orig'=>__('origination',true),'term'=>__('termination',true));
				 		echo $form->input('report_type',
				 		array('options'=>$type,'label'=>false ,'div'=>false,'type'=>'select'));
				 ?></td>
      --> 
      </tr>
      <tr> <?php echo $this->element('search_report/search_orig_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"></td>
        <td class="value"></td>
      </tr>
      <tr> <?php echo $this->element('search_report/search_orig_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
      </tr>
      
      <!--
      <tr>
      
       <td class="label" align="right"> Code Deck<span class="tooltip" id="ht-100001-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
          <br>
          If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
        <td class="value"><select name="code_deck" id="CdrCodeDeck">
            <option value=""> </option>
            <option value="5">CN</option>
            <option value="6">afd</option>
            <option value="7">fdafda</option>
            <option value="8">43432</option>
            <option value="9">1345677</option>
            <option value="2">ALL</option>
            <option value="13">test</option>
            <option value="14">china</option>
            <option value="16">sz</option>
            <option value="17">hk</option>
            <option value="21">jp</option>
            <option value="22">11</option>
            <option value="24">bbbbb</option>
            <option value="26">mmmmm</option>
            <option value="15">19</option>
            <option value="18">chinaDX</option>
            <option value="28">dddd</option>
            <option value="29">jjjjjjjjjj</option>
            <option value="27">sdgwgwe</option>
            <option value="55">ffg</option>
            <option value="56">6666</option>
            <option value="57">888</option>
            <option value="58">999</option>
            <option value="59">7876</option>
            <option value="60">9879</option>
          </select></td>
          
          
          
      </tr>
      --> 
      
      <!--
      <tr>
      <td class="label" align="right"><?php __('currency')?></td>
        <td class="value"  align="left"><?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'   ','label'=>false ,'div'=>false,'type'=>'select'));?></td>
      </tr>
      -->
     
      
      <?php echo $this->element('report/group_by');?>
      <?php //echo $this->element('search_report/output_sub');?>
    </tbody>
  </table>
</fieldset>
