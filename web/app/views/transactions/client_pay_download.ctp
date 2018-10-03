<div id="title">
	<h1><?php echo __('Client Payment Download',true);?></h1>
	<form method="GET" action="">
	<ul id="title-menu">
		<li>
  	 		<a class="link_back" href="<?php echo $this->webroot?>transactions/client_pay_view"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"><?php echo __('goback',true);?></a>
  	  </li>
	</ul>
	</form>
</div>
<div id="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>	
<?php endif;?>
</table>
</div>
