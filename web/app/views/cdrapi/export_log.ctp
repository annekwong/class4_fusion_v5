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
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/summary_reports">
            <?php __('CDR Export Log') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('CDR Export Log'); ?>
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
                <?php if ($type == 1): ?>
                    <li>
                        <a href="<?php echo $this->webroot; ?><?php echo $_SESSION['login_type'] == 3 ? "did_client/cdr" : "cdrapi/summary_reports"?>" class="glyphicons list">
                            <i></i>
                            <?php __('CDR Search') ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $this->webroot; ?>cdrapi/code_based_report" class="glyphicons list">
                            <i></i>
                            <?php __('Code Based Report') ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>cdrapi/export_log" class="glyphicons book_open">
                        <i></i>
                        <?php __('Export Log') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="app">
                <div class="wrapper small">
                    <?php if (!empty($data)): ?>
                        <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;" >
                            <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Process</th>
                                <th>Created On</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item['CdrApiExportLog']['id'] ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $item['CdrApiExportLog']['start_time']) ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $item['CdrApiExportLog']['end_time']) ?></td>
                                    <td><?php echo $statuses[$item['CdrApiExportLog']['status']] ?></td>
                                    <td><?php echo $item['CdrApiExportLog']['completed_records'] . '/' . $item['CdrApiExportLog']['total_records'] ?></td>
                                    <td><?php echo $item['CdrApiExportLog']['create_on'] ?></td>
                                    <td><?php echo $item['User']['name'] ?></td>
                                    <td>
                                        <?php if ($item['CdrApiExportLog']['status'] == 1): ?>
                                            <a target="_blank" href="<?php echo $this->webroot;?>cdrapi/download/<?php echo base64_encode($item['CdrApiExportLog']['id']); ?>" class="download">
                                                <i class="icon-download-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="center msg"><h2><?php  echo __('no_data_found') ?></h2></div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>