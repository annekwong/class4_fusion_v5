<style type="text/css">
    .in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{ width:250px;}
    select, textarea, input[type="text"]{margin-bottom: 0;}
    th .btn-primary,th .btn-primary:hover{background: #7FAF00;}
</style>
<?php #pr($name_join_arr); ?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>alerts/rules"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>alerts/alert_rules_log">
        <?php if ($post_data['rule_name'])
        { ?>
        <?php echo __('Edit Rule', true); echo "[".$post_data['rule_name']."]"; ?>
<?php }else{ ?>
        <?php echo __('Add Rule', true); ?>
<?php } ?>
        </a>
    </li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php if ($post_data['rule_name'])
        { ?>
        <?php echo __('Edit Rule', true); echo "[".$post_data['rule_name']."]"; ?>
<?php }else{ ?>
        <?php echo __('Add Rule', true); ?>
<?php } ?>
    </h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
<?php echo $this->element('xback', Array('backUrl' => 'alerts/rules')) ?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="wizard">
        <div class="widget widget-tabs widget-tabs-double widget-tabs-gray widget-body-white">
            <div class="widget-head">
                <ul>
                    <li class="active">
                        <a class="glyphicons paperclip step" id="step1"  data-toggle="tab" href="#tab1-2">
                            <i></i>
                            <span class="strong"><?php __('Step 1'); ?></span>
                            <span><?php __('Define Monitoring Scope'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js projector step" id="step2" hit="" data-toggle="tab" href="#tab2-2" >
                            <i></i>
                            <span class="strong"><?php __('Step 2'); ?></span>
                            <span><?php __('Define Condition'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tag step" id="step3"  hit=""   data-toggle="tab" href="#tab3-2">
                            <i></i>
                            <span class="strong"><?php __('Step 3'); ?></span>
                            <span><?php __('Define Frequency And Sample Size'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a class="glyphicons no-js tint step" id="step4" data-toggle="tab" href="#tab4-2">
                            <i></i>
                            <span class="strong"><?php __('Step 4'); ?></span>
                            <span><?php __('Define Action'); ?></span>
                        </a>
                    </li>
                </ul>    
            </div>
            <div class="widget-body">
                <form method="post" id="rule_form" >
                    <div class="tab-content">
                        <div id="tab1-2" class="tab-pane active">
<?php echo $this->element("rule/step1") ?>
                        </div>
                        <div id="tab2-2" class="tab-pane">
<?php echo $this->element("rule/step2") ?>
                        </div>  
                        <div id="tab3-2" class="tab-pane">
                            <?php echo $this->element("rule/step4") ?>
                        </div>

                        <div id="tab4-2" class="tab-pane">
                            <?php echo $this->element("rule/step3") ?>

                        </div>

                    </div>


                </form>

            </div>

        </div>
    </div>
    <input type="hidden" name="AlertRules[id]" value="1" />
    <input type="hidden" id="step_" value="1" />



</div>

<script type="text/javascript">
    $(function() {

        $(".step:gt(1)").css('cursor', 'not-allowed');

        $("#step3").bind('click', die);
        $("#step4").bind('click', die);

        $("#step1").click(function() {
            $("#step_").val(1);
        });
        var selected_trunk = false;
        $('.trunk_type select:visible').on('change', function(){
            if($(this).val()){
                selected_trunk = true;
            }else{
                selected_trunk = false;
            }
        });

        <?php if (!$post_data['monitor_by']): ?>
       /* $("#next1").on('click', function() {
            var dis_options = ['0', '1', '2', '5', '6', '7'];
            $.each(dis_options, function (index, value) {
                if (!selected_trunk && !$('#select_all').is(':checked')) {
                    $('#acd option[value="' + value + '"]').hide();
                    $('#acd option[value="3"]').attr('selected', 'selected');
                } else {
                    $('#acd option').show();
                    $('#acd option[value="0"]').attr('selected', 'selected');
                }
            });
        });*/
        <?php endif; ?>

    });


    var die = function() {
        return false;
    };
</script>