<style type="text/css">
    .smallbox {
        background:#fff;
        display:none;
        position:absolute;
        right:20px;
        padding:20px;
        border:1px solid #ccc;
        text-align:left;
        font-weight:bold;
    }

</style>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Table') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php echo $this->element('xback',Array('backUrl'=>'clientrates/view_rate'))?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" name="carriersearch" id="advance_form">
                    <!--                <form id="like_form" method="get">-->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Code') ?>:</label>
                        <input type="text" id="search" class="in-search default-value input in-text defaultText" value="<?php echo isset($_GET['code']) ? $_GET['code'] : ''; ?>" name="code">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button  class="btn query_btn" id="search_button"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->

                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="javascript:void(0)">
                            <i class="icon-long-arrow-down"></i>
                        </a>
                    </div>
                    <!--                </form>-->
            </div>
            <div class="clearfix"></div>
            <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                <div class="widget-head"><h3 class="heading glyphicons show_thumbnails"><i></i><?php __('Advance') ?></h3></div>
                <div class="widget-body">

                    <div class="filter-bar">
                        <input type="hidden" value="0" id="adv_search" name="adv_search">
                        <div>
                            <label><?php echo __('rate', true); ?>:</label>
                            <input type="text" style="width:80px;" name="rate_begin" class="input in-input in-text" value="<?php echo isset($_GET['rate_begin']) ? $_GET['rate_begin'] : ''; ?>">
                            -
                            <input type="text" style="width:80px;" name="rate_end" class="input in-input in-text" value="<?php echo isset($_GET['rate_end']) ? $_GET['rate_end'] : ''; ?>">
                        </div>
                        <div>
                            <label><?php echo __('country', true); ?>:</label>
                            <input type="text" style="width:80px;" name="country" class="input in-input in-text" value="<?php echo isset($_GET['country']) ? $_GET['country'] : ''; ?>">
                        </div>
                        <div>
                            <label><?php __('code_name'); ?>:</label>
                            <input type="text" style="width:80px;" name="code_name" class="input in-input in-text" value="<?php echo isset($_GET['code_name']) ? $_GET['code_name'] : ''; ?>">
                        </div>
                        <div>
                            <label><?php echo __('Effective On', true); ?>:</label>
                            <select name="time" class="select in-select time_selected" style="width:100px" >
                                <option value="current" <?php echo !isset($_GET['time']) || $_GET['time'] == 'current' ? 'selected' : ''; ?>><?php __('current on') ?></option>
                                <option value="new" <?php echo isset($_GET['time']) && $_GET['time'] == 'new' ? 'selected' : ''; ?>><?php __('future for') ?></option>
                                <option value="old" <?php echo isset($_GET['time']) && $_GET['time'] == 'new' ? 'selected' : ''; ?>><?php __('old for') ?></option>
                                <option value="all" <?php echo (isset($_GET['time']) && $_GET['time'] == 'all') ? 'selected' : ''; ?>><?php __('all') ?></option>
                                <option value="in" <?php echo isset($_GET['time']) && $_GET['time'] == 'in' ? 'selected' : ''; ?>><?php __('in') ?></option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="time_val"
                                   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" value="<?php echo isset($_GET['time_val']) ? $_GET['time_val'] : date("Y-m-d H:i:s") ?>"   class="input in-text wdate" readonly="readonly"
                                   id="search-now-wDt">
                        </div>
                        <div>
                            <label><?php echo __('Profile', true); ?>:</label>
                            <select name="profile" class="select in-select" style="width:80px">
                                <option value=""></option>
                                <?php foreach ($profiles as $profile): ?>
                                    <option value="<?php echo $profile[0]['time_profile_id'] ?>" <?php echo isset($_GET['profile']) &&  $profile[0]['time_profile_id'] == $_GET['profile']? 'selected' : ''; ?>><?php echo $profile[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <button id="notcsv" class="btn query_btn">Query</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <?php
            $rates = $p->getDataArray();
            if (!empty($rates)):
                ?>
                <table id="mytable" class="list footable table table-striped table_page_num tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('code', __('Code', true)) ?></th>
                        <th><?php echo $appCommon->show_order('code_name', __('code_name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('country', __('country', true)) ?></th>
                        <th><?php echo $appCommon->show_order('rate', __('rate', true)) ?></th>
                        <?php if ($appRate->is_show_jur_rate(base64_decode($this->params['pass'][0]))): ?>
                        <th><?php echo $appCommon->show_order('inter_rate', __('inter_rate', true)) ?></th>
                        <th><?php echo $appCommon->show_order('intra_rate', __('intra_rate', true)) ?></th>
                        <?php endif; ?>
<!--                        <th>--><?php //__('Setup Fee'); ?><!--</th>-->
                        <th><?php echo $appCommon->show_order('effective_date', __('effective_date', true)) ?></th>
                        <th><?php echo $appCommon->show_order('end_date', __('end_date', true)) ?></th>
                        <th><?php __('Extra Fields'); ?></th>
                    <tr>
                    </thead>

                    <tbody>
                    <?php foreach ($rates as $rate): ?>
                        <tr>
                            <td><?php echo $rate[0]['code'] ?></td>
                            <td><?php echo $rate[0]['code_name'] ?></td>
                            <td><?php echo $rate[0]['country'] ?></td>
                            <td><?php echo $rate[0]['rate'] ?></td>
                            <?php if ($appRate->is_show_jur_rate(base64_decode($this->params['pass'][0]))): ?>
                            <td><?php echo $rate[0]['intra_rate'] ?></td>
                            <td><?php echo $rate[0]['inter_rate'] ?></td>
                        <?php endif; ?>
<!--                            <td>--><?php //echo $rate[0]['setup_fee'] ?><!--</td>-->
                            <td><?php echo $rate[0]['effective_date'] ?></td>
                            <td><?php echo $rate[0]['end_date'] ?></td>
                            <td>
                                <a class="showother" href="###">
                                    <?php echo $rate[0]['min_time'] ?>/<?php echo $rate[0]['interval'] ?>/<?php echo $rate[0]['grace_time'] ?>/<?php echo $rate[0]['time_profile'] ?>
                                </a>
                                <ul class="smallbox">
                                    <li><?php echo __('Min Time', true); ?>:<?php echo $rate[0]['min_time'] ?></li>
                                    <li><?php echo __('Interval', true); ?>:<?php echo $rate[0]['interval'] ?></li>
                                    <li><?php echo __('Grace Time', true); ?>:<?php echo $rate[0]['grace_time'] ?></li>
                                    <li><?php echo __('Seconds', true); ?>:<?php echo $rate[0]['seconds'] ?></li>
                                    <li><?php echo __('Profile', true); ?>:<?php echo $rate[0]['time_profile'] ?></li>
                                    <li><?php echo __('Time Zone', true); ?>:<?php echo empty($rate[0]['zone']) ? '+00' : $rate[0]['zone'] ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php else: ?>
                <h1 style="text-align:center"><?php echo __('no_data_found', true); ?></h1>
            <?php endif; ?>
        </div>

        <script type="text/javascript">
            jQuery(function($) {
                $('.showother').toggle(function() {
                    $(this).next().show();
                }, function() {
                    $(this).next().hide();
                });

                $('#getcsv').click(function(){
                    $('#advance_form').attr('target','_blank');
                });

                $('#notcsv').click(function(){
                    $('#advance_form').attr('target','_self');
                });

                $('#advance_btn').click(function(){
                    if($(this).find('i').hasClass('icon-long-arrow-down')){
                        $('#adv_search').val('1');
                        $('#search_button').hide();
                    } else {
                        $('#adv_search').val('0');
                        $('#search_button').show();
                    }
                })

                $(".time_selected").change(function(){
                    var $time_selected =  $(this).val();
                    if ($time_selected == 'current' || $time_selected == 'all'){
                        $("#search-now-wDt").hide();
                    }else{
                        $("#search-now-wDt").show();
                    }
                }).trigger('change');

            });
        </script>