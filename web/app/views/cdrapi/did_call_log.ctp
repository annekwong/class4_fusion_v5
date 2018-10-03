<style>
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
    <li><a href="<?php echo $this->webroot ?>cdrapi/summary_reports">
            <?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/summary_reports">
            <?php __('DID Call Log') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('DID Call Log'); ?>
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
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>cdrapi/summary_reports" class="glyphicons list">
                        <i></i>
                        <?php __('DID Call Log Search') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>cdrapi/export_log/1" class="glyphicons book_open">
                        <i></i>
                        <?php __('DID Call Export Log') ?>
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
                            <th v-for="column in table.header" v-if="column.active == true">
                                {{column.key}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in table.content">
                            <tr>
                                <td v-for="(column, index) in item" v-if="column.active == true">
                                    {{column.value}}
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="center msg"  v-if="table.content.length == 0"><h2><?php  echo __('no_data_found') ?></h2></div>
                </div>
                <!--                    <div class="pagination" v-if="table.content.length > 0">-->
                <!--                        <button id="prev" class="btn margin-bottom10 btn-primary" v-on:click="prevPage">Prev</button>-->
                <!--                        <span id="currentPage">{{table.offset + 1}}</span>-->
                <!--                        of-->
                <!--                        <span id="totalPages">{{table.maxOffset}}</span>-->
                <!--                        <button id="next" class="btn margin-bottom10 btn-primary"  v-on:click="nextPage">Next</button>-->
                <!--                    </div>-->

                <div class="pagination" v-if="table.content.length > 1">
                    <button class="btn btn-primary" v-for="item in table.maxOffset" v-if="(table.offset + 1 - item <= 2 && item - table.offset - 1 <= 2) || (table.offset <= 2 && item <= 5) || (table.maxOffset - table.offset - 1 < 2 && table.maxOffset - item <= 4)" v-bind:class="table.offset + 1 == item ? 'active': ''" v-on:click="setPage(item)">{{item}}</button>
                </div>
            </div>

            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
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
                        'group_time' => false,
                        'gettype' => '  <select id="query-output" name="query[output]" class="input in-select">
                                            <option value="web">Web</option>
                                            <option value="csv">CSV</option>
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
                                    echo $form->select('Cdr.field', $cdr_field, $report_fields, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true, 'selected' => $report_fields), false);
                                    ?>
                                    <button type="button" class="btn btn-primary" style="display:block; width: 100%;" onclick="saveFields('<?php echo $this->webroot; ?>', 'did_call_log')">Save fields as default</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php echo __('Origination Vendor', true); ?></td>
                                <td><?php
                                    echo $form->input('ingress_alias', array('options' => $ingresses, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                                <td class="in-out_bound">&nbsp;</td>
                                <?php
                                if ($outbound_report)
                                {
                                    ?>
                                    <td class="align_right padding-r10"><?php echo __('Origination Client', true); ?></td>
                                    <td><?php echo $form->input('egress_alias', array('options' => $carriers, 'label' => false, 'empty' => '', 'div' => false, 'type' => 'select')); ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                                    <td class="in-out_bound">&nbsp;</td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td class="align_right padding-r10">DID</td>
                                <td>
                                    <input type="text" name="orig_src_number" id="orig_src_number" value="<?php if(isset($getData['orig_src_number']) && !empty($getData['orig_src_number']) && $getData['orig_src_number'] != $dids) echo $getData['orig_src_number'];?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php echo $form->end(); ?>
            </fieldset>

        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>js/report.js"></script>
<script src="<?php echo $this->webroot; ?>js/report/base.js"></script>

<script>
    var optionalHeaders = {
        "start_time_of_date": "Time",
        "call_duration": "Duration",
        "origination_source_number": "ANI",
        "origination_destination_number": "DNIS",
        "trunk_id_termination": "Origination Client",
        "trunk_id_origination": "Origination Vendor",
        "ingress_client_rate": "Buy Rate",
        "ingress_client_cost": "Buy Cost",
        "egress_cost": "Sell Cost",
        "origination_source_host_name": "Origination IP",
        "termination_destination_host_name": "Termination IP"
    };
    var webroot = "<?php echo $this->webroot; ?>";
    var currentUrl = "<?php echo $this->webroot; ?>cdrapi/ajaxRequest";
    var formObject = $("#report_form");
    var report = new Report(webroot, currentUrl, formObject, optionalHeaders);
    var defaultFields = "<?php echo $defaultFields; ?>".split(',');
    var dids = "<?php echo $dids ?>";

    var Vue = new Vue({
        el: "#app",
        data: {
            fields : [],
            table: {
                header: [],
                content: [],
                offset: 0,
                maxOffset: 0
            }
        },
        methods: {
            getData: function () {
                let data = report.getData(Vue.table.offset);
                if(data.length > 0) {
                    let header = [];

                    data[0].forEach(function (item, key) {
                        header.push({
                            'key': item.key,
                            'active': item.active
                        });
                    })

                    Vue.table.header = header;
                    Vue.table.content = data;
                } else {
                    Vue.table.header = [];
                    Vue.table.content = [];
                }
            },
            setMaxOffset: function () {
                Vue.table.maxOffset = report.getMaxOffset();
            },
            clearOffset: function () {
                Vue.table.offset = 0;
            },
            prevPage: function () {
                if ((Vue.table.offset - 1) < 0) {
                    return true;
                }

                Vue.table.offset--;
                Vue.getData();
            },
            nextPage: function () {
                if ((Vue.table.offset + 1) >= Vue.table.maxOffset) {
                    return true;
                }

                if ((Vue.table.offset + 2) == Vue.table.maxOffset) {
                    Vue.loadData();
                }

                Vue.getData();
            },
            loadData: function () {
                report.loadData( function () {
                    Vue.setMaxOffset();
                });
            },
            setPage: function (item) {
                Vue.table.offset = item - 1;

                if ((Vue.table.offset + 1) == Vue.table.maxOffset) {
                    Vue.loadData();
                }

                Vue.getData();
            },
            initFields: function (fields) {
                $("#query-fields").html('');
                fields.forEach(function (item, key) {
                    let selected = defaultFields.indexOf(key.toString()) !== -1 ? 'selected' : '';
                    $("#query-fields").append('<option value="' + key + '" ' + selected + '>' + item + '</option>');
                });
            }
        }
    });

    $(formObject).submit(function (event) {
        event.preventDefault();
        checkDidInput();

        if ($("#query-output").val() == "web") {
            Vue.clearOffset();
            report.initData(function () {
                Vue.getData();
                Vue.setMaxOffset();
            });
        } else {
            report.getCsv(function () {
                setTimeout(function () {
                    location.href = "<?php echo $this->webroot;?>cdrapi/export_log/1";
                }, 200)
            });
        }


        return false;
    });

    $(function () {
        checkDidInput();

        report.initHeader(function () {
            Vue.fields = report.getHeader();
            Vue.initFields(report.getHeader());
            report.initData(function () {
                Vue.getData();
                Vue.setMaxOffset();
            });
        });
    });

    function checkDidInput() {
        $("#orig_src_number_default").remove();
        var value = $("#orig_src_number").val();

        if (value.trim().length == 0) {
            $("#report_form").append("<input type='hidden' id='orig_src_number_default' name='orig_src_number_default' value='" + dids + "'>");
        }
    }

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
