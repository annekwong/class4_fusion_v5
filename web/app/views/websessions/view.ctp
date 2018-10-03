<style>
    input[type="text"]{margin-bottom: 0;}

    .empty-table thead tr th {
        display: none !important;
    }

    .empty-table tbody tr td, .empty-table tbody tr, .empty-table tbody, .empty-table {
        border: 0px !important;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>websessions/view"><?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>websessions/view">
      <?php echo __('User Sign in History') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('User Sign-On History') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>

  <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
    
    <!--<col width="8%">
-->
    <thead>
      <tr>
        <th><?php echo $appCommon->show_order('create_time', __('Login At',true))?></th>
        <th>&nbsp;<?php echo __('User',true);?></th>
        <th><?php echo __('host',true);?></th>
        <th><?php echo __('Agent',true);?></th>
        <th><?php echo __('Result',true);?></th>
      </tr>
    </thead>
    <tbody>
      <?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
      <tr class="row-1">
        <td align="center"><?php echo $mydata[$i][0]['create_time']?></td>
        <td align="center"><?php echo $mydata[$i][0]['user_name']?></td>
        <td align="center"><?php echo $mydata[$i][0]['host']?></td>
        <td align="center"><?php echo $mydata[$i][0]['agent']?></td>
        <td align="center"><?php echo $mydata[$i][0]['msg'] == '' ? 'Success' : $mydata[$i][0]['msg']?></td>
      </tr>
      <?php }?>
    </tbody>
    <tbody>
    </tbody>
  </table>
  <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
  <br />
  <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
  <div id="search">
  <form name="myform" method="get"> 
  <input type="hidden" name="issearch"value="TRUE" />
  <table class="form footable table table-striped table-bordered  table-white table-primary empty-table">
      <thead>
  <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
      </thead>
  	<tbody>
    <tr>
     <td><?php echo __('Login At',true);?>:</td>
      <td><?php echo __('From',true);?><input type="text" name="start" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" />
        <?php __('To')?><input type="text" name="end" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})"  style="width:120px;" /></td>
        
        <td><?php echo __('User',true);?>:</td>
      <td><input type="text" name="user" /></td>
        
        <td><?php echo __('host',true);?>:</td>
      <td><input type="text" name="host" /></td>
        <td><input type="submit" value="<?php echo __('Search',true);?>" class="input in-submit btn btn-primary" /></td>
    </tr>
    </tbody>
  </table>
  </form>
  </div>


</div>
    </div>
</div>
<?php
			if (!empty($searchform)) {
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($searchform);
			 foreach($d as $k) { ?>
<script>if (document.getElementById("<?php echo $k?>"))document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }?>
<script>document.getElementById("advsearch").style.display="block";</script>
<?php }?>
