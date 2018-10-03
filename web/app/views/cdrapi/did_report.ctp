<link rel="stylesheet" href="<?php echo $this->webroot;?>js/jschosen/chosen.css">
<style>
    table.form tbody tr td {
        vertical-align: top;
    }

    div.pagination {
        float: right;
        margin-top: 10px;
    }

    div.pagination:after {
        clear: both;
    }

    div.progress {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        z-index: 99999;
        background: rgba(117, 117, 117, 0.5);
        height: 5px;
    }

    div.progress-bar {
        height: 5px;
        background: rgba(8, 165, 8, 1);
    }

    select option:disabled {
        color: #ccc;
        font-style: italic;
    }

    .btn-primary:active, .btn-primary.active:focus {
        background-color: #354900;
    }

    .btn-primary:active, .btn-primary.active:hover {
        background-color: #354900;
    }
</style>

<div class="progress">
    <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="70"
         aria-valuemin="0" aria-valuemax="100" style="width:70%">
    </div>
</div>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/did_report">
            <?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/did_report">
            <?php __('DID Report') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('DID Report'); ?>
    </h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 0) echo 'class="active"'; ?>>
                    <a class="glyphicons no-js user" href="<?php echo $this->webroot; ?>cdrapi/did_report/0"><i></i>Client Report</a>
                </li>
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a class="glyphicons no-js user" href="<?php echo $this->webroot; ?>cdrapi/did_report/1"><i></i>Vendor Report</a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="app">
                <div class="wrapper small">
                    <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;" v-if="table.content.length > 0">
                        <thead>
                        <tr>
                            <th v-for="(column, index) in table.header">
                                {{column}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in table.content">
                            <tr>
                                <td v-for="(column, index) in item" v-if="typeof table.header[index] !== 'undefined'">
                                    {{column}}
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="center msg"  v-if="apiConnected == true && table.content.length == 0"><h2><?php  echo __('no_data_found') ?></h2></div>
                    <div class="center danger-msg"  v-if="apiConnected == false"><h2><i class="icon-warning"></i> Could Not Establish Connection With API</h2></div>
                </div>
            </div>

            <fieldset class="query-box" style=" clear:both;margin-top:10px;">
                <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
                <div class="clearfix"></div>
                <?php
                $url = "/" . $this->params['url']['url'];

                echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
                    'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
                ?>

                <table class="form" style="width: 100%">
                    <tbody>
                    <?php
                    echo $this->element('report/form_period', array(
                        'group_time' => true,
                        'newReport' => true,
                        'gettype' => '  <select id="query-output" name="query[output]" class="input in-select">
                                            <option value="web" selected>WEB</option>
                                            <option value="csv">CSV</option>
                                            <option value="xls">XLS</option>
                                        </select>'
                    ));
                    ?>
                    </tbody>
                </table>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <table class="form" style="width:100%">
                        <tbody>
                        <tr style="display: none;">
                            <td colspan="6"></td>
                            <td  valign="top" rowspan="9" colspan="2" style="padding-left: 10px;width:25%;">
                                <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                                <?php
                                echo $form->select('Cdr.field', array(), array(), array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">DID</td>
                            <td>
                                <input type="text" name="orig_src_number">
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if ($type == 1): ?>
                        <input type="hidden" name="group_select[]" value="ingress_id">
                    <?php else: ?>
                        <input type="hidden" name="group_select[]" value="egress_id">
                    <?php endif; ?>
                    <input type="hidden" name="group_select[]" value="dest_number">
                </div>
            </fieldset>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#query-start_time-wDt").click(function() {
            WdatePicker({dateFmt: 'HH:00:00'});
        });
        $("#query-stop_time-wDt").click(function() {
            WdatePicker({dateFmt: 'HH:00:00'});
        });
    });
</script>

<script src="<?php echo $this->webroot; ?>js/report/base.js"></script>
<script>
    var costField = "<?php echo $type == 1 ? "total_cost" : "total_cost_term"; ?>";
    var fields = [
        "asr", "acd", "total_calls", "not_zero_calls", 'total_duration', 'did_price', costField, 'monthly_charge'
    ];

    var currentUrl = "<?php echo $this->webroot; ?>cdrapi/ajaxRequest";
    var formObject = $("#report_form");

    // Custom headers
    var replaceFields = {
        'ingress_id': 'DID Vendor',
        'egress_id': 'DID Client',
        'dest_number': 'DID',
        'total_calls': 'Call Attempt',
        'not_zero_calls': 'Connected',
        'total_duration': 'Duration',
        'did_price': 'Setup Fee',
        'monthly_charge': 'Fixed Fee'
    };
    <?php if($type == 1): ?>
    replaceFields.total_cost = 'Usage Charge';
    <?php else: ?>
    replaceFields.total_cost_term = 'Usage Charge';
    <?php endif;?>

    // List for sorting
    var sortFields = [
        'DID Vendor', 'DID Client', 'DID', 'asr', 'acd', 'Call Attempt', 'Connected', 'Duration',
        'Setup Fee', 'Usage Charge', 'Fixed Fee',
    ];
    var report = new ReportBase(currentUrl, formObject);
    var vendorsList = "<?php echo $vendors; ?>".split(',');

    var Vue = new Vue({
        el: "#app",
        data: {
            fields : [],
            table: {
                header: [],
                content: []
            },
            numbers: [],
            apiConnected: true
        },
        methods: {
            getData: function () {
                let data = report.getData();
                let total_row_index = 'Total';
                if (data === 'false') {
                    Vue.table.content = [];
                } else {
                    if (data.length > 0) {
                        Vue.table.header = {};
                        for (item in data[0]) {
                            if (Vue.fields[item] !== undefined) {
                                Vue.table.header[item] = Vue.fields[item];
                            } else {
                                Vue.table.header[item] = item;
                            }
                        }
                        // if((typeof data[data.length -1]['egress_id'] !== 'undefined' && data[data.length -1]['egress_id'] == total_row_index) ||
                        //   (typeof data[data.length -1]['time'] !== 'undefined' && data[data.length -1]['time'] == total_row_index)){
                        //     delete data[data.length -1];
                        // }

                        for (var index in data) {
                            if (data[index]['dest_number'] === undefined) {
                                delete data[index];
                            }
                        }

                        Vue.table.content = data;
                    } else {
                        Vue.table.content = [];
                    }
                }
            },
            initFields: function (callback = null) {
                let allFields = report.fields;
                Vue.fields = {};

                $("#query-fields").html('');

                fields.forEach(function (item, key) {
                    Vue.fields[item] = allFields[item];
                    let selected = 'selected';

                    $("#query-fields").append('<option value="' + item + '" ' + selected + '>' + allFields[item] + '</option>');
                });
                if (typeof callback === 'function') {
                    callback();
                }
            },
            initNumbers: function (callback = null) {
                $.ajax({
                    'url': "<?php echo $this->webroot;?>cdrapi/ajaxGetDids",
                    'method': 'POST',
                    'type': 'json',
                    'success': function (response) {
                        Vue.numbers = JSON.parse(response);
                    }
                });

                if (typeof callback === 'function') {
                    callback();
                }
            }
        }
    });

    $(formObject).submit(function (event) {
        event.preventDefault();
        if ($("#query-output").val() == "web") {
            report.initData(function () {
                Vue.getData();
            });
        } else if ($("#query-output").val() == "csv") {
            report.getCsv();
        } else {
            report.getXls();
        }

        return false;
    });

    $(function () {
        if (vendorsList.length > 0) {
            vendorsList.forEach(function (item, key) {
                $(formObject).append("<input name='ingress_alias[]' value='" + item + "' type='hidden' >");
            });
        } else {
            $(formObject).append("<input name='ingress_alias[]' value='-1' type='hidden' >");
        }

        Vue.initFields(function () {
            Vue.initNumbers(function () {
                report.initData(function () {
                    Vue.getData();
                });
            });
        });
    });

</script>

<?php //if (Configure::read('debug') !== 0): ?>
<!--    <script>-->
<!--        (function() {-->
<!--            var origOpen = XMLHttpRequest.prototype.open;-->
<!--            XMLHttpRequest.prototype.open = function () {-->
<!--                this.addEventListener('load', function () {-->
<!--                    if ($("#requests").length == 0) {-->
<!--                        $(".cake-sql-log:last").after('<table class="cake-sql-log" id="requests"><thead><tr><th>Request</th><th>Response</th></tr></thead><tbody></tbody></table>');-->
<!--                    }-->
<!---->
<!--                    $("#requests tbody").append("<tr style='vertical-align: top'><td>" + this.responseURL + "</td><td>" + this.responseText + "</td></tr>");-->
<!--                });-->
<!--                origOpen.apply(this, arguments);-->
<!--            };-->
<!--        })();-->
<!--    </script>-->
<?php //endif; ?>
