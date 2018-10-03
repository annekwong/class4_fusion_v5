<style type="text/css">
    #multiple {display:none;}
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LNP Request') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Trunk Management>>LNP Request>>Detail')?></h4>
    <div class="buttons pull-right">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/lnp_request"><i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active" ><a href="<?php echo $this->webroot; ?>did/lnp_request/detail/1" class="glyphicons left_arrow"><i></i><?php __('Log'); ?></a></li>
                <li><a href="<?php echo $this->webroot; ?>did/lnp_request/detail/2" class="glyphicons right_arrow"><i></i><?php __('Submit'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg"><?php echo __('no_data_found', true); ?></div>
                    <table class="list" style="display:none;">

                        <thead>
                            <tr>
                                <td><?php __('Number')?></td>
                                <td><?php __('Status')?></td>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php __('Number')?></th>
                                <th><?php __('Status')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['LnpRequestDetail']['number']; ?></td>
                                    <td><?php echo $status[$item['LnpRequestDetail']['status']]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



