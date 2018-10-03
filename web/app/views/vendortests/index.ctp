<style type="text/css">
#codenametable{
    border:none;
    background-color: transparent;
    font-size: 1em;
    color:#000;
	height:23px;
}    
#codenametable tr{
    border:none;
    border-bottom:1px solid #ccc;
    background-color: transparent;
}  
#codenametable tr:last-child {
    border:none;
}
#codenametable td{
    border:none;
    background-color: transparent;
} 
</style>
<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
    <ul id="title-menu">
	
	    <li>
	    	<a class="link_btn" href="<?php echo $this->webroot ?>vendortests/addproject">
	    		<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('Add Project',true);?>
	    	</a>
	    </li>
	   
	</ul>
</div>
<?php
    $data =$p->getDataArray();
?>
<div id="container">
    <?php if(empty($data)): ?>
    <div class="msg">
        <?php __('No Data is Available')?>
    </div>
    <?php endif; ?>
    <div id="dis_temp"  style="<?php echo empty($data) ? 'display:none' : '' ?>">
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>

                <td><?php echo __('Project Name',true);?></td> 
                <td><?php echo __('Trunk',true);?></td>
                <td><?php echo __('code_name',true);?></td>
                <!--
                <td><?php echo __('DEST Number',true);?></td>
                <td><?php echo __('SRC Numbers',true);?></td>
                -->
                <td><?php __('Start Time'); ?></td>
                <td><?php __('End Time'); ?></td>
                <td><?php echo __('status',true);?></td>
                <td><?php echo __('action',true);?></td>

            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['project_name']; ?></td>
                <td><?php echo $item[0]['alias']; ?></td>
                <td>
                   <?php echo $item[0]['code_name']; ?>
                </td>
                <td><?php echo $item[0]['start_epoch']; ?></td>
                <td><?php echo $item[0]['end_epoch']; ?></td>
                <td>
                    <?php
                        if($item[0]['status'] == 0)
                            echo "<img src='{$this->webroot}images/check_waiting.gif' />";
                        else
                            echo "<img src='{$this->webroot}images/check_ok.png' />";
                    ?>
                </td>
                <td>
                    <a title="Delete" href=<?php echo $this->webroot ?>vendortests/delete/<?php echo $item[0]['id'] ?> title="Delete">
                        <img src="<?php echo $this->webroot ?>images/delete.png" title="Delete"/>
                    </a>
                    <a href="<?php echo $this->webroot ?>vendortests/summary/<?php echo $item[0]['id'] ?>" title="Check Result">
                        <img src="<?php echo $this->webroot ?>images/result.png" title="Check Result" />
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    </div>
</div>

<script type="text/javascript">
function getdata() {
    $.ajax({
        'url'      : '<?php echo $this->webroot.'vendortests/fresh_data/'.$pageSize.'/'.$offset; ?>',
        'type'     : 'GET',
        'dataType' : 'json',
        'success'  : function(data) {
            var $table = $('table.list tbody');
            $table.empty();
            $.each(data, function(index, item) {
                var $tr = $('<tr>');
                var img;
                if(item[0]['status'] == 0) {
                    img = "<img src='<?php echo $this->webroot; ?>images/check_waiting.gif' />";
                } else {
                    img = "<img src='<?php echo $this->webroot; ?>images/check_ok.png' />";;
                }

                var project_id = item[0]['id'];
                var start_epoch = item[0]['start_epoch'] == null ? '-' : item[0]['start_epoch'];
                var end_epoch = item[0]['end_epoch'] == null ? '-' : item[0]['end_epoch'];
                var trunk_alias = item[0]['alias'] == null ? '-' : item[0]['alias'];
                var project_name = item[0]['project_name'] == null ? '-' : item[0]['project_name'];
                var code_name = item[0]['code_name'] == null ? '-' : item[0]['code_name'];
                $tr.append('<td>' + project_name + '</td>');
                $tr.append('<td>' + trunk_alias + '</td>');
                $tr.append('<td>' + code_name + '</td>');
                $tr.append('<td>' + start_epoch + '</td>');
                $tr.append('<td>' + end_epoch + '</td>');
                $tr.append('<td>' + img + '</td>');
                $tr.append('<td>' + '<a title="Delete" href=<?php echo $this->webroot ?>vendortests/delete/' + project_id + ' title="Delete"><img src="<?php echo $this->webroot ?>images/delete.png" title="Delete"/></a><a href="<?php echo $this->webroot ?>vendortests/summary/'+project_id+'" title="Check Result"><img src="<?php echo $this->webroot ?>images/result.png" title="Check Result" /></a>' + '</td>');
                $table.append($tr);
            })
        }
    });
}
$(function() {
   var interval_time = 3000;
   window.setInterval("getdata()", interval_time);
});
</script>