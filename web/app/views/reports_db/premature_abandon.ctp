<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/premature_abandon">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/premature_abandon">
        <?php echo __('Premature Abandon') ?></a></li>
</ul>
<?php

    $user_id = $_SESSION['sst_user_id'];
    $res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Premature Abandon') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">


                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                        <tr>
                            <th rowspan="2"></th>
                            <th rowspan="2"><?php __('Total Calls')?></th>
                            <th colspan="2"><?php __('1s') ?></th>
                            <th colspan="2"><?php __('2s') ?></th>
                            <th colspan="2"><?php __('3s') ?></th>
                            <th colspan="2"><?php __('4s') ?></th>
                            <th colspan="2"><?php __('5s') ?></th>
                            <th colspan="2"><?php __('6s') ?></th>
                        </tr>
                        <tr>
                            <th><?php __('Count')?></th>
                            <th>%</th>
                            
                            <th><?php __('Count')?></th>
                            <th>%</th>
                            
                            <th><?php __('Count')?></th>
                            <th>%</th>
                            
                            <th><?php __('Count')?></th>
                            <th>%</th>
                            
                            <th><?php __('Count')?></th>
                            <th>%</th>
                            
                            <th><?php __('Count')?></th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php __('No Duration Calls')?></td>
                            <td><label class="counts" id = "no_0_c"></label></td>
                            <td><label class="counts" id="no_1_c"></label></td>
                            <td><label class="bbb" id="no_1_b"></label></td>
                            <td><label class="counts" id="no_2_c"></label></td>
                            <td><label class="bbb" id="no_2_b"></label></td>
                            <td><label  class="counts"id="no_3_c"></label></td>
                            <td><label class="bbb" id="no_3_b"></label></td>
                            <td><label  class="counts" id="no_4_c"></label></td>
                            <td><label  class="bbb" id="no_4_b"></label></td>
                            <td><label class="counts" id="no_5_c"></label></td>
                            <td><label  class="bbb" id="no_5_b"></label></td>
                            <td><label class="counts" id="no_6_c"></label></td>
                            <td><label class="bbb" id="no_6_b"></label></td>
                        </tr>
                        
                        <tr>
                            <td><?php __('Connected Calls')?></td>
                            <td><label class="counts" id="yes_0_c"></label></td>
                            <td><label class="counts" id="yes_1_c"></label></td>
                            <td><label class="bbb" id="yes_1_b"></label></td>
                            <td><label class="counts" id="yes_2_c"></label></td>
                            <td><label class="bbb" id="yes_2_b"></label></td>
                            <td><label class="counts" id="yes_3_c"></label></td>
                            <td><label class="bbb" id="yes_3_b"></label></td>
                            <td><label class="counts" id="yes_4_c"></label></td>
                            <td><label class="bbb" id="yes_4_b"></label></td>
                            <td><label class="counts" id="yes_5_c"></label></td>
                            <td><label class="bbb" id="yes_5_b"></label></td>
                            <td><label class="counts" id="yes_6_c"></label></td>
                            <td><label class="bbb" id="yes_6_b"></label></td>
                        </tr>
                    </tbody>
                </table>

            <?php echo $form->create('Cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/reports_db/premature_abandon/" . $type, 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('Search')?></h4>
                <div class="clearfix"></div>
                <?php echo $this->element('search_report/search_js'); ?>
                <table class="form" style="width:100%">
                    <?php echo $this->element('report_db/premature_abandon_form_period', array('group_time' => false, 'gettype' => false)) ?>
                </table>
                

            </fieldset>
<?php echo $form->end(); ?>
        </div>


        <script type="text/javascript">
//            var no_0_c = '';
//            var no_1_c = '';
//            var no_2_c = '';
//            var no_3_c = '';
//            var no_4_c = '';
//            var no_5_c = '';
//            var no_6_c = '';
//            var yes_0_c = '';
//            var yes_1_c = '';
//            var yes_2_c = '';
//            var yes_3_c = '';
//            var yes_4_c = '';
//            var yes_5_c = '';
//            var yes_6_c = '';
$(function(){
    $('form').submit(function(){
//                     no_0_c = '';
//                     no_1_c = '';
//                     no_2_c = '';
//                     no_3_c = '';
//                     no_4_c = '';
//                     no_5_c = '';
//                     no_6_c = '';
//                     yes_0_c = '';
//                     yes_1_c = '';
//                     yes_2_c = '';
//                     yes_3_c = '';
//                     yes_4_c = '';
//                     yes_5_c = '';
//                     yes_6_c = '';
        //alert($("#no_0_c").parents('tr').eq(0).find('.bbb').length);

        //$.each($(".counts"),function(index,content){

        $('table:eq(0)').find('tr').find('td:gt(0) label').html("<img src='<?php echo $this->webroot?>images/check_waiting.gif' />");

        $.ajaxSetup({
            async: false
        });
        var total_no,total_yes;
        $.ajax({
            'url': '<?php echo $this->webroot?>reports_db/get_count_all/true',
            'type': 'post',
            'dataType': 'json',
            'data': {'data': $('form').serialize()},
            'success': function (data) {

                total_no = data['no_0_c'];
                total_yes = data['yes_0_c'];

                $('#no_0_c').html(data['no_0_c']);
                $('#yes_0_c').html(data['yes_0_c']);

            },
            'beforeSend':function(){

            }
        });

        $.ajax({
            'url':'<?php echo $this->webroot?>reports_db/get_count_all',
            'type':'post',
            'dataType':'json',
            'data':{'data':$('form').serialize()},
            'success':function(data){
                for(var i=1; i<=6;i++){
                    $('#no_'+i+'_c').html(data['no_'+i+'_c']);
                    if(total_no == '0'){

                        $('#no_'+i+'_b').html('0.00');
                    } else {
                        console.log(data['no_'+i+'_c'],total_no);
                        var val = (data['no_'+i+'_c']/total_no).toFixed(2);
                        $('#no_'+i+'_b').html(val);
                    }
                }
                for(var i=1; i<=6;i++){
                    $('#yes_'+i+'_c').html(data['yes_'+i+'_c']);

                    if(total_yes == '0'){

                        $('#yes_'+i+'_b').html('0.00');
                    } else {
                        console.log(data['yes_'+i+'_c'],total_yes);
                        var val = (data['yes_'+i+'_c']/total_yes).toFixed(2);
                        $('#yes_'+i+'_b').html(val);
                    }
                }



                /*if(content.id == 'no_0_c'){

                 no_0_c = data['count'];
                 $.each($("#no_0_c").parents('tr').eq(0).find('.bbb'),function(index1,content1){

                 if(no_0_c == 0 || no_0_c == null){
                 $(content1).html(0);
                 } else {
                 if(index1 == 0){
                 //alert(no_1_c);
                 if(no_1_c != '' && data['count'] != 0){
                 //alert((no_1_c/data['count']).toFixed(2));
                 $(content1).html((no_1_c/data['count']).toFixed(2));
                 }
                 }else if(index1 == 1){
                 if(no_2_c != '' && data['count'] != 0){
                 $(content1).html((no_2_c/data['count']).toFixed(2));
                 }
                 }else if(index1 == 2){
                 if(no_3_c != '' && data['count'] != 0){
                 $(content1).html((no_3_c/data['count']).toFixed(2));
                 }
                 }else if(index1 == 3){
                 if(no_4_c != '' && data['count'] != 0){
                 $(content1).html((no_4_c/data['count']).toFixed(2));
                 }
                 }else if(index1 == 4){
                 if(no_5_c != '' && data['count'] != 0){
                 $(content1).html((no_5_c/data['count']).toFixed(2));
                 }
                 }else if(index1 == 5){
                 if(no_6_c != '' && data['count'] != 0){
                 $(content1).html((no_6_c/data['count']).toFixed(2));
                 }
                 }
                 }

                 });
                 }else if(content.id == 'yes_0_c'){
                 yes_0_c = data['count'];
                 //console.log(content,yes_0_c == 0 || yes_0_c == null);
                 $.each($("#yes_0_c").parents('tr').eq(0).find('.bbb'),function(index2,content2){
                 if(no_0_c == 0){
                 $(content2).html(0);
                 } else {
                 if (index2 == 0) {
                 if (yes_1_c != '' && data['count'] != 0) {
                 $(content2).html((yes_1_c / data['count']).toFixed(2));
                 }
                 } else if (index2 == 1) {
                 if (yes_2_c != '' && data['count'] != 0) {
                 $(content2).html((yes_2_c / data['count']).toFixed(2));
                 }
                 } else if (index2 == 2) {
                 if (yes_3_c != '' && data['count'] != 0) {
                 $(content2).html((yes_3_c / data['count']).toFixed(2));
                 }
                 } else if (index2 == 3) {
                 if (yes_4_c != '' && data['count'] != 0) {
                 $(content2).html((yes_4_c / data['count']).toFixed(2));
                 }
                 } else if (index2 == 4) {
                 if (yes_5_c != '' && data['count'] != 0) {
                 $(content2).html((yes_5_c / data['count']).toFixed(2));
                 }
                 } else if (index2 == 5) {
                 if (yes_6_c != '' && data['count'] != 0) {
                 $(content2).html((yes_6_c / data['count']).toFixed(2));
                 }
                 }
                 }
                 });
                 }else{
                 if(no_0_c == 0){
                 $(content).html(0);
                 } else {
                 if (content.id == 'no_1_c') {
                 no_1_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().html((no_1_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'no_2_c') {
                 no_2_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().html((no_2_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'no_3_c') {
                 no_3_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().html((no_3_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'no_4_c') {
                 //alert(no_0_c);
                 no_4_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((no_4_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'no_5_c') {
                 no_5_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((no_5_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'no_6_c') {
                 no_6_c = data['count'];
                 if (no_0_c != '' && no_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((no_6_c / no_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_1_c') {
                 yes_1_c = data['count'];
                 if (yes_0_c != '' && yes_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_1_c / yes_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_2_c') {
                 yes_2_c = data['count'];
                 if (yes_0_c != '' && yes_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_2_c / yes_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_3_c') {
                 yes_3_c = data['count'];
                 if (yes_0_c != '' && yes_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_3_c / yes_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_4_c') {
                 yes_4_c = data['count'];
                 if (yes_0_c != '' && yes_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_4_c / yes_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_5_c') {
                 yes_5_c = data['count'];
                 if (yes_0_c != '' && yes_0_c != 0) {
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_5_c / yes_0_c).toFixed(2));
                 }
                 } else if (content.id == 'yes_6_c') {
                 //alert(yes_0_c);

                 yes_6_c = data['count'];
                 //alert(yes_6_c);
                 if (yes_0_c != '' && yes_0_c != 0) {
                 //alert('ww');
                 $(content).parents('td').eq(0).next().find('.bbb').html((yes_6_c / yes_0_c).toFixed(2));
                 }
                 }
                 }
                 }*/
            }
        });

        // });



        return false;
    });
})


        </script>
    </div>
