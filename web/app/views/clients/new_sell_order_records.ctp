<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('New Sell Order Records')?></h1>
  <ul id="title-menu">
		<li>
			<?php echo $this->element("xback",Array('backUrl'=>'clients/new_sell_order'))?>
    	</li>
 	</ul>
</div>

<div id="container">
   
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list">
        <thead>
           
            <tr>
                <td><?php __('Time')?></td>
                <td><?php __('Country')?></td>
                <td><?php __('Code Name')?></td>
                <td><?php __('Code')?></td>
                <td><?php __('Rate')?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo date('Y-m-d H:i:sO',$item[0]['time']);?></td>
                <td><?php echo $item[0]['country']?></td>
                <td><?php echo $item[0]['code_name']?></td>
                <td><?php echo $item[0]['code']?></td>
                <td><?php echo $item[0]['rate']?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>





