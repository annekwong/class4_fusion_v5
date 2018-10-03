
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate sending template Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate sending template Log') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending" class="glyphicons list">
                        <i></i><?php __('Rate sending')?>     		
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_templates" class="glyphicons cogwheel">
                        <i></i><?php __('Template')?>    		
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>rates/rate_sending_logging" class="glyphicons book_open">
                        <i></i><?php __('Log')?>  		
                    </a>
                </li>
            </ul>
        </div>
        <div id="container" class="widget-body">


            <?php
            $data = $p->getDataArray();
            ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Date Time')?></th>
                        <th><?php __('Record')?></th>
                        <th><?php __('File')?></th>
                        <th><?php __('Status')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?php echo $item[0]['log_datetime']; ?></td>
                            <td><?php
                                echo substr($item[0]['data'], 0, 160);
                                ;
                                ?></td>
                            <td><a href="<?php echo $this->webroot . '/rates/get_file/' . base64_encode($item[0]['file']); ?>"><?php __('Download')?></a></td>
                            <td><?php echo $status[$item[0]['status']]; ?></td>
                        </tr>
<?php endforeach; ?>
                </tbody>
            </table>
            <div class="bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
<?php echo $this->element('page'); ?>
                </div> 
            </div>
        </div>
    </div>
</div>