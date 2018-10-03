<div id="title">
  <h1><?php  __('Configuration')?> &gt;&gt;<?php echo __('Manage Registration')?></h1>
</div>
<style>
    
    .list1 td{ line-height:2;}
</style>
<div id="container">
    <form method="post" action="" id="registration">
    <input type="hidden" name="id" value="<?php echo $p[0][0]['id'];?>" />
    <input type="hidden" name="client_id" value="<?php echo $p[0][0]['client_id'];?>" />
    <table class="list1">
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Username')?><lable style="color:red;">*</lable></td>
    <td style="text-align: left;">&nbsp;<input readonly value="<?php echo $p[0][0]['name'];?>" type="text" id="username" name="username"  class="in-input"><?php __('Use 5 to 25 characters')?>.</td>
            <td style="text-align: right;font-weight:bold;"><?php __('Company Name')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['company_name'];?>" type="text" id="company_name" name="company_name" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Password')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="password" id="password" name="password" class="in-input"><?php __('(Req. min of 8 characters)')?></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Address 1')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['addr1'];?>" type="text" name="address1" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Repeat Password')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="password" id="repeat_password" name="repeat_password" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Address 2')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['addr2'];?>" type="text" name="address2" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('City')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['city'];?>" type="text" id="city" name="city" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('State / Province')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['province'];?>" type="text" name="stateorprovince" class="in-input"></td>
        </tr>
       
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Name')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['corporate_contact_name'];?>" type="text" id="corporatecontactname" name="corporatecontactname" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Zip or Post Code')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['post_code'];?>" type="text" name="ziporpostcode" class="in-input"></td>
        </tr>
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Phone')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['corporate_contact_phone'];?>" type="text" id="corporatecontactphone" name="corporatecontactphone" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Country')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;
                <select name ="country" class="in-select">
                    <?php
                        foreach($counties as $country){
                                if($p[0][0]['country'] == $country[0]['country']){
                            ?>
                            <option selected value="<?php echo $country[0]['country'];?>"><?php echo $country[0]['country'];?></option>
                            <?php  
                                }else{
                            ?>
                                <option value="<?php echo $country[0]['country'];?>"><?php echo $country[0]['country'];?></option>
                            <?php  
                                }
                            
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr> 
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Cell')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['corporate_contact_cell'];?>" type="text" id="corporatecontactcell" name="corporatecontactcell" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Alternate Email(s)')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['alternate_emails'];?>" type="text" name="alternateemail" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Email')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['corporate_contact_email'];?>" type="text" id="corporatecontactemail" name="corporatecontactemail" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Contact Fax')?></td>
            <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['corporate_contact_fax'];?>" type="text" name="corporatecontactfax" class="in-input"></td>
        </tr> 
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Confirm Email')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;<input type="text" value="<?php echo $p[0][0]['corporate_contact_email'];?>" id="confirmmail" name="confirmmail" class="in-input"></td>
            <td style="text-align: right;font-weight:bold;"><?php __('Corporate Registration')?></td>
            <td style="text-align: left;">&nbsp;<input type="text" value="<?php echo $p[0][0]['corporate_registration_country'];?>" name="countryorregion" class="in-input"></td>
        </tr>
        
        <tr>
            <td style="text-align: right;font-weight:bold;"><?php __('Transaction Fee')?><lable style="color:red;">*</lable></td>
            <td style="text-align: left;">&nbsp;
                <select name="transaction_fee_id">
                    <?php
                        foreach($transaction_fees as $transaction_fee){
                            if($transaction_fee[0]['id'] == $p[0][0]['transaction_fee_id']){
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
        <table class="list1">
            <thead>
                <tr style="font-size: 14px;">
                    <th colspan="2" ><?php __('Primary Contact')?></th>
                    <th colspan="2" ><?php __('Technical Contact')?></th>
                    <th colspan="2" ><?php __('Billing Contact')?></th>
                </tr>
            </thead>
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['primary_contact_name'];?>" type="text" id="primary_contact_name" name="primary_contact_name" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['technical_contact_name'];?>" type="text" id="technical_contact_name" name="technical_contact_name" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Contact Name')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['billing_contact_name'];?>" type="text" id="billing_contact_name" name="billing_contact_name" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['primary_job_tite'];?>" type="text" name="primary_job_tite" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['technical_job_tite'];?>" type="text" name="technical_job_tite" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('job title')?></td>
                <td style="text-align: left;">&nbsp;<input value="<?php echo $p[0][0]['billing_job_tite'];?>" type="text" name="billing_job_tite" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="primary_email" value="<?php echo $p[0][0]['primary_email'];?>" name="primary_email" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="technical_email" value="<?php echo $p[0][0]['technical_email'];?>" name="technical_email" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Email')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="billing_email" value="<?php echo $p[0][0]['billing_email'];?>" name="billing_email" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" value="<?php echo $p[0][0]['primary_fax'];?>" name="primary_fax" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" value="<?php echo $p[0][0]['technical_fax'];?>" name="technical_fax" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Fax')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" value="<?php echo $p[0][0]['billing_fax'];?>" name="billing_fax" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="primary_phone" value="<?php echo $p[0][0]['primary_phone'];?>" name="primary_phone" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="technical_phone" value="<?php echo $p[0][0]['technical_phone'];?>" name="technical_phone" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Phone')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" id="billing_phone" value="<?php echo $p[0][0]['billing_phone'];?>" name="billing_phone" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_mobile" value="<?php echo $p[0][0]['primary_mobile'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile')?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_mobile" value="<?php echo $p[0][0]['technical_mobile'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Mobile') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_mobile" value="<?php echo $p[0][0]['billing_mobile'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_yahoo" value="<?php echo $p[0][0]['primary_yahoo'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_yahoo" value="<?php echo $p[0][0]['technical_yahoo'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Yahoo') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_yahoo" value="<?php echo $p[0][0]['billing_yahoo'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_msn" value="<?php echo $p[0][0]['primary_msn'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_msn" value="<?php echo $p[0][0]['technical_msn'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Msn') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_msn" value="<?php echo $p[0][0]['billing_msn'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_skype" value="<?php echo $p[0][0]['primary_skype'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_skype" value="<?php echo $p[0][0]['technical_skype'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('Skype') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_skype" value="<?php echo $p[0][0]['billing_skype'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_aql" value="<?php echo $p[0][0]['primary_aql'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_aql" value="<?php echo $p[0][0]['technical_aql'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('AOL') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_aql" value="<?php echo $p[0][0]['billing_aql'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_icq" value="<?php echo $p[0][0]['primary_icq'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_icq" value="<?php echo $p[0][0]['technical_icq'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('ICQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_icq" value="<?php echo $p[0][0]['billing_icq'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="primary_qq" value="<?php echo $p[0][0]['primary_qq'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="technical_qq" value="<?php echo $p[0][0]['technical_qq'];?>" class="in-input"></td>
                <td style="text-align: right;font-weight:bold;"><?php __('QQ') ?></td>
                <td style="text-align: left;">&nbsp;<input type="text" name="billing_qq" value="<?php echo $p[0][0]['billing_qq'];?>" class="in-input"></td>
            </tr>
        </table>
    </div>
    <br/>
    <div id="add_bank" style="font-weight:bold;"><img onclick="changeBankDetails('show')" src="<?php echo $this->webroot?>images/bullet_toggle_plus.png"><?php __('Bank Details ( Optional )')?></div>
    <div id="bank_details" style="display:none;">
        <HR  width="100%" color='green' size=1>
        <br/>
        <table class="list1">
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Paypal Account')?>:</td>
                <td style="text-align: left;"><input type="text" name="paypal" value="<?php echo $p[0][0]['paypal'];?>" class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Bank Name')?>:</td>
                <td style="text-align: left;"><input type="text" name="bankname" value="<?php echo $p[0][0]['bank_name'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Bank Address')?>:</td>
                <td style="text-align: left;"><textarea  name="bankaddress"  class="in-input"><?php echo $p[0][0]['bank_address'];?></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Account Name')?>:</td>
                <td style="text-align: left;"><input type="text" name="accountname" value="<?php echo $p[0][0]['bank_account_name'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Account Number')?>:</td>
                <td style="text-align: left;"><input type="text" name="accountnumber" value="<?php echo $p[0][0]['bank_account_number'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Routing Number')?>:</td>
                <td style="text-align: left;"><input type="text" name="routingnumber" value="<?php echo $p[0][0]['bank_routing_number'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Swift # / IBAN')?> #:</td>
                <td style="text-align: left;"><input type="text" name="swiftiban" value="<?php echo $p[0][0]['bank_swift'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Notes')?>:</td>
                <td style="text-align: left;"><textarea  name="notes"  class="in-input"><?php echo $p[0][0]['bank_notes'];?></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Intermediately Bank')?>:</td>
                <td style="text-align: left;"><textarea  name="intermediately_bank"  class="in-input"><?php echo $p[0][0]['intermediately_bank'];?></textarea></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('ACH')?> #:</td>
                <td style="text-align: left;"><input type="text" name="ach" value="<?php echo $p[0][0]['ach'];?>"  class="in-input"></td>
            </tr>
            
            <tr>
                <td style="text-align: right;font-weight:bold;"><?php __('Currency Preference')?>:</td>
                <td style="text-align: left;"><?php __('USD')?></td>
            </tr>
        </table>
       
    </div>
            <br />
            <div style="text-align: center;">
            <input type="submit" value="<?php __('submit')?>" class="in-submit">
            <a href="<?php echo $this->webroot?>clients/registration"><input type="button" value="<?php __('reset')?>" class="in-submit"></a>
            </div>
    </form>
</div>

<script>
    
    $(function (){
        $("#registration").submit(function (){
            var flag = true;
            
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
                'url' : '<?php echo $this->webroot?>clients/check_email/'+corporatecontactemail+"/"+<?php echo $p[0][0]['client_id'];?>,
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
            
            
            company_name = $("#company_name").val()
            if(company_name == ''){
                flag = false;
                jGrowl_to_notyfy('Company Name can not be empty',{theme:'jmsg-error'});
            }
            
           
            
            
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
             
          
            /*security_question = $("#security_question").val()
            if(security_question == ''){
                flag = false;
                jGrowl_to_notyfy('Security Question can not be empty',{theme:'jmsg-error'});
            }
            
            if(security_question.length > 128){
                flag = false;
                jGrowl_to_notyfy('Security Question can not exceed 128 characters in length!',{theme:'jmsg-error'});
            }*/
            
            city = $("#city").val()
            if(city == ''){
                flag = false;
                jGrowl_to_notyfy('City can not be empty',{theme:'jmsg-error'});
            }
            
            /*security_answer = $("#security_answer").val()
            if(security_answer == ''){
                flag = false;
                jGrowl_to_notyfy('Security Answer can not be empty',{theme:'jmsg-error'});
            }
            
            if(security_question.length > 64){
                flag = false;
                jGrowl_to_notyfy('Security Answer can not exceed 64 characters in length!',{theme:'jmsg-error'});
            }*/
            
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


