<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Logs Manage') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Logs Manage')?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget  widget-heading-simple widget-body-white">


        <div class="filter-bar">

            <form  method="get">
                <!-- Filter -->
                <div>
                    <label><?php __('Search')?>:</label>
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                </div>
                <!-- // Filter END -->


                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->

                <div class="pull-right" title="Advance">
                    <a id="advance_btn" class="btn" href="###">
                        <i class="icon-long-arrow-down"></i> 
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="widget-body">
        <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
            <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance')?></h3></div>
            <div class="widget-body">
                <form action="" method="get" id="search_panel"  >
                    <!-- Filter -->
                    <div class="filter-bar">
                        <div>
                            <label><?php echo __('type', true); ?>:</label>
                            <select name="type" class="select in-select" id="type">
                                <option value="">===<?php __('Select')?>===</option>
                                <option value="country"<?php
                                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'country') {
                                    echo "selected=selected";
                                }
                                ?>><?php __('Country')?></option>
                                <option value="code_name"<?php
                                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'code_name') {
                                    echo "selected=selected";
                                }
                                ?>><?php __('Destination')?></option>
                                <option value="code"<?php
                                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'code') {
                                    echo "selected=selected";
                                }
                                ?>><?php __('Code')?></option>
                                <option value="order"<?php
                                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'order') {
                                    echo "selected=selected";
                                }
                                ?>><?php __('Order ID')?></option>
                            </select>
                        </div>
                        <div>
                            <label><?php echo __('Value', true); ?>:</label>
                            <input type="text" class="input in-text" style="width:120px;" name="search_val" value="<?php echo!empty($_REQUEST['search_val']) ? $_REQUEST['search_val'] : ''; ?>"  id="search_val">
                        </div>

                        <div>
                            <label><?php echo __('Time', true); ?>:</label>
                            <input type="text" readonly onFocus="WdatePicker({maxDate: '#F{$dp.$D(\'end_date\')}', dateFmt: 'yyyy-MM-dd HH:mm:ss'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo!empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date("Y-m-d 00:00:00"); ?>">
                            --
                            <input type="text" readonly onFocus="WdatePicker({minDate: '#F{$dp.$D(\'start_date\')}', dateFmt: 'yyyy-MM-dd HH:mm:ss'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo!empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date("Y-m-d 23:59:59"); ?>">

                        </div>

                        <div>
                            <label><?php echo __('name', true); ?>:</label>
                            <input type="text" class="input in-text" style="width:120px;" name="name" value="<?php echo!empty($_REQUEST['name']) ? $_REQUEST['name'] : ''; ?>"  id="query-id_clients_name">
                        </div>
                        <div>
                            <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                        </div>
                    </div>





                    </tr>
                    <!-- // Filter END -->
                </form>
            </div>
        </div>

        <div class="separator bottom row-fluid">
            <div class="pagination pagination-large pagination-right margin-none">
                <?php echo $this->element('page'); ?>
            </div> 
        </div>
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list_id">
            <thead>
                <tr>
                    <th><?php echo __('Search Type', true); ?></th>
                    <th><?php echo __('Search Value', true); ?></th>
                    <th><?php echo __('Search Time', true); ?></th>
                    <th><?php echo __('name', true); ?></th>

                </tr>

            </thead>
            <tbody>
                <?php
                $mydata = $p->getDataArray();
                $loop = count($mydata);
                for ($i = 0; $i < $loop; $i++) {
                    ?>
                    <tr class="row-1">

                        <td><?php echo $mydata[$i][0]['type']; ?></td>
                        <td><?php echo $mydata[$i][0]['search_val']; ?></td>
                        <td><?php echo $mydata[$i][0]['search_time']; ?></td>
                        <td><?php echo $mydata[$i][0]['name']; ?></td>

                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="separator bottom row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div> 
    </div>


    <div class="clearfix"></div>
</div>
</div>




<script language="Javascript" type="text/javascript">
    function regenerate(invoice_id)
    {
        $.get("<?php echo $this->webroot ?>pr/pr_invoices/regenerate", {"invoice_id": invoice_id}, function(d) {
            alert(d);
            window.location.reload();
        });

    }
    $("#trigger_a").click(function() {

        $("#trigger_div").show();

    });

    $("#trigger_hide").click(function() {

        $("#trigger_div").hide();
    });

</script>