<fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
    <div class="clearfix"></div>
    <div class="row separator">
        <div class="span2">
            <?php __('time') ?>:
            <?php echo $form->input('smartPeriod', array('options' => $appCommon->get_time_select(), 'label' => false,
                'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod',
                'style' => 'width:90px;', 'div' => false, 'type' => 'select', 'selected' => $appCommon->_get('smartPeriod','curDay')));
            ?>
        </div>
        <div class="span7">
            <input type="text" id="query-start_date-wDt" class="in-text input  wdate width80" onchange="setPeriod('custom')"
                   readonly="readonly" onkeydown="setPeriod('custom')" value="" name="start_date">
            &nbsp;<input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                         readonly="readonly" value="00:00:01" name="start_time" class="input in-text width80">
            &nbsp;--&nbsp;
            <input type="text" id="query-stop_date-wDt" class="in-text input  wdate width80" onchange="setPeriod('custom')"
                   readonly="readonly" onkeydown="setPeriod('custom')" value="" name="stop_date">
            &nbsp;<input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')" readonly="readonly"
                         onkeydown="setPeriod('custom')" value="23:59:59" name="stop_time" class="input in-text width80">
            in
            <?php echo $form->input('tz',array('name' => 'query[tz]','id' => 'query-tz','class'=>'width120',
                'options' =>$appCommon->get_timezone_arr(),'selected' => $appCommon->_get('query.tz'),'label' => false,'div' => false )); ?>
        </div>
        <div class="span2">
            <?php
            $r = array('' => __('alltime', true), 'YYYY-MM-DD  HH24:00:00' => __('byhours', true), 'YYYY-MM-DD' => __('byday', true), 'YYYY-MM' => __('bymonth', true), 'YYYY' => __('byyear', true));
            echo $form->input('group_by_date', array('options' => $r, 'label' => false, 'id' => 'query-group_by_date',
                'style' => 'width: 120px;', 'name' => 'group_by_date',
                'div' => false, 'type' => 'select', 'selected' => $appCommon->_get('group_by_date')));
            ?>
        </div>
        <div class="span2">
            <select  class="width120" name="show_type">
                <option value="0">Web</option>
                <option value="1">CSV</option>
                <option value="2">XLS</option>
            </select>
        </div>
        <div class="span1">
            <input type="submit" value="query" id="formquery" class="btn btn-primary margin-bottom10">
        </div>

        <div class="col-md-2"></div>
    </div>
</fieldset>
<script type="text/javascript">
    $(function(){
        $("select[name='show_type']").change(function(){
            var $this = $(this);
            var change_type = $(this).find('option:selected').val();
            if (change_type == 0){
                $this.closest('form').removeAttr('target');
            }else{
                $this.closest('form').attr('target','_blank');
            }

        });
    })
</script>