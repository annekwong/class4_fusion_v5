<style type="text/css">
    .width125{
        width: 125px;
    }
</style>
<?php
$width_class = 'width125';
if(isset($has_trunk)){
    $width_class = '';
}
?>
<div class="row-fluid margin-bottom10 center">
    <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/us_domestic_traffic/{$function}")); ?>
    <?php if (in_array($active,array('far','flr'))): ?>
        <div class="span3">
            <input type="hidden" name="us_rate_table" value="0" />
            <span class="align_right padding-r10"><?php __('Routing Plan')?></span>
            <select id="routing_plan" name="routing_plan" class="validate[required] <?php echo $width_class; ?>">
                <option value="">
                </option>
                <?php
                foreach ($routing_plans as $routing_plan)
                {
                    $checked = '';
                    if (isset($_GET['routing_plan']) && $_GET['routing_plan'] == $routing_plan[0]['id'])
                        $checked = "selected = 'selected'";
                    echo "<option value='" . $routing_plan[0]['id'] . "' $checked>" . $routing_plan[0]['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="span3">
            <span class="align_right padding-r10"><?php __('Ingress Trunk')?></span>
            <?php echo $form->input('trunk_id',array('type' =>'select','options'=>$ingress,'label' =>false,'div'=>false,
                'class'=>'validate[required]','name' =>'trunk_id')); ?>
        </div>
    <?php else: ?>
        <div class="<?php echo isset($has_trunk) ? 'span3' : 'span2'; ?>">
            <span class="align_right padding-r10"><?php __('Rate Table')?></span>
            <select id="us_rate_table" name="us_rate_table" class="validate[required] <?php echo $width_class; ?>">
                <option value="">
                </option>
                <?php
                foreach ($us_rate_tables as $us_rate_table)
                {
                    $checked = '';
                    if (isset($_GET['us_rate_table']) && $_GET['us_rate_table'] == $us_rate_table['Rate']['rate_table_id'])
                        $checked = "selected = 'selected'";
                    echo "<option value='" . $us_rate_table['Rate']['rate_table_id'] . "' $checked>" . $us_rate_table['Rate']['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="<?php echo isset($has_trunk) ? 'span3' : 'span2'; ?>">
            <span class="align_right padding-r10"><?php __('Bill Method')?></span>
            <?php echo $form->input('bill_method',array('type' =>'select','options'=>array('DNIS','LRN'),'label' =>false,'div'=>false,
                'class'=>"validate[required] $width_class",'name' =>'bill_method')); ?>
        </div>
        <div class="<?php echo isset($has_trunk) ? 'span3' : 'span2'; ?>">
            <span class="align_right padding-r10"><?php __('Routing Plan')?></span>
            <select id="routing_plan" name="routing_plan" class="validate[required] <?php echo $width_class; ?>">
                <option value="">
                </option>
                <?php
                foreach ($routing_plans as $routing_plan)
                {
                    $checked = '';
                    if (isset($_GET['routing_plan']) && $_GET['routing_plan'] == $routing_plan[0]['id'])
                        $checked = "selected = 'selected'";
                    echo "<option value='" . $routing_plan[0]['id'] . "' $checked>" . $routing_plan[0]['name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <?php if(isset($has_trunk)): ?>
            <div class="span3">
                <span class="align_right padding-r10"><?php __('Egress Trunk')?></span>
                <?php echo $form->input('trunk_id',array('type' =>'select','options'=>$egress,'label' =>false,'div'=>false,
                    'class'=>'validate[required]','name' =>'trunk_id')); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="span6">
        <table class="form" style="width:100%">
            <?php echo $this->element('us_reports/form_period')?>
        </table>
    </div>
    <?php echo $form->end(); ?>
</div>