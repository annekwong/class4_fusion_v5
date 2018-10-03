<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Vendors') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('Vendors') ?></h1>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus"  onclick="return judge_billing_rule(this);" href="<?php echo $this->webroot ?>did/vendors/add"><i></i>
            <?php __('Create New')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br />
                        <h2><?php echo __('no_data_found', true); ?></h2>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php __('View IP')?></th>
                                <th><?php __('Name')?></th>
                                <th><?php __('Balance')?></th>
                                <th><?php __('Billing Rule')?></th>
                                <th><?php __('Update At')?></th>
                                <th><?php __('Update By')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>


                        <?php
                        $count = count($this->data);
                        for ($i = 0; $i < $count; $i++):
                            ?>
                            <tbody id="resInfo<?php echo $i ?>">
                                <tr class="row-<?php echo $i % 2 + 1; ?>">
                                    <td>
                                        <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $i; ?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('View All') ?>"/>
                                    </td>
                                    <td><?php echo $this->data[$i]['Client']['name'] ?></td>
                                    <td>
                                        <?php echo $this->data[$i]['Balance']['balance'] < 0 ? '(' . str_replace('-', '', number_format($this->data[$i]['Balance']['balance'], 3)) . ')' : number_format($this->data[$i]['Balance']['balance'], 3); ?>
                                    </td>
                                    <td><?php echo isset($routing_rules[$this->data[$i]['Resource']['billing_rule']]) ? $routing_rules[$this->data[$i]['Resource']['billing_rule']]: ""; ?></td>
                                    <td><?php echo $this->data[$i]['Client']['update_at'] ?></td>
                                    <td><?php echo $this->data[$i]['Client']['update_by'] ?></td>
                                    <td>
                                        <a href="<?php echo $this->webroot ?>did/vendors/edit/<?php echo $this->data[$i]['Client']['client_id'] ?>" title="<?php __('Edit')?>"> 
                                            <i class="icon-edit"></i> 
                                        </a>

                                        <a onclick="return myconfirm('<?php __("sure to delete"); ?>',this);" href="<?php echo $this->webroot ?>did/vendors/delete/<?php echo $this->data[$i]['Client']['client_id'] ?>">
                                            <i class="icon-remove"></i>
                                        </a>
                                        <?php if ($this->data[$i]['Client']['status'] == 1) { ?>
                                            <a title=" <?php echo __('Click to inactive'); ?>"  onclick="return myconfirm('Are you sure to inactive the selected <?php echo $this->data[$i]['Client']['name'] ?>?',this)"   href="<?php echo $this->webroot ?>did/vendors/disable/<?php echo $this->data[$i]['Client']['client_id'] ?>" > <i class="icon-check"></i> </a>
                                        <?php } else { ?>
                                            <a title=" <?php echo __('Click to active'); ?>" onclick="return myconfirm('Are you sure to active the selected <?php echo $this->data[$i]['Client']['name'] ?>?',this)" href="<?php echo $this->webroot ?>did/vendors/enable/<?php echo $this->data[$i]['Client']['client_id'] ?>"  > <i class="icon-unchecked"></i> </a>
                                        <?php } ?>

                                        <a target="_blank" href="<?php echo $this->webroot ?>did/did_reposs/index/<?php echo $this->data[$i]['Client']['ingress_id'] ?>" title="<?php __('View DID')?>"> 
                                            <i class="icon-list-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr style="height:auto">
                                    <td colspan="6">
                                        <div id="ipInfo<?php echo $i ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display: none;"> 
                                            <table>
                                                <tr>
                                                    <td><?php __('IP')?></td>
                                                </tr>
                                                <?php foreach ($this->data[$i]['ResourceIps'] as $ip): ?>
                                                    <tr>
                                                        <td><?php echo $ip; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endfor; ?>

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
<?php echo $this->element("did_judge_billing_rule"); ?>