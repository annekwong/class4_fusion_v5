<link type="text/css" rel="stylesheet" href="<?php echo $this->webroot?>css/ipcentrex.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->webroot?>css/list.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->webroot?>css/form.css">
<script type="text/javascript" 	src="<?php echo $this->webroot?>js/res.js"></script>
<div id="title">
	<h1><?php echo __('configu')?>&gt;&gt;<?php echo __('addvoipgateway')?>        
	</h1>
	<?php echo $form->create ('Gatewaygroup', array ('action' => 'add' ));?>
	<ul id="title-menu">
		<li>
			<input type="submit" value="<?php echo __('submit')?>" />
		</li>
		<li>
			<a class="link_back" href="<?php echo $this->webroot;?>gatewaygroups/view_egress"  style="width: 105px;">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt=""> <?php echo __('goback')?> 
			</a>
		</li>
	</ul>
</div>
<div id="container">  
	<div id="context_table_div">
		<div id="context_div">
			<div id="context_right_div" style="margin-left:5%">
				<div class="content_right_div">
					<div style="padding: 10px;">
						<div id="context_right_form_div">
							<div class="form_panel_Res">
								<input type="hidden" value="" name="inputRId">
									<div class="form_panel_Info">
										<div id="up_panel_info">
											<table>
												<tbody>
													<tr>
														<td>
																<?php echo __('clients')?> :		
																<?php echo $form->input('client_id',array('options'=>$c,'empty'=>'--请选择一个客户--','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
														</td>
														<td width="250px"><?php echo __('gatewayid')?> : 
														   <?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
														</td>
														<td >
																<?php echo __('gatewayname')?> :
																<?php echo $form->input('name',array('id'=>'name','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'15'));?>
														</td>
														<td>
															<input type="radio"  checked="checked" value="true" name="gress" id="ingress1" onchange='check_gress()'>
															<input type="hidden" value="true"    id="_ingress"  name="_ingress"><?php echo __('ingress')?>
														</td>
														<td>
															<input type="radio" value="true" name="gress" id="egress1"  onchange='check_gress()'>
															<input type="hidden" value="false" name="_egress"   id="_egress"><?php echo __('egress')?>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
		<div id="down_panel_info">
		

		
			<span class="spacing"><input type="checkbox" value="true" name="active" id="active1" onchange='check_active()'>
			<input type="hidden" value="false"  id="_active" name="_active">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="super" ><font class=" resource_add_Edit_style_0"><?php echo __('active')?></font></span></span> 
			
			
			<span class="spacing"  id="_direct_span"  style="display: none"><input type="checkbox" value="true" name="direct" id="direct1"  onchange='check_direct()'>
			<input type="hidden" value="false" name="_direct"   id="_direct">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="super"  id="direct"><font class=" resource_add_Edit_style_0"><?php echo __('mediapass')?></font></span></span>
		
		
		
			<span class="spacing"><input type="checkbox" value="true" name="LNP" id="LNP1"  onchange='check_LNP()'>
			<input type="hidden" value="false" name="_LNP"  id="_LNP">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="super"  id="LNP"><font class=" resource_add_Edit_style_0"><?php echo __('lrn',true);?></font></span></span><!--
			
			  <span class="spacing" ><input id="nat1" name="nat" type="checkbox" value="true"/><input type="hidden" name="_nat" value="on"/>&nbsp;&nbsp;&nbsp;<span id='nat' onclick="javascript:checkControl(this)" class="super"><font class=" resource_add_Edit_style_0">NAT</font></span></span>-->
    
     <span class="spacing"><input type="checkbox" value="true" name="t38" id="t381"  onchange='check_t38()'>
     <input type="hidden" value="false"  id="_t38"  name="_t38">&nbsp;&nbsp;&nbsp;<span class="super"  id="t38">
      <font class=" resource_add_Edit_style_0"><?php __('T.38')?></font></span></span>
      
      
      <span class="spacing"><input type="checkbox" value="true" name="lrn_block" id="lrn_block1" onchange='check_lrn()'>
      <input type="hidden" value="false" name="_lrn_block"   id="_lrn_block">&nbsp;&nbsp;&nbsp;<span class="super"  id="lrn_block">
      <font class=" resource_add_Edit_style_0"><?php echo __('Block LRN',true);?></font></span></span>
      
      <span class="spacing"  id="_tdm_span"  style="display: none"><input type="checkbox" value="true" name="tdm" id="tdm1" onchange='check_tdm()'>
      <input type="hidden" value="false" name="_tdm"   id="_tdm">&nbsp;&nbsp;&nbsp;<span class="super"  id="lrn_block">
      <font class=" resource_add_Edit_style_0"><?php echo __('TDM',true);?></font></span></span>
      
          <span class="spacing"  id='_pay_monthly_span' style="display: none">
          <input type="checkbox" value="true" name="pay_monthly" id="pay_monthly1" onchange='check_month()'>
      <input type="hidden" value="false" name="_pay_monthly"   id="_pay_monthly">&nbsp;&nbsp;&nbsp;<span class="super"  id="lrn_block">
      <font class=" resource_add_Edit_style_0"><?php echo __('monthlyplan')?></font></span></span>
      
       </div>

		</div>

		<div class="form_panel_capa">
				<table>
			<tbody>
			<tr>


				
				<td  class="res"><?php echo __('calllimit')?> :
												   		<?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
		</td>
				<td colspan="3"  class="res"><?php echo __('cps')?> :
				
				<?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
		</td>
			</tr>
		</tbody></table>
		
		


		</div>

                
		<div class="form_panel_Part"  id="rate_div" style="display:none">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4"><?php echo __('Rates')?></font></span></div>
		<div class=" resource_add_Edit_style_44">
								<?php echo $form->input('rate_table_id',array('options'=>$rate,
								'empty'=>'--请选择费率--','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		
		
		
</div>
		</div>
		
		
		
				<div class="form_panel_Part"  id="pass_through_div" style="display:none">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4">号码透传规则</font></span></div>
		<div class=" resource_add_Edit_style_44">
								<?php

$tmp=array('1'=>'透传','2'=>'不透传','3'=>'不透传禁显');	$se='2';							
echo $form->input('pass_through',array('options'=>$tmp, 'selected'=>$se,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		
		
		
</div>
		</div>

<?php //*************************Host配置*****************************************?>

		<div class="form_panel_Part">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4"><?php echo __('host_ip')?></font></span> 
		<span class=" resource_add_Edit_style_5">
		<a class="orange" onclick="javascript:createHost();" href="javascript:;"><?php echo __('add')?></a></span></div>
		<div class="form_panel_table">
		
		<table cellspacing="0" cellpadding="0" width="950px;">
		<thead>
			<tr>
				<td height="25px" class="leftAlign1 rightBorder"><?php echo __('IP/FQDN',true);?></td>
				<td class="leftAlign rightBorder"  style="width: 65px;"><?php echo __('netmask')?></td>
				<td class="leftAlign rightBorder"><?php echo __('port')?></td>
				<td class="leftAlign rightBorder"><?php echo __('calllimit')?> </td>
				<td class="leftAlign rightBorder">	<?php echo __('cpslimit')?> </td>
				<td class="leftAlign rightBorder"><?php echo __('timeprofile')?></td>
					<td class="leftAlign rightBorder">是否注册</td>
					<td class="leftAlign rightBorder">用户名</td>
						<td class="leftAlign rightBorder">密码</td>
				<td width="200px" class="leftAlign "></td>
			</tr>




					<tr>
	
						<td height="28px" class="backGround leftAlign1 rightBorder topBorder">
						<input type="text" class="reg_ip0"   rel='reg_ip0' size="25" id="ip0" name="ip[]"     style="width: 90px;"></td>
						<td class="backGround centerAlign rightBorder topBorder">
						
						<select class="netmask0" id="netmask0" name="netmask[]"   style="width: 90px;">
							<option value="32">32</option>
							<option value="31">31</option>
							<option value="30">30</option>
							<option value="29">29</option>
							<option value="28">28</option>
							<option value="27">27</option>
							<option value="26">26</option>
							<option value="25">25</option>
							<option value="24">24</option>

						</select></td>
						<td class="backGround centerAlign rightBorder topBorder">
						<input type="text" value="5060" size="8" class="port0" id="port0" name="port[]"  style="width: 90px;"></td>
						
						
						
						<td class="backGround centerAlign rightBorder topBorder">
						<input  type="text" maxlength="6" size="8" class="capa0" id="capa0" name="capa[]"  style="width: 90px;"></td>
						<td class="backGround centerAlign rightBorder topBorder">
						
						<input s type="text" maxlength="6" size="8" class="cps0" id="cps0" name="cps[]"  style="width: 90px;"></td>
						
						<td class="backGround centerAlign rightBorder topBorder">
						<select style="width:120px;" id="time_profile_id0" name="time_profile_id[]">
								<option value=""><?php echo __('select')?></option>
								<?php $loop = count($timeprofiles);
								for ($i = 0;$i<$loop;$i++) {
								?>
										<option value="<?php echo $timeprofiles[$i][0]['time_profile_id']?>"><?php echo $timeprofiles[$i][0]['name']?></option>
								<?php
								} 
								?>
						</select>
						</td>

	<td style="width: 63px;">
	    
       <input type="hidden"  id="need_register0"   value="false" name="need_register[]" />
        <input type="checkbox"  title="是否容许网关注册"   value="read" name="readable_check"  id="_need_register0"  
        onchange='check_register()'
        class="input in-checkbox"/>
	</td>
	

	<td ><input type="text"  size="8" class="port0" id="username0" name="username[]"  style="width: 100px;"></td>
						
		<td >	<input type="text"  size="8" class="port0" id="pass0" name="pass[]"  style="width: 100px;"></td>
						<td class="backGround leftAlign topBorder"><!--
						
						<a class=" resource_add_Edit_style_9" 
						onclick="deleteHostTr(this)" href="javascript:;"><?php echo __('del')?></a><input type="hidden" value="" id="counts1" name="ipcount">
						
						--></td>
					</tr>
					</thead>
							<tbody id="timeBody"></tbody>
					
					
					

			<tr>
				<td><a id="ipHost"></a></td>
			</tr>
		</table>
		</div>
		<div class=" resource_add_Edit_style_16"  id="_strate_div"  style="display: none;"><span class=" resource_add_Edit_style_17"><input type="radio" checked="checked" value="1" name="strategy" id="strategy1"></span><span>&nbsp;<?php echo __('topdown')?></span> <span class=" resource_add_Edit_style_18"><input type="radio" value="2" name="strategy" id="strategy2"></span><span>&nbsp;<?php echo __('roundrobin')?></span></div>
		</div>



<?php //*************************Host配置*****************************************?>










<?php //*************************Action配置*****************************************?>
		<div class="form_panel_Part">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4"><?php echo __('digitmapping')?></font></span> 
		<span class=" resource_add_Edit_style_5">
		<a class="orange" onclick="javascript:createAction();" href="javascript:;"><?php echo __('add')?></a></span></div>
		<div class="form_panel_table">
		
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
		
				<td height="25px" class="leftAlign1 rightBorder">时间段</td>
				<td class="leftAlign rightBorder"><?php echo __('matchprefix')?></td>
				<td class="leftAlign rightBorder"><?php echo __('action')?></td>
				<td class="leftAlign rightBorder"><?php echo __('addordelchars')?></td>
				<td width="200px" class="leftAlign "></td>
			</tr>

					<tr>

						<td height="28px" class="backGround centerAlign rightBorder topBorder">
						
														<select style="width:120px;" name="direct[]"  id="direct0">
								<option value=""><?php echo __('select')?></option>
								<?php

							
								$loop = count($timeprofiles);
								for ($i = 0;$i<$loop;$i++) {
								?>
										<option value="<?php echo $timeprofiles[$i][0]['time_profile_id']?>"><?php echo $timeprofiles[$i][0]['name']?></option>
								<?php
								} 
								?>
						</select>
						
</td>
						
						<td class="backGround centerAlign rightBorder topBorder">
						<input type="text" id="match0" size="15" name="match[]" class="match0"></td>
						<td class="backGround centerAlign rightBorder topBorder">
						<select class="action0"  id="action0"   name="action[]">
							<option value="1"><?php echo __('addprefix')?></option>
							<option value="3"><?php echo __('delprefix')?></option>
							<option value="2"><?php echo __('addsuffix')?></option>
							<option value="4"><?php echo __('delsuffix')?></option>
						</select></td>
						<td class="backGround centerAlign rightBorder topBorder">
						
						<input type="text" title="If Action is Delete, this value is the number of characters/digits to be deleted. If the Action is Add, this value contains the characters/digits to be added." 
						  id="digit0" size="20" class="digit0" name="digit[]"></td>
						  
						<td align="left" width="60px" class="backGround leftAlign  topBorder"><a class=" resource_add_Edit_style_24" href="javascript:;" onclick="deleteTd(this,false,null,'delDirId','dirlink')"><?php echo __('del')?></a></td>
					</tr>
					
							<tbody id="actiontimeBody"></tbody>
			<tr>
				<td><a id="dirlink"></a></td>
			</tr>
		</table>
		</div>
		</div>



<?php //*************************Action配置*****************************************?>










<?php //*************************Product配置*****************************************?>
		<div class="form_panel_Part"  id="_product_div">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4"><?php echo __('routetypes')?></font></span>
		 <span class=" resource_add_Edit_style_32">
		<!--   <a class="orange" onclick="javascript:createProduct();" href="javascript:;">
		 
<?php echo __('add')?></a>--></span></div>
		<div class="form_panel_table">
		<table cellspacing="0" cellpadding="0" width="100%">
		
		<tr>
							<td class="label label2"><?php echo __('routeconf')?></td>
							<td class="value value2">
					    						<select id="route_strategy_id" style="float:left;width:150px;" name="data[Gatewaygroup][route_strategy_id]">
					    								<option value=""><?php echo __('select')?></option>
					    								<?php
					    										$loop = count($routepolicy);
					    										for($i=0;$i<$loop;$i++) { 
					    								?>
					    												<option value="<?php echo $routepolicy[$i][0]['route_strategy_id']?>"><?php echo $routepolicy[$i][0]['name']?></option>
					    							<?php 
					    											}
					    									?>
					    						</select>
							</td>
					</tr>		
		
			<!--  <tr>
				
				<td height="25px" class="leftAlign1 rightBorder"><?php echo __('produname')?></td>
				<td class="leftAlign rightBorder"><?php echo __('matchprefix')?></td>
				<td width="200px" class="leftAlign "></td>
			</tr>

					<tr>
						<td height="28px" class="backGround leftAlign1 rightBorder topBorder">
						
							<?php echo $form->input('proId',array('id'=>'proId0','name'=>'proId[]','options'=>$p,
								'empty'=>'--请选择一个静态路由--','label'=>false, 'class' =>'proId0' ,'div'=>false,'type'=>'select'));?>
						
						
					</td>
						<td class="backGround leftAlign rightBorder topBorder">
						<input type="text" maxlength="8" size="10" class="prefix0" id="prefix0" name="prefix[]"></td>
						<td align="left" class="backGround leftAlign topBorder">
						<a class=" resource_add_Edit_style_36" href="javascript:;" onclick="deleteTd(this,false,null,'delPId','prolink')"><?php echo __('del')?></a></td>
					</tr>
					-->
						<tbody id="producttimeBody"></tbody>
					
					
			<tr>
				<td><a id="prolink"></a></td>
			</tr>
		</table>
		</div>
		</div>

<?php //*************************Product配置*****************************************?>










  
   <?php //*************************Codecs配置*****************************************?>             
                <div class="form_panel_Part">
                  <div class="form_panel_title">
                    <span><font class=" resource_add_Edit_style_4"><?php echo __('codecs')?></font></span>
                  </div>
       
                  <div id="code_panel_1" class="code_panel_1" style="height:150px;">
                  
                   <?php //*************************左边下拉框*****************************************?>  
				                  <div id="oriDiv" class="code_panel_2" style="margin-top:20px;margin-left:20%;width:200px;height:100px;">
				                  								<?php echo $form->input('select1',array('id'=>'select1','options'=>$d,'multiple' => true,
				                  								'style'=>'width: 200px; height: 100px;',
					'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		
				                  
				                  


				                    
				                  </div>
				                  
				                  
				                  <?php //*************************左右选项按钮*****************************************?>  
				                  <div class="code_panel_3" style="margin-left: 0px; padding-left: 5px;">
                                                      <input  style="width: 88px; height: 25px; margin-left: 20px;"    onclick="DoAdd();"  type="button"  value="<?php __('Add')?>"  />

				                     <br/>
				                        <br/>
				                   
				      <input  type="button"   style="width: 88px; height: 25px; margin-left: 20px;"  onclick="DoDel();"   value="<?php __('Delete')?>"  />
				                        
				                      
				                    
				                  </div>
				                  
				                  <?php //*************************右边选项卡*****************************************?>  
				                  <div class="code_panel_4">
				                  <div class="code_panel_5" id="targetDiv"style="margin-top:20px;width:200px;height:100px;">
				                  
				                          								<?php echo $form->input('select2',array('id'=>'select2','options'=>'','multiple' => true,
				                  								'style'=>'width: 200px; height: 100px;',
					'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
				                     
				                  </div>
				                  </div>
				                  
				                  
				                  
				    
                  </div>
            

                </div>
                
                
                
     <?php //*************************Codecs配置*****************************************?>                 
                
                    
		<div class="form_panel_Part"  id="_mapping_div">
		<div class="form_panel_title"><span><font class=" resource_add_Edit_style_4"><?php echo __('digitmapping')?></font></span></div>
		<div class=" resource_add_Edit_style_44">
								<?php echo $form->input('translation_id',array('options'=>$r,
								'empty'=>__('pleaseselectdigitmap',true),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		
		&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('timeprofile')?>:<select id="GatewaygroupTimeProfileId" name="data[Gatewaygroup][time_profile_id]">
				<option value=""><?php echo __('select')?></option>
				<?php
						$loop = count($timeprofiles);
						for ($i=0;$i<$loop;$i++) { 
				?>
							<option value="<?php echo $timeprofiles[$i][0]['time_profile_id']?>"><?php echo $timeprofiles[$i][0]['name']?></option>
				<?php
						} 
				?>
		</select>		
</div>
		</div>


		<div class=" resource_add_Edit_style_45">  </div>


		<div   id="submit_div"  align="right" ><input type="hidden" value="Submit" name="Submit">
		<div>
		<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
			<input type="button" value="<?php echo __('cancel')?>" onclick="winClose();" class="input in-button">
			<input type="button" value="<?php echo __('apply')?>" onclick="applyForm(this.form)" class="input in-button">
			
		
    </div>
		</div>


		<?php echo $form->end();?>
	</div>
 </div>
	</div>
</div>
</div>
</div>
	 </div>
	 </div>
 	
	 	<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>