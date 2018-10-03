<STYLE type="text/css">
 .tableTd {
     border-width: 0.5pt;
  border: solid;
 }
 .tableTdContent{
  border-width: 0.5pt;
  border: solid;
 }
 #titles{
  font-weight: bolder;
 }

</STYLE>
<table>
<thead>
<tr>
  <?php foreach($fields as $field): ?>
   <th><?php echo $field; ?></th>
  <?php endforeach;?>
</tr>
</thead>
<tbody>
  <?php if(!empty($rates)):
    foreach($rates[0] as $rate): ?>
      <tr>
          <?php foreach($fields as $field): ?>
           <td><?php echo $rate[$field]; ?></td>
          <?php endforeach;?>
      </tr>
    <?php endforeach;?>
  <?php endif;?>
</tbody>
</table>
<?php exit();?>