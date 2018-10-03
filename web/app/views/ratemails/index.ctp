<div id="title">
    <h1><?php echo __('Configuration',true);?>&gt;&gt;<?php echo __('Rate Amendment Template',true);?> </h1>
    <ul id="title-menu">
    <li>
        <a href="<?php echo $this->webroot ?>ratemails/add" id="add" class="link_btn">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/add.png"><?php echo __('createnew',true);?>
        </a>
        </li>
    
    </ul>
</div>

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

<div class="container">
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