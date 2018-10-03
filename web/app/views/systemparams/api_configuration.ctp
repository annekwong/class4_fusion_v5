<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('API Configuration') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('API Configuration') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

        <div class="widget widget-heading-simple widget-body-white">
            <div class="widget-body">
                
                <div class="widget widget-tabs widget-tabs-double-2 widget-tabs-gray">

                    <!-- Tabs Heading -->
<!--                    <div class="widget-head">
                        <ul>
                            <li class="active"><a data-toggle="tab" href="#tabAPI" class="glyphicons keys"><i></i><span><?php __('API Key') ?></span></a></li>
                            <li><a data-toggle="tab" href="#tabLink" class="glyphicons link"><i></i><span><?php __('API Link') ?></span></a></li>
                        </ul>
                    </div>-->
                    <!-- // Tabs Heading END -->

                    <div class="widget-body">
                        <div class="tab-content">

                            <!-- Tab content -->
                            <div class="tab-pane widget-body-regular active" id="tabAPI">
                                <div class="margin-none center">
                                        <h4 class="separator bottom"><?php __('API Key') ?>:<?php echo $api_key; ?></h4>
                                        <a href="<?php echo $this->webroot ?>systemparams/regenerate_api_key" class="btn btn-primary"><?php __('Regenerate')?></a>
                                </div>
                            </div>
                            <!-- // Tab content END -->

                            <!-- Tab content -->
                            <div class="tab-pane widget-body-regular active" id="tabLink">
                                <div class="accordion" id="accordion">

                                    <!-- Accordion Item -->
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-1">
                                                <?php __('Billing Rule')?>
                                            </a>
                                        </div>
                                        <div style="height: 0px;" id="collapse-1" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                    <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/billing_rule/add.json')?></div>
                                                    <table class="table  tableTools table-bordered  table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="center"><?php __('Parameter')?></th>
                                                                <th><?php __('Description')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="center"><?php __('name')?></td>
                                                                <td><?php __('Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('did_price')?></td>
                                                                <td><?php __('Price/DID/Month')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('channel_price')?></td>
                                                                <td><?php __('Price/Channel Limit')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('min_price')?></td>
                                                                <td><?php __('Price/Channel Limit')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('billed_channels')?></td>
                                                                <td><?php __('Price/Max Channel Usage')?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                     <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/billing_rule/modify/{id}.json')?></div>
                                                    <table class="table  tableTools table-bordered  table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="center"><?php __('Parameter')?></th>
                                                                <th><?php __('Description')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="center"><?php __('name')?></td>
                                                                <td><?php __('Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('did_price')?></td>
                                                                <td><?php __('Price/DID/Month')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('channel_price')?></td>
                                                                <td><?php __('Price/Channel Limit')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('min_price')?></td>
                                                                <td><?php __('Price/Channel Limit')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('billed_channels')?></td>
                                                                <td><?php __('Price/Max Channel Usage')?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                     
                                                     <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/billing_rule/remove/{id}.json')?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Accordion Item END -->

                                    <!-- Accordion Item -->
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-2">
                                                  <?php __('Client')?>
                                            </a>
                                        </div>
                                        <div style="height: auto;" id="collapse-2" class="accordion-body in collapse">
                                            <div class="accordion-inner">
                                                    <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/clients/add.json')?></div>
                                                    <table class="table  tableTools table-bordered  table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="center"><?php __('Parameter')?></th>
                                                                <th><?php __('Description')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="center"><?php __('name')?></td>
                                                                <td><?php __('Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('login_username')?></td>
                                                                <td><?php __('Portal Login Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('login_password')?></td>
                                                                <td><?php __('Portal Login Password')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('media_type')?></td>
                                                                <td><?php __('1:Proxy Media 2:Bypass Media')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('t38')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('tfc2833')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('auto_invocing')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('pricing_rule')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('ip_addresses')?>:</td>
                                                                <td><?php __('list of ip address')?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                     <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/clients/modify/{id}.json')?></div>
                                                    <table class="table  tableTools table-bordered  table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="center"><?php __('Parameter')?></th>
                                                                <th><?php __('Description')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="center"><?php __('name')?></td>
                                                                <td><?php __('Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('login_username')?></td>
                                                                <td><?php __('Portal Login Name')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('login_password')?></td>
                                                                <td><?php __('Portal Login Password')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('media_type')?></td>
                                                                <td><?php __('1:Proxy Media 2:Bypass Media')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('t38')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('tfc2833')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('pricing_rule')?></td>
                                                                <td><?php __('1: Yes 0: No')?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="center"><?php __('ip_addresses')?>:</td>
                                                                <td><?php __('list of ip address')?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                     
                                                     <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/clients/remove/{id}.json')?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Accordion Item END -->

                                    <!-- Accordion Item -->
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-3">
                                                <?php __('Vendor')?>
                                            </a>
                                        </div>
                                        <div style="height: 0px;" id="collapse-3" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/add.json')?></div>
                                                <table class="table  tableTools table-bordered  table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th class="center"><?php __('Parameter')?></th>
                                                            <th><?php __('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="center"><?php __('name')?></td>
                                                            <td><?php __('Name')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('login_username')?></td>
                                                            <td><?php __('Portal Login Name')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('login_password<')?>/td>
                                                            <td><?php __('Portal Login Password')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('media_type')?></td>
                                                            <td><?php __('1:Proxy Media 2:Bypass Media')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('t38')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('tfc2833')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('pricing_rule')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('ip_addresses')?>:</td>
                                                            <td><?php __('list of ip address')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/modify/{id}.json')?></div>
                                                <table class="table  tableTools table-bordered  table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th class="center"><?php __('Parameter')?></th>
                                                            <th><?php __('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="center"><?php __('name')?></td>
                                                            <td><?php __('Name')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('login_username')?></td>
                                                            <td><?php __('Portal Login Name')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('login_password')?></td>
                                                            <td><?php __('Portal Login Password')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('media_type')?></td>
                                                            <td><?php __('1:Proxy Media 2:Bypass Media')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('t38')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('tfc2833')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('auto_invocing')?></td>
                                                            <td><?php __('1: Yes 0: No')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('ip_addresses')?>:</td>
                                                            <td><?php __('list of ip address')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/remove/{id}.json')?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Accordion Item END -->

                                    
                                    <!-- Accordion Item -->
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-4">
                                                <?php __('DID')?>
                                            </a>
                                        </div>
                                        <div style="height: 0px;" id="collapse-4" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/did_reposs/add.json')?></div>
                                                <table class="table  tableTools table-bordered  table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th class="center"><?php __('Parameter')?></th>
                                                            <th><?php __('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="center"><?php __('did')?></td>
                                                            <td><?php __('DID')?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="center"><?php __('vendor_id')?></td>
                                                            <td><?php __('Vendor ID')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/did_reposs/{did}.json')?></div>
                                                <table class="table  tableTools table-bordered  table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th class="center"><?php __('Parameter')?></th>
                                                            <th><?php __('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="center"><?php __('vendor_id')?></td>
                                                            <td><?php __('Vendor ID')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/did_reposs/remove/{did}.json')?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Accordion Item END -->

                           
                            
                            <!-- // Accordion Item END -->
                            <!-- Accordion Item -->
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-5">
                                        <?php __('DID Assignment')?>
                                    </a>
                                </div>
                                <div style="height: 0px;" id="collapse-5" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/did_assign/add.json')?></div>
                                        <table class="table  tableTools table-bordered  table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="center"><?php __('Parameter')?></th>
                                                    <th><?php __('Description')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="center"><?php __('did')?></td>
                                                    <td><?php __('DID')?></td>
                                                </tr>
                                                <tr>
                                                    <td class="center"><?php __('client_id')?></td>
                                                    <td><?php __('Client ID')?></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/did_assign/{did}.json')?></div>
                                        <table class="table  tableTools table-bordered  table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="center"><?php __('Parameter')?></th>
                                                    <th><?php __('Description')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="center"><?php __('client_id')?></td>
                                                    <td><?php __('Client ID')?></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="alert alert-primary"><?php __('POST')?>    <?php echo $this->webroot ?><?php __('api/vendors/did_assign/remove/{did}.json')?></div>
                                    </div>
                                </div>
                            </div>
                            <!-- // Accordion Item END -->

                            
                        </div>
                    </div>
                    <!-- // Tab content END -->

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
            
</div>