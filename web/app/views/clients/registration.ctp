<style>
    
    .list1 td{ line-height:2;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Registration') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Registration') ?></h4>
    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">



<div id="container">
    <form method="post" action="" id="registration">
    <table class="list1 table dynamicTable tableTools table-bordered  table-white">
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Username')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="username" name="username" class="in-input"><?php __('Use 5 to 25 characters')?>.</td>
            <td style="text-align: right;font-weight:bold;"><?php __('Company Name')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="company_name" name="company_name" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Password')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="password" id="password" name="password" class="in-input"><?php __('(Req. min of 8 characters)')?></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Address 1')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="address1" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Repeat Password')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="password" id="repeat_password" name="repeat_password" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Address 2')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="address2" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Security Question')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="security_question" name="security_question" class="in-input"><?php __('max 128 characters')?></td>
            <td style="text-align: right;font-weight:bold;"><?php __('City')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="city" name="city" class="in-input"></td>
        </tr>
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Security Answer')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="security_answer" name="security_answer" class="in-input"><?php __('max 64 characters')?></td>
            <td style="text-align: right;font-weight:bold;"><?php __('State / Province')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="stateorprovince" class="in-input"></td>
        </tr>
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Name')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="corporatecontactname" name="corporatecontactname" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Zip or Post Code')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="ziporpostcode" class="in-input"></td>
        </tr>
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Phone')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="corporatecontactphone" name="corporatecontactphone" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Country')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;
                <select name ="country" class="in-select">
                    <?php
                        foreach($counties as $country){
                            ?>
                                <option value="<?php echo $country[0]['country'];?>"><?php echo $country[0]['country'];?></option>
                            <?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr> 
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Cell')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="corporatecontactcell" name="corporatecontactcell" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Alternate Email(s)')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="alternateemail" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Email')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="corporatecontactemail" name="corporatecontactemail" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Fax')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="corporatecontactfax" class="in-input"></td>
        </tr> 
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Confirm Email')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" id="confirmmail" name="confirmmail" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Registration')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" name="countryorregion" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Transaction Fee')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;
                <select name="transaction_fee_id">
                    <?php
                        foreach($transaction_fees as $transaction_fee){
                            if($transaction_fee[0]['is_default'] == 't'){
                            ?>
                            <option selected value="<?php echo $transaction_fee[0]['id'];?>"><?php echo $transaction_fee[0]['name'];?></option>
                            <?php
                            }else{
                            ?>
                            <option value="<?php echo $transaction_fee[0]['id'];?>"><?php echo $transaction_fee[0]['name'];?></option>  
                            <?php
                            }
                        }
                    
                    ?>
                </select>
            </td>
            <td style="text-align: right;font-weight:bold;"></td>
            <td style="text-align: left;"></td>
        </tr>
       
    </table>
    <br/>
    <div id="add_mem" style="font-weight:bold;"><img onclick="changeMembership('hide')" src="<?php echo $this->webroot?>images/bullet_toggle_minus.png"><?php __('Membership details')?></div>
    <div id="membership_details">
        <HR  width="100%" color='green' size=1>
        <br/>
        <table class="list1 table dynamicTable tableTools table-bordered  table-white">
            <thead>
                <tr style="font-size: 14px;">
                    <th colspan="2" ><?php __('Primary Contact')?></th>
                    <th colspan="2" ><?php __('Technical Contact')?></th>
                    <th colspan="2" ><?php __('Billing Contact')?></th>
                </tr>
            </thead>
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="primary_contact_name" name="primary_contact_name" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="technical_contact_name" name="technical_contact_name" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="billing_contact_name" name="billing_contact_name" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_job_tite" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_job_tite" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_job_tite" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="primary_email" name="primary_email" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="technical_email" name="technical_email" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="billing_email" name="billing_email" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_fax" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_fax" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_fax" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="primary_phone" name="primary_phone" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="technical_phone" name="technical_phone" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="billing_phone" name="billing_phone" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_mobile" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_mobile" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_mobile" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_yahoo" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_yahoo" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_yahoo" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_msn" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_msn" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_msn" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_skype" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_skype" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_skype" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_aql" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_aql" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_aql" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_icq" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_icq" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_icq" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_qq" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_qq" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_qq" class="in-input"></td>
            </tr>
        </table>
    </div>
    <br/>
    <div id="add_bank" style="font-weight:bold;"><img onclick="changeBankDetails('show')" src="<?php echo $this->webroot?>images/bullet_toggle_plus.png"><?php __('Bank Details ( Optional )')?></div>
    <div id="bank_details" style="display:none;">
        <HR  width="100%" color='green' size=1>
        <br/>
        <table class="list1 table dynamicTable tableTools table-bordered  table-white">
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Paypal Account')?>:</td>
                <td style="text-align: left;"><input type="text" name="paypal" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Bank Name')?>:</td>
                <td style="text-align: left;"><input type="text" name="bankname" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Bank Address')?>:</td>
                <td style="text-align: left;"><textarea  name="bankaddress" class="in-input"></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Account Name')?>:</td>
                <td style="text-align: left;"><input type="text" name="accountname" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Account Number')?>:</td>
                <td style="text-align: left;"><input type="text" name="accountnumber" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Routing Number')?>:</td>
                <td style="text-align: left;"><input type="text" name="routingnumber" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Swift # / IBAN #')?>:</td>
                <td style="text-align: left;"><input type="text" name="swiftiban" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Notes')?>:</td>
                <td style="text-align: left;"><textarea  name="notes" class="in-input"></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Intermediately Bank')?>:</td>
                <td style="text-align: left;"><textarea  name="intermediately_bank" class="in-input"></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('ACH #')?>:</td>
                <td style="text-align: left;"><input type="text" name="ach" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Currency Preference')?>:</td>
                <td style="text-align: left;"><?php __('USD')?></td>
            </tr>
        </table>
       
    </div>
            <br />
            <div style="text-align: center;">
            <input type="submit" value="<?php __('submit')?>" class="btn btn-primary">
            <a href="<?php echo $this->webroot?>clients/registration"><input type="button" value="<?php __('reset')?>" class="in-submit  btn btn-default"></a>
            </div>
    </form>
</div>

<script>
    
    $(function (){
        $("#registration").submit(function (){
            var flag = true;
            
          
          
            username = $("#username").val();
            if(username == ''){
                flag = false;
                jGrowl_to_notyfy('Username can not be empty',{theme:'jmsg-error'});
            }
            
            if(username.length < 5 || username.length > 24){
                flag = false;
                jGrowl_to_notyfy('Username must be at least 5 characters in length and can not exceed 25 characters in length!',{theme:'jmsg-error'});
            }
            
            $.ajax({
                'url' : '<?php echo $this->webroot?>clients/check_username/'+username,
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {},
                'async' : false,
                'success' :function (data){
                    if(data == 'no'){
                        flag = false;
                        jGrowl_to_notyfy("This Username already exists!",{theme:'jmsg-error'});
                    }
                }
            });
            
            
            company_name = $("#company_name").val()
            if(company_name == ''){
                flag = false;
                jGrowl_to_notyfy('Company Name can not be empty',{theme:'jmsg-error'});
            }
            
            regex2 = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
            
            confirmmail = $("#confirmmail").val()
            if(confirmmail == ''){
                flag = false;
                jGrowl_to_notyfy('Confirm Email can not be empty',{theme:'jmsg-error'});
            }
            
            if(!regex2.test(confirmmail)){
                jGrowl_to_notyfy('Confirm Email is not correct!',{theme:'jmsg-error'});
                flag = false;
            }
            
            
            
            corporatecontactemail = $("#corporatecontactemail").val()
            if(corporatecontactemail == ''){
                flag = false;
                jGrowl_to_notyfy('Corporate Contact Email can not be empty',{theme:'jmsg-error'});
            }
            
            if(!regex2.test(corporatecontactemail)){
                jGrowl_to_notyfy('Corporate Contact Email is not correct!',{theme:'jmsg-error'});
                flag = false;
            }
            
            if(corporatecontactemail != confirmmail){
                jGrowl_to_notyfy('The Corporate Contact Email does not match the Confirm Email!',{theme:'jmsg-error'});
                flag = false;
            }
            
            
             $.ajax({
                'url' : '<?php echo $this->webroot?>clients/check_add_email/'+corporatecontactemail,
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {},
                'async' : false,
                'success' :function (data){
                    if(data == 'no'){
                        flag = false;
                        jGrowl_to_notyfy("This email already exists!",{theme:'jmsg-error'});
                    }
                }
            });
            
            
            password = $("#password").val()
            if(password == ''){
                flag = false;
                jGrowl_to_notyfy('Password can not be empty',{theme:'jmsg-error'});
            }
            
            if(password.length < 8){
                flag = false;
                jGrowl_to_notyfy('Password must be at least 8 characters in length ',{theme:'jmsg-error'});
            }
            
            repeat_password = $("#repeat_password").val()
            if(repeat_password == ''){
                flag = false;
                jGrowl_to_notyfy('Repeat Password can not be empty',{theme:'jmsg-error'});
            }
            
            if(password != repeat_password){
                flag = false;
                jGrowl_to_notyfy('The Password field does not match the Repeatpassword field!',{theme:'jmsg-error'});
            }
             
          
            security_question = $("#security_question").val()
            if(security_question == ''){
                flag = false;
                jGrowl_to_notyfy('Security Question can not be empty',{theme:'jmsg-error'});
            }
            
            if(security_question.length > 128){
                flag = false;
                jGrowl_to_notyfy('Security Question can not exceed 128 characters in length!',{theme:'jmsg-error'});
            }
            
            city = $("#city").val()
            if(city == ''){
                flag = false;
                jGrowl_to_notyfy('City can not be empty',{theme:'jmsg-error'});
            }
            
            security_answer = $("#security_answer").val()
            if(security_answer == ''){
                flag = false;
                jGrowl_to_notyfy('Security Answer can not be empty',{theme:'jmsg-error'});
            }
            
            if(security_question.length > 64){
                flag = false;
                jGrowl_to_notyfy('Security Answer can not exceed 64 characters in length!',{theme:'jmsg-error'});
            }
            
            corporatecontactname = $("#corporatecontactname").val()
            if(corporatecontactname == ''){
                flag = false;
                jGrowl_to_notyfy('Corporate Contact Name can not be empty',{theme:'jmsg-error'});
            }
            
           corporatecontactphone = $("#corporatecontactphone").val()
            if(corporatecontactphone == ''){
                flag = false;
                jGrowl_to_notyfy('Corporate Contact Phone can not be empty',{theme:'jmsg-error'});
            }
            
            corporatecontactcell = $("#corporatecontactcell").val();
            if(corporatecontactcell == ''){
                flag = false;
                jGrowl_to_notyfy('Corporate Contact Cell can not be empty',{theme:'jmsg-error'});
            }
            
            
            /*primary_contact_name = $("#primary_contact_name").val(); 
            if(primary_contact_name == ''){
                flag = false;
                jGrowl_to_notyfy('Primary Contact Name can not be empty',{theme:'jmsg-error'});
            }
            
            technical_contact_name = $("#technical_contact_name").val();
            if(technical_contact_name == ''){
                flag = false;
                jGrowl_to_notyfy('Technical Contact Name can not be empty',{theme:'jmsg-error'});
            }
            
            billing_contact_name = $("#billing_contact_name").val();
            if(billing_contact_name == ''){
                flag = false;
                jGrowl_to_notyfy('Billing Contact Name can not be empty',{theme:'jmsg-error'});
            }
            */
            
            primary_email = $("#primary_email").val();
            /*if(primary_email == ''){
                flag = false;
                jGrowl_to_notyfy('Primary Email can not be empty',{theme:'jmsg-error'});
            }*/
            
            if(primary_email != '' && !regex2.test(primary_email)){
                jGrowl_to_notyfy('Primary Email is not correct!',{theme:'jmsg-error'});
                flag = false;
            }
            
            technical_email = $("#technical_email").val();
            /*if(technical_email == ''){
                flag = false;
                jGrowl_to_notyfy('Technical Email can not be empty',{theme:'jmsg-error'});
            }*/
            
            if(technical_email != '' && !regex2.test(technical_email)){
                jGrowl_to_notyfy('Technical Email is not correct!',{theme:'jmsg-error'});
                flag = false;
            }
            
            billing_email = $("#billing_email").val();
            /*if(billing_email == ''){
                flag = false;
                jGrowl_to_notyfy('Billing Email can not be empty',{theme:'jmsg-error'});
            }*/
            
            if(billing_email != '' && !regex2.test(billing_email)){
                jGrowl_to_notyfy('Billing Email is not correct!',{theme:'jmsg-error'});
                flag = false;
            }
            
            /*primary_phone = $("#primary_phone").val();
            if(primary_phone == ''){
                flag = false;
                jGrowl_to_notyfy('Primary Phone can not be empty',{theme:'jmsg-error'});
            }
            
            technical_phone = $("#technical_phone").val();
            if(technical_phone == ''){
                flag = false;
                jGrowl_to_notyfy('Technical Phone can not be empty',{theme:'jmsg-error'});
            }
            
            billing_phone = $("#billing_phone").val();
            if(billing_phone == ''){
                flag = false;
                jGrowl_to_notyfy('Billing Phone can not be empty',{theme:'jmsg-error'});
            }*/
            return flag;
            
        });
    });
    
    function changeMembership(obj){
        if(obj == "show"){
            $("#membership_details").slideDown();
            $("#add_mem").html('');
            $("#add_mem").append("<img onclick=\"changeMembership('hide')\" src=\"<?php echo $this->webroot?>images/bullet_toggle_minus.png\">Membership details</div>");
        }else{
            $("#membership_details").slideUp();
            $("#add_mem").html('');
            $("#add_mem").append("<img onclick=\"changeMembership('show')\" src=\"<?php echo $this->webroot?>images/bullet_toggle_plus.png\">Membership details</div>");
        }
        
    }
    
    function changeBankDetails(obj){
        if(obj == "show"){
            $("#bank_details").slideDown();
            $("#add_bank").html('');
            $("#add_bank").append("<img onclick=\"changeBankDetails('hide')\" src=\"<?php echo $this->webroot?>images/bullet_toggle_minus.png\">Bank Details ( Optional )</div>");
        }else{
            $("#bank_details").slideUp();
            $("#add_bank").html('');
            $("#add_bank").append("<img onclick=\"changeBankDetails('show')\" src=\"<?php echo $this->webroot?>images/bullet_toggle_plus.png\">Bank Details ( Optional )</div>");
        }
    }
</script>


