<style>
	.group_by_list td select{width: 180px;}
</style>
<?php if($report_group){ ?>
	<tr id="group_by">
		<td colspan="8" >
			<p class="separator text-center"><i class="icon-table icon-3x"></i></p>
		</td>

	</tr>
	<tr class="group_by_list">
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #1 </td>
		<td colspan="2"><?php
			$groupby=array(
				''=>'',
				'orig_client_name'=>'ORIG Carrier',
				'ingress_alias'=>'Ingress Trunk',
				'term_client_name'=>'TERM Carrier',
				'egress_alias'=>'Egress Trunk',

			);
			if (Configure::read('statistics.group_all')){
				$more_fields = array(
					'orig_code_name'=>'ORIG Code Name',
					'orig_country'=>'ORIG Country',
					'term_code_name'=>'TERM Code Name',
					'term_country'=>'TERM Country',
					'termination_source_host_name'=>'Switch IP '
				);
				$code_fields_arr = array();
				if (Configure::read('statistics.have_code_rate')){
					$code_fields_arr = array(
						'orig_code'=>'ORIG Code',
						'orig_rate'=>'ORIG Rate',
						'term_code'=>'TERM Code',
						'term_rate'=>'TERM Rate',
					);
				}

				$groupby = array_merge($groupby,$more_fields);
			}

			echo $form->input('group_by1',
				array('name'=>'group_by[0]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #2</td>
		<td colspan="2"><?php
			echo $form->input('group_by2',
				array('name'=>'group_by[1]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #3</td>
		<td ><?php
			echo $form->input('group_by3',array('name'=>'group_by[2]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
	</tr>
	<tr class="group_by_list">
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #4</td>
		<td colspan="2"><?php
			echo $form->input('group_by4',array('name'=>'group_by[3]','options'=>$groupby,'empty'=>'  '      ,   'label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #5</td>
		<td  colspan="2"><?php
			echo $form->input('group_by5',array('name'=>'group_by[4]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
		<td class="align_right padding-r10"><?php echo __('Group By',true);?> #6</td>
		<td ><?php
			echo $form->input('group_by6',array('name'=>'group_by[5]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
			?></td>
	</tr>

<?php  }  ?>