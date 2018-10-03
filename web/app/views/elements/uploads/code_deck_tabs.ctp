<ul>
    <li><a href="<?php echo $this->webroot ?>codedecks/codes_list/<?php echo base64_encode($id); ?>" class="glyphicons list"><i></i><?php __('Code Deck List'); ?></a></li>
                <li class="active"><a href="<?php echo $this->webroot ?>uploads/code_deck/<?php echo base64_encode($id) ?>" class="glyphicons upload"><i></i><?php __('Import'); ?></a></li>
                <li><a href="<?php echo $this->webroot ?>down/code_deck/<?php echo base64_encode($id) ?>" class="glyphicons download"><i></i><?php __('Export'); ?></a></li>
            </ul>