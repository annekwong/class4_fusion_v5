<?php if($report_type=='spam_report'){?>
<script	type="text/javascript">

var refresh_interval = 1800000;//时间间隔  3分钟
//修改刷新间隔
var change_time = function(obj){
	refresh_interval = obj.value * 1000*60;//刷新间隔
	change_spam();//通过IP获取数据刷新页面
}


var change_spam = (function(){
	var intervalHander = null;
	return function(){
		var param=$('#report_form').serialize();
			request_url='<?php echo  $this->webroot?>'+'cdrreports/spam_ajax_data?'+param;
		 if(intervalHander){
			 clearInterval(intervalHander);//清除定时器
		 }

		 //定时执行任务  获取历史数据
		 intervalHander =  AjaxInterval(refresh_interval,request_url,{},{success : function(data){
				$('#refresh_div').html(data);
				jGrowl_to_notyfy('Report Has been refreshed',{theme:'jmsg-success'});
			}});
	}	
})();
</script>
<?php }?>