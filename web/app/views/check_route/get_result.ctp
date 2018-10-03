<div class=" jsp_resourceNew_style_2" style="padding:5px;"> 
                                        <table class="list table dynamicTable tableTools table-bordered  table-white">
                                            <tr>
                                                <td><?=__('start_time')?></td>
                                                <td><?=__('Completed Time')?></td>
                                                <td><?=__('egress')?></td>
                                                <td><?=__('ani')?></td>
                                                <td><?=__('dnis')?></td>
                                                <td><?=__('cost')?></td>
                                                <td><?=__('origination_source_host_name')?></td>
                                                <td><?=__('termination_destination_host_name')?></td>
                                                <td><?=__('ring_time')?></td>
                                                <td><?=__('pdd')?></td>
                                                <td><?=__('release_cause')?></td>
                                            </tr>
                                        <?php
                                        $show_release_cause_arr = $app->show_release_cause();
                                        foreach ($results as $item){
                                            ?>
                                                <tr>
                                                    <td><?= gmt_to_local_time($item['start_time'], $time_tz)." ".$time_tz; ?></td>
                                                    <td><?= gmt_to_local_time($item['end_time'], $time_tz)." ".$time_tz; ?></td>
                                                    <td><?=$item['egress_name']?></td>
                                                    <td><?=$item['source_number']?></td>
                                                    <td><?=$item['destination_number']?></td>
                                                    <td><?=round($item['cost'],5)?></td>
                                                    <td><?=$item['origination_source_host_name']?></td>
                                                    <td><?=$item['termination_destination_host_name']?></td>
                                                    <td><?=$item['ring_time']?></td>
                                                    <td><?=$item['pdd']?></td>
                                                    <td><?=$show_release_cause_arr[$item['release_cause']]?></td>
                                                </tr>
                                        <?php
                                        }
                                        ?>
                                        </table>
                                    </div>