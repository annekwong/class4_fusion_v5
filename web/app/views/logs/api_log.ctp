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
    <li><a href="<?php echo $this->webroot ?>logs/api_log">
            <?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>logs/api_log">
            <?php __('API Call Log') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('API Call Log'); ?>
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
            <div id="app">
                <div class="wrapper small">
                    <?php if (!empty($this->data)): ?>
                        <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;" >
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Request</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo date('Y-m-d H:i:s', $item['ApiLog']['time']) ?></td>
                                    <td><?php echo $item['ApiLog']['status'] ?></td>
                                    <td><?php echo $item['ApiLog']['request'] ?></td>
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