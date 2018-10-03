<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Manage Registration') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Manage Registration') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

        </div>

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
                    <label><?php __('Search')?>Output:</label>
                    <select onchange="set_out_put1(this)" name="out_put" style="width:100px;" class="in-select select" id="output">
                        <option value="web"><?php __('Web')?></option>
                        <option value="csv"><?php __('Excel CSV')?></option>
                        <option value="xls"><?php __('Excel XLS')?></option>
                    </select>
                </div>
                <!-- // Filter END -->
                <div>
                    <select name="user_type">
                        <option value="all"  <?php echo (isset($_GET['user_type']) && $_GET['user_type'] == 'all') ? 'selected=selected' : ''; ?>><?php __('All')?></option>
                        <option value="1" <?php echo (empty($_GET['user_type']) || $_GET['user_type'] == 1) ? 'selected=selected' : ''; ?>><?php __('New')?></option>
                        <option value="2" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 2) ? 'selected=selected' : ''; ?>><?php __('Hold')?></option>
                        <option value="3" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 3) ? 'selected=selected' : ''; ?>><?php __('Accept')?></option>
                        <option value="4" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 4) ? 'selected=selected' : ''; ?>><?php __('Mail Validated')?></option>

                    </select>
                </div>
                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
    </div>
    <div class="widget-body">
        <?php
        $mydata = $p->getDataArray();
        $loop = count($mydata);
        ?>
        <div class="separator bottom row-fluid">
            <div class="pagination pagination-large pagination-right margin-none">
<?php echo $this->element('page'); ?>
            </div> 
        </div>
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list_id">
            <col width="14%"></col>
            <col width="14%"></col>
            <col width="14%"></col>
            <col width="14%"></col>
            <col width="14%"></col>
            <col width="14%"></col>
            <col width="14%"></col>
            <thead>
                <tr>

                    <th><?php echo __('Company Name', true); ?></th>
                    <th><?php echo __('Corporate Contact Name', true); ?></th>
                    <th><?php echo __('Corporate Contact Phone', true); ?></th>
                    <th><?php echo __('Corporate Email', true); ?></th>
                    <th><?php echo $appCommon->show_order('name', __('name', true)) ?> </th>
                    <th><?php echo __('status', true); ?></th>
                    <th><?php echo __('Login', true); ?></th>
                    <?php if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) { ?>
                        <th  class="last"><?php echo __('action') ?></th>
<?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $status_arr = array(1 => 'New', 2 => 'Hold', 3 => 'Accepted', 4 => 'Mail Validated');
                for ($i = 0; $i < $loop; $i++) {
                    ?>
                    <tr>

                        <td><?php echo $mydata[$i][0]['company_name']; ?></td>
                        <td><?php echo $mydata[$i][0]['corporate_contact_name']; ?></td>
                        <td><?php echo $mydata[$i][0]['corporate_contact_phone']; ?></td>
                        <td><?php echo $mydata[$i][0]['corporate_contact_email']; ?></td>
                        <td>
    <?php if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) { ?>
                                <a style="width:80%;display:block" title="<?php echo __('edituser') ?>" 

                                   href="<?php echo $this->webroot ?>users/view_orderuser/<?php
                                   echo

                                   $mydata[$i][0]['id']
                                   ?>">
                                <?php echo array_keys_value($mydata[$i][0], 'name') ?>
                                </a>
                            <?php } else { ?><?php
                                echo array_keys_value($mydata[$i][0], 'name');
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($mydata[$i][0]['status'])) {
                                echo $status_arr[$mydata[$i][0]['status']];
                            }
                            /*
                              if($mydata[$i][0]['status']==1){
                              echo $status_arr[1];
                              }
                              else if($mydata[$i][0]['status']==2){
                              echo $status_arr[2];
                              }else{
                              echo $status_arr[3];
                              }
                             */
                            /*
                              if (!empty($mydata[$i][0]['client_status']))
                              {
                              echo $status_arr[$mydata[$i][0]['status']];
                              } */
                            ?>

                        </td>
                        <td>
    <?php if ($mydata[$i][0]['status'] != 0): ?>
                            <a title="<?php __('disable')?>" href="<?php echo $this->webroot; ?>users/changestatus/<?php echo $mydata[$i][0]['id']; ?>/0">
                                    <i class="icon-check"></i>
                                </a>
    <?php else: ?>
                                <a title="<?php __('enable')?>" href="<?php echo $this->webroot; ?>users/changestatus/<?php echo $mydata[$i][0]['id']; ?>/1">
                                    <i class="icon-unchecked"></i>
                                </a>
                        <?php endif; ?>
                        </td>
    <?php if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) { ?>

                            <td class="last">

                                <?php
                                if ($mydata[$i][0]['status'] == 1) {
                                    ?>


                                <?php } ?>

                                <?php
                                if ($mydata[$i][0]['status'] == 4) {
                                    ?>
                                    <a title="<?php echo __('accept user') ?>" href="<?php echo $this->webroot ?>clients/add/?order_user_id=<?php echo $mydata[$i][0]['id']; ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                <?php } ?> 
                                <?php
                                //$mydata[$i][0]['status']==3
                                if (true) {
                                    ?>
                                    <a title="Reset Password" href="<?php echo $this->webroot ?>users/reset_password/<?php echo $mydata[$i][0]['id']; ?>">
                                        <i class="icon-key"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="inactive(this, '<?php echo $mydata[$i][0]['id'] ?>');"> 
                                        <i title=" <?php echo __('Change To Hold') ?>" class="icon-check"></i>
                                    </a>
                                <?php } ?>
        <?php if ($mydata[$i][0]['status'] == 2) { ?>

                                    <a href="javascript:void(0)" onclick="active(this, '<?php echo $mydata[$i][0]['id'] ?>');"> 
                                        <i title=" <?php echo __('Change To Accept') ?>" class="icon-unchecked"></i>
                                    </a>
        <?php } ?>

                                <a  title="<?php echo __('del') ?>"  onclick="return confirm('Are you sure to delete users <?php echo array_keys_value($mydata[$i][0], 'name') ?> ? ');" href="<?php echo $this->webroot ?>users/del_order_user/<?php echo $mydata[$i][0]['id'] ?>/<?php echo $mydata[$i][0]['name'] ?>">
                                    <i class="icon-remove"></i> 
                                </a>
                            </td>
                    <?php } ?>
                    </tr>
<?php } ?>
            </tbody>
        </table>
        <div class="separator bottom row-fluid">
            <div class="pagination pagination-large pagination-right margin-none">
<?php echo $this->element('page'); ?>
            </div> 
        </div>
    </div>
</div>


<div class="clearfix"></div>



<script type="text/javascript">
//启用Reseller
    function active(obj, user_id) {
        if (confirm("<?php echo __('Are you sure to change this user status to accept?') ?>")) {
            jQuery.get("<?php echo $this->webroot ?>users/holdornot?status=3&id=" + user_id, function(data) {
                if (data.trim() != null) {
                    obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot ?>images/flag-1.png";
                    obj.title = "<?php echo __('change to hold') ?>";
                    obj.onclick = function() {
                        inactive(this, user_id);
                    };
                    window.location.reload();
                    jGrowl_to_notyfy("<?php echo __('Change User Status to accept success') ?>", {theme: 'jmsg-success'});

                } else {
                    jGrowl_to_notyfy("<?php echo __('activefailed') ?>", {theme: 'jmsg-alert'});
                }
            });
        }
    }
    function inactive(obj, user_id) {
        if (confirm("<?php echo __('Are you sure you to change this user status to hold?') ?>")) {
            jQuery.get("<?php echo $this->webroot ?>users/holdornot?status=2&id=" + user_id, function(data) {
                if (data.trim() != null) {
                    obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot ?>images/flag-0.png";
                    obj.title = "<?php echo __('Change User Status to Hold success') ?>";
                    obj.onclick = function() {
                        active(this, user_id);
                    };
                    window.location.reload();
                    jGrowl_to_notyfy("<?php echo __('Hold user success') ?>", {theme: 'jmsg-success'});
                } else {
                    jGrowl_to_notyfy("<?php echo __('inactivefailed') ?>", {theme: 'jmsg-alert'});
                }
            });
        }
    }
</script>

</div>
