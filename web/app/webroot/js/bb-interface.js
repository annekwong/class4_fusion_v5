
/**
 * 
 * @param interval
 * @param url
 * @param data
 * @param options
 * @return
 */
var AjaxInterval = function(interval,url,data,options){
	var intervalFlag = null;
	
	//将返回数据填入div
	var default_options = {
					success : function(data){
						if(options['div']){
						  $(options['div']).html(data);
						}
			},
					error : function(){}					
			};
	
	//用options 重写  default_options
			default_options = $.extend(default_options,options);
			var ajax = function(){
					if(!intervalFlag){
							intervalFlag = true;
							//获取数据
							$.ajax({url:url,data:data,
									success : function(data){
									
								intervalFlag = null;									
								//拿到数据填充div
								default_options.success(data);
								},
									error : function(data){
										intervalFlag = null;										
										default_options.error(data);
								}				
							});							
					}
			}
		ajax();
		 return  	setInterval(ajax,interval);//定时执行  ajax() 返回定时器
}



/**
 * Init time
 */
$(function () {
	if ($('#topmenu-time').length) {
	    setInterval(function () {bbTime('topmenu-time');}, 1000);
    }
});

/**
 * Run dropdown menu
 */
$(function () {
    //$('#topmenu-menu > li').bind('mouseover', jsddm_open);
    //$('#topmenu-menu > li').bind('mouseout',  jsddm_timer);
});
//$(document).bind('block', jsddm_close);

/**
 * Run advanced / smart  search
 */
var smartSearch = 0;
$(function () {
	if (!smartSearch) {
        //$('#title-search').hide();
    } else if (smartSearch == 2) { // dynamic search
        $('#title-search input[type=text]').bind('keyup', dynSearchRun);
    } else if (smartSearch == 3) { // dynamic search in popups
    	$('#search-_q').bind('keyup', dynSearchRun);
    }
    if (!$('#advsearch').length) {
        return;
    }
    if ($('#advsearch[rel*=advsearch-open]').length) {
        advSearchToggle(1);
        return;
    }
    $('#title-search-adv').show();
    
    var search = $.query(location.search);
    $('#advsearch input,#advsearch select').each(function () {
        if ($(this).attr('name') && search[$(this).attr('name')] && search[$(this).attr('name')] != '') {
            advSearchToggle(1);
            return;
        }
    });
    var search = $('#search-_q');
    if (search.val() != '' && search.val() != search.attr('title')) {
	    
    	//点击高级搜索的时候将模糊搜索的input 加入高级搜索的表单
    	$('#advsearch').find('form').submit(function() {
	    	$('#advsearch > form').append('<input name="search[_q]" type="hidden" value="' + $('#search-_q').val() + '" />');
	    });
    }
});




function  show_all_rate(){
//	if(!$('#search_q').val()){
//		$('#search_q').attr('class','invalid');
//		jQuery.jGrowl('Please input search condition !',{theme:'jmsg-error'});
//		return;
//	}

	$('#likesearch').submit(function() {
		$('#likesearch').append('<input name="filter_effect_date" type="hidden" value="all" />');
    });
	
	
	$('#likesearch').submit();
}

/*
 *显示当前rate
 */
function show_current_rate(){
    $("#likesearch").submit(function(){
        $('#likesearch').append('<input name="filter_effect_date" type="hidden" value="" />');
    });
    $("#likesearch").submit();
}

/**
*去掉a，button，submit点击时出现的虚线框
*/
$(function(){ 
    $('a,input[type="button"],input[type="submit"],input[type="radio"],input[type="checkbox"]').bind('focus',function(){ 
        if(this.blur){ 
            this.blur(); 
        }; 
    });
	$('input[type="radio"],input[type="checkbox"]').addClass('border_no');
});
/**
 * MAIN INIT
 */
$(function() {
	

		//init_ext_Tooltips();
	
    initTooltips();

    initPopups();
	
    initList();
    initForms();
    initInputDefault();
    initFocuses();
});




/**
 * 
 * 
 * 
 */



function initTooltips(obj){
	   if (!obj) {
	        obj = $(document);
	    }
	   obj.find('*[rel=tooltip]').live('click',function () {
		   var d=$('#'+this.id+'-tooltip').dialog({
			   width: 460,
			   resizeStop: function(event, ui) {
			   var top =$(this).attr('style','top:0px; display:block;"');
			  
			   
			   
		   }
		   });
	
		  // $('#'+this.id+'-tooltip').find('dl').live('click',function(){$(this).attr('top','0');});

	   });
	   
	
	
}

/**
 * Init tooltips / helptips(点击页面的列表页面生成提示框的)
 */
function init_ext_Tooltips(obj)
{
    if (!obj) {
        obj = $(document);
    }
    
    //找到rel=tooltip的tr,给当前的tr生成提示
    obj.find('*[rel=tooltip_ext]').tooltip({
        track: false,
        delay: 0,
        showURL: false,
        bodyHandler: function() { 
    	   //找到每一行的对应的提示(i_911-tooltip)
    		var t = $('#'+this.id+'-tooltip');
	    	if(t.length > 0){
	            return $('<div/>').append($('#'+this.id+'-tooltip').clone().removeClass('tooltip').show()).html();
	    		}
    	}, 
        fade: false,
        top:0
    });
    
    
    //对有 rel="helptip"的标签生成黄色的帮助提示
/*   obj.find('span[rel=helptip]').tooltip({
        track: true,
        delay: 0,
        showURL: false,
        bodyHandler: function() { 
            return $('<div />').append($('#'+this.id+'-tooltip').clone().removeClass('tooltip')).html();
        }, 
        extraClass:'helptip',
        fade:false
    });*/
}

/**
 * Handle popup links
 */
function initPopups(obj)
{
    if (!obj) {
        obj = $(document);
    }
    
    
   
    obj.find('a[rel*=popup]').each(function () {
        // stop propagation of the event first
        $(this).click(function (event) {
            event.stopPropagation();
            this.blur();
        });
     
        // if handler already set - retreat
//        if ($(this).attr('onclick') != undefined)  {
//            return this;
//        }
        
        // main handler
       
        $(this).click(function (event) {
            if ($(this).attr('rel').indexOf('delete') != -1) {
                if (!confirm(L['deleteConfirm'])) {
                    return false;
                }
            }
            var info = $(this).attr('rel').match(/popup-(\d+)x(\d+)/);
            if (info != null) {
               // winOpen(this.href, info[1], info[2]);
            } else {
              //  winOpen(this.href);
            }
            return false;
        });
    });
}

/**
 * Handle list / meta
 * 初始化列表页面
 */
function initList(obj)
{
	

    if (!obj) {
        obj = $(document);
    }
    
    // find all table-lists找到class=list的tale
    var list = obj.find('table.list');
    
    // color rows
    /*
    list.find('> * > tr > td:last-child').addClass('last');
    list.find('> tbody > tr:even').addClass('row-1');//给单数行设置
    list.find('> tbody > tr:odd').addClass('row-2');//给复数行设置样式row_2
    */
    // hover / click  给列表的每一行添加鼠标旋停的样式，和点击的样式
    obj.find('table.list:not(table.list-form) > tbody > tr').hover(function () {
        $(this).addClass('row-hover');
    }, function () {
        $(this).removeClass('row-hover');
    }).click(function () {
        if ($(this).is('.row-active')) {
            $(this).removeClass('row-active');
        } else {
            $(this).addClass('row-active');
        }
    });
    
    
    //给列表的上下分页添加样式
    obj.find('.list-meta').not('.xpage').each(function () {
        // handle page-gometa
        $(this).find('.list-meta-pnum').click(function (event) {
            $(this).hide()
                .parent().find('.list-meta-pgo').show()
                .find('input').focus();
        });
        
        // on blur of page-go - hide it
        $(this).find('.list-meta-pgo input').bind('blur', function () {
            $(this).parent().parent().hide()
                .parent('.list-meta').find('.list-meta-pnum').show();
        });
        
        // handle page-ipp
        $(this).find('.list-meta-ipp').click(function (event) {
            $(this).hide()
                .parent().find('.list-meta-ippa').show()
                .find('select').focus();
        });
        
        // on blur of ipp - hide it
        $(this).find('.list-meta-ippa select').bind('blur', function () {
        	
       
            $(this).parent().parent().hide()
                .parent('.list-meta').find('.list-meta-ipp').show();
        });
        
        // on change of ipp - submit
        $(this).find('.list-meta-ippa select').bind('change', function () {
       var form_size=$('#report_form').size(); 
       if(form_size==1){
    	   $('#exchange_size').val($(this).val());
           
    	   $('#report_form').submit();
    	   return ;
       }
	   		/*
			var url=location.toString().split('?')[0];
			 var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+","g"));
			 
			 for(var i = 0; i < result.length; i++){
				 result[i] = result[i].substring(1);
			 }
			var req_str=location.toString().split('?')[1];
			alert(result);
			location=url+"?"+result;
			
        	*/
			var page_url = $("#page_url").val();
			if ('undefined' == page_url)
			{
				var url=location.toString().split('?')[0];
				var req_str=location.toString().split('?')[1];
				var params=$.query(req_str);
				params.size=$(this).val();
				var str = jQuery.param(params); 
				location=url+"?"+str;
			}
			else
			{
				location = page_url+"&size="+$(this).val();
			}
			
        });
    });
}


/**
 * Handle cols / form / inputs
 */

/*
function gopage_form(){

 	var url=location.toString().split('?')[0];
	var req_str=location.toString().split('?')[1];
	var params=$.query(req_str);

	params.page=$('#gopage').val();
	var str = jQuery.param(params); 
alert(str);
	location=url+"?"+str;
	
	
}
*/
function gopage_form(){
	
	var page_url = $("#page_url").val();
			if ('undefined' == page_url)
			{
				var url=location.toString().split('?')[0];
					var req_str=location.toString().split('?')[1];
					var params=$.query(req_str);
				
					params.page=$('#gopage').val();
					var str = jQuery.param(params); 
				
				location=url+"?"+str;
			}
			else
			{
				location = page_url+"&page="+$('#gopage').val()+"&size="+$('#pagesize').val();
			}

 }
	 
	 
function initForms(obj)
{
    if (!obj) {
        obj = $(document);
    }
    /*
    // form containers and columns
    var tmp = obj.find(".cols > tbody > tr");
    tmp.find("> td:first-child").addClass('first');
    tmp.find("> td:last-child").addClass('last');
    
    // form cells
    obj.find('table.form > tbody > tr').each(function () {
        var label = 'label';
        var value = 'value';
        if (!$(this).parents('table.form').find('> col').length) {
            if ($(this).find('> td').length == 1) {
                label = 'single';
            } else if ($(this).find('> td').length == 2) {
                label += ' label2';
                value += ' value2';
            } else if ($(this).find('> td').length == 4) {
                label += ' label4';
                value += ' value4';
            }
        }
        $(this).find('> td:even:not(.label):not(.value):not(.buttons)').addClass(label);
        $(this).find('> td:odd:not(.label):not(.value):not(.buttons)').addClass(value);
    });

    */
    
    // default inputs actions
    /*
    obj.find('input,textarea,select,button').each(function () {
       var className = this.tagName == 'INPUT' ? 'in-'+this.type : 'in-'+this.tagName.toLowerCase();
        $(this).addClass('input');
        $(this).addClass(className);
    }).blur(function () {
        $(this).removeClass('focus');
    }).hover(function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    });
    // no hovers for list-form
    obj.find('table.list-form input,table.list-form textarea,table.list-form select').unbind('mouseover').unbind('mouseout');
    
    // checkbox - multiselect hovers
    obj.find('div.cb_select').hover(function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    }).addClass('input');
    
    // fix of input width for IE
    if (/msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent)) {
        obj.find('.value .in-text,.value .in-password,.value .in-textarea').each(function () {
            $(this).closest('td').css('padding-right', '8px').addClass('ie-field-padding');
        });
        $('.list-form .ie-field-padding .input').each(function () {
            $(this).closest('.ie-field-padding').css('padding-right', '2px').removeClass('ie-field-padding');
            $(this).width($(this).width()-8);
        });
    }
    */
}


/**
 * Handle default values in the inputs
 */
function initInputDefault(obj)
{
    if (!obj) {
        obj = $(document);
    }
    
    obj.find("input.default-value[title]").focus(function() {
        if ($(this).val() == $(this)[0].title) {
            $(this).val("");
            $(this).removeClass("defaultText");
        }
    }).blur(function() {
        if ($(this).val() == "") {
            $(this).val($(this)[0].title);
            $(this).addClass("defaultText");
        }
    }).blur();
}

/**
 * Set focus to the inputs with .get-focus class
 */
function initFocuses() 
{
	$('input.get-focus').focus();
}




/*
 * 显示透明的层  覆盖页面 使得页面不能点击
 * css文件中需要有个 "#cover" 的样式
 * #cover{
	background: #fff;
	position: absolute;
	left: 0px;
	top: 0px;
	filter:alpha(opacity=50); // IE //
	-moz-opacity:0.5; // Moz + FF 
	opacity: 0.5;
}
 * 需要使用的页面需要加一个div 如：
 * <div id="cover"></div> 即可
 * id:
 */


function cover_bb(id,url){
	$.ajax({
		url:url,
		type:'GET',
		dataType:'json',
		success:function(array){
	if(array.length==1){
		
		$('#eve_content').html(array[0][0].content);
	}

	},
	 error:function(){
		
		var htmlStr = "Sorry!System has error!Please restart the system,and login to view!";
		
		$('#eve_content').html(htmlStr);
	}
	});
	
	
	
	var cover = document.getElementById("cover_bb");
	with(cover){
		style.zIndex = 77;
		style.width = parent.document.body.offsetWidth+"px";
		style.height = parent.document.body.scrollHeight+"px";
		style.display = "";
	}
	var showDiv = document.getElementById(id);
	showDiv.style.display = "";
	showDiv.style.zIndex = 99;
	return showDiv;
} 

/*
 * 关闭透明的层
 */
function closeCover_bb(id){
	var showDiv = document.getElementById(id);
	showDiv.style.display ="none";
	var cover = document.getElementById("cover_bb");
	cover.style.display = "none";
} 