<?php if ($xpaginator->xpageCount() > 1):?>
      <ul class="pagination">
        <?php echo htmlspecialchars_decode($xpaginator->first("&laquo;&laquo;"));?>
        <?php echo htmlspecialchars_decode($xpaginator->prev("&laquo;"));?>
        <?php echo htmlspecialchars_decode($xpaginator->numbers(Array(),true));?>
        <?php echo htmlspecialchars_decode($xpaginator->next("&raquo;"));?>
        <?php echo htmlspecialchars_decode($xpaginator->last("&raquo;&raquo;"));?>
      </ul>
<?php endif; ?>
  