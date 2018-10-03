<style type="text/css">
    .form .label{width:15%; height:25px; line-height:25px; font-weight:bold;border-bottom:1px dashed #ccc;}
    .form .value{width:30%;text-align:left; height:25px; line-height:25px; border-bottom:1px dashed #ccc;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="Finance"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('View Credit Application') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php __('Finance') ?>
        &gt;&gt;<?php echo __('View Credit Application', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="pull-right buttons newpadding">
        <?php echo $this->element('xback', Array('backUrl' => 'clients/credit_view')) ?> </li>

    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php $id = array_keys_value($this->params, 'pass.0') ?>
            <?php echo $form->create('Client', array('action' => 'credit_detail')); ?>
            <fieldset>
                <legend><?php echo __('Company Information', true); ?></legend>
                <table class="form table dynamicTable tableTools table-bordered  table-primary table-white">
                    <tr>
                        <td align="right"><?php echo __('Legal Name', true); ?>:</td>
                        <td align="left"><?php echo $p['legal_name']; ?></td>

                        <td align="right"><?php echo __('Register number', true); ?>:</td>
                        <td align="left"><?php echo $p['register_number']; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('Established', true); ?>:</td>
                        <td align="left"><?php echo $p['established']; ?></td>

                        <td align="right" ><?php echo __('Country of Incorporation', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['country_incorporation']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('gross_annual_revenue', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['gross_annual_revenue']; ?></td>

                        <td  align="right"><?php echo __('Principals', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['principals']; ?></td>
                    </tr>

                    <tr>
                        <td  align="right"><?php echo __('Head Office Address', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['head_office_address']; ?></td>

                        <td  align="right"><?php echo __('Phone', true); ?> :</td>
                        <td style="text-align: left;"><?php echo $p['phone']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Email', true); ?> :</td>
                        <td style="text-align: left;"><?php echo $p['email']; ?></td>

                        <td  align="right"><?php echo __('Company URL', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['company_url']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Annual Sales Volumes', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['annual_sales_volumes']; ?></td>

                        <td  align="right"><?php echo __('D&B(Dun & Bradstreet)', true); ?> :</td>
                        <td style="text-align: left;"><?php echo $p['d_b']; ?></td>
                    </tr>
                </table>
            </fieldset>


            <fieldset>
                <legend><?php echo __('Company Information', true); ?></legend>
                <table class="form table dynamicTable tableTools table-bordered  table-primary table-white">
                    <tr>
                        <td align="right"><?php __('Bank (Branch) Name') ?>:</td>
                        <td align="left"><?php echo $p['bank_name']; ?></td>

                        <td align="right"><?php echo __('Address', true); ?>:</td>
                        <td align="left"><?php echo $p['address']; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('City', true); ?>:</td>
                        <td align="left"><?php echo $p['city']; ?></td>

                        <td align="right" ><?php echo __('Postal Code', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['postal_code']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('country', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['country']; ?></td>

                        <td  align="right"><?php __('Bank Officer')?>:</td>
                        <td style="text-align: left;"><?php echo $p['bank_officer']; ?></td>
                    </tr>

                    <tr>
                        <td  align="right"><?php __('Account Type')?>:</td>
                        <td style="text-align: left;"><?php echo $p['account_type']; ?></td>

                        <td  align="right"><?php __('Account')?>:</td>
                        <td style="text-align: left;"><?php echo $p['account']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php __('SWIFT')?>:</td>
                        <td style="text-align: left;"><?php echo $p['swift']; ?></td>

                        <td  align="right"><?php echo __('Phone', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['bank_phone']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Email', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['bank_email']; ?></td>

                        <td  align="right"></td>
                        <td style="text-align: left;"></td>
                    </tr>
                </table>
            </fieldset>


            <fieldset>
                <legend><?php echo __('Trade References', true); ?></legend>
                <table class="form table dynamicTable tableTools table-bordered  table-primary table-white">
                    <tr>
                        <td align="right">1# <?php echo __('Company Name', true); ?>:</td>
                        <td align="left"><?php echo $p['company_name_1']; ?></td>

                        <td align="right"><?php echo __('Years doing business', true); ?>:</td>
                        <td align="left"><?php echo $p['years_doing_business_1']; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('Contact Person', true); ?>:</td>
                        <td align="left"><?php echo $p['contact_person_1']; ?></td>

                        <td align="right" ><?php echo __('Position', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['position_1']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Phone', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_phone_1']; ?></td>

                        <td  align="right"><?php echo __('Fax', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_fax_1']; ?></td>
                    </tr>

                    <tr>
                        <td  align="right"><?php echo __('Email', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_email_1']; ?></td>

                        <td  align="right"></td>
                        <td style="text-align: left;"></td>
                    </tr>



                    <tr>
                        <td align="right">2# <?php echo __('Company Name', true); ?>:</td>
                        <td align="left"><?php echo $p['company_name_2']; ?></td>

                        <td align="right"><?php echo __('Years doing business', true); ?>:</td>
                        <td align="left"><?php echo $p['years_doing_business_2']; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('Contact Person', true); ?>:</td>
                        <td align="left"><?php echo $p['contact_person_2']; ?></td>

                        <td align="right" ><?php echo __('Position', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['position_2']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Phone', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_phone_2']; ?></td>

                        <td  align="right"><?php echo __('Fax', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_fax_2']; ?></td>
                    </tr>

                    <tr>
                        <td  align="right"><?php echo __('Email', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_email_2']; ?></td>

                        <td  align="right"></td>
                        <td style="text-align: left;"></td>
                    </tr>



                    <tr>
                        <td align="right">3# <?php echo __('Company Name', true); ?>:</td>
                        <td align="left"><?php echo $p['company_name_3']; ?></td>

                        <td align="right"><?php echo __('Years doing business', true); ?>:</td>
                        <td align="left"><?php echo $p['years_doing_business_3']; ?></td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('Contact Person', true); ?>:</td>
                        <td align="left"><?php echo $p['contact_person_3']; ?></td>

                        <td align="right" ><?php echo __('Position', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['position_3']; ?></td>
                    </tr>
                    <tr>
                        <td  align="right"><?php echo __('Phone', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_phone_3']; ?></td>

                        <td  align="right"><?php echo __('Fax', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_fax_3']; ?></td>
                    </tr>

                    <tr>
                        <td  align="right"><?php echo __('Email', true); ?>:</td>
                        <td style="text-align: left;"><?php echo $p['trade_email_3']; ?></td>

                        <td  align="right"></td>
                        <td style="text-align: left;"></td>
                    </tr>
                </table>
            </fieldset>

            <div id="form_footer" class="center">
                <input type="hidden" name="data[Credit][id]" value="<?php echo $id; ?>" />
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="submit" value="<?php echo __('submit', true); ?>" class="input in-submit btn btn-primary">
                <input type="button" value="<?php __('Cancel')?>" onClick="winClose();" class="input in-button btn btn-default">
            </div>
            <?php echo $form->end(); ?> </div>
    </div>
</div>

