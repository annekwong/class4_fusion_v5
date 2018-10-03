<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_back" href="<?php echo $this->webroot ?>vendortests/summary/<?php echo $_GET['resultid']; ?>" >
                <img width="16" height="16" src="/Class4/images/icon_back_white.png" alt="<?php echo __('goback',true);?>">
                &nbsp;<?php echo __('goback',true);?> 
            </a>
        </li>
    </ul>
</div>



<div class="container">
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Initiated',true);?></td>
                <td><?php echo __('SRC Number',true);?></td>
                <td><?php echo __('DEST Number',true);?></td>
                <td><?php echo __('pdd',true);?></td>
                <td><?php echo __('Ring Time',true);?></td>
                <td><?php echo __('DUR',true);?></td>
                <td><?php echo __('Diconnect Code/Reason',true);?></td>
                <td><?php echo __('Media Packets',true);?></td>
                <td><?php echo __('SIP Capture',true);?></td>
                <td><?php echo __('RBT',true);?></td>
                <td><?php echo __('Audio',true);?></td>
            </tr>
        </thead>
         <tbody>
            <?php foreach($data as $item) {?>
            <tr>
                <td><?php echo intval($item[0]['start_epoch']) == 0 ? 0 : date('Y-m-d H:i:s',intval(substr($item[0]['start_epoch'], 0, 10))); ?></td>
                <td><?php echo $item[0]['ani']; ?></td>
                <td><?php echo $item[0]['dnis']; ?></td>
                <td><?php echo $item[0]['pdd']; ?></td>
                <td><?php echo intval($item[0]['ring_epoch']) == 0 ? 0 : intval(substr($item[0]['answer_epoch']), 0, 10) - intval(substr($item[0]['ring_epoch'], 0, 10)); ?></td>
                <td><?php echo $item[0]['duration']; ?></td>
                <td><?php echo $item[0]['release_cause']; ?></td>
                <td>1/0</td>
                <td><a target="_blank" href="<?php echo $this->webroot . 'vendortests/viewcap/'. base64_encode($item[0]['sip_capture_path']); ?>">View</a></td>
                <?php
                    $audio_path = Configure::read('vendortest.audiopath');
                    $pcap_path = Configure::read('vendortest.sip_capture');
                    $real_pcap_path = $pcap_path . $item[0]['time'];
                    /*
                    
                    $files = $searchfile->search($real_pcap_path, $item[0]['pcap_path']);
                    $show = array();
                    foreach($files as $file) {
                        array_push($show,'<a target="_blank" href="'.$this->webroot.'vendortests/viewcap/'.base64_encode($real_pcap_path.'/'.$file).'">View</a>');
                    }
                    
                    echo '<td>'.implode('&nbsp;&nbsp', $show).'</td>';
                    */
                    echo '<td></td>';
                    
                    $real_audio_path = $audio_path . $item[0]['time'];
                    $files = $searchfile->search($real_audio_path, $item[0]['media_path']);
                    $show = array();
                    foreach($files as $file) {
                        //if(trim(strstr($file,".") == '.wav'))
                            array_push($show,'<a href="'.$this->webroot.'vendortests/downaudio/'.base64_encode($real_audio_path.'/'.$file).'">Down</a>');
                    }
                    echo '<td>'.implode('&nbsp;&nbsp', $show).'</td>';
                ?>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>