<style type="text/css">
    .list tbody .row-hover td {
        font-size: 12px;
    }
    .list .label2{ text-align:right;font-size:12px;}
    .list td.last{ text-align:left;font-size:12px;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Invoice') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit Invoice') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit Invoice') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:void(0)" onclick="history.go(-1)">
            <i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form enctype="multipart/form-data" method="post" action="<?php echo $this->webroot ?>invoices/edit">
                <input type="hidden" id="invoice_id" value="<?php echo $list[0][0]['invoice_id'] ?>" name="invoice_id" class="input in-hidden">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <colgroup>
                    <col width="40%">
                    <col width="60%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="right"><?php echo __('Invoice No', true); ?> </td>
                            <td><strong><?php echo $list[0][0]['invoice_number'] ?></strong></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Invoice Date', true); ?> </td>
                            <td><?php echo $list[0][0]['invoice_time'] ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('type', true); ?> </td>
                            <td><img align="absmiddle" width="16" height="16" src="<?php echo $this->webroot ?>images/t-out.gif">
                                <?php
                                if ($list[0][0]['type'] == '0') {
                                    echo ' Outgoing invoice';
                                } else {
                                    echo ' incoming Invoices';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Due Date', true); ?> </td>
                            <td><?php echo $list[0][0]['due_date'] ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="delim single"><div></div></td>
                        </tr>

                        <tr>
                            <td class="right"><?php echo __('Client', true); ?>  </td>
                            <td><strong><?php echo $list[0][0]['client_name'] ?></strong></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Period', true); ?> </td>
                            <td><?php echo $list[0][0]['invoice_start'] ?> - <?php echo $list[0][0]['invoice_end'] ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Total', true); ?> </td>
                            <td>
                                <strong><?php echo $list[0][0]['amount1'] ?> </strong>
                                <span> / </span>
                                <strong><?php echo $list[0][0]['amount1'] ?> </strong>
                                &nbsp;&nbsp;<strong class="pos"></strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="delim single"><div></div></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('State', true); ?> </td>
                            <td>
                                <?php
                                $state = $list[0][0]['state'];
                                if ($state == 0)
                                    echo 'normal';
                                else if ($state == 1)
                                    echo 'send';
                                else if ($state == 2)
                                    echo 'vertify';
                                else if ($state == 3)
                                    echo 'sent failure';
                                else if ($state == 9)
                                    echo 'sended';
                                ?>
                                <!--<select id="state" name="state" style="width: 218px;"  class="input in-select">
                            <option selected="selected" value="0">normal</option>
                            <option value="1">to send </option>
                            <option value="2"> to verify </option></select>
                                --></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Credit', true); ?> </td>
                            <td><?php echo $list[0][0]['credit_amount'] ?><input type="hidden" id="credit"  style="width: 218px;" value="<?php echo $list[0][0]['credit_amount'] ?>" name="credit" class="input in-text"></td>
                        </tr>
                        <!--<tr>
                            <td>Attach file </td>
                            <td>
                                <input type="file" id="attach" style="width: 218px;" name="attach" class="input in-file">        <a style="padding-left: 5px; vertical-align: bottom;" title="Download File" href="/admin/invoices/getFile?id="><img width="16" height="16" src="/static/_viewimages/download.png"></a>    </td>
                        </tr>
                        --><tr>
                            <td class="right"><?php echo __('Attach CDR file', true); ?> </td>
                            <td>
                                <input type="file" id="attach_cdr" name="attach_cdr" class="input in-file">            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php
//var_dump($_SERVER["DOCUMENT_ROOT"],$list[0][0]['cdr_path']);
                                $cdr_path = $list[0][0]['cdr_path'];
                                if (!empty($cdr_path) && is_file($cdr_path)) {
                                    //$webroot = substr($_SERVER["DOCUMENT_ROOT"],-1) == '/' ? $_SERVER["DOCUMENT_ROOT"] : $_SERVER["DOCUMENT_ROOT"].'/';
                                    //$cdr_path = str_replace($webroot, '', $cdr_path);
                                    //$cdr_webroot = $_SERVER['HTTP_HOST'].'/'.$cdr_path;
                                    ?>
                                    <a href="<?php echo $this->webroot; ?>invoices/download_file/<?php echo str_replace('/', '|', $cdr_path); ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/attached_cdr.gif"></a>
                                <?php } ?>
                            </td>
                        </tr>

                    </tbody></table>

                <div id="form_footer" class="center">
                    <?php if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) { ?><input type="submit" value="<?php echo __('submit', true); ?>" class="input in-submit btn btn-primary">
                    <?php } ?>
                    <input type="button" value="<?php echo __('cancel', true); ?>" onclick="winClose();" class="input in-button btn btn-default">

                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    //&lt;![CDATA[


    var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
    function showClients()
    {
        ss_ids_custom['client'] = _ss_ids_client;
        winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);

    }
    var lastId = 0;
    function addItem(row)
    {
        lastId++;
        if (!row || !row['id']) {
            row = {};
        }

        // fix row values
        for (k in row) {
            if (row[k] == null)
                row[k] = '';
        }

        // prepare row
        var tRow = $('#tpl-entries').clone().appendTo($('#entries'));
        tRow.attr('id', 'row-' + lastId).show();

        // set names / values
        tRow.find('input,select').each(function() {
            var name = $(this).attr('name').substring(1).replace('%n', lastId);
            var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);
            $(this).attr('id', field + '-' + lastId);
            $(this).attr('name', name);
            $(this).val(row[field]);
        });

        // remove of the row
        tRow.find('a[rel=delete]').click(function() {
            $(this).closest('tr').remove();
            return false;
        });

        // styles
        initForms(tRow);
        initList();
        $('#add_positions').show();
    }

    /**
     * Watch for type of invoice generation 
     */


    function updateDirection() {
        if ($('#direction').val() == 'in') {
            $('#products_block').hide();
        } else {
            $('#products_block').show();
        }
    }
    updateDirection();

    function updateCurrency() {
        var currency = $('#id_currencies option:selected').text();
        $('#total_stats_cur').html(currency);
        $('#total_other_cur').html(currency);
    }
    updateCurrency();

    function updateCdrOutput() {
        if ($('#cdr_generate').attr('checked')) {
            $('#cdr_output_row').show();
        } else {
            $('#cdr_output_row').hide();
        }
    }
    updateCdrOutput();

    function setPeriod(type, current)
    {
        if (type != 'custom') {
            period = calcPeriod(type, current);
            $('#period_start-wDt').val(period['startDate']);
            $('#start_time-wDt').val(period['startTime']);
            $('#period_finish-wDt').val(period['stopDate']);
            $('#stop_time-wDt').val(period['stopTime']);
        }
        $('#smartPeriod').val(type);
    }
    setPeriod('prevWeek');

    $('#state').val('<?php echo $list[0][0]['state'] ?>');
    //]]&gt;
</script></div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity = 0.5" onmouseover="this.style.opacity = 1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
