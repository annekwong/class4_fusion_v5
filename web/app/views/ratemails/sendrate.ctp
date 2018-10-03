<style>
    .divScroll{
        OVERFLOW:auto;
        scrollbar-face-color: #FFFFFF;
        scrollbar-shadow-color: #D2E5F4;
        scrollbar-highlight-color: #D2E5F4;
        scrollbar-3dlight-color: #FFFFFF;
        scrollbar-darkshadow-color: #FFFFFF;
        scrollbar-track-color: #FFFFFF;
        scrollbar-arrow-color: #D2E5F4";
    }
</style>

<style type="text/css">
.form .value, .list-form .value {
    width: 350px;
    text-align:left;
    padding-left:10px;
}
.form .label, .list-form .label {
    width: 150px;
}
.more_content {
    cursor:pointer;
}
#showbox {
  background: none repeat scroll 0 0 #FFFFFF;
  border: 10px solid #7EAC00;
  height: 200px;
  overflow: hidden;
  width: 300px;
  position: fixed;
  display:none;
}
#showbox h1 {
  background: none repeat scroll 0 0 #CCCCCC;
  font-weight: bold;
  line-height: 30px;
  margin: 5px;
  padding-left: 10px;
  text-align: left;
  overflow:hidden;
}
#showbox span {
  cursor: pointer;
  float: right;
  padding-right: 10px;
}
#showbox p {
  padding:5px;
}

</style>

<div id="title">
    <h1><?php __('Send Rate')?></h1>
    <ul id="title-menu">
<!--<li>
<a id="add" class="link_btn" href="javascript:void(0)"><img  alt="" src="<?php echo $this->webroot;?>images/add.png">Create New </a>
</li>-->

<?php if($part_type == 2){?>
<li>
        <a href="<?php echo $this->webroot ?>ratemails/add" id="add" class="link_btn">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/add.png"><?php echo __('createnew',true);?>
        </a>
</li>
<?php }?>
<li>
	<a href="javascript:window.history.go(-1);" class="link_back"><img width="16" height="16" alt="Back" src="<?php echo $this->webroot ?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>    
</li>
<?php if($part_type == 2){?>
<li>
      <?php //********************模糊搜索**************************?>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        <input type="submit" name="submit" value="" class="search_submit"/>
      </form>
</li>
    <?php }?>
</ul>
    
</div>

<ul class="tabs">
    <li <?php if($part_type==null){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>ratemails/sendrate/<?php echo $this->params['pass'][0] ?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Send Rate')?></a></li>
    <li <?php if($part_type=='1'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>ratemails/sendrate/<?php echo $this->params['pass'][0] ?>/1"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('History')?></a></li>
    <li <?php if($part_type=='2'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>ratemails/sendrate/<?php echo $this->params['pass'][0] ?>/2"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Mail Templates')?></a></li>
</ul>

<div class="container">
    
    
    <?php
            if($part_type == null){
    ?>
                <form id="myform" name="myform" method="post" action="<?php echo $this->webroot ?>ratemails/sendrate/<?php echo $this->params['pass'][0] ?>">

            <!--

                    <table class="list">
                        <thead>
                            <tr>

                                <td><?php echo __('Ingress Name',true);?></td>
                                <td><?php echo __('tech_prefix',true);?></td>
                                <td><?php echo __('Rate Table',true);?></td> <td >Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td>
                                    <select name="ingress[]" id="ingress">
                                        <?php foreach($ingresss as $ingress): ?>
                                        <option value="<?php echo $ingress[0]['resource_id']; ?>"><?php echo $ingress[0]['alias']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="tech-prefix[]" id="teach_prefix">
                                    </select>
                                </td>
                                <td>
                                    <select name="rate_table[]" id="rate_table">
                                    </select>
                                </td>
                                <td>
                                    <a class="delete" href="###">
                                    <i class='icon-remove'></i> 
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="clear:both; text-align:center;">
                        <span><?php echo __('Net Change or Full List',true);?></span>
                        <select name="type">
                            <option value="PARTIAL">Net Change</option>
                            <option value="FULL">Full List</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <span><?php echo __('Email Template',true);?></span>
                        <select name="template">
                            <?php foreach($templates as $template): ?>
                            <option value="<?php echo $template['Ratemail']['id']; ?>"><?php echo $template['Ratemail']['name']; ?></option>    
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="form_footer">
                        <input type="submit" value="<?php echo __('submit',true);?>" />
                    </div>
                    -->

                    <table styte="text-align:center;font-size:12px !important;font-family:Arial,Helvetica,sans-serif;">
                        <tr>
                            <td style="width:30%;text-align:right;"><?php echo __('Rate Table',true);?>:</td>
                            <td style="text-align:left">
                                <select style="width:150px;" id="select_code_name" name="rate_table[]" onchange="changeCodeName(this);">
                                    <?php
                                        foreach($tech_prefixs as $tech_prefix ){
                                    ?>
                                    <option value="<?php echo $tech_prefix[0]['rate_table_id'].','.$tech_prefix[0]['tech_prefix'];?>"><?php echo $tech_prefix[0]['name']?>[<?php echo $tech_prefix[0]['tech_prefix'] ?>]</option>
                                    <?php       
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;text-align:right;"><?php echo __('Mail Template',true);?>:</td>
                            <td style="text-align:left">
                                <select name="template" style="width:150px;">
                                    <?php foreach($templates as $template): ?>
                                    <option value="<?php echo $template['Ratemail']['id']; ?>"><?php echo $template['Ratemail']['name']; ?></option>    
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;text-align:right;"><?php echo __('Send Mode',true);?>:</td>
                            <td style="text-align:left">
                                <select id="type" name="type" style="width:150px;" >
                                    <option value="PARTIAL"><?php __('Net Change')?></option>
                                    <option value="FULL"><?php __('Full List')?></option>
                                </select>
                                <input  class="border_no input in-checkbox" type="checkbox" value="SEND" id="send" onchange="getCodeName(this);"><?php __('Send Selected')?>

                            </td>
                           
                        </tr>

                        <tr style="display:none;" id="codeName">
                            <td style="width:30%;text-align:right;"><?php echo __('Code Name',true);?>:</td>
                            <td style="text-align:left">
                            <div class="rate_table" >
                                <div id="code_names" class="block divScroll" style="height: 100px;">


                                </div>

                            </div>
                            </td>
                        </tr>
                    </table>

                    <div id="form_footer">
                        <input type="submit" value="<?php echo __('submit',true);?>" />
                    </div>

                </form>
            </div>

            <script type="text/javascript" refer="refer">
            /*$(function() {
                $('#ingress').change(function() {
                    var $this = $(this);
                    $.ajax({
                        url : '<?php echo $this->webroot ?>ratemails/get_tech/' + $this.val(),
                        type : 'GET',
                        dataType : 'json',
                        success : function(data) {
                            var $tech = $this.parent().parent().find('#teach_prefix');
                            $tech.empty();
                            $.each(data, function(index, value) {
                                var teach_name = value[0]['tech_prefix'] == "" ? 'None' : value[0]['tech_prefix'];
                                $tech.append('<option newer="'+value[0]['id']+'" value="'+teach_name+'">'+teach_name+'</option>');
                            });
                            $tech.change();
                        }
                    });
                });

                $('#teach_prefix').change(function() {
                    var $this = $(this);
                    $.ajax({
                        url : '<?php echo $this->webroot ?>ratemails/get_table/' + $this.find('option:selected').attr('newer'),
                        type : 'GET',
                        dataType : 'json',
                        success : function(data) {
                            var $table = $this.parent().parent().find('#rate_table');
                            $table.empty();
                            $.each(data, function(index, value) {
                                $table.append('<option value="'+value[0]['rate_table_id']+'">'+value[0]['name']+'</option>');
                            });
                        }
                    });
                });

                $('#add').click(function() {
                    $('table.list tbody tr:last').clone(true).appendTo('table.list tbody');
                });

                $('#ingress').change();

                $('.delete').click(function() {
                    if($('table.list tbody tr').length > 1) {
                        $(this).parent().parent().remove();
                    }
                });

            });*/


                $(function(){
                    if(document.getElementById('send').checked){
                        $('#codeName').show();
                    }else{
                        $('#codeName').hide();
                        $('#code_names').find('input[type=checkbox]').attr('checked','');
                    }
                    
                    
                    var rate_table= $("#select_code_name").val().split(',');
                    var rate_table_id = rate_table[0];
                    $("#code_names").html('');
                    $.post("<?php echo $this->webroot; ?>ratemails/get_code_name/"+rate_table_id, {}, function(data){
                        $.each(eval(data),function(index,content){
                            $("#code_names").append("<div class='chkboxgroup'><input class='border_no input in-checkbox' type='checkbox' value='"+content[0].code_name+"' name='rate[]'><span>"+content[0].code_name+"</span></div>");
                        });
                    });
                    
                });


                function getCodeName(obj){
                    if(obj.checked){
                        $('#codeName').show();
                    }else{
                        $('#codeName').hide();
                        $('#code_names').find('input[type=checkbox]').attr('checked','');
                    }
                }
                
                
                function changeCodeName(obj){
                    var rate_table= $(obj).val().split(',');
                    var rate_table_id = rate_table[0];
                    $("#code_names").html('');
                    $.post("<?php echo $this->webroot; ?>ratemails/get_code_name/"+rate_table_id, {}, function(data){
                        $.each(eval(data),function(index,content){
                            $("#code_names").append("<div class='chkboxgroup'><input class='border_no input in-checkbox' type='checkbox' value='"+content[0].code_name+"' name='rate[]'><span>"+content[0].code_name+"</span></div>");
                        });
                    });
                    
                }
            </script>
    <?php    
            }else if($part_type == 1){
            $data =$p->getDataArray();
    ?>
        <div id="toppage"></div>
        <table class="list">
            <thead>
                <tr>
                    <td><?php echo __('Send Date',true); echo $appCommon->show_order('send_date',__(' ',true));?></td>
                    <td><?php echo __('Send To',true);?></td>
                    <td><?php echo __('action',true);?></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $item): ?>
                <tr>
                    <td><?php echo $item['Ratemailhistory']['send_date'] ?></td>
                    <td><?php echo $item['Ratemailhistory']['send_to'] ?></td>
                    <td>
                        <a href="<?php echo $this->webroot; ?>ratemailhistorys/detail/<?php echo $item['Ratemailhistory']['id'] ?>">
                            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/attached_cdr.gif">
                        </a>
                        <a href="<?php echo $this->webroot; ?>ratemailhistorys/delete/<?php echo $item['Ratemailhistory']['id'] ?>">
                            <i class='icon-remove'></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="tmppage"> <?php echo $this->element('page');?> </div>
    </div>
    <?php        
            }else if($part_type == 2){
    ?>
    
            <table class="list">
                <thead>
                    <tr>
                        <td><?php echo __('Name',true);?></td>
                        <td><?php echo __('From Address',true);?></td>
                        <td><?php echo __('From Name',true);?></td>
                        <td><?php echo __('Subject',true);?></td>
                        <td><?php echo __('Content',true);?></td>
                        <td><?php echo __('action',true);?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templates as $template): ?>
                    <tr>
                        <td><?php echo $template['Ratemail']['name']; ?></td>
                        <td><?php echo $template['Ratemail']['from_address']; ?></td>
                        <td><?php echo $template['Ratemail']['from_name']; ?></td>
                        <td><?php echo $template['Ratemail']['subject']; ?></td>
                        <td class="more_content" control="<?php echo $template['Ratemail']['id']; ?>">    
                        <?php
                            echo strlen($template['Ratemail']['content']) < 40 ? $template['Ratemail']['content'] : substr($template['Ratemail']['content'], 0, 40) . '...';
                        ?>
                        </td>
                        <td>
                            <a href="<?php echo $this->webroot ?>ratemails/edit/<?php echo $template['Ratemail']['id']; ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <a href="<?php echo $this->webroot ?>ratemails/delete/<?php echo $template['Ratemail']['id']; ?>">
                                <i class='icon-remove'></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
     <div id="tmppage"> <?php echo $this->element('page');?> </div>
    
        </div>

        <div id="showbox">
            <h1 id="drag">
                <span><img src="<?php echo $this->webroot; ?>images/showbox_close.png" /></span>
            </h1>
            <p>
            </p>
        </div>

        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.center.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.easydrag.js"></script>

        <script type="text/javascript">
        $(function() {
            var $showbox = $("#showbox");
            $showbox.easydrag();
            $showbox.setHandler('drag');
            $('.more_content').click(function() {
                var id = $(this).attr('control');
                $.ajax({
                    url : '<?php echo $this->webroot; ?>ratemails/getcontent/' + id,
                    type : 'GET',
                    dataType : 'text',
                    success : function(data) {
                        $showbox.center();
                        $('p', $showbox).text(data);
                    }
                });
            });
            $('#drag span').click(function() {
                $showbox.hide();
            });
        });
        </script>
    
    <?php        
            }
    
    ?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    