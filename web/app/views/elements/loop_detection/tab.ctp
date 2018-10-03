<ul class="tabs">
    <li <?php if ($current_page == 0) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>loop_detection" class="glyphicons dumbbell">
            <i></i>
            <?php __('Define Looping Rule')?>
        </a>
    </li>
  <li  <?php if ($current_page == 1) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>loop_detection/loop_found"  class="glyphicons list">
            <i></i>
            <?php __('Loop Found')?>
        </a>
    </li>
</ul>