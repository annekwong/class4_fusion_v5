<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Send Rate Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Send Rate Log') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2><?php echo __('no_data_found') ?></h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Client Name') ?></th>
                        <th><?php __('Trunk Name') ?></th>
                        <th><?php __('Rate Table Name') ?></th>
                        <th><?php __('Download On') ?></th>
                        <th><?php __('Download IP') ?></th>
                        <th><?php __('Rate File') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $items)
                    {
                        ?>
                        <tr>
                            <td><?php echo $items['Client']['name'] ?></td>
                            <td><?php echo $items['Resource']['alias'] ?></td>
                            <td><?php echo $items['RateTable']['name'] ?></td>
                            <td><?php echo $items['RateDownloadLog']['download_time'] ?></td>
                            <td><?php echo $items['RateDownloadLog']['download_ip'] ?></td>
                            <td>
                                <?php if($items['RateDownloadLog']['file_path']): ?>
                                    <a target="_blank" href="<?php echo $this->webroot; ?>rates/download_send_rate_file?download=1&flg=<?php echo urlencode(base64_encode($items['RateDownloadLog']['id'])) ?>"><i class="icon-file-text"></i></a>
                                <?php else: ?>
                                    --
                                <?php  endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>


<script type="text/javascript">



</script>