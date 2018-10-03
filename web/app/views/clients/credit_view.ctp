<ul class="breadcrumb">
    <li><?php __('You are here')?>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Credit Application') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Credit Application') ?></h4>
    <div>
        <?php if (isset($extraSearch)) { ?>
            <a class="link_back" href="<?php echo $extraSearch ?>" onClick="history.go(-1)"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot ?>images/icon_back_white.png"> &nbsp;<?php echo __('goback') ?> </a>
        <?php } ?>

    </div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">





            <?php
            $action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
            $w = isset($action['writable']) ? $action['writable'] : '';
            ?>



            <!-- <div id="toppage"></div>-->
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)) {
                ?>
                <div class="msg"><?php echo __('no_data_found', true); ?></div>
            <?php } else {
                ?>
                <div class="overflow_x">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary " id="key_list" >
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('id', __('id', true)) ?></th>
                            <th><?php echo $appCommon->show_order('legal_name', __('Legal Name', true)) ?></th>
                            <th></th>
                            <th><?php echo $appCommon->show_order('register_number', __('Register number', true)) ?></th>
                            <th><?php echo $appCommon->show_order('established', __('Established', true)) ?></th>
                            <th><?php echo $appCommon->show_order('country_incorporation', __('country', true)) ?></th>
                            <th><?php echo $appCommon->show_order('gross_annual_revenue', __('gross_annual_revenue', true)) ?></th>
                            <th><?php echo $appCommon->show_order('principals', __('Principals', true)) ?></th>
                            <th><?php echo $appCommon->show_order('head_office_address', __('Address', true)) ?></th>
                            <th><?php echo $appCommon->show_order('phone', __('Phone', true)) ?></th>
                            <th><?php echo $appCommon->show_order('email', __('Email', true)) ?></th>

                            <th><?php echo $appCommon->show_order('company_url', __('Company URL', true)) ?></th>
                            <th><?php echo $appCommon->show_order('annual_sales_volumes', __('Annual Sales Volumes', true)) ?></th>
                            <th><?php echo $appCommon->show_order('d_b', __('D&B', true)) ?> </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['id']; ?></td>
                                <td><a href="<?php echo $this->webroot ?>clients/credit_detail/<?php echo base64_encode($mydata[$i][0]['id']); ?>"><?php echo $mydata[$i][0]['legal_name']; ?></a></td>
                                <td style="text-align:center;">
                                    <a title="<?php echo __('download') ?>" 
                                       href="<?php echo $this->webroot . 'clients/createpdf_credit/' . base64_encode($mydata[$i][0]['id']) ?>" ><i class="icon-file-text"></i>
                                </td>
                                <td><?php echo $mydata[$i][0]['register_number']; ?></td>
                                <td><?php echo $mydata[$i][0]['established']; ?></td>
                                <td><?php echo $mydata[$i][0]['country_incorporation']; ?></td>
                                <td><?php echo $mydata[$i][0]['gross_annual_revenue']; ?></td>
                                <td><?php echo $mydata[$i][0]['principals']; ?></td>
                                <td><?php echo $mydata[$i][0]['head_office_address']; ?></td>
                                <td><?php echo $mydata[$i][0]['phone']; ?></td>
                                <td><?php echo $mydata[$i][0]['email']; ?></td>
                                <td><?php echo $mydata[$i][0]['company_url']; ?></td>
                                <td><?php echo $mydata[$i][0]['annual_sales_volumes']; ?></td>
                                <td><?php echo $mydata[$i][0]['d_b']; ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>

        </div>
    </div>
</div>