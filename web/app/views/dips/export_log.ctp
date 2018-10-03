<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dips">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dips">
        <?php __('LRN Dipping Record') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('LRN Dipping Record Export Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LRN Dipping Record Export Log') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a href="<?php echo $this->webroot; ?>dips/index"  class="glyphicons list">
                        <i></i><?php __('LRN Dipping Record') ?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>dips/export_log" class="glyphicons book_open">
                        <i></i><?php __('LRN Dipping Record Export Log') ?>
                    </a>
                </li>
            </ul>


        </div>
        <div class="widget-body">




            <div id="container">


                <?php if (empty($this->data)): ?>
                    <div class="msg center">
                        <br />
                        <h3><?php  echo __('no_data_found') ?></h3>
                    </div>
                <?php else: ?>
                    <table  class="scroll_table list nowrap with-fields footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Job ID')?></th>
                                <th><?php __('Tiggered Time')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Number of Rows')?></th>
                                <th><?php __('File Size')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td>#<?php echo $item['CdrExportLog']['id']; ?></td>
                                    <td><?php echo $item['CdrExportLog']['export_time']; ?></td>
                                    <td><?php echo $status[$item['CdrExportLog']['status']]; ?></td>
                                    <td>
                                        <?php
                                        echo (int) shell_exec("wc -l " . realpath(ROOT . '/../download/cdr_download/' . $item['CdrExportLog']['file_name']));
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($item['CdrExportLog']['status'] == 4): ?> 
                                            <?php
                                            $fileSize = @filesize(realpath(ROOT . '/../download/cdr_download/' . $item['CdrExportLog']['file_name'] . '.gz'));
                                            echo $appCommon->to_readable_size($fileSize);
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($fileSize > 0 && $item['CdrExportLog']['status'] == 4): ?>
                                        <a title="<?php __('Download')?>" href="<?php echo $this->webroot ?>cdrreports_db/export_log_down?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                                <i class="icon-download-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($item['CdrExportLog']['status'] != 4): ?> 
                                        <a title="<?php __('Kill Job')?>" href="<?php echo $this->webroot ?>cdrreports_db/export_log_kill/1?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                                <i class="icon-remove-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $show_files = $('.show_files');

        $show_files.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>cdrreports/export_log_files/',
                    {'key': $this.data('id')},
            function(responseText, textStatus, XMLHttpRequest) {
                $dd.dialog({
                    'width': '600px'
                });
            }
            );

        });
    });
</script>