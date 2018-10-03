<style type="text/css">
	.width100{width:100px;}
</style>
<div id="title">
  <h1>
  <?php __('Add')?>
    <?php if(	 $_SESSION['gress']=='ingress'){
    	echo __('Ingress',true);
    }else{
    		echo __('Egress',true);
    	}?>
   <?php __('Action')?>
  </h1>
  <ul id="title-menu">
				<li>
    			<a class="link_back"href="javascript:history.back()">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php echo __('goback',true);?>    			</a>
    		</li>
  </ul>
</div>
<div id="container">
<ul class="tabs">
    <?php if(	 $_GET['gress']=='ingress'){?>
     <li><a href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress?<?php echo $this->params['getUrl']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?></a></li><!--
     <li><a href="<?php echo $this->webroot?>gatewaygroups/add_host?<?php echo $this->params['getUrl']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/>HOST</a>  </li>
    	--><li class="active"><a href="<?php echo $this->webroot?>gatewaygroups/add_direction?<?php echo $this->params['getUrl']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('Action')?></a>        </li>
     <li><a href="<?php echo $this->webroot?>gatewaygroups/add_translation_time?<?php echo $this->params['getUrl']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('DigitMapping')?></a>        </li>
    <?php }else{?>
     <li  ><a href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_egress?<?php echo $this->params['getUrl']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?></a></li><!--
      <li  ><a href="<?php echo $this->webroot?>gatewaygroups/add_host?<?php echo $this->params['getUrl']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/>HOST</a>  </li>
    --><li  class="active"><a href="<?php echo $this->webroot?>gatewaygroups/add_direction?<?php echo $this->params['getUrl']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('Action')?></a>        </li>
    <?php }?>
       </ul>
<form  action="<?php echo $this->webroot ?>gatewaygroups/add_direction_post"   id="add_form" method="post">
<input type="hidden" id="resource_id" value="<?php   if(!empty($resource_id)){echo  $resource_id ;}else{  echo '';}?>" name="resource_id" class="input in-hidden">
<input type="hidden" id="gress" value="<?php echo $_GET['gress']?>" name="gress" class="input in-hidden">
<fieldset>
<legend>
<a onclick="addItem('ip');return false;" href="#">
<img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('add',true);?>&nnbsp;<?php __('Action')?></a></legend>
<table class="list list-form"  style="width: auto">
<thead>
    <tr>
        <td width="12%"><?php __('timeprofile')?></td>
        <td width="8%"><?php __('Target')?></td>
        <td width="8%"><?php __('code')?></td>
        <td width="8%"><?php __('action')?></td>
        <td width="8%"><?php __('Chars to Add')?></td>
        <td width="8%"><?php __('Num of chars to Del')?></td>
        <td width="8%"><?php __('numbertype')?></td>
        <td width="8%"><?php __('numberlength')?></td>
        <td width="8%" class="last"><?php echo __('action',true);?></td>
    </tr>
</thead>
<tbody class="rows" id="rows-ip">
<?php  $size=count($host);
  for($i=0;$i<$size;$i++){?>
  	<?php $iadd=$i+1?>
  	<tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1?>" style="">
    <td style="width: 200px;" class="value">
       <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['direction_id']?>" class="input in-hidden">
    			<?php 
    			$ii=$i+1;
    		
    			echo $form->input('client_id',array('options'=>$timepro,  'id'=>"ip-time_profile_id-$ii",'name'=>"accounts[$ii][time_profile_id]",'selected'=>$host[$i][0]['time_profile_id'],
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
    <td style="width: 350px;" class="value">
				<?php 
    			$t=array('0'=>__('ani',true),'1'=>__('dnis',true));
    			echo $form->input('client_id',array('options'=>$t,  'id'=>"ip-type-$ii",'name'=>"accounts[$ii][type]",'selected'=>$host[$i][0]['type'],
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    			?>
    </td>
    <td style="width: 300px;" class="value"><input type="text" check="Num" value="<?php echo $host[$i][0]['dnis']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][dnis]" id="ip-dnis-<?php echo $i+1?>" ></td>
    <td style="width: 350px;" class="value">
				<?php 
    			$t=array('1'=>__('AddPrefix',true),'3'=>__('DelPrefix',true),'2'=>__('Addsuffix',true),'4'=>__('Delsuffix',true));
    			echo $form->input('client_id',array('options'=>$t,  'id'=>"ip-action-$ii",'name'=>"accounts[$ii][action]",'selected'=>$host[$i][0]['action'],
    			'style'=>'205px;','onchange'=>'PrefixChange(this)',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    			?>
    </td>
    <td style="width: 300px;" class="value"><input id="ip-digits-<?php echo $i+1 ;?>" type="text" check="Num" maxLength="16"   value="<?php echo $host[$i][0]['digits']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][digits]" id="ip-digits-<?php echo $i+1?>"></td>
    <td><?php echo $xform->input('deldigits',Array('name'=>"accounts[{$iadd}][deldigits]",'selected'=>$host[$i][0]['digits'],'options'=>Array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9)))?></td>
    <td style="width: 350px;" class="value">
    <?php 
    		$t=array('0'=>'all','1'=>'>','2'=>'=','3'=>'<');
    		echo $form->input('number_type',array('options'=>$t,  'id'=>"ip-number_type-$ii",'name'=>"accounts[$ii][number_type]",'selected'=>$host[$i][0]['number_type'],
    		'style'=>'205px;',
    		'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    		?>
     </td>
     <td style="width: 300px;" class="value">
        <input type="text" check='Num' value="<?php echo $host[$i][0]['number_length']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][number_length]" id="ip-number_length-<?php echo $i+1?>" check="Num">
     </td>
    	<td style="width: 200px;" class="value last">
    			<a href="#" rel="delete"  onclick=" $(this).closest('tr').remove();">
    					<i class="icon-remove"></i>
    			</a>
    	</td>
		</tr>
  	
  	
  	
 <?php }?> 
<?php //  用id="tpl-ip"表示  准备复制的hang ?>
	 <tr  style="display:none" id="tpl-ip" class="  row-2">
    <td class="value"  style="width: 200px;">
       	<?php 
      			echo $form->input('client_id',array('options'=>$timepro,'name'=>"_accounts[%n][time_profile_id]",'style'=>'205px;',
    				'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
      				?>
    </td>
    <td class="value"  style="width: 300px;">
		     <?php 
		    			$t=array('0'=>__('ani',true),'1'=>__('dnis',true));
		    			echo $form->input('client_id',array('options'=>$t, 'name'=>"_accounts[%n][type]",
		    			'style'=>'205px;',
		    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
		    		?>
    	</td>
     <td style="width: 300px;" class="value"><input type="text"  class="input in-text" name="_accounts[%n][dnis]"  check="Num" ></td>
     <td style="width: 350px;" class="value">
					<?php 
		    	  	$t=array('1'=>__('AddPrefix',true),'3'=>__('DelPrefix',true),'2'=>__('Addsuffix',true),'4'=>__('Delsuffix',true));
		    			echo $form->input('client_id',array('options'=>$t,  'name'=>"_accounts[%n][action]",
		    			'style'=>'205px;','onchange'=>'PrefixChange(this)',
		    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
		    		?>
     </td>
     <td class="value"  style="width: 300px;"><input id="digits" type="text" name="_accounts[%n][digits]" class="input in-text" check="Num"></td>
     <td><?php echo $xform->input('deldigits',Array('name'=>"_accounts[%n][deldigits]",'disabled'=>'disabled','options'=>Array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9)))?></td>
     <td style="width: 350px;" class="value">
					<?php 
			    		$t=array('0'=>'all','1'=>'>','2'=>'=','3'=>'<');
			    		echo $form->input('client_id',array('options'=>$t,  'name'=>"_accounts[%n][number_type]",
			    		'style'=>'205px;',
			    		'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','check'=>'Num'));
			    	?>
     </td>
	    <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][number_length]" class="input in-text" check="Num"></td>
	    <td class="value last"  style="width: 200px;"><a rel="delete" href="#"><i class="icon-remove"></i></a></td>
			</tr>
</tbody>
</table>
</fieldset>
<div id="form_footer">
   <input type="submit" value="<?php __('submit')?>" class="input in-submit">
   <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit">
</div>
</form>
<script type="text/javascript" language="JavaScript">
//<![CDATA[
var lastId = '<?php  echo   $size;?>';
var dr_groups = [];
function addItem(type, row)
{
    lastId++;
    if (!row || !row['id']) {
        row = {
            'auth_type': type,
            'name': 'account_' + lastId,
            'proxy_mode': '',
            'orig_enabled': 1,
            'term_enabled': 1
        };
    }
  for (k in row) { if (row[k] == null) row[k] = ''; }
    var tRow = $('#tpl-'+type).clone(true);//复制准备好的行
    tRow.attr('id', 'row-'+lastId).show();//设置显示
    tRow.find('input,select').each(function () {
    var el = $(this);//当前表单元素
    var name = $(this).attr('name').substring(1).replace('%n', lastId);//设置名字(将名字中的%n替换为lastId)  accounts[6][id]
    var field = name.substring(name.lastIndexOf('[')+1, name.length-1);  //id
    el.attr('id', type+'-'+field+'-'+lastId);//设置id  ip-id-6
    el.attr('name', name);
//        对checkbox的处理
        if (el.attr('type') == 'checkbox') {
//给checkbox注册事件
        	if(field=='need_register'){
        	   el.click(function () {
        	   if($(this).attr("checked")==true){
        	    	$(this).attr("value",'true');}else{$(this).attr("value",'false');}
        	   					});
            			}
         	if (typeof(row[field]) == 'object') {
             el.attr('checked', jQuery.inArray(1*el.attr('value'), row[field]) != -1 ? 'checked' : '');
             el.attr('name', el.attr('name') + '[]');
          } else {
             el.attr('checked', row[field] ? 'checked' : '');
            			 }
        } else {
            el.val(row[field]);
        			}
    });
    
    // remove of the row  给删除用的图片链接注册事件 
    tRow.find('a[rel=delete]').click(function () {
        $(this).closest('tr').remove(); //找到他最靠近的tr删除之
        return false;
    });

    buildParams(tRow);
    if (row['id']) {
        tRow.appendTo($('#rows-'+type));
    } else {
        tRow.prependTo($('#rows-'+type));//<tbody class="rows" id="rows-ip">  将tr加入tbody
    }
    
    // styles
    if (!row['id']) {
        initForms(tRow);
        initList();
    }
}

function buildParams(row)
{
    var s = '';

    if (row.find('input[name*=orig_capacity]').val()){ s += ' / OC: '+row.find('input[name*=orig_capacity]').val();}
    if (row.find('input[name*=term_capacity]').val()) s += ' / TC: '+row.find('input[name*=term_capacity]').val();
    if (row.find('select[name*=protocol]').val()) s += ' / '+row.find('select[name*=protocol] :selected').text();
    if (row.find('input[name*=proxy_mode]').val()) s += ' / P: '+row.find('input[name*=proxy_mode]').val();
    if (row.find('select[name*=id_dr_plans]').val()) s += '<br/>RP: '+row.find('select[name*=id_dr_plans] :selected').text();
    if (row.find('select[name*=orig_rate_table]').val()) s += '<br/>Orig RT: '+row.find('select[name*=orig_rate_table] :selected').text();
    if (row.find('select[name*=term_rate_table]').val()) s += '<br/>Term RT: '+row.find('select[name*=term_rate_table] :selected').text();

    var dr_group = '';
    row.find('input[name*=dr_groups]').each(function() {
        if ($(this).attr('checked')) {
            if (dr_group != '') dr_group += ', ';
        	dr_group += dr_groups[$(this).val()];
        }
    });
    if (dr_group != '') {
        s += '<br/>G: ' + dr_group;
    }
    
	if (s.substring(0, 3) == ' / ') {
		s = s.substring(3);
	}
    if (s.substring(0, 5) == '<br/>') {
        s = s.substring(5);
    }
    if (!s) {
        s = '&mdash; &raquo;';
    }

    row.find('#tpl-params-text').html(s);
    return s;
}
function hideParams()
{
    $('.rows div.params-block:visible').hide().attr('id', '').each(function () {
         buildParams($(this).parent().parent());
    });
}

//live event handlers
$('.rows #tpl-params-block div').live('click', function (e) {
    e.stopPropagation();
});
$('.rows #tpl-params-block div a').live('click', function () {
    hideParams();
    return false;
});
$('.rows #tpl-params-link').live('click', function () {
    var vis = 0;
    var div = $(this).parent().find('div');
    if (div.is(':visible')) vis = 1;
    hideParams();
    if (!vis) {
        div.attr('id', 'tooltip').show();
    }
    return false;
});
$('.rows #tpl-delete-row').live('click', function () {
    $(this).closest('tr').remove();
    return false;
});
$(window).click(hideParams);
</script>
</div>
<script type="text/javascript">
  jQuery(document).ready(function(){
      jQuery('input[check=Num]').xkeyvalidate({type:'Num'}).attr('maxLength','8');

      jQuery('#add_form').submit(function(){
	         
          var	flag=true;
          jQuery('#rows-ip').find('tr:visible').each(function(){
        	       var dnis='';
        	       var char_add='';
        	       var char='';
        	       var number_length='';
   			        dnis=jQuery(this).find('input[id*=ip-dnis-]:visible').val();
   			        char_add=jQuery(this).find('input[id*=ip-digits-]:visible').val();
   			       // char=jQuery(this).find('#digits:visible').val();
   			        number_length=jQuery(this).find('input[id*=ip-number_length-]:visible').val();
   			       if(dnis!=''){
   			         if(/\D/.test(dnis)){
   			        	    jGrowl_to_notyfy('Code, must be whole number! ',{theme:'jmsg-error'});
   			        	    flag=false;              
   			                         }
   			                      }
   	         if(char_add!=''||char!=''){
                if(/\D/.test(char_add)||/\D/.test(char)){
               	 jGrowl_to_notyfy('Chars to Add, must be whole number!',{theme:'jmsg-error'});
   	        	    flag=false; 
                                      }
   	                             }
   	         if(number_length!=''){
   	             if(/\D/.test(number_length)){
   	            	 jGrowl_to_notyfy('Number Length, must be whole number!',{theme:'jmsg-error'});
   		        	    flag=false; 
   	                                   }
   		                      }
            });
       return flag;
   });

      
     });    
  	jQuery('select[id^=ip-action]').each(function(){jQuery(this).change()}); 
		function PrefixChange(obj){
		  if(jQuery(obj).val()==3 || jQuery(obj).val()==4){
			  	jQuery(obj).parent().parent().find('input[id^=ip-digits],input[id=digits]').attr('disabled','disabled').val('');
			  	jQuery(obj).parent().parent().find('select[id^=ip-deldigits],select[id=deldigits]').removeAttr('disabled').find('option[value=]').remove();
		  }else{
			  	jQuery(obj).parent().parent().find('input[id^=ip-digits],input[id=digits]').removeAttr('disabled');
			  	jQuery(obj).parent().parent().find('select[id^=ip-deldigits],select[id=deldigits]').attr('disabled','disabled').append('<option/>').val('');
		  	}
		}
		
</script>









<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>