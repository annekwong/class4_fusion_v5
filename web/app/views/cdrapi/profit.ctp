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
    <li><a href="<?php echo $this->webroot ?>cdrapi/profit">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/profit">
            <?php __('Profitability Analysis') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('Profitability Analysis'); ?>
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
            <ul>
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>cdrapi/profit/1"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                <li <?php if ($type == 2) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>cdrapi/profit/2"class="glyphicons right_arrow">
                        <i></i>
                        <?php __('Termination')?>
                    </a>
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
                                <td v-for="(column, index) in item">
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
                        <tr>
                            <td colspan="6"></td>
                            <td  valign="top" rowspan="9" colspan="2" style="padding-left: 10px;width:25%;">
                                <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                                <?php
                                echo $form->select('Cdr.field', array(), array(), array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                                ?>
                                <button type="button" class="btn btn-primary" style="display:block; width: 100%;" onclick="saveFields('<?php echo $this->webroot; ?>', '<?php echo $type == 1 ? 'profit' : 'profit_term'; ?>')">Save fields as default</button>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10"><?php echo __('Ingress', true); ?></td>
                            <td>
                                <?php
                                echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'class' => 'chosen-select', 'div' => false, 'multiple' => 'multiple', 'type' => 'select'));
                                ?><?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td class="in-out_bound">&nbsp;</td>
                            <td class="align_right padding-r10"><?php echo __('Egress', true); ?></td>
                            <td><?php echo $form->input('egress_alias', array('options' => $egress, 'label' => false, 'class' => 'chosen-select', 'empty' => '', 'div' => false, 'multiple' => 'multiple', 'type' => 'select')); ?>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                            <td class="in-out_bound">&nbsp;</td>
                        </tr>
                        <tr>
                            <?php echo $this->element('search_report/search_orig_country') ?>
                            <td class="in-out_bound" colspan="3">&nbsp</td>
                        </tr>
                        </tbody>
                    </table>

                    <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                    <input type="hidden" name="group_select[]" value="<?php echo $type == 1 ? 'ingress_id' : 'egress_id'; ?>">
                    <table class="form" style="width:100%">
                        <tr>
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <td class="align_right padding-r10"><?php __('Group By')?> #<?php echo $i; ?></td>
                                <td class="value">
                                    <select class="upper-first" name="group_select[]" style="width:160px;">
                                        <option value=""></option>
                                        <?php if ($type == 2) : ?>
                                        <option value="ingress_id">Ingress Trunk</option>
                                        <?php else: ?>
                                        <option value="egress_id">Egress Trunk</option>
                                        <?php endif; ?>
                                        <option value="orig_code">Orig Code</option>
                                        <option value="source_number">Source Number</option>
                                        <option value="dest_number">Destination Number</option>
                                    </select>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    </table>
                </div>
            </fieldset>

        </div>
    </div>
</div>


<script src="<?php echo $this->webroot;?>js/jschosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>

<script src="<?php echo $this->webroot; ?>js/report/base.js"></script>
<script>

    var currentUrl = "<?php echo $this->webroot; ?>cdrapi/ajaxRequest";
    var formObject = $("#report_form");
    var report = new ReportBase(currentUrl, formObject);

    <?php if ($type == 1): ?>
    var fields = [
        "total_duration", "total_duration_percent", "margin", "margin_percent", "total_calls",
        "not_zero_calls", "total_cost", "total_cost_term", "npr_count", "npr"
    ];
    <?php else: ?>
    var fields = [
        "total_duration_term", "total_duration_percent_term", "margin", "margin_percent",
        "total_calls_term", "not_zero_calls", "total_cost_term", "total_cost", "npr_count", "npr"
    ];
    <?php endif;?>

    var defaultFields = "<?php echo $defaultFields; ?>".split(',');

    var Vue = new Vue({
        el: "#app",
        data: {
            fields : [],
            table: {
                header: [],
                content: []
            },
            apiConnected: true
        },
        methods: {
            getData: function () {
                let data = report.getData();

                if (data == false) {
                    Vue.table.content = [];
                    Vue.apiConnected = false;
                } else {
                    Vue.apiConnected = true;

                    if (data.length > 0) {
                        Vue.table.header = {};
                        let groupFields = {
                            'ingress_id': 'Ingress Trunk',
                            'egress_id': 'Egress Trunk',
                            'orig_code': 'Country Code',
                            'dest_number': 'Destination Number',
                            'source_number': 'Source Number'
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
                    let selected = defaultFields.length > 0 ? defaultFields.indexOf(item) !== -1 ? 'selected' : '' : 'selected';

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
        Vue.initFields(function () {
            report.initData(function () {
                Vue.getData();
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
