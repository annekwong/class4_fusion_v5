<style type="text/css">
.form .label{width:120px;}
</style>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;View Credit Application</h1>
  <ul id="title-menu">
    <li> <?php echo $this->element('xback',Array('backUrl'=>'credits/index'))?> </li>
  </ul>
</div>
<div class="container">
  <fieldset>
    <legend>COMPANY INFORMATION</legend>
    <table class="form">
      <tr>
        <td align="right"><?php echo __('Legal Name',true);?>:</td>
        <td align="left">{$legal_name}</td>

        <td align="right"><?php echo __('Registration number',true);?>:</td>
        <td align="left">{$register_number}</td>
      </tr>
      <tr>
        <td align="right"><?php echo __('Established',true);?>:</td>
        <td align="left">{$established}</td>

        <td align="right" >Country of Incorporation:</td>
        <td style="text-align: left;">{$country_incorporation}</td>
      </tr>
      <tr>
        <td  align="right"><?php echo __('gross_annual_revenue',true);?>:</td>
        <td style="text-align: left;">{$gross_annual_revenue}</td>

      	<td  align="right"><?php echo __('Principals',true);?>:</td>
      	<td style="text-align: left;">{$principals}</td>
      </tr>
      
      <tr>
      	<td  align="right">Head Office Address:</td>
      	<td style="text-align: left;">{$head_office_address}</td>

      	<td  align="right"><?php echo __('Phone',true);?> :</td>
      	<td style="text-align: left;">{$phone}</td>
      </tr>
      <tr>
      	<td  align="right"><?php echo __('Email',true);?> :</td>
      	<td style="text-align: left;">{$email}</td>

      	<td  align="right"><?php echo __('Company URL',true);?>:</td>
      	<td style="text-align: left;">{$company_url}</td>
      </tr>
      <tr>
      	<td  align="right"><?php echo __('Annual Sales Volumes',true);?>:</td>
      	<td style="text-align: left;">{$annual_sales_volumes}</td>

      	<td  align="right"><?php echo __('D&B',true);?> (Dun & Bradstreet):</td>
      	<td style="text-align: left;">{$d_b}</td>
      </tr>
    </table>
  </fieldset>


<fieldset>
    <legend><?php echo __('Company Information',true);?></legend>
    <table class="form">
      <tr>
        <td align="right"><?php echo __('Bank (Branch) Name',true);?>:</td>
        <td align="left">{$bank_name}</td>

        <td align="right"><?php echo __('Address',true);?>:</td>
        <td align="left">{$address}</td>
      </tr>
      <tr>
        <td align="right"><?php echo __('city',true);?>:</td>
        <td align="left">{$city}</td>

        <td align="right" ><?php echo __('Postal Code',true);?>:</td>
        <td style="text-align: left;">{$postal_code}</td>
      </tr>
      <tr>
        <td  align="right"><?php echo __('country',true);?>:</td>
        <td style="text-align: left;">{$country}</td>

      	<td  align="right"><?php echo __('Bank Officer',true);?>:</td>
      	<td style="text-align: left;">{$bank_officer}</td>
      </tr>
      
      <tr>
      	<td  align="right"><?php echo __('Account Type',true);?>:</td>
      	<td style="text-align: left;">{$account_type}</td>

      	<td  align="right"><?php echo __('Account',true);?>  :</td>
      	<td style="text-align: left;">{$account}</td>
      </tr>
      <tr>
      	<td  align="right"><?php echo __('SWIFT',true);?> :</td>
      	<td style="text-align: left;">{$swift}</td>

      	<td  align="right"><?php echo __('Phone',true);?>:</td>
      	<td style="text-align: left;">{$bank_phone}</td>
      </tr>
      <tr>
      	<td  align="right"><?php echo __('Email',true);?>:</td>
      	<td style="text-align: left;">{$bank_email}</td>

      	<td  align="right"></td>
      	<td style="text-align: left;"></td>
      </tr>
    </table>
  </fieldset>


<fieldset>
    <legend><?php echo __('Trade References',true);?></legend>
    <table class="form">
      <tr>
        <td align="right">1# <?php echo __('Company Name',true);?>:</td>
        <td align="left">{$company_name_1}</td>

        <td align="right"><?php echo __('Years doing business',true);?>:</td>
        <td align="left">{$years_doing_business_1}</td>
      </tr>
      <tr>
        <td align="right"><?php echo __('Contact Person',true);?>:</td>
        <td align="left">{$contact_person_1}</td>

        <td align="right" ><?php echo __('Position',true);?>:</td>
        <td style="text-align: left;">{$position_1}</td>
      </tr>
      <tr>
        <td  align="right"><?php echo __('Phone',true);?>:</td>
        <td style="text-align: left;">{$trade_phone_1}</td>

      	<td  align="right"><?php echo __('Fax',true);?>:</td>
      	<td style="text-align: left;">{$trade_fax_1}</td>
      </tr>
      
      <tr>
      	<td  align="right"><?php echo __('Email',true);?>:</td>
      	<td style="text-align: left;">{$trade_email_1}</td>

      	<td  align="right"></td>
      	<td style="text-align: left;"></td>
      </tr>
      
      
      
      <tr>
        <td align="right">2# <?php echo __('Company Name',true);?>:</td>
        <td align="left">{$company_name_2}</td>

        <td align="right"><?php echo __('Years doing business',true);?>:</td>
        <td align="left">{$years_doing_business_2}</td>
      </tr>
      <tr>
        <td align="right"><?php echo __('Contact Person',true);?>:</td>
        <td align="left">{$contact_person_2}</td>

        <td align="right" ><?php echo __('Position',true);?>:</td>
        <td style="text-align: left;">{$position_2}</td>
      </tr>
      <tr>
        <td  align="right"><?php echo __('Phone',true);?>:</td>
        <td style="text-align: left;">{$trade_phone_2}</td>

      	<td  align="right"><?php echo __('Fax',true);?>:</td>
      	<td style="text-align: left;">{$trade_fax_2}</td>
      </tr>
      
      <tr>
      	<td  align="right"><?php echo __('Email',true);?>:</td>
      	<td style="text-align: left;">{$trade_email_2}</td>

      	<td  align="right"></td>
      	<td style="text-align: left;"></td>
      </tr>
      
      
      
      <tr>
        <td align="right">3# <?php echo __('Company Name',true);?>:</td>
        <td align="left">{$company_name_3}</td>

        <td align="right"><?php echo __('Years doing business',true);?>:</td>
        <td align="left">{$years_doing_business_3}</td>
      </tr>
      <tr>
        <td align="right"><?php echo __('Contact Person',true);?>:</td>
        <td align="left">{$contact_person_3}</td>

        <td align="right" ><?php echo __('Position',true);?>:</td>
        <td style="text-align: left;">{$position_3}</td>
      </tr>
      <tr>
        <td  align="right"><?php echo __('Phone',true);?>:</td>
        <td style="text-align: left;">{$trade_phone_3}</td>

      	<td  align="right"><?php echo __('Fax',true);?>:</td>
      	<td style="text-align: left;">{$trade_fax_3}</td>
      </tr>
      
      <tr>
      	<td  align="right"><?php echo __('Email',true);?>:</td>
      	<td style="text-align: left;">{$trade_email_3}</td>

      	<td  align="right"></td>
      	<td style="text-align: left;"></td>
      </tr>
    </table>
  </fieldset>
</div>
