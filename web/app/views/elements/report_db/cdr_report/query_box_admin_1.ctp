<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('search')?></h4>
    <div class="pull-right" title="Advance">
        <a id="advance_btn" class="btn" href="###">
            <i class="icon-long-arrow-down"></i> 
        </a>
    </div>
    <div class="clearfix"></div>
  <?php echo $this->element('search_report/search_js');?>
  <?php 	echo $this->element('search_report/search_js_show');?>
  <?php
$url="/".$this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports_db/summary_reports/spam/';}else{$url='/cdrreports_db/summary_reports/';}
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
  <?php echo $appCommon->show_page_hidden();?> <?php echo $this->element('search_report/search_hide_input');?>
  <table class="form" style="width: 100%">
    <tbody>
        <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
                                                                                                  onchange="repaintOutput();" name="query[output]"
                                                                                                  class="input in-select">
        <option value="web">Web</option>
        <option value="csv">Excel CSV</option>
        <option value="xls">Excel XLS</option>
                
    </select>'))?>
    </tbody>
  </table>
  <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
  <table class="form" style="width: 100%">
    <tbody>
        <tr>
            <?php echo $this->element('search_report/orig_carrier_select');?>
            <td class="in-out_bound">&nbsp;</td>

            <td class="align_right padding-r10"><?php echo __('Ingress',true);?></td>
            <td >
            <?php 
            echo $form->input('ingress_alias',
            array('options'=>$ingress,'label'=>false ,'div'=>false,'type'=>'select'));
            ?>
            <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="align_right padding-r10"><?php echo __('Class4-server',true);?></td>
        <td ><?php 
					 				echo $form->input('server_ip',array('options'=>$server,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
					 		?></td>
        </tr>
        
        <tr>
            <td class="align_right padding-r10"><?php echo __('dnis',true);?> </td>
            <td ><input type="text" id="query-dst_number" value=""
                        name="query[dst_number]" class="input in-text" style="width: 220px"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="align_right padding-r10"><?php echo __('ani',true);?></td>
            <td ><input type="text" id="query-src_number" value=""
                        name="query[src_number]" class="input in-text" style="width: 220px;">
                                    <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <?php  if ($_SESSION['role_menu']['Statistics']['cdrreports_db:spam']['model_x']) {?>
        <td></td>
        <td ></td>
          <?php }?>
         </tr>
         
         <tr>
            <td class="align_right padding-r10"><?php echo __('LRN number vendor',true);?> </td>
            <td >
                <select name="query[lrn_number_vendor]" id="query-number-vendor" class="input in-select">
                    <option value=""></option>
                    <option value="1"><?php __('Client')?></option>
                    <option value="2"><?php __('NPDB')?></option>
                    <option value="3"><?php __('Cache')?></option>
                </select><?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
             <td class="in-out_bound">&nbsp;</td>
            <td class="align_right padding-r10"><?php echo __('LRN number',true);?> </td>
            <td ><input type="text" id="query-number" value=""
                        name="query[lrn_number]" class="input in-text" style="width: 220px;"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                                     <td class="in-out_bound">&nbsp;</td>
            <td></td>
            <td ></td>
         </tr>

      <!--<tr>
        <td colspan="8">
            <table style="width: 100%;">
            <tr  class="period-block">
              <td  style=" width:100px;text-align: left;"><span  style="color: #568ABC; font-size: 1.15em;font-weight: bold;"><?php echo __('Suppress Filter',true);?> </span></td>
              <td style="width:auto;"  colspan="5"></td>
              
            </tr>
             <tr> 
                <td >&nbsp;</td>
              <td style="width:350px;"><?php echo $this->element("search_report/checkbox_orig_host")?></td>
             <td ></td>
              <td></td>
            </tr>
          </table>
        </td>
      </tr>-->
    </tbody>
  </table>
  </div>
</fieldset>
<?php echo $form->end();?>