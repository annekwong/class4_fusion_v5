<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Send Rate Log Detail') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Send Rate Log') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_back btn btn-icon glyphicons btn-inverse circle_arrow_left" href="<?php echo $this->webroot ?>rates/send_rate_log"><i></i> <?php echo __('Back', true); ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-single widget-body-white">
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
                        <th><?php __('Trunk') ?></th>
                        <th><?php __('Delivered To') ?></th>
                        <th><?php __('Status') ?></th>
                        <th><?php __('Error File') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $items)
                    {
                        ?>
                        <tr>
                            <td><?php echo $items['Resource']['alias'] ?></td>
                            <td><?php echo $items['RateSendLogDetail']['send_to'] ?></td>
                            <td>
                                <?php
                                if($items['RateSendLogDetail']['status'])
                                    __('Succeed');
                                else
                                    __('Failed');
                                ?>
                            </td>
                            <td>
                                <?php if(!$items['RateSendLogDetail']['status']): ?>
                                    <a href="javascript:void(0)" class="show_error" data-value="<?php echo $items['RateSendLogDetail']['error']; ?>"
                                       title="<?php echo substr_replace($items['RateSendLogDetail']['error'],'...',20); ?>">
                                        <i class="icon-info-sign"></i>
                                    </a>
<!--                                    <a title="--><?php //echo substr_replace($items['RateSendLogDetail']['error'],'...',20); ?><!--" target="_blank" href="--><?php //echo $this->webroot; ?><!--rates/download_error_file/--><?php //echo base64_encode($items['RateSendLogDetail']['id']); ?><!--/1">-->
<!--                                        <i class="icon-download"></i>-->
<!--                                    </a>-->
                                <?php endif; ?>
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
    $(function(){
        $('.show_error').click(function (){
            notyfy({
                text: $(this).data('value'),
                type: 'error',
                layout: 'center'
            });
        });
    })


</script>
