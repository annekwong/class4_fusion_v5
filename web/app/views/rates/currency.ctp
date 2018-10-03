    <script language="JavaScript" type="text/javascript">
    function generate_tmp(){
       var tab = document.getElementById("ratetab");
       var inputs = tab.getElementsByTagName("input");
       var ids = '';
       var count = 0;
       for (var i = 0;i<inputs.length;i++) {
           if (inputs[i].type=='checkbox' && inputs[i].checked == true){
               ids += inputs[i].value+",";
               count ++;
           }
       }
		if (count <=0){
			jGrowl_to_notyfy('Please select at least one templates rates',{theme:'jmsg-alert'});
			return;
		}
		ids = ids.substring(0,ids.lastIndexOf(","));
		document.getElementById("g_ids").value = ids;
		if (count  ==1){
			cover('copyratetmp');document.getElementById('tmpid').value=ids;
			return;
		}
		cover('generatert');
   	}
   function generate_rt(){
        	var rt_n = document.getElementById("pname_g").value;
        	if (!rt_n){jGrowl_to_notyfy('Please enter the name of the template new rates',{theme:'jmsg-alert'});return;}
					var cur = document.getElementById("g_currency").value;
        	var r_url = "<?php echo $this->webroot?>rates/generate_tmp?rt_n="+rt_n+"&cur="+cur+"&way="+$('input[name=way]:checked').val()+"&ids="+$('#g_ids').val();
        	location = r_url;
        }
    			function copy_rate(){
        	var v = document.getElementById("tmpid").value;
        	var n = document.getElementById("pname").value;
        	if (!n)
            jGrowl_to_notyfy('<?php echo __('enterratename')?>',{theme:'jmsg-alert'});
        	else location = '<?php echo $this->webroot?>rates/copy_tmp?id='+v+'&name='+n;
        			}

    			function add_tmp(){
        	var codecs = "<?php echo $codecs?>";
        	var currs = "<?php echo $currs?>";
        	/* var jurisdiction_countries = <?php echo json_encode($appRate->format_jurisdiction_countries_for_options($jurisdiction_countries))?>; */
        	var addition = document.createElement("option");
        	addition.innerHTML="<?php  echo __('select')?>";
        	addition.value = "";
    				var columns = [
    		        			   {hidden:true},
    		        			   {hidden:true},
    		        			   {tag:'input',className:'height20 width100 in-text check_strNum'},
    		        	
    		        			   {tag:'select',addtion:addition.cloneNode(true),className:'width100 in-select',options:eval(codecs)},
    		        			   {tag:'input',className:'height20 width100 in-select',defaultV:'<?php echo $this->params['pass'][0] ?>'},
    		        			   {hidden:true},
    		        			  /*{tag:'select',className:'height20 width100 in-select',options:eval(jurisdiction_countries)}, */
    		        			   {hidden:true},
    		        			   {tag:'a',innerHTML:"<a style='float:left;margin-left:35px;' href='#' title='Save' onClick='save_rate(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a style='float:left;margin-left:20px;' title='Delete' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"}
    		   		       								];
    		 			var tr=createRow('ratetab',columns);
    		 			jQuery(tr).find('td:nth-child(5) input').hide();
    		 			jQuery(tr).find('input.check_strNum').xkeyvalidate({type:'strNum'}).attr('maxLength','16');
        			}

    			function save_rate(tr){
        			var curr_url='<?php echo $_SESSION['curr_url'];?>';
        			var name = tr.cells[2].getElementsByTagName("input")[0].value;
        			if (!name)
            			jGrowl_to_notyfy('<?php echo __('enterratename')?>',{theme:'jmsg-alert'});
        			else {
            			var c = tr.cells[3].getElementsByTagName("select")[0].value;
            			var cu = tr.cells[4].getElementsByTagName("input")[0].value;
            			
            			if (!cu){
            				jGrowl_to_notyfy('<?php echo __('nocurrency')?>',{theme:'jmsg-alert'});
            				return;
                		}
						url = "<?php echo $this->webroot ?>rates/add_curr_tmp?n="+name+"&c="+c+"&cu="+cu+"";
              			location=url ;
			        }
    			}

    			function edit_rate(tr){
    	      var codecs = "<?php echo $codecs?>";
    	      var currs = "<?php echo $currs?>";
        					//修改名字
        		var n_cell = tr.cells[2];
        		var n_in = document.createElement("input");
        		n_in.className = "height20 width100";
        		n_in.value = n_cell.innerHTML.trim();
        		n_cell.innerHTML = "";
        		n_cell.appendChild(n_in);
        		var s = document.createElement("select");
                 		//号码组
			      var cod_cel = tr.cells[3];
							var cod = s.cloneNode(true);
							cod.options.length = 0;
							var r = eval(codecs);
			      var l = r.length;
			      var o = document.createElement("option");
			      o.innerHTML = "<?php echo __('select')?>";
			      o.value = "";
			      cod.appendChild(o);
			      for (var i = 0;i<l;i++) {
			        var op = document.createElement("option");
			        op.value = r[i][0].code_deck_id;
			        op.innerHTML = r[i][0].name;
			        cod.appendChild(op);
			        if (cod_cel.innerHTML.trim()==r[i][0].name.trim())op.selected=true;
			           		}
			      cod_cel.innerHTML = "";
			      cod_cel.appendChild(cod);

			      //币率
			      var cur_cel = tr.cells[4];
			      var cur = s.cloneNode(true);
			      cur.options.length = 0;
							var r = eval(currs);
			      var l = r.length;
			      for (var i = 0;i<l;i++) {
			        var op = document.createElement("option");
			        op.value = r[i][0].currency_id;
			        op.innerHTML = r[i][0].code;
			        cur.appendChild(op);
			        if (cur_cel.innerHTML.trim()==r[i][0].code.trim())op.selected=true;
			           		}
	         cur_cel.innerHTML = "";
	         cur_cel.appendChild(cur);
	         var cancel = tr.cells[7].getElementsByTagName("a")[1].cloneNode(true);
	          cancel.title = "Cancel";
	          cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
	          cancel.onclick = function(){location.reload();}
	          tr.cells[7].appendChild(cancel);
	                       
	          tr.cells[7].getElementsByTagName("img")[1].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
	          tr.cells[7].getElementsByTagName("img")[1].title="Save";
		         jQuery(tr).find('a:nth-child(2)').attr('onclick','');
	          jQuery(tr).find('a:nth-child(2)').click(
	        		  function(){
				          var id = tr.cells[1].innerHTML.trim();
				          if (!n_in.value)
				         	 jGrowl_to_notyfy('Please enter the name of the template new rates',{theme:'jmsg-alert'});
				          else{  
					          url = "<?php echo $this->webroot?>rates/update_tmp?n="+n_in.value+"&c="+cod.value+"&cu="+cur.value+"&id="+id;
					          location = url;
				          					}
	         					 	}
	    	          			);
	          //增加校验
	    jQuery(tr).find('input').xkeyvalidate({type:'strNum'}).attr('maxLength','16');
	          } 
    </script>
<div id="title">
  <h1><?php __('System')?>&gt;&gt; <?php echo __('rateTable')?>  <font  class="editname" title="Name"><?php echo array_keys_value($code_name,'0.0.code')==''?'':"[".array_keys_value($code_name,'0.0.code')."]"?> </font></h1>
  <?php if($_SESSION['login_type']=='1'){?>
  <ul id="title-search">
    <li>
    	<form>
    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
    	</form>
    </li>
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" class=""></li>
  </ul>
   <ul id="title-menu">
   <?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?>
    <li><a class="link_btn" href="javascript:void(0)" onclick="add_tmp();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    <li><a class="link_btn" href="javascript:void(0)" onclick="generate_tmp();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/menuIcon_025.gif"> <?php echo __('generatert')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>rates/delete_all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('ratetab','<?php echo $this->webroot?>rates/delete_selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
    <?php }?>
    <?php
    	if (isset($curr_search)) { 
    ?>
    <li>
    	<?php echo $this->element("xback",Array('backUrl'=>'currs/index'))?>
    </li>
    <?php }?>
  </ul>
  <?php }?>
</div>

<dl id="copyratetmp" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('copyratetmp')?></dd>
	<dd style="margin-top:10px;margin-left:5%;"><?php echo __('ratetmpname')?>:<input class="input in-text" id="pname"/></dd>
	<dd><input style="display:none" id="tmpid"/></dd>
	<dd style="margin-left: 20%; width:200px;height:auto; ">
		<input type="button" onclick="copy_rate();" value="<?php echo __('submit')?>" class="input in-button" >
		<input type="button" onclick="closeCover('copyratetmp');" value="<?php echo __('cancel')?>" class="input in-button"  >
	</dd>
</dl>

<dl id="generatert" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:150px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('generatert')?></dd>
	<dd style="margin-top:10px;"><?php echo __('ratetmpname')?>:<input class="input in-text" id="pname_g"/>
	</dd>
	<dd style="margin-top:10px;"><?php echo __('currency')?>:
	<select style="float:right" id="g_currency" name="g_currency">
						<?php
							for ($i=0;$i<count($currs_s);$i++) { 
						?>
								<option value="<?php echo $currs_s[$i][0]['currency_id']?>"><?php echo $currs_s[$i][0]['code']?></option>
						<?php
							} 
						?>
				</select>
	</dd>
	<dd style="margin-top:10px;text-align:center">
		<input type="radio" name="way" checked value="1"/>用最低值
		<input type="radio" name="way" value="2"/>用最高值
		<input type="radio" name="way" value="3"/>用平均值
	</dd>
	<dd><input id="g_ids" style="display:none"/></dd>
	<dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="generate_rt();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('generatert');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>

<div id="container">
<fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
<form method="get">
<input type="hidden" name="advsearch" value="1"/>
<table  style="width:auto;">
<tbody>
	<tr>
			<td style="display: none;">
				<label style="padding-top:3px;"><?php echo __('ratetmpname')?>:</label>
				<input type="text" name="name" class="input in-text"/>
			</td>
			<td>
				<label style="padding-top:3px;"><?php echo __('codedecks')?>:</label>
				<select id="search_code_deck" class="select in-select" name="search_code_deck">
						<option value=""><?php echo __('select')?></option>
						<?php
							for ($i=0;$i<count($codecs_s);$i++) { 
						?>
								<option value="<?php echo $codecs_s[$i][0]['code_deck_id']?>"><?php echo $codecs_s[$i][0]['name']?></option>
						<?php
							} 
						?>
				</select>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('currency')?>:</label>
				<select id="search_currency" name="search_currency" class="select in-select">
						<option value=""><?php echo __('select')?></option>
						<?php
							for ($i=0;$i<count($currs_s);$i++) { 
						?>
								<option value="<?php echo $currs_s[$i][0]['currency_id']?>"><?php echo $currs_s[$i][0]['code']?>
								
								
								</option>
						<?php
						 
							} 
						?>
				</select>
			</td>
			<td></td>
			<td class="buttons"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
		</tr>
</tbody></table>
</form>
<?php
	if (!empty($searchForm)) {
		$d = array_keys($searchForm);
		foreach($d as $k) {?>
		    echo '';
			<script type="text/javascript">
				if (document.getElementById("<?php echo $k?>")){
					
					document.getElementById("<?php echo $k?>").value = "<?php echo $searchForm[$k]?>";
				}
			</script>
<?php 
		}
?>
<script type="text/javascript">document.getElementById("advsearch").style.display='block';</script>
<?php }?>
</fieldset>

<div id="tmpres" style="display:none;">
<select  class="in-select input" style="width:100px;height:20px;">
				    					<?php
							for ($i=0;$i<count($r_reseller);$i++){ 
						?>
								<option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
									<?php
										$space = "";
										for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
											 	$space .= "&nbsp;&nbsp;";
										}
										if ($i==0){
											
											echo "{$r_reseller[$i][0]['name']}";
										} else {
											  echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
											   
										}
									?>
								</option>
							<?php
								} 
							?>
							<option value=""><?php echo __('anyreseller')?></option>
				    		</select>
</div>
<div id="cover"></div>
<div id="toppage"></div>
<div>
<table class="list">
	<thead>
		<tr>
		 <td>
		 
		 <?php if($_SESSION['login_type']=='1'){?>
		 <input type="checkbox" onclick="checkAllOrNot(this,'ratetab');" value=""/>
		 <?php }?>
		 </td>
    <td><?php echo $appCommon->show_order('rate_table_id',__('ID',true))?>    </td>
    <td><?php echo $appCommon->show_order('name',__('Name',true))?></td>

    <td><?php echo $appCommon->show_order('code_deck',__('Code Deck',true))?></td>
    <td><?php echo $appCommon->show_order('currency',__('Currency',true))?></td>
    <td><?php echo $appCommon->show_order('egress_rate',__('Usage Count',true))?></td>
    <td><?php echo $appCommon->show_order('jurisdiction_country_id',__('Jurisdiction Country',true))?></td>
    <td style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_gift_money','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_gift_money')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_gift_money','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td  style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_money_order','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_money_order')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_money_order','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?> <td class="last"><?php echo __('action')?></td>
   <?php }?>
		</tr>
	</thead>
	<tbody id="ratetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1"  id="i_<?php echo $loop-$i?>">
				<td style="text-align:center">
				 <?php if($_SESSION['login_type']=='1'){?>
				<input type="checkbox" value="<?php echo $mydata[$i][0]['rate_table_id']?>"/>
				 <?php }?>
				</td>
		    <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_table_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['name']?></td>
		    <td><?php echo $mydata[$i][0]['code_deck']?></td>
		    <td><?php echo $mydata[$i][0]['currency']?></td>
		    <td><?php echo $mydata[$i][0]['client_rate']+$mydata[$i][0]['egress_rate']?></td>			
				 <td><?php echo $appRate->show_jurisdiction_country($jurisdiction_countries,$mydata[$i][0]['jurisdiction_country_id'])?></td>
		    <?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?>
            <td   style="text-align: center;"  align="center" valign="middle">
		    		<a title="<?php echo __('viewrates')?>" style="float:left;margin-left:10px;"   target='_blank'
		    		href="<?php echo $this->webroot?>clientrates/view/<?php echo $mydata[$i][0]['rate_table_id']?>/<?php echo $this->params['pass'][0]?>">
		    			<img src="<?php echo $this->webroot?>images/bOrigTariffs.gif" />
		    		</a>
	        	<?php if($_SESSION['login_type']==1){?>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:8px;" href="#" onclick="edit_rate(this.parentNode.parentNode);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:8px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>rates/del_rate_tmp/<?php echo $mydata[$i][0]['rate_table_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    		<a title="<?php echo __('copyratetmp')?>" style="float:left;margin-left:8px;" href="javascript:void(0)" onclick="cover('copyratetmp');document.getElementById('tmpid').value='<?php echo $mydata[$i][0]['rate_table_id']?>';">
		    			<img src="<?php echo $this->webroot?>images/arrow_orange.gif" />
		    		</a>
		    		<?php }?>
		    </td>
            <?php }?>
		    <td style="display:none"><?php echo $mydata[$i][0]['reseller_id']?></td>
				</tr>
				<tr style="display:none;"><td>
					<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
					    <dd><?php echo  __('createdate')?>:</dd>
					    <dd><?php echo $mydata[$i][0]['create_time']?></dd>
					    <dd><?php echo __('updateat')?>:</dd>
					    <dd><?php echo $mydata[$i][0]['modify_time']?></dd>
					</dl>				
				</td></tr>
		<?php }?>
		</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>

<?php 
			if(empty($mydata)){?>
			<div class="msg"><?php echo __('no_data_found')?></div><?php }else{?>
<?php }?>
</div>
