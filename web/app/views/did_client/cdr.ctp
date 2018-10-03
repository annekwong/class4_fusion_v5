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
    <li><a href="<?php echo $this->webroot ?>did_client/cdr">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did_client/cdr">
            <?php __('CDR Search') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('CDR Search'); ?>
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
                    <a href="<?php echo $this->webroot; ?>did_client/cdr" class="glyphicons list">
                        <i></i>
                        <?php __('CDR Search') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>cdrapi/export_log/1" class="glyphicons book_open">
                        <i></i>
                        <?php __('CDR Export Log') ?>
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
                            <th v-for="column in table.header" v-if="column.active == true && column.key != 'Orig Call ID' && column.key != 'Orig Switch IP'">
                                {{column.key}}
                            </th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in table.content">
                            <tr>
                                <td v-for="(column, index) in item" v-if="column.active == true && column.key != 'Orig Call ID' && column.key != 'Orig Switch IP'">
                                    {{column.value}}
                                </td>
                                <td v-for="(column, index) in item" v-if="column.key == 'Orig Call ID'" style="display: none;" data-name="origCallId">
                                    {{column.value}}
                                </td>
                                <td v-for="(column, index) in item" v-if="column.key == 'Orig Switch IP'" style="display: none;" data-name="origSwitchIp">
                                    {{column.value}}
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="view_pcap" title="Get PCAP" onclick="viewPcap(this)">
                                        <i class="icon-bullseye"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="center msg"  v-if="table.content.length == 0"><h2><?php  echo __('no_data_found') ?></h2></div>
                </div>

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
                        <tr style="display: none">
                            <td colspan="6"></td>
                            <td  valign="top" rowspan="9" colspan="2" style="padding-left: 10px;width:25%;">
                                <div align="left"><?php echo __('Show Fields', true); ?>:</div>

                                <?php
                                echo $form->select('Cdr.field', $cdr_field, $report_fields, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true, 'selected' => $report_fields), false);
                                ?>
                                <button type="button" class="btn btn-primary" style="display:block; width: 100%;" onclick="saveFields('<?php echo $this->webroot; ?>', 'did_client_cdr')">Save fields as default</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">DID</td>
                            <td>
                                <input type="text" name="orig_src_number" id="orig_src_number" value="<?php if(isset($getData['orig_src_number']) && !empty($getData['orig_src_number'])) echo $getData['orig_src_number'];?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <input type="hidden" name="human_readable" value="true">
                </div>
                <?php echo $form->end(); ?>
            </fieldset>

        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>js/report.js"></script>
<script src="<?php echo $this->webroot; ?>js/report/base.js"></script>
<script src="https://unpkg.com/vue"></script>

<!--<script>-->
<!--    (function () {-->
<!--        $("#query-fields option").each(function () {-->
<!--            if (!$(this).text().includes('_')) {-->
<!--                $(this).prop('selected', true);-->
<!--            } else {-->
<!--                $(this).attr('disabled', 'disabled');-->
<!--            }-->
<!--        });-->
<!--    })();-->
<!--</script>-->

<script>
    var optionalHeaders = {
        "start_time_of_date": "Time",
        "origination_source_number": "",
        "origination_destination_number": "DID",
        "termination_source_host_name": "",
        "call_duration": "Duration",
        "egress_rate": "",
        "egress_cost": "",
        "release_cause": "Return Cause",
        "origination_call_id": "",
        "origination_destination_host_name": ""
    };
    var webroot = "<?php echo $this->webroot; ?>";
    var currentUrl = "<?php echo $this->webroot; ?>cdrapi/ajaxRequest";
    var formObject = $("#report_form");
    var report = new Report(webroot, currentUrl, formObject, optionalHeaders);
    var clientTrunks = "<?php echo $clientTrunks; ?>".split(',');

    function viewPcap(element) {
        let parent = $(element).parent().parent();
        let callId = $(parent).find('td[data-name="origCallId"]').text();
        let switchIp = $(parent).find('td[data-name="origSwitchIp"]').text();
        let time = $(parent).find('td:eq(1)').text();
        let duration = $(parent).find('td:eq(5)').text();

        $.ajax({
            url: "<?php echo $this->webroot; ?>did_client/ajaxGetSip",
            data: {
                callId: callId,
                switchIp: switchIp,
                time: time,
                duration: duration
            },
            method: "POST",
            success: function (data) {
                let decodedData = JSON.parse(data);
                let code = decodedData.self_status == 0 ? 101 : 201;

                showMessages_new('[{code: ' + code + ', msg: \'' + decodedData.msg + '\'}]');

                if (decodedData.self_status == 1) {
                    location.href = "<?php echo $this->webroot; ?>cdrreports_db/sip_requests";
                }
            }
        });
    }

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
                    let selected = 'selected';
                    $("#query-fields").append('<option value="' + key + '" ' + selected + '>' + item + '</option>');
                });
            }
        }
    });

    $(formObject).submit(function (event) {
        event.preventDefault();
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
        if (clientTrunks.length > 0) {
            clientTrunks.forEach(function (item, key) {
                $(formObject).append("<input name='egress_alias[]' value='" + item + "' type='hidden' >");
            });
        } else {
            $(formObject).append("<input name='egress_alias[]' value='-1' type='hidden' >");
        }

        report.initHeader(function () {
            Vue.fields = report.getHeader();
            Vue.initFields(report.getHeader());
            report.initData(function () {
                Vue.getData();
                Vue.setMaxOffset();
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
