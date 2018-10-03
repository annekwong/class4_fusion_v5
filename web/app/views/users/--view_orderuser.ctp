<style>
/*
#registration {
	width:600px;
	text-align:left;
	font-size:12px
}
#regis_title {
	background-color:#FED17B;
	height:20px;
	font-size:14px;
	padding-left:15px;
	font-weight:bold
}
#regis_body {
	background-color:#F0F5FA;
	font-size:12px;
	padding-left:10px;
	margin-bottom:15px;
	padding-top:5px;
	padding-bottom:5px
}
#regis_body table {
	border:none;
	padding:0px;
	margin:0px;
	cellpadding:0px;
	cellspacing:0px;
	width:100%
}
#regis_body table td {
	height:40px;
	text-align:left;
}
#regis_body table th {
	width:33%;
	height:30px;
	text-align:left;
}
font {
	color:#f2c154;
	font-weight:bold;
}
#regis_footer {
	height:30px;
}
.btn {
	font-weight:bold;
	font-size:12px;
	width:100px;
}
*/
.list_new td{ border:1px solid #EDEDED !important;}
font{color:#f00;}

</style>
<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Manage Registration')?>
  
  </h1>
</div>
<div id="container" style="padding-left:0px;padding-right:0px;">
  <div id="registration">
  <div id="regis_title"><b><?php echo __('Welcome to ICX Portal',true);?></b></div>
  <div id="regis_body" style="padding-top:15px; text-align:left;"> <?php __('Complete this form to register for your ICX Account')?>. <br/>
    <?php __('Please enter your information in English only')?>.<br/>
    (<font>*</font>) <?php __('Required Field')?>. </div>
  <form name="" id="changecount" action="../update_orderuser/<?php echo $p[0][0]['id'];?>" method="post">
  <input type="hidden" name="id" value="<?php echo $p[0][0]['id'];?>" />
  <input type="hidden" name="paypal" value="<?php echo $p[0][0]['paypal'];?>" />
    <div class="register">
      <div id="regis_title"><?php echo __('Create you ICX ID',true);?></div>
      <div id="regis_body" style="text-align:center;">
        <ul>
          <li style="height:50px;"> <span class="form_left"><?php echo __('Username',true);?> <font>*</font> </span> <span class="form_right">
            <input class="text" name="username" id="username" type="text" value="<?php echo $p[0][0]['name'];?>" style="width:210px;"/>
            <p style="font-size:11px;"><?php __('Use 5 to 25 characters (letters, numbers, underscores, and dot )')?>.</p>
            </span> <span class="check_input_info"></span> </li>
          <li style="height:50px;"> <span class="form_left"><?php echo __('Password',true);?> <font>*</font> </span> <span class="form_right">
            <input class="text" name="password" id="password" type="password" value="" style="width:210px;"/>
            <p style="font-size:11px;"><?php __('(Req. min of 6 characters)')?>.</p>
            </span> <span class="check_input_info"></span> </li>
            
            <!--
          <li style="height:50px;"> <span class="form_left">Security Question <font>*</font></span> <span class="form_right">
            <input class="text" name="security_question" id="security_question" type="text" value="<?php //echo $p[0][0]['question'];?>" style="width:300px;"/>
            <p style="font-size:11px;">max 128 characters</p>
            </span> <span class="check_input_info"></span> </li>
          <li style="height:50px;"> <span class="form_left">Security Answer <font>*</font></span> <span class="form_right">
            <input class="text" name="security_answer" id="security_answer" type="password" value="<?php //echo $p[0][0]['answer'];?>" style="width:300px;"/>
            <p style="font-size:11px;">max 64 characters</p>
            </span><span class="check_input_info"></span> </li>
            -->
          <li> <span class="form_left"><?php echo __('Corporate Contact Name',true);?> <font>*</font></span> <span class="form_right">
            <input  class="text"name="corporatecontactname" id="corporatecontactname" type="text" value="<?php echo $p[0][0]['corporate_contact_name'];?>"/>
            </span><span class="check_input_info"></span> </li>
          <li> <span class="form_left"><?php echo __('Corporate Contact Phone',true);?><font>*</font></span> <span class="form_right">
            <input class="text" name="corporatecontactphone" id="corporatecontactphone" type="text" value="<?php echo $p[0][0]['corporate_contact_phone'];?>" />
            </span><span class="check_input_info"></span> </li>
          <li> <span class="form_left"><?php echo __('Corporate Contact Cell',true);?> <font>*</font></span> <span class="form_right">
            <input  class="text" name="corporatecontactcell" id="corporatecontactcell" type="text" value="<?php echo $p[0][0]['corporate_contact_cell'];?>" />
            </span><span class="check_input_info"></span> </li>
          <li> <span class="form_left"><?php echo __('Corporate Contact Email',true);?> <font>*</font></span> <span class="form_right">
            <input class="text"name="corporatecontactemail" id="corporatecontactemail" type="text" value="<?php echo $p[0][0]['corporate_contact_email'];?>" />
            </span><span class="check_input_info"></span> </li>
          <li> <span class="form_left"><?php echo __('Confirm Email',true);?> <font>*</font></span> <span class="form_right">
            <input class="text"name="confirmmail" id="confirmmail" type="text" value="<?php echo $p[0][0]['corporate_contact_email'];?>" />
            </span><span class="check_input_info"></span> </li>
        </ul>
      </div>
      <div id="regis_title" class="mem"><?php echo __('Membership details',true);?> <img src="images/bullet_toggle_plus.png" /></div>
      <div id="regis_body">
        <ul>
          <li> <span class="form_left"><?php echo __('Company Name',true);?> </span> <span class="form_right">
            <input class="text" name="company_name" id="company_name" type="text" value="<?php echo $p[0][0]['company_name'];?>"/>
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Address',true);?> 1 </span> <span class="form_right">
            <input class="text" name="address1" id="address1" type="text" value="<?php echo $p[0][0]['addr1'];?>" style="width:300px;"/>
            </span> </li>
          <li> <span class="form_left"><?php echo __('Address',true);?> 2 </span> <span class="form_right">
            <input class="text" name="address2" id="address2" type="text" value="<?php echo $p[0][0]['addr2'];?>" style="width:300px;"/>
            </span> </li>
          <li> <span class="form_left"><?php echo __('City',true);?> </span> <span class="form_right">
            <input class="text" name="city" id="city" type="text" value="<?php echo $p[0][0]['city'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('State/Province',true);?> </span> <span class="form_right">
            <input  class="text"name="stateorprovince" id="stateorprovince" type="text" value="<?php echo $p[0][0]['province'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Zip or Post Code',true);?> </span> <span class="form_right">
            <input class="text" name="ziporpostcode" id="ziporpostcode" type="text" value="<?php echo $p[0][0]['post_code'];?>"/>
            </span> <span class="check_input_info"> </span></li>
          <li> <span class="form_left"> <?php echo __('country',true);?></span><span class="form_right">
            <?php 
   			echo $form->input("country",
				array('options'=>$country,'name'=>'country','label'=>false ,'div'=>false,'type'=>'select','selected'=>$p[0][0]['country'],'style'=>'width:160px'));
			?>
            </span> </li>
          <li> <span class="form_left"><?php echo __('Alternate Email',true);?>(s)</span> <span class="form_right">
            <input  class="text" name="alternateemail" id="alternateemail" type="text" value="<?php echo $p[0][0]['alternate_emails'];?>" />
            </span><span class="check_input_info">  </span> </li>
          <li> <span class="form_left"> <?php echo __('Corporate Contact Fax',true);?> </span> <span class="form_right">
            <input class="text" name="corporatecontactfax" id="corporatecontactfax" type="text" value="<?php echo $p[0][0]['corporate_contact_fax'];?>" />
            </span> <span class="check_input_info"> </span></li>
          <li style="height:50px;"> <span class="form_left"><?php echo __('Corporate Registration Country / Region',true);?><br>
             </span> <span class="form_right">
            <input class="text" name="countryorregion" id="countryorregion" type="text" value="<?php echo $p[0][0]['corporate_registration_country'];?>" />
			<?php 
   			//echo $form->input("countryorregion",array('options'=>$country,'name'=>'countryorregion','label'=>false ,'div'=>false,'type'=>'select','selected'=>$p[0][0]['corporate_registration_country']));
			?>
            </span> </li>
          <li> <span class="form_left"><?php echo __('Billing Contact name',true);?> </span> <span class="form_right">
            <input class="text" name="billingname" id="billingname" type="text" value="<?php echo $p[0][0]['billing_contact_name'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Billing Contact Phone',true);?> </span> <span class="form_right">
            <input class="text" name="billingphone" id="billingphone" type="text" value="<?php echo $p[0][0]['billing_contact_phone'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Billing Contact Email',true);?> </span> <span class="form_right">
            <input class="text" name="billingemail" id="billingemail" type="text" value="<?php echo $p[0][0]['billing_contact_email'];?>" />
            </span> <span class="check_input_info"> </span></li>
          <li> <span class="form_left"> <?php echo __('Billing Contact Fax',true);?> </span> <span class="form_right">
            <input  class="text" name="billingfax" id="billingfax" type="text" value="<?php echo $p[0][0]['billing_contact_fax'];?>" />
            </span> <span class="check_input_info"> </span></li>
          <li> <span class="form_left"> <?php echo __('NOC Contact Phone',true);?> </span> <span class="form_right">
            <input class="text" name="noc_ontact_phone" id="noc_ontact_phone" type="text" value="<?php echo $p[0][0]['noc_contract_phone'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('NOC Contact Email',true);?> </span> <span class="form_right">
            <input class="text" name="noc_contact_email" id="noc_contact_email" type="text" value="<?php echo $p[0][0]['noc_contract_email'];?>" />
            </span> <span class="check_input_info"></span></li>
            
          <li> <span class="form_left"><?php echo __('NOC Contact Type',true);?> </span> <span class="form_right">
            
              <div style="float:left; width:80px;padding-top:3px;"><input type="radio" id="msn" name="noc_contract_type" value="1" <?php if($p[0][0]['noc_contract_type']=='1'){ echo 'checked="checked"';}?> style="float:left;width:20px;margin-top:5px;" onclick="selectNocType('msn');"/>
              <label for="msn"><?php echo __('MSN',true);?> </label>
              </div>
              
              <div style="float:left; width:80px;padding-top:3px;">
              <input type="radio" id="skpye" name="noc_contract_type" value="2" <?php if($p[0][0]['noc_contract_type']=='2'){ echo 'checked="checked"';}?> style="float:left;width:20px;margin-top:5px;" onclick="selectNocType('skpye');"/>
              <label for="skpy"><?php echo __('Skpye',true);?> </label>
              </div>
              <div style="float:left; width:80px;padding-top:3px;">
              <input type="radio" id="other" name="noc_contract_type" value="3"<?php if($p[0][0]['noc_contract_type']=='3'){ echo 'checked="checked"';}?> style="float:left;width:20px;margin-top:5px;" onclick="selectNocType('other');"/>
              <label for="other"><?php echo __('Other',true);?></label>
              </div>
              
              </span> </li>
              
          <li style="height:100px;"> <span class="form_left"><?php echo __('NOC Contact IM',true);?><font id="noc_required">*</font></span> <span class="form_right">
            <textarea name="noc_contact_im" id="noc_contact_im" type="text" style="width:95%;height:100px"><?php echo $p[0][0]['noc_contract_im'];?></textarea>
            </span> </li>
        </ul>
      </div>
      <br />
      <div id="regis_title" class="bank"><?php __('Bank details')?> <img src="images/bullet_toggle_plus.png" /></div>
      <div id="regis_body">
        <ul>
        <li> <span class="form_left"> <?php echo __('Pay Pal',true);?></span> <span class="form_right">
            <input  class="text"name="paypal" id="paypal" type="text" value="<?php echo $p[0][0]['paypal'];?>" />
            </span> </li>
 
          <li> <span class="form_left"> <?php echo __('Bank Name',true);?> </span> <span class="form_right">
            <input  class="text"name="bankname" id="bankname" type="text" value="<?php echo $p[0][0]['bank_name'];?>" />
            </span> </li>
          <li> <span class="form_left"><?php echo __('Address',true);?> </span> <span class="form_right">
            <input  class="text" name="bankaddress" id="bankaddress" type="text" value="<?php echo $p[0][0]['bank_address'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('City',true);?> </span> <span class="form_right">
            <input  class="text"name="bankcity" id="bankcity" type="text" value="<?php echo $p[0][0]['bank_city'];?>" />
            </span> </li>
          <li> <span class="form_left"><?php echo __('State/Province',true);?> </span> <span class="form_right">
            <input  class="text"name="stateorprovince" id="stateorprovince" type="text" value="<?php echo $p[0][0]['bank_province'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('country',true);?></span> <span class="form_right">
             <?php 
   			echo $form->input("bankcountry",
				array('options'=>$country,'name'=>'bankcountry','label'=>false ,'div'=>false,'type'=>'select','selected'=>$p[0][0]['bank_country'],'style'=>'width:160px'));
			?>
            </span> </li>
          <li> <span class="form_left"><?php echo __('Zip Code',true);?> </span> <span class="form_right">
            <input class="text"  name="zipcode" id="zipcode" type="text" value="<?php echo $p[0][0]['bank_post_code'];?>" />
            </span> <span class="check_input_info"></span></li>
          <li> <span class="form_left"><?php echo __('Account Name',true);?> </span> <span class="form_right">
            <input class="text" name="accountname" id="accountname" type="text" value="<?php echo $p[0][0]['bank_account_name'];?>" />
            </span> </li>
          <li> <span class="form_left"><?php echo __('Routing Number',true);?> </span> <span class="form_right">
            <input class="text" name="routingnumber" id="routingnumber" type="text" value="<?php echo $p[0][0]['bank_routing_number'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Account Number',true);?> </span> <span class="form_right">
            <input class="text" name="accountnumber" id="accountnumber" type="text" value="<?php echo $p[0][0]['bank_account_number'];?>" />
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Swift/IBAN',true);?> </span> <span class="form_right">
            <input  class="text" name="swiftiban" id="swiftiban" type="text" value="<?php echo $p[0][0]['bank_swift'];?>" />
            </span> </li>
          <li style="height:100px;"> <span class="form_left"><?php echo __('Notes',true);?> </span> <span class="form_right">
            <textarea name="notes" id="notes" style="width:95%;height:100px"><?php echo $p[0][0]['bank_notes'];?></textarea>
            </span> </li>
          <li> <span class="form_left"> <?php echo __('Currency Preference',true);?> </span> <span class="form_right">
          <div style="float:left; width:80px;padding-top:3px;">
              <input type="hidden" id="usd" name="currency_preference" value="1" style="float:left;width:20px;margin-top:5px; text-align:center;"/>
                <label for="usd" style="font-weight:bold;"><?php echo __('USD',true);?></label>
                </div>
          <!--
            <div style="float:left; width:80px;padding-top:3px;">
              <input type="radio" id="usd" name="currency_preference" value="1" <?php if($p[0][0]['currency_preference']=='1'){?>checked="checked"<?php }?>  style="float:left;width:20px;margin-top:5px;"/>
              <label for="usd">USD</label>
            </div>
            
            <div style="float:left; width:80px;padding-top:3px;">
              <input type="radio" id="rmb" name="currency_preference" value="2" <?php if($p[0][0]['currency_preference']=='2'){?>checked="checked"<?php }?> style="float:left;width:20px;margin-top:5px;"/>
              <label for="rmb">RMB</label>
            </div>
            <div style="float:left; width:80px;padding-top:3px;">
              <input type="radio" id="euro" name="currency_preference" value="3" <?php if($p[0][0]['currency_preference']=='3'){?>checked="checked"<?php }?>style="float:left;width:20px;margin-top:5px;"/>
              <label for="euro">EURO</label>
            </div>
            -->
            </span> </li>
        </ul>
      </div>
      <div id="form_footer">
        <ul>
          <li>
            <?php  if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) {?>
            <input type="submit" name="submit" id="submit" value="<?php echo __('submit',true);?>" class="in-submit input" style="width:auto;"/>
            <?php }?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" name="reset" id="reset" value="<?php echo __('reset',true);?>" class="in-submit input"/>
          </li>
        </ul>
      </div>
    </div>
  </form>
</div>

</div>

<script type="text/javascript">
jQuery(document).ready(function(){
   $('#submit').click(function() {
	   var temp=/^[\w\-\.\_]+@[\w\-\.]+(\.\w+)+$/;
	   
	   if($("#username").val()==''){
		   jQuery('#username').addClass('invalid');
           jGrowl_to_notyfy('User name cannot be null',{theme:'jmsg-error'});
			return false;
	   }
	  
	   if($("#corporatecontactemail").val()!=''){
			if(!temp.test($("#corporatecontactemail").val())){
				jQuery('#corporatecontactemail').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Email Address',{theme:'jmsg-error'});
				return false;
			}
		}
		if($("#confirmmail").val()!=''){
			if(!temp.test($("#confirmmail").val())){
				jQuery('#confirmmail').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Email Address',{theme:'jmsg-error'});
				
				return false;
			}
			if($("#confirmmail").val()!=$("#corporatecontactemail").val()){
				jQuery('#confirmmail').addClass('invalid');
           		jGrowl_to_notyfy('Confirm Email does not match',{theme:'jmsg-error'});
				return false;
			}
		}
	   if($("#billingemail").val()!=''){
			if(!temp.test($("#billingemail").val())){
				jQuery('#billingemail').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Email Address',{theme:'jmsg-error'});
				return false;
			}
		}
		if($("#alternateemail").val()!=''){
			if(!temp.test($("#alternateemail").val())){
				jQuery('#alternateemail').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Email Address',{theme:'jmsg-error'});
				return false;
			}
		}
		if($("#noc_contact_email").val()!=''){
			if(!temp.test($("#noc_contact_email").val())){
				jQuery('#noc_contact_email').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Email Address',{theme:'jmsg-error'});
				return false;
			}
		}
		var checkfax=/(^[0-9]{3,4}\-[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{2,4}\-[0-9]{3,4}\-[0-9]{7,8}-[0-9]{3,4}$)|(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{3,4}\-[0-9]{7,8}\-[0-9]{3,4}$)|(^[0-9]{7,8}\-[0-9]{3,4}$)|(^[0-9]{7,15}$)/;
		
		if($("#corporatecontactfax").val()!=''){
			if(!checkfax.test($("#corporatecontactfax").val())){
				jQuery('#corporatecontactfax').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Fax Address',{theme:'jmsg-error'});
				return false;
			}
		}
		if($("#billingfax").val()!=''){
			if(!checkfax.test($("#billingfax").val())){
				jQuery('#billingfax').addClass('invalid');
           		jGrowl_to_notyfy('Please specify a valid Fax Address',{theme:'jmsg-error'});
				return false;
			}
		}
		if($('input[name=noc_contract_type]:checked').val()=='1'||$('input[name=noc_contract_type]:checked').val()=='2'){
			if($('#noc_contact_im').val()==''){
				jQuery('#noc_contact_im').addClass('invalid');
				jGrowl_to_notyfy('NOC Contact IM is required field cannot be left blank',{theme:'jmsg-error'});
				return false;
			}
		}
		$('#changecount').submit();
	});
});

function selectNocType(obj){
	if(obj=='other'){
		$('#noc_required').hide();
	}else{
		$('#noc_required').show();
		$('#'+obj).attr('checked');
	}
}
</script>
