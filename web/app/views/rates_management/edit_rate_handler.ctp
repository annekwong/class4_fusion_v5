<style type="text/css">
    .form_input {float:left;width:220px;}

    .container ul{
        padding-left:20px;
    }
    .container ul li {
        padding:3px;
    }
    select,input[type="text"]{margin: 5px 0;}
    .table-condensed{border-left: 1px solid #EBEBEB;border-bottom: 1px solid #EBEBEB;}
    .table-condensed td{border-right:1px solid #EBEBEB;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Auto Rate Upload', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit Rate Handler', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Rate Upload', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rates_management/rate_handler_list"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>
        <div class="widget-body">
            <form action="<?php echo $this->webroot; ?>rates_management/add_save_rate_handler_handle" method="post" >
                <input type="hidden" name="id" value="<?php echo $data['RateHandler']['id']; ?>" />
                <table class="table table-condensed" >
                    <col width="40%">
                    <col width="60%">
                    <tr>
                        <td class="align_right"><?php __('Name') ?></td>
                        <td>
                            <input type="text" name="name" class="validate[required]"  value="<?php echo $data['RateHandler']['name']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"> <?php __('Rate Delivery From') ?></td>
                        <td>
                            <input type="text" name="rate_delivery_from" class="input-xlarge validate[custom[email]]" value="<?php echo $data['RateHandler']['rate_delivery_from']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Rate Delivery To') ?></td>
                        <td>
                            <input type="text" name="rate_delivery_to" class="input-xlarge validate[custom[email]]" value="<?php echo $data['RateHandler']['rate_delivery_to']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"> <?php __('SMTP Host') ?></td>
                        <td>
                            <input type="text" name="smtp_host" class="validate[required]" value="<?php echo $data['RateHandler']['smtp_host']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('SMTP Port') ?></td>
                        <td>
                            <input type="text" name="smtp_port" class="validate[required]" value="<?php echo $data['RateHandler']['smtp_port']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Imap Host') ?></td>
                        <td>
                            <input type="text" name="imap_host" class="validate[required]" value="<?php echo $data['RateHandler']['imap_host']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Imap Port') ?></td>
                        <td>
                            <input type="text" name="imap_port" class="validate[required]" value="<?php echo $data['RateHandler']['imap_port']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Mail Password') ?></td>
                        <td>
                            <input type="password" name="password" />
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right"><?php __('SSL Auth') ?></td>
                        <td>
                            <input id="mail_ssl" class="input-xlarge" type="checkbox" name="mail_ssl" <?php if(!empty($data['RateHandler']['mail_ssl'])): ?>checked="checked"<?php endif; ?>/>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('TLS Auth') ?></td>
                        <td>
                            <input id="mail_tls" class="input-xlarge" type="checkbox" name="mail_tls" <?php if(!empty($data['RateHandler']['mail_tls'])): ?>checked="checked"<?php endif; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right"><?php __('Rate Table') ?></td>
                        <td>
                            <select name="rate_table_id">
                                <?php foreach ($rate_table as $rate_table_id => $rate_table_name): ?>
                                    <option value="<?php echo $rate_table_id; ?>" <?php if(!strcmp($rate_table_id,$data['RateHandler']['rate_table_id'])): ?><?php endif; ?> ><?php echo $rate_table_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr style="text-align:center;">
                        <td class="buttons-group center" colspan="2">
                            <input class="input in-submit btn btn-primary" type="submit" value="<?php __('Submit') ?>">
                            <input class="input in-submit btn btn-default" type="reset" value="<?php __('Revert') ?>">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>