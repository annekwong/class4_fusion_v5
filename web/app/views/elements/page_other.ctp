<div class="xpage">
  <ul class="list-meta" id="page" style="display:block;">
    <li class="list-meta-plist numbers" id="allpages">
      <?php 
  	$last=(int)$p->getTotalPages();
   $web_root=$this->webroot;
   $base_href = $appCommon->base_href();
		$request_string = $appCommon->_request_string(array('page','size'));
		$req_str=!empty($request_string)?"&{$request_string}":'';
   $size=(int)$p->getPageSize();
   $curr=(int)$p->getCurrPage();#当前页
   $prev=$curr-1;
   $next=($curr+1)>$last?$last:($curr+1);
		
   $first_page= <<<EOD
   <ul class="pagination">
  <li><a id="first_page" class="page-first" href="{$web_root}{$base_href}?page=1&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_first_active.jpg"/></a></li>
EOD;
   $prev_page= <<<EOD
   		<li><a id="prev_page" class="page-prev"href="{$web_root}{$base_href}?page={$prev}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_prev_active.jpg"/></a></li>
EOD;
   $next_page= <<<EOD
   	<li> <a id="next_page" class="page-next" href="{$web_root}{$base_href}?page={$next}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_next_active.jpg"/></a></li>
EOD;
   	
   $last_page= <<<EOD
   		<li> 	<a id="last_page" class="page-last"	href="{$web_root}{$base_href}?page={$last}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_last_active.jpg"/></a></li>
EOD;
   
      echo $first_page;
      echo $prev_page;
   	
   	if ($p->getTotalPages() >= 10){?>
      <?php

				$page_code=$curr;
				$page_code_t=0;
			if($curr>$last-10){
			$page_code_t=$last-10;
			
	
}
				
   for ($i = 0;$i<10;$i++) {
   
						if($curr<=$last-10){
	   			 $page_code=$curr+$i;
	
	
}else{
	
	$page_code=$i+1+$page_code_t;
	
}

if($page_code>$last){
	break;
	
}
						$k=$page_code; 
   				$style='class="page"';
   				if($k==$curr){	$style='class="page active"'."style='color:red'";}
   				$page_href= <<<EOD
				<li>	<a {$style}		id="p{$page_code}"		href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}">{$page_code}   </a>	</li>
				
EOD;
echo $page_href;
   }?>
      <?php } else

   	
   	
   	{
   		?>
      <?php
   			for ($i = 0;$i<(int)$p->getTotalPages();$i++) {
   				$k=$i+1; 
   					$style='class="page"';
   				if($k==$curr){
   					$style='class="page active"'."style='color:red'";
   				}
   				$page_href= <<<EOD
				<li>	<a   {$style}   id = "p{$k}"			href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}" >{$k}</a></li>
EOD;

   				echo $page_href;
   				?>
      <?php }
   	
   	}
   	
   	echo $next_page;
      echo $last_page;
   	?>
  </ul>
  </li>
  <li class="list-meta-ipp">Rows: <span><?php echo (int)$p->getPageSize()?></span></li>
  <li class="list-meta-ippa">
    <form   action="" method="get">
      Rows
      <select name="size" class="input in-select">
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="35">35</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </form>
  </li>
  <li class="list-meta-pnum pages"><?php __('Pages')?>: <span><?php echo (int)($p->getTotalPages());?></span></li>
  <li class="list-meta-pgo inputPages">
    <form id="gopageform" name="gopageform"  method="get" onsubmit="gopage_form();return false;">
      <span style="float:left; height:18px; line-height:18px; display:block;"><?php __('Go2')?>:</span>
      <input type="text" style="height:13px float:left;" id="gopage" value="<?php echo (int)$p->getCurrPage()?>" name="page" class="input in-text in-input">
      <input type="hidden" name="size"/>
    </form>
  </li>

    
  </ul>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('select[name=size] option[value=<?php echo (int)$p->getPageSize()?>]').attr('selected','selected');
});

function initList(obj) {
    if (!obj) {
        obj = $(document);
    }
    
    // find all table-lists找到class=list的tale
    var list = obj.find('table.list');
    
    // color rows
    list.find('> * > tr > td:last-child').addClass('last');
    list.find('> tbody > tr:even').addClass('row-1');//给单数行设置
    list.find('> tbody > tr:odd').addClass('row-2');//给复数行设置样式row_2
    
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
        	var url=location.toString().split('&size')[0];
               /*
        	var req_str=location.toString().split('?')[1];
        	var params=$.query(req_str);
        	params.size=$(this).val();
        var str = jQuery.param(params); 
        */
       var str=$(this).val();
        	location=url+"&size="+str;
                
        });
    });
}
</script>
