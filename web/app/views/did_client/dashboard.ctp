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
<ul class="breadcrumb">
    <li></li>
</ul>
<div class="progress">
    <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="70"
         aria-valuemin="0" aria-valuemax="100" style="width:70%">
    </div>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget  widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <fieldset class="query-box" style=" clear:both;margin-top:10px;">
                <!--                <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> --><?php //__('search') ?><!--</h4>-->
                <div class="clearfix"></div>
                <?php
                $url = "/" . $this->params['url']['url'];

                echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
                    'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
                ?>

                <div>
                    <label for="">DID:</label>
                    <select name="orig_src_number" id="">
                        <option value="">All</option>
                        <?php foreach ($dids as $did): ?>
                            <option value="<?php echo $did['DidBillingRel']['did'] ?>"><?php echo $did['DidBillingRel']['did'] ?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <table class="form" style="width: 100%">
                    <tbody>
                    <?php
                    echo $this->element('report/form_period', array(
                        'group_time' => true,
                        'newReport' => true,
                        'gettype' => '  <input type="hidden" name="query[output]" value="web" class="input in-select">'
                    ));
                    ?>
                    </tbody>
                </table>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray" style="display: none;">
                    <table class="form" style="width:100%">
                        <tbody>
                        <tr>
                            <td colspan="6"></td>
                            <td  valign="top" rowspan="9" colspan="2" style="padding-left: 10px;width:25%;">
                                <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                                <?php
                                echo $form->select('Cdr.field', array(), array(), array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                                ?>
                                <button type="button" class="btn btn-primary" style="display:block; width: 100%;" onclick="saveFields('<?php echo $this->webroot; ?>', 'did_client_reports_did')">Save fields as default</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="group_select[]" value="dest_number">
                </div>
            </fieldset>
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
                                <td v-for="(column, index) in item"  v-if="typeof table.header[index] !== 'undefined'">
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
<script src="https://unpkg.com/vue"></script>
<script>
    var fields = [
        "calls", "success_calls", "asr_global", "acd", "total_duration"
    ];

    var currentUrl = "<?php echo $this->webroot; ?>cdrapi/ajaxRequest";
    var formObject = $("#report_form");
    var report = new ReportBase(currentUrl, formObject);
    var clientTrunks = "<?php echo $clientTrunks; ?>".split(',');

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

                if (data === false) {
                    Vue.table.content = [];
                    Vue.apiConnected = false;
                } else {
                    Vue.apiConnected = true;

                    if (data.length > 0) {
                        Vue.table.header = {};
                        let groupFields = {
                            'time': 'Time',
                            'ingress id': 'Ingress Trunk',
                            'egress id': 'Egress Trunk',
                            'country code': 'Country Code',
//                        'ingress id': 'Ingress Trunk',
                            'destination number': 'Destination Number'
                        }
                        for (item in data[0]) {
                            if (groupFields[item] !== undefined) {
                                Vue.table.header[item] = groupFields[item];
                            } else {
                                Vue.table.header[item] = Vue.fields[item];
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
            }
        }
    });

    $(formObject).submit(function (event) {
        event.preventDefault();
        report.initData(function () {
            Vue.getData();
        });

        return false;
    });

    $(function () {
        if (clientTrunks.length > 0) {
            clientTrunks.forEach(function (item, key) {
                $(formObject).append("<input name='egress_alias[]' value='" + item + "' type='hidden' >");
            });
        } else {
            $(formObject).append("<input name='egress_alias[]' value='-1' type='hidden' >");
        }

        Vue.initFields(function () {
            report.initData(function () {
                Vue.getData();
            });
        });
    });

</script>
