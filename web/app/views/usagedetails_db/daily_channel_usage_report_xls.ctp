  <?php if(empty($data)): ?>
  <?php else: ?>
  <table class="list">
      <thead>
          <tr>
              <td><?php __('Report Time'); ?></td>
              <td><?php __('Ingress Channels(Max)') ?></td>
          </tr>
      </thead>
      <tbody>
          <?php foreach($data as $item): ?>
          <tr>
              <td><?php echo $item[0]['report_time']; ?></td>
              <td><?php echo $item[0]['max']; ?></td>
          </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
    
  <?php endif; ?>
