<!--导入所有reoprt页面的input和select样式文件-->
<style>
    .footable-last-column .in-date td{border:0;}
</style>
<?php echo $this->element('magic_css');?>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDR Rerating') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('CDR Rerating') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<?php
$url="/".$this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
    'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
<?php echo $appCommon->show_page_hidden();?> <?php echo $this->element('search_report/search_hide_input');?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li class="active">
                    <a class="glyphicons no-js cogwheel" href="<?php echo $this->webroot ?>cdrreports_db/rerating">
                        <i></i><?php echo __('Rerate CDR') ?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js list" href="<?php echo $this->webroot ?>cdrreports_db/rerating_list">
                        <i></i><?php echo __('Rerate Result') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">


            <?php
            if (isset ( $exception_msg ) && $exception_msg) :	?>
                <?php	echo $this->element ( 'common/exception_msg' );?>
            <?php endif;?>
            <?php echo $this->element('report/real_period')?>
            <?php   if($action_type=='query')
            {
                echo 	$this->element('report_db/cdr_report/cdr_table3');
            }

            if($action_type=='Process')
            {
                echo 	$this->element('report/cdr_report/process_table');
            }

            if($action_type=='Rerating')
            {
                echo 	$this->element('report/cdr_report/cdr_rerate_table');

            } ?>
            <?php
            echo $this->element('report/cdr_report/query_box_rerating');

            ?>
            <div style="display: none;" id="charts_holder">
                <?php echo $this->element('search_report/search_js_show');?>




            </div>
            <script type="text/javascript">

                jQuery(document).ready(function(){
//                    jQuery(#query-src_number,#query-interval_from,#query-interval_to).xkeyvalidate({type:'checkNum'});
                });

            </script>

        </div>
        <?php echo $form->end();?>
    </div>
</div>

<script>
    $(function(){
        $.sort_select('#query-res_status');
        $.sort_select('#query-res_status_ingress');
    });
</script>