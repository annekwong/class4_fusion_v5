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
            <?php __('Statistics') ?></a></li>
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
    <div class="widget widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
                <div class="wrapper small">
                    <?php if (count($data) == 0): ?>
                        <div class="center msg"><h2><?php  echo __('no_data_found') ?></h2></div>
                    <?php else: ?>
                        <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;" v-if="table.content.length > 0">
                            <thead>
                            <tr>
                                <?php
                                $first = $data[0];

                                foreach ($first as $header => $value):
                                ?>
                                    <th><?php echo $header ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $item): ?>
                                    <tr>
                                        <?php foreach ($item as $column):?>
                                            <td><?php echo $column ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
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
                            <td class="align_right padding-r10">DID</td>
                            <td>
                                <select name="orig_src_number" id="">
                                    <option></option>
                                    <?php foreach ($dids as $did): ?>
                                        <option value="<?php echo $did['DidBillingRel']['did']; ?>" <?php if ($did['DidBillingRel']['did'] == $_GET['orig_src_number']) echo 'selected'; ?>><?php echo $did['DidBillingRel']['did']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="in-out_bound">&nbsp;</td>
                            <td class="align_right padding-r10"></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".fakeloader").remove();
    });
</script>

