<div class=" jsp_resourceNew_style_2" style="padding:5px;"> 
                                        <table class="list table dynamicTable tableTools table-bordered  table-white">
                                            <tr>
                                                <td><?=__('Ingress Name')?></td>
                                            </tr>
                                        <?php
                                        foreach ($results as $item){
                                            ?>
                                                <tr>
                                                    <td><?php echo $item['alias']; ?></td>
                                                </tr>
                                        <?php
                                        }
                                        ?>
                                        </table>
                                    </div>