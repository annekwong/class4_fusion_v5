<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>email_log"><?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>email_log"><?php echo __('Email Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Email Log',true);?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2>
                        <?php echo __('No Data Found', true); ?>
                    </h2>
                </div>
            <?php else: ?>
                <table class="dynamicTable colVis list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('EmailLog.send_time', __('Sent Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('client.name', __('Carrier', true)) ?></th>
                        <th><?php __('Type')?></th>
                        <th><?php echo $appCommon->show_order('EmailLog.email_addresses', __('Email Address', true)) ?></th>
                        <th><?php echo $appCommon->show_order('EmailLog.status', __('Email Status', true)) ?></th>
                        <!--th><?php __('Failure Cause')?></th-->
                        <!--th><?php __('Action')?></th-->
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['EmailLog']['send_time']; ?></td>
                            <td>
                                <?php
                                if($item['EmailLog']['client_id'] != 0){
                                    echo $item['client']['name'];
                                }

                                ?>
                            </td>
                            <td>
                                <?php
                                echo array_key_exists($item['EmailLog']['type'], $types) ? $types[$item['EmailLog']['type']] : '';
                                ?>
                            </td>
                            <td>
                                 <a href="javascript:void(0)" title="<?php echo trim($item['EmailLog']['email_addresses'], '"') ?>" data-layout="center" data-type="primary" data-toggle="notyfy"
                                   data-value="<?php echo trim($item['EmailLog']['email_addresses'], '"') ?>">
                                    <?php echo $appCommon->sub_string(trim($item['EmailLog']['email_addresses'], '"'),40); ?>
                                </a>
                            </td>
                            <td>
                                <?php if($item['EmailLog']['status'] == null): ?>
                                    Waiting
                                <?php elseif($item['EmailLog']['status'] == 0): ?>
                                    Success
                                <?php else: ?>
                                    <a href="javascript:void(0)" title="<?php echo trim($item['EmailLog']['error'], '"') ?>" data-layout="center" data-type="primary" data-toggle="notyfy"
                                       data-value="<?php echo trim($item['EmailLog']['error'], '"') ?>">
                                        Fail
                                    </a>
                                <?php endif; ?>

                            </td>
                            <!--td><a href="javascript:void(0)" data-layout="center" data-type="primary" data-toggle="notyfy"
                                   data-value="<?php echo htmlspecialchars(trim($item['EmailLog']['error'],'"')); ?>">
                                    <?php echo $appCommon->sub_string(htmlspecialchars(trim($item['EmailLog']['error'],'"')),40); ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                if($item['EmailLog']['status'] && $item['EmailLog']['type'] == 7){
                                    echo '<a href="'. "{$this->webroot}email_log/resend_email/{$item['EmailLog']['id']}" . '" title="Resend"><i class="icon-reply"></i></a>';
                                }
                                $files = explode(';', $item['EmailLog']['files']);

                                foreach ($files as $file):
                                    if(!empty($file)) {
                                        echo '<a class="icon-download" title="Download" target="_block" href="' . $this->webroot .'email_log/get_file/' . base64_encode($file)  .'"><i></i></a>&nbsp;';
                                        break;
                                    }
                                endforeach;
                                ?>
                            </td-->
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="clearfix"></div>


            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <div style="margin:0px auto; text-align:center;">
                    <form method="get" name="myform">
                        <?php __('Carrier')?>:
                        <select name="client">
                            <option value=""><?php __('All')?></option>
                            <?php foreach($clients as $client): ?>
                                <option <?php if(isset($_GET['client']) && $_GET['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php __('Type')?>
                        <select name="type">
                            <option value=""><?php __('All')?></option>
                            <?php foreach ($types as $key=>$value): ?>
                                <option value="<?php echo $key; ?>" <?php if(isset($_GET['type']) && $_GET['type'] == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php __('Email')?>:
                        <input type="text" value="<?php echo $email ?>" name="email" />
                        <?php __('Time')?>:
                        <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                        ~
                        <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                        <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/custom_notyfy.js"></script>

