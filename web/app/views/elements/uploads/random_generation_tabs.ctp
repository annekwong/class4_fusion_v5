<ul class="tabs">
                <li>
                    <a class="glyphicons justify" href="<?php echo $this->webroot; ?>random_ani/random_generation/<?php echo base64_encode($id); ?>">
                        <i></i>
                        <?php __('ANI Number') ?>
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>uploads/random_generation/<?php echo base64_encode($id); ?>">
                        <i></i>
                        <?php __('Import') ?> 
                    </a>
                </li>
                <li>
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>random_ani/auto_populate/<?php echo base64_encode($id); ?>">
                        <i></i>
                        <?php __('Auto Populate') ?>  
                    </a>
                </li>
                <li>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>random_ani/auto_populate_log/<?php echo base64_encode($id); ?>">
                        <i></i>
                        <?php __('Auto Populate Log') ?>  
                    </a>
                </li>
            </ul>