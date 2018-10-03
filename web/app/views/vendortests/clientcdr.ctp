<style type="text/css">
#client_number {
    overflow:hidden;
}
#client_number li {
    float:left;
    width: 185px;
    text-align:center;
    border:1px solid #CCC;
    margin:2px;
}
#client_number li a {
    display:block;   
    overflow:hidden;
    padding:5px;
}

#client_number li a:hover {
    border:1px solid green;
}
</style>
<?php echo $this->element("selectheader")?>

<div id="title"><h1><?php echo __('cdr',true);?></h1></div>

<div class="container">
    <div id="toppage"></div>
    <?php
        if(empty($clientcdrs)) :
            echo '<div class="msg">'.__('no_data_found',true).'</div>';            
        else:
    ?>
    <ul id="client_number">
        <?php foreach($clientcdrs as $clientcdr): ?>
        <li><a href="###" title="<?php echo $clientcdr['origination_destination_number']; ?>"><?php echo $clientcdr['origination_destination_number']; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php
        endif;
    ?>
</div>

<script type="text/javascript">
$(function() {
    $('#client_number a').click(function() {
        window.opener.fillinput($(this).text());
        window.opener=null;      
        window.open('','_self');      
        window.close();
    });
});
</script>

<?php echo $this->element("selectfooter")?>
