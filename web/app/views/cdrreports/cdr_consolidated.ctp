<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports/cdr_consolidated">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports/cdr_consolidated">
        <?php echo __('Consolidated CDR') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Consolidated CDR') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (!$loop)
            {
                ?>
                <div class="center msg">
                    <h3><?php echo __('no_data_found') ?></h3>
                </div>
                <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Ingress Name') ?></th>
                            <th><?php __('Ingress_ANI') ?></th>
                            <th><?php __('Ingress_DNIS') ?></th>
                            <th><?php __('Time') ?></th>
                            <th><?php __('Return cause') ?></th>
                            <th><?php __('Failure Cause') ?></th>
                            <th><?php __('Egress ATT Count') ?></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['trunk_id_origination']; ?></td>
                                <td><?php echo $mydata[$i][0]['origination_source_number']; ?></td>
                                <td><?php echo $mydata[$i][0]['origination_destination_number']; ?></td>
                                <td><?php echo $mydata[$i][0]['time']; ?></td>
                                <td><?php echo $mydata[$i][0]['binary_value_of_release_cause_from_protocol_stack']; ?></td>
                                <td><?php echo isset($return_cause[$mydata[$i][0]['release_cause']]) ? $return_cause[$mydata[$i][0]['release_cause']]: 'other'; ?></td>
                                <td><a title="Egress Attempt" href="javascript:void(0)" class="egress_attempt" hit='form<?php echo $i; ?>'><?php echo $mydata[$i][0]['count'] ?></a></td>
                            </tr>
                        <form id="form<?php echo $i; ?>" action="<?php echo $this->webroot; ?>cdrreports/get_egress_attempt" method="get">
                            <input type="hidden" value="<?php echo $mydata[$i][0]['origination_source_number']; ?>" name="origination_source_number"  />
                            <input type="hidden" value="<?php echo $mydata[$i][0]['origination_destination_number']; ?>" name="origination_destination_number"  />
                            <input type="hidden" value="<?php echo $mydata[$i][0]['time']; ?>" name="time"  />
                            <input type="hidden" value="<?php echo $mydata[$i][0]['binary_value_of_release_cause_from_protocol_stack']; ?>" name="binary_value_of_release_cause_from_protocol_stack"  />
                            <input type="hidden" value="<?php echo $mydata[$i][0]['release_cause']; ?>" name="release_cause"  />
                        </form>

                    <?php } ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <div class="filter-bar">

            <form action="" method="get">
                <!-- Filter -->
                <div>
                    <label><?php __('Ingress_ANI') ?>:</label>
                    <input type="text"  class="in-search default-value input in-text defaultText" title="<?php echo __('Ingress_ANI') ?>" value="<?php if (!empty($ingress_ani)) echo $ingress_ani; ?>" name="ingress_ani">
                </div>
                <!-- // Filter END -->
                <!-- Filter -->
                <div>
                    <label><?php __('Ingress_DNIS') ?>:</label>
                    <input type="text"  class="in-search default-value input in-text defaultText" title="<?php echo __('Ingress_DNIS') ?>" value="<?php if (!empty($ingress_dnis)) echo $ingress_dnis; ?>" name="ingress_dnis">
                </div>
                <!-- // Filter END -->

                <!-- Filter -->
                <div>
                    <label><?php __('Time') ?>:</label>
                    <input id="start_date" class="input in-text wdate input-small" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="time_start" value="<?php if (!empty($time_start)) echo $time_start; ?>">
                    -- 
                    <input id="end_date" class="wdate input in-text input-small" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="time_end" value="<?php if (!empty($time_end)) echo $time_end; ?>">
                </div>
                <!-- // Filter END -->

                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function() {

        $(".egress_attempt").click(function(){
            
            var formid = $(this).attr('hit');
            $("#"+formid).submit();
    
        });
        
    });

</script>