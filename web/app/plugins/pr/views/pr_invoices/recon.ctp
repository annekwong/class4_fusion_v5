<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Invoices', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoices', true); ?></h4>
    <div class="buttons pull-right">
        <a href="javascript:history.go(-1)" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
            <i></i>
            &nbsp;<?php echo __('goback', true); ?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form action="<?php echo $this->webroot; ?>pr/pr_invoices/recon/<?php echo $this->params['pass'][0] ?>" method="post" enctype="multipart/form-data" name="myform">
                <table class=" footable table table-striped tableTools table-bordered  table-white table-primary">
                    <colgroup>
                        <col width="50%">
                        <col width="50%">
                    </colgroup>
                    <tr>
                        <td class="text-right" style="border-top: solid 1px #efefef"><?php __('Upload file to compare')?>:</td>
                        <td style="border-top: solid 1px #efefef"><input type="file" name="upfile" /></td>
                    </tr>

                    <tr>
                        <td colspan="2"  style="height: 30px"><span class="text-center" style="display: inline-block;width: 100%;"><?php __('Current Status')?>:<?php echo ucwords($status); ?></span></td>
                    </tr>
                    <tr>
                        <td  class="text-right" style="height: 30px"><a  class="input in-button" href="<?php echo $this->webroot ?>pr/pr_invoices/start_reconcile/<?php echo $invoice_id ?>"><?php __('Start Compare')?></a></td>
                        <td><a href="<?php echo $this->webroot ?>pr/pr_invoices/get_recom_example_file"><?php __('Down Example file')?></a></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="text-center" style="display: inline-block;width: 100%">
                                <input type="submit" class=" btn btn-primary" value="<?php __('Submit')?>" />
                            </span>
                        </td>
                    </tr>




                </table>
            </form>
            <p class="separator text-center"><i class="icon-ellipsis-horizontal icon-3x"></i></p>
            <?php
            $data = $p->getDataArray();
            ?>
            <?php
            if (empty($data)):
                ?>
            <br />
            <table id="list" class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th colspan="3"><?php __('Partner'); ?></th>
                    <th colspan="2"><?php __('System'); ?></th>
                    <th colspan="2"><?php __('Minute Diff'); ?></th>
                    <th colspan="2"><?php __('Cost Diff'); ?></th>
                </tr>
                <tr>
                    <th><?php __('Code'); ?></th>
                    <th><?php __('Minute'); ?></th>
                    <th><?php __('Cost'); ?></th>
                    <th><?php __('Minute'); ?></th>
                    <th><?php __('Cost'); ?></th>
                    <th><?php __('Amt'); ?></th>
                    <th><?php __('%'); ?></th>
                    <th><?php __('Amt'); ?></th>
                    <th><?php __('%'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                        <td colspan="9"><div class="msg center"><h3><?php  echo __('no_data_found') ?></h3></div></td>
                </tr>
                </tbody>

            </table>

            <?php else: ?>
                <h1 style="margin:10px;">
                    <a class="input in-button" href="<?php echo $this->webroot ?>pr/pr_invoices/down_reconcile_list/<?php echo $invoice_id; ?>">Export</a>
                </h1>
                <br />
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <table id="list" class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th colspan="3"><?php __('Partner'); ?></th>
                            <th colspan="2"><?php __('System'); ?></th>
                            <th colspan="2"><?php __('Minute Diff'); ?></th>
                            <th colspan="2"><?php __('Cost Diff'); ?></th>
                        </tr>
                        <tr>
                            <th><?php __('Code'); ?></th>
                            <th><?php __('Minute'); ?></th>
                            <th><?php __('Cost'); ?></th>
                            <th><?php __('Minute'); ?></th>
                            <th><?php __('Cost'); ?></th>
                            <th><?php __('Amt'); ?></th>
                            <th><?php __('%'); ?></th>
                            <th><?php __('Amt'); ?></th>
                            <th><?php __('%'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item[0]['code']; ?></td>
                                <td><?php echo round($item[0]['minute'], 2); ?></td>
                                <td><?php echo round($item[0]['cost'], 2); ?></td>
                                <td><?php echo round($item[0]['sys_minute'], 2); ?></td>
                                <td><?php echo round($item[0]['sys_cost'], 2); ?></td>
                                <td><?php echo round($item[0]['minute_diff_amt'], 2); ?></td>
                                <td><?php echo round($item[0]['minute_diff_per'], 2); ?></td>
                                <td><?php echo round($item[0]['cost_diff_amt'], 2); ?></td>
                                <td><?php echo round($item[0]['cost_diff_per'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>