<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('SIP Packet Search') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('SIP Packet Search') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons right_arrow" href="<?php echo $this->webroot; ?>sip_packet">
                        <i></i><?php __('Origination')?></a></li>
                <li><a class="glyphicons left_arrow" href="<?php echo $this->webroot; ?>sip_packet/termination">
                        <i></i><?php __('Termination')?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div id="container">

                <?php $mydata = $p->getDataArray(); ?>
                <?php if (empty($mydata)) : ?>
                    <div id="noRows" class="msg center"><h3><?php echo __('no_data_found', true); ?></h3></div>
                <?php else: ?>
                    <div class="clearfix"></div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php echo $appCommon->show_order('time', __('Time', true)) ?></th>
                                <th><?php echo $appCommon->show_order('origination_destination_number', __('Orig ANI', true)) ?></th>
                                <th><?php echo $appCommon->show_order('origination_source_number', __('Orig DNIS', true)) ?></th>
                                <th><?php echo $appCommon->show_order('origination_call_id', __('Orig Call ID', true)) ?></th>
                                <th><?php echo $appCommon->show_order('origination_source_host_name', __('Ingress IP', true)) ?></th>
                                <th><?php echo $appCommon->show_order('origination_destination_host_name', __('Orig Profile IP', true)) ?></th>
                                <th><?php echo $appCommon->show_order('call_duration', __('Duration', true)) ?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mydata as $row): ?>
                                <tr>
                                    <td><?php echo $row[0]['time'] ?></td>
                                    <td><?php echo $row[0]['origination_destination_number'] ?></td>
                                    <td><?php echo $row[0]['origination_source_number'] ?></td>
                                    <td><?php echo $row[0]['origination_call_id'] ?></td>
                                    <td><?php echo $row[0]['origination_source_host_name'] ?></td>
                                    <td><?php echo $row[0]['origination_destination_host_name'] ?></td>
                                    <td><?php echo $row[0]['call_duration'] ?></td>
                                    <td>
                                        <a target="_blank" href="<?php echo $this->webroot ?>sip_packet/download_sip_cap/<?php echo $row[0]['id'] ?>/<?php echo base64_encode($row[0]['time']) ?>" title="Download SIP PCAP">
                                            <img src="<?php echo $this->webroot ?>images/export.png">
                                        </a>
                    <!--                    <a href="<?php echo $this->webroot ?>sip_packet/view_call_flow/<?php echo $row[0]['id'] ?>/<?php echo base64_encode($row[0]['time']) ?>" title="View Calll Flow">
                                            <img src="<?php echo $this->webroot ?>images/view.png">
                                        </a>-->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
                <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search"><i></i> Search</h4>
                    <form method="get">
                        <table class="form" style="width: 100%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('ANI')?></td>
                                <td><input type="text" name="ani" value="<?php echo isset($_GET['ani']) ? $_GET['ani'] : '' ?>"></td>
                                <td class="align_right padding-r10"><?php __('DNIS')?></td>
                                <td><input type="text" name="dnis" value="<?php echo isset($_GET['dnis']) ? $_GET['dnis'] : '' ?>"></td>
                                <td class="align_right padding-r10"><?php __('Start Time')?></td>
                                <td><input type="text" name="start_time"  value="<?php echo isset($_GET['start_time']) ? $_GET['start_time'] : date("Y-m-d 00:00:00") ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"></td>
                                <td class="align_right padding-r10"><?php __('End Time')?></td>
                                <td><input type="text" name="end_time"  value="<?php echo isset($_GET['end_time']) ? $_GET['end_time'] : date("Y-m-d 23:59:59") ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"></td>

                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Ingress IP')?></td>
                                <td><input type="text" name="ingress_ip" value="<?php echo isset($_GET['ingress_ip']) ? $_GET['ingress_ip'] : '' ?>"></td>
                                <td class="align_right padding-r10"><?php __('Orig Profile IP')?></td>
                                <td><input type="text" name="orig_ip" value="<?php echo isset($_GET['orig_ip']) ? $_GET['orig_ip'] : '' ?>"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><input type="submit" class="btn btn-primary margin-bottom10" value="<?php __('Query')?>"></td>
                            </tr>
                        </table>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
</div>