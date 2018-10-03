<style type="text/css">
    .height20 {
        height:20px;
    }
    .width80: {
        width:80px;
    }
    .in-text {
        width:80px;
    }
</style>
<script><!--
    function type_change(obj){
        var currRow = obj.parentNode.parentNode;
        if (obj.value == "1"){
            currRow.cells[6].getElementsByTagName("select")[0].style.display="none";
            currRow.cells[6].getElementsByTagName("span")[0].style.display="none";
            currRow.cells[7].getElementsByTagName("select")[0].style.display="";
            currRow.cells[7].getElementsByTagName("span")[0].style.display="";
            currRow.cells[10].getElementsByTagName("select")[0].style.display="none";
        } else if (obj.value == "2"){
            currRow.cells[6].getElementsByTagName("select")[0].style.display="";
            currRow.cells[6].getElementsByTagName("span")[0].style.display="";
            currRow.cells[7].getElementsByTagName("select")[0].style.display="none";
            currRow.cells[7].getElementsByTagName("span")[0].style.display="none";
            currRow.cells[10].getElementsByTagName("select")[0].style.display="";
        } else {
            currRow.cells[6].getElementsByTagName("select")[0].style.display="";
            currRow.cells[7].getElementsByTagName("select")[0].style.display="";
            currRow.cells[6].getElementsByTagName("span")[0].style.display="";
            currRow.cells[7].getElementsByTagName("span")[0].style.display="";
            currRow.cells[10].getElementsByTagName("select")[0].style.display="";
        }
    }

    function jur_change(obj) {

        var currRow = obj.parentNode.parentNode;
        if (obj.value != ""){
            currRow.cells[8].getElementsByTagName("select")[0].style.display="";
            currRow.cells[9].getElementsByTagName("select")[0].style.display="";
        } else {
            currRow.cells[8].getElementsByTagName("select")[0].style.display="none";
            currRow.cells[9].getElementsByTagName("select")[0].style.display="none";
        } 

    }

    function add_fk(){
        jQuery('#list_div').show();
        jQuery('#msg_div').remove();
        if(jQuery('#rec_strategy').attr('add')!=null)
        {
            return;
        }
        var product = "<?php echo $products ?>";
        var dynamic = "<?php echo $dynamics ?>";
        var inter_intra = "<?php echo $inter_intra; ?>";
        var jur_country = "<?php echo $jur_country; ?>";
        var routetype = [
            {k:'1',v:"<?php echo __('dyroute') ?>"},
            {k:'2',v:"<?php echo __('staroute') ?>"},
            {k:'3',v:"<?php echo __('stfirst') ?>"},
            {k:'4',v:"<?php echo __('dyfirst') ?>"}
        ];
        var columns = [
            {hidden:true},
            {hidden:true},
            {hidden:true},
            {className:'marginTop9 width80 input in-text'},
            {className:'marginTop9 width80 input in-text',ownevents:{
                    onkeyup:function(){
                        while(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumberprefix') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},
            {tag:'select',options:routetype,className:'marginTop9 width80 input in-select',ownevents:{onchange:function(){type_change(this);}}},
            {tag:'select',hidden:true,options:eval(product),className:'marginTopStatic width80 input in-select'},
            {tag:'select',options:eval(dynamic),className:'marginTopDyna  width80 input in-select'},
            {tag:'select',hidden:true,options:eval(inter_intra),className:'marginTopStaticwidth80 input in-select'},
            {tag:'select',hidden:true,options:eval(inter_intra),className:'marginTopStatic width80 input in-select'},
            {tag:'select',hidden:true,options:eval(jur_country),className:'marginTopStatic width80 input in-select',ownevents:{onchange:function(){jur_change(this);}}},
            {className:'marginTop9 width80 input in-text',ownevents:{
                    onkeyup:function(){
                        while(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumber') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},
            {className:'marginTop9 width80 input in-text',ownevents:{
                    onkeyup:function(){
                        while(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumber') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},              
            {innerHTML:" "},
            {innerHTML:" "},  

            /*
                                 {tag:'input',type:'checkbox',className:'marginTop9 height20 width80 input in-text',ownevents:{onclick:function (){ $(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false'); }}  },
                                 {tag:'input',type:'checkbox',className:'marginTop9 height20 width80 input in-text',ownevents:{onclick:function (){ $(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false'); }} },
                                 {tag:'input',type:'checkbox',className:'marginTop9 height20 width80 input in-text',ownevents:{onclick:function (){ $(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false'); }} },
             */
				 
            {innerHTML:"<a class='marginTop9' href='javascript:void(0)' title='save' onclick='save_code(this.parentNode.parentNode);'><img src='<?php echo $this->webroot ?>images/menuIcon_004.gif' /></a><a  class='marginTop9' title='Dalete' href='javascript:void(0)' onclick='jQuery(\"#rec_strategy\").removeAttr(\"add\");this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"}
        ];
        var tr=createRow("rec_strategy",columns);
                                 
                                 
        var img1 = document.createElement('span');
        img1.innerHTML = " <img style=\"cursor:pointer;vertical-align:top;\" src=\"<?php echo $this->webroot ?>images/add.png\"  onclick=\"showDiv('pop-static-div','500','100','');\" />";
        $("#rec_strategy tr:last")[0].cells[6].appendChild(img1);
                        
                        
        var img2 = document.createElement('span');
        img2.innerHTML = " <img style=\"cursor:pointer;vertical-align:top;\" src=\"<?php echo $this->webroot ?>images/add.png\"  onclick=\"showDiv('pop-div','500','auto','');\" />";
        $("#rec_strategy tr:last")[0].cells[7].appendChild(img2);        
                                 
                                 
        type_change($($("#rec_strategy tr:last")[0].cells[5]).find('select')[0]);          
        jQuery(tr).find('input:nth-child(1)').attr('maxLength','16');
        jQuery(tr).find("input:nth-child(1)").eq(6).attr('checked', 'true');
        jQuery('#rec_strategy').attr('add','true');
				
    }



    function save_code(tr){
        var digits =tr.cells[3].getElementsByTagName('input')[0].value;
        var  reg=/^\d+$/;
        if(/[^0-9A-Za-z-\_\s]/.test(digits)){
            jQuery(tr.cells[3].getElementsByTagName('input')[0]).addClass('invalid');
            jGrowl_to_notyfy('Prefix can only be a-z,A-Z,0-9,-,_,<space>',{theme:'jmsg-error'});
            return false;
        }
        var params = {
            digits :tr.cells[4].getElementsByTagName('input')[0].value,
            static_route_id :tr.cells[6].getElementsByTagName('select')[0].style.display=="none"?"":tr.cells[6].getElementsByTagName('select')[0].value,
            dynamic_route_id :tr.cells[7].getElementsByTagName('select')[0].style.display=="none"?"":tr.cells[7].getElementsByTagName('select')[0].value,
            intra_static_route_id :tr.cells[8].getElementsByTagName('select')[0].style.display=="none"?"":tr.cells[8].getElementsByTagName('select')[0].value,
            inter_static_route_id :tr.cells[9].getElementsByTagName('select')[0].style.display=="none"?"":tr.cells[9].getElementsByTagName('select')[0].value,
            jurisdiction_country_id :tr.cells[10].getElementsByTagName('select')[0].style.display=="none"?"":tr.cells[10].getElementsByTagName('select')[0].value,
            /*
                lnp :tr.cells[7].getElementsByTagName('input')[0].value,
                lrn_block :tr.cells[8].getElementsByTagName('input')[0].value,
                dnis_only :tr.cells[9].getElementsByTagName('input')[0].value,
             */    
            ani_digits: tr.cells[3].getElementsByTagName('input')[0].value, 
            ani_min_length: tr.cells[11].getElementsByTagName('input')[0].value, 
            ani_max_length: tr.cells[12].getElementsByTagName('input')[0].value, 	
            route_type :tr.cells[5].getElementsByTagName('select')[0].value,
            pid : "<?php echo $id ?>"
        };

        jQuery.post('<?php echo $this->webroot ?>routestrategys/add_route',params,function(data){
            var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
            var  tmp = data.split("|");
            if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
            jGrowl_to_notyfy(tmp[0],p);
        });
    }


    function edit(currRow,type){
        var product = "<?php echo $products ?>";
        var dynamic = "<?php echo $dynamics ?>";
        var inter_intra = "<?php echo $inter_intra; ?>";
        var jur_country = "<?php echo $jur_country; ?>";

        var routetype = [
            {k:'1',v:"<?php echo __('dyroute') ?>"},
            {k:'2',v:"<?php echo __('staroute') ?>"},
            {k:'3',v:"<?php echo __('stfirst') ?>"},
            {k:'4',v:"<?php echo __('dyfirst') ?>"}
        ];
        var columns = [
            {hidden:true},
            {hidden:true},
            {hidden:true},
            {className:'marginTop9 width80 input in-text checkLength'},
            {className:'marginTop9 width80 input in-text checkLength',ownevents:{
                    onkeyup:function(){
                        if(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]+/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumberprefix') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},
            {tag:'select',options:routetype,selected:currRow.cells[5].innerHTML.trim(),className:'marginTop9 width80 input in-text ',ownevents:{onchange:function(){type_change(this);}}},
            {tag:'select',options:eval(product),selected:currRow.cells[6].innerHTML.trim(),className:'marginTopStatic  width80 input in-text '},
            {tag:'select',options:eval(dynamic),selected:currRow.cells[7].innerHTML.trim(),className:'marginTopDyna width80 input in-text '},
            {tag:'select',options:eval(inter_intra),selected:currRow.cells[8].innerHTML.trim(),className:'marginTopStatic width80 input in-text '},
            {tag:'select',options:eval(inter_intra),selected:currRow.cells[9].innerHTML.trim(),className:'marginTopDyna width80 input in-text '},
            {tag:'select',options:eval(jur_country),selected:currRow.cells[10].innerHTML.trim(),className:'marginTopDyna width80 input in-text ',ownevents:{onchange:function(){jur_change(this);}}},
            {className:'marginTop9 width80 input in-text checkLength',ownevents:{
                    onkeyup:function(){
                        if(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]+/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumber') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},                    
            {className:'marginTop9 width80 input in-text checkLength',ownevents:{
                    onkeyup:function(){
                        if(/[^0-9]/.test(jQuery(this).val()))
                        {
                            var val=jQuery(this).val().replace(/[^0-9]+/,'');
                            jQuery(this).val(val);
                            showMessages("[{'field':'#select','code':'101','msg':'<?php __('Canonlybenumber') ?>'}]");
                            jQuery(this).focus();
                        }
                    }
                }},                 
            {},
            {}
        ];
        editRow(currRow,columns);
                        
                        
                        
        var img1 = document.createElement('span');
        img1.innerHTML = " <img style=\"cursor:pointer;vertical-align:top;\" src=\"<?php echo $this->webroot ?>images/add.png\"  onclick=\"showDiv('pop-static-div','500','100','');\" />";
        currRow.cells[6].appendChild(img1);
                        
                        
        var img2 = document.createElement('span');
        img2.innerHTML = " <img style=\"cursor:pointer;vertical-align:top;\" src=\"<?php echo $this->webroot ?>images/add.png\"  onclick=\"showDiv('pop-div','500','auto','');\" />";
        currRow.cells[7].appendChild(img2);


                        
                        
        jQuery('input.checkLength').attr('maxLength','16');
			
        /*
                        if(currRow.cells[7].getElementsByTagName("input")[0].value.trim() == 'True'){
                                currRow.cells[7].getElementsByTagName("input")[0].checked='checked';
                        }

                        if(currRow.cells[8].getElementsByTagName("input")[0].value.trim()=='True'){
                                currRow.cells[8].getElementsByTagName("input")[0].checked='checked';
                                }

                        if(currRow.cells[9].getElementsByTagName("input")[0].value.trim()=='True'){
                                currRow.cells[9].getElementsByTagName("input")[0].checked='checked';
                                }
         */
        type_change(currRow.cells[5].getElementsByTagName("select")[0]);
        jur_change(currRow.cells[10].getElementsByTagName("select")[0]);
        //var btn = currRow.cells[10].getElementsByTagName("a")[0];
        var btn = currRow.cells[15].getElementsByTagName("a")[0];
        if (btn){
            /*
                         var cancel = currRow.cells[10].getElementsByTagName("a")[1].cloneNode(true);
                     cancel.title = "Cancel";
                         $(cancel).addClass('cancel');
                     cancel.getElementsByTagName("img")[0].src="";
                     cancel.onclick = function(){}
                     currRow.cells[10].appendChild(cancel);
             */
            $a = $('<a />');
            $img = $('<img />').attr('src', '<?php echo $this->webroot ?>images/rerating_queue.png')
            .css('cursor', 'pointer');
            $a.append($img);
            $a.click(function() {
                location.reload();
            });
            //currRow.cells[10].appendChild($a.get(0));
            currRow.cells[15].appendChild($a.get(0));
			 
            btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot ?>images/menuIcon_004.gif";
            btn.onclick = function(){
                /*原本在下面params的空白处
                                lnp :currRow.cells[7].getElementsByTagName('input')[0].value,
                                lrn_block :currRow.cells[8].getElementsByTagName('input')[0].value,
                                dnis_only :currRow.cells[9].getElementsByTagName('input')[0].value,
                 */

                var params = {
                    ani_digits :currRow.cells[3].getElementsByTagName('input')[0].value,
                    digits :currRow.cells[4].getElementsByTagName('input')[0].value,
                    static_route_id :currRow.cells[6].getElementsByTagName('select')[0].style.display=="none"?"":currRow.cells[6].getElementsByTagName('select')[0].value,
                    dynamic_route_id :currRow.cells[7].getElementsByTagName('select')[0].style.display=="none"?"":currRow.cells[7].getElementsByTagName('select')[0].value,
                    intra_static_route_id :currRow.cells[8].getElementsByTagName('select')[0].style.display=="none"?"":currRow.cells[8].getElementsByTagName('select')[0].value,
                    inter_static_route_id :currRow.cells[9].getElementsByTagName('select')[0].style.display=="none"?"":currRow.cells[9].getElementsByTagName('select')[0].value,
                    jurisdiction_country_id :currRow.cells[10].getElementsByTagName('select')[0].style.display=="none"?"":currRow.cells[10].getElementsByTagName('select')[0].value,
                    ani_min_length :currRow.cells[11].getElementsByTagName('input')[0].value,
                    ani_max_length :currRow.cells[12].getElementsByTagName('input')[0].value,
		
                    route_type :currRow.cells[5].getElementsByTagName('select')[0].value,
                    id : currRow.cells[1].innerHTML.trim(),
                    pid : "<?php echo $id ?>"
                };
                jQuery.post('<?php echo $this->webroot ?>routestrategys/update_route',params,function(data){
                    var p = {theme:'jmsg-success',beforeClose:function(){
				    	
                            window.location.reload();
				    	
                            var  str=location.toString();
	                
                            if(str.indexOf("?")){
                                location=location.toString();
                            } else{
		                                                   
                                location=location.toString()+"?edit_id="+params.id;
                            }
                     
                        },life:100};
                    var  tmp = data.split("|");
                    if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
                    jGrowl_to_notyfy(tmp[0],p);
                });
            }	
        }
    }
    --></script>
<div id="cover"></div>
<?php $w = $session->read('writable') ?>
<div id="title">
    <h1>Routing &gt;&gt; Routing Plan:<font  class="editname" title="Name">
        <?php echo empty($rs_name[0][0]['name']) || $rs_name[0][0]['name'] == '' ? '' : "[" . $rs_name[0][0]['name'] . "]"; ?>
        </font> </h1>
    <ul id="title-search">
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
            </form>
        </li>
    </ul>
    <ul id="title-menu">

        <!--
                              <li><a href="<?php echo $this->webroot ?>routestrategys/import_rate/<?php echo $id; ?>"><img width="9" height="9" src="<?php echo $this->webroot ?>images/import.png"> <?php __('import') ?></a></li>
                              <li><a href="<?php echo $this->webroot ?>routestrategys/download_rate/<?php echo $id; ?>"><img width="10" height="5" alt="" src="<?php echo $this->webroot ?>images/export.png"><?php __('download') ?></a></li>
                                    
        -->
        <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) { ?>

            <li> <a class="link_btn" href="#" onClick="add_fk();return false"> <img width="9" height="9" alt="" src="<?php echo $this->webroot ?>images/add.png"> <?php echo __('createnew') ?> </a> </li>

            <li><a class="link_btn" rel="popup" href="javascript:void(0)" onClick="deleteAll('<?php echo $this->webroot ?>routestrategys/del_route/all/<?php echo $id ?>');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/delete.png"> <?php echo __('deleteall') ?></a></li>
            <li><a class="link_btn" rel="popup" href="javascript:void(0)" onClick="deleteSelected('rec_strategy','<?php echo $this->webroot ?>routestrategys/del_route/selected/<?php echo $id ?>');"><img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/delete.png"> <?php echo __('deleteselected') ?></a></li>
        <?php } ?>

        <li> <a class="link_back" href="<?php echo $this->webroot ?>routestrategys/strategy_list"> <img width="9" height="5" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="">
                <?php __('back') ?>
            </a> </li>

    </ul>
</div>
<div id="container">
    <ul class="tabs">
        <li class="active"><a href="<?php echo $this->webroot ?>routestrategys/routes_list/<?php echo $id; ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif">List</a></li>
        <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_x']) { ?> <li><a href="<?php echo $this->webroot ?>uploads/route_plan/<?php echo $id; ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> Import</a></li>
            <li><a href="<?php echo $this->webroot ?>down/routing_plan/<?php echo $id; ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> Export</a></li>
        <?php } ?>
    </ul>
    <?php $d = $p->getDataArray();
    if (count($d) == 0) { ?>
        <div class="msg"  id="msg_div"><?php echo __('no_data_found') ?></div>
    <?php } else {
        ?>
        <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found') ?></div>
    <?php } ?>
    <?php $d = $p->getDataArray();
    if (count($d) == 0) { ?>
        <div  id="list_div"  style="display: none;">
        <?php } else {
            ?>
            <div   id="list_div">
<?php } ?>
            <div id="toppage"></div>
            <table class="list">
                <thead>
                    <tr>
                        <td><input type="checkbox" onClick="checkAllOrNot(this,'rec_strategy');" value=""/></td>
                        <td>
<?php echo $appCommon->show_order('route_id', __('ID', true)) ?>
                        </td>
                        <td>&nbsp;<?php echo __('strategy_name') ?>&nbsp;</td>
                        <td>
<?php echo $appCommon->show_order('ani_prefix', __('ANI Prefix', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('digits', __('DNIS Prefix', true)) ?>
                        </td>

                        <td>
<?php echo $appCommon->show_order('route_type', __('dyorsta', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('static_route', __('staroute', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('dynamic_route', __('dyroute', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('intra_static_route_id', __('Intra Static Route', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('inter_static_route_id', __('Inter Static Route', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('jurisdiction_country_id', __('Jurisdiction Country', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('ani_min_length', __('ANI Min Length', true)) ?>
                        </td>
                        <td>
<?php echo $appCommon->show_order('ani_max_length', __('ANI LESS THAN / LT Length', true)) ?>
                        </td>
                        <td><?php echo __('Update At', true) ?></td>
                        <td><?php echo __('Update By', true) ?></td>
                        <!--
                        <td>LRN&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onClick="my_sort('route_type','asc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a><a class="sort_dsc"  href="javascript:void(0)" onClick="my_sort('route_type','desc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a></td>
                        <td>Block LRN&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onClick="my_sort('static_route','asc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a><a class="sort_dsc"  href="javascript:void(0)" onClick="my_sort('static_route','desc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a></td>
                        <td>DNIS Only&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onClick="my_sort('dynamic_route','asc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a><a class="sort_dsc"  href="javascript:void(0)" onClick="my_sort('dynamic_route','desc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/p.png"></a></td>
                        -->
                        <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) { ?><td class="last" style="width:10%;"><?php echo __('action') ?></td>
<?php } ?>
                    </tr>
                </thead>
                <tbody id="rec_strategy">
                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++) {
                        ?>
                        <tr class="row-1">
                            <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['route_id'] ?>"/></td>
                            <td><?php echo $mydata[$i][0]['route_id'] ?></td>
                            <td style="font-weight: bold;"><?php echo $mydata[$i][0]['strategy'] ?></td>
                            <td><?php echo $mydata[$i][0]['ani_prefix'] ?></td>
                            <td><?php echo $mydata[$i][0]['digits'] ?></td>
                            <td><?php
                            if ($mydata[$i][0]['route_type'] == 1) {
                                echo __('dyroute', true);
                            } else if ($mydata[$i][0]['route_type'] == 2) {
                                echo __('staroute', true);
                            } else if ($mydata[$i][0]['route_type'] == 3) {
                                echo __('stfirst', true);
                            } else {
                                echo __('dyfirst', true);
                            }
                            ?></td>
                            <td><?php echo $mydata[$i][0]['static_route'] ?></td>
                            <td><?php echo $mydata[$i][0]['dynamic_route'] ?></td>
                            <td><?php echo $mydata[$i][0]['intra_static_route'] ?></td>
                            <td><?php echo $mydata[$i][0]['inter_static_route'] ?></td>
                            <td><?php echo $mydata[$i][0]['jurisdiction_country'] ?></td>
                            <td><?php echo $mydata[$i][0]['ani_min_length'] ?></td>
                            <td><?php echo $mydata[$i][0]['ani_max_length'] ?></td>
                            <td><?php echo $mydata[$i][0]['update_at'] ?></td>
                            <td><?php echo $mydata[$i][0]['update_by'] ?></td>
                            <!--
                            <td ><?php if (empty($mydata[$i][0]['lnp'])) {
                                echo 'False';
                            } else {
                                echo 'True';
                            } ?></td>
                            <td ><?php if (empty($mydata[$i][0]['lrn_block'])) {
                                echo 'False';
                            } else {
                                echo 'True';
                            } ?></td>
                            <td ><?php if (empty($mydata[$i][0]['dnis_only'])) {
                                echo 'False';
                            } else {
                                echo 'True';
                            } ?></td>
                            -->
    <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) { ?><td style="width:auto">
                                    <a title="<?php echo __('edit') ?>"  href="javascript:void(0)" onClick="edit(this.parentNode.parentNode,<?php echo $mydata[$i][0]['route_type'] ?>);"> <i class="icon-edit"></i> </a> <a title="<?php echo __('del') ?>"  href="javascript:void(0)" onClick="ex_delConfirm(this,'<?php echo $this->webroot ?>routestrategys/del_route/<?php echo $mydata[$i][0]['route_id'] ?>/<?php echo $id ?>','routing <?php echo $mydata[$i][0]['digits'] ?>');"> <i class='icon-remove'></i> </a>
                                </td>
    <?php } ?>
                        </tr>
<?php } ?>
                </tbody>
            </table>
            <div id="tmppage"> <?php echo $this->element('page'); ?> </div>
<?php echo $this->element('routestrategys/massedit') ?> 
        </div>
    </div>
</div>



<!-----------Add Rate Table----------->
<div id="pop-div" class="pop-div" style="display:none;height:auto">
    <div class="pop-thead">
        <span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content">



    </div>
<?php echo $this->element('dynamicroutes/massAdd') ?>
</div>
<div id="pop-clarity" class="pop-clarity" style="display:none;"></div>


<!-----------Add Rate Table----------->
<div id="pop-static-div" class="pop-div" style="display:none;height:auto">
    <div class="pop-thead">
        <span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-static-div')" id="pop-static-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-static-content">

        <lable><?php __('Name')?>:</lable><input type="text" name="pname" id="pname" style="width:150px;"/>
        <p>
        <div onclick="checkPname()"><a id="massbtn" class="input in-button"><?php echo __('submit', true); ?></a></div>

    </div>
</div>
<div id="pop-static-clarity" class="pop-static-clarity" style="display:none;"></div>

<script type="text/javascript">
    function checkPname(){
        $.post('<?php echo $this->webroot; ?>routestrategys/addStaticRouting',{name:$("#pname").val()},
        function (data){
            if(data == 'nameIsNull'){
                alert('The field name cannot be NULL!');
            }else if(data == 'nameNotPreg'){
                alert('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!');
            }else if(data == 'nameLength'){
                alert('the length of the name must be less than 30');
            }else if(data == 'nameIsHave'){
                alert('name is already in use!');
            }else if(data =='no'){
                alert('add failed');
            }else{
                $(".marginTopStatic").append("<option value = '"+data+"'>"+$("#pname").val()+"</option>");
                $(".marginTopStatic").attr('value',data);
                $("#product_id").attr('value',data);
                $("#pname").attr("value","");
                closeDiv('pop-static-div');
                showDiv('pop-static-div1','700','auto','');
            }
        }
        
    );
    }
</script>


<div id="pop-static-div1" class="pop-div" style="display:none;height:auto">
    <div class="pop-thead">
        <span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-static-div1')" id="pop-static-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-static-content1">


    </div>
<?php echo $this->element('routestrategys/massAdd') ?>
</div>
<div id="pop-static-clarity1" class="pop-static-clarity" style="display:none;"></div>


