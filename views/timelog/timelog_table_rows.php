	<?php foreach ($timelogs AS $t): ?>
	<tr class="timelog table-row timelog-<?php echo $t->getDate() ?>">
		<td class="date align-right">
			<a class="hidden showInSidebar" title="Show in sidebar form" href="<?php echo $top_uri.'/changeSidebarFormTimelog/'.$t->getId() ?>"><img alt="show in sidebar" src="<?php echo $base_uri ?>images/icons/layout_content.png"/></a>
			<a class="hidden editTimelog" title="Edit timelog" href="<?php echo $top_uri.'/edit/'.$t->getId() ?>"><img alt="edit" src="<?php echo $base_uri ?>images/icons/pencil.png"/></a>
		</td>
		<td class="start-time time"><?php echo $t->getStartTimeNice() ?></td>
		<td class="end-time time"><?php echo $t->getEndTimeNice() ?></td>
		<td class="hours align-right"><?php echo $t->getHours() ?></td>
		<td class="project"><?php echo $t->getProjectName() ?></td>
		<td class="category"><?php echo $t->getCategoryName() ?></td>
		<td class="notes"><?php echo $t->getNotesExtract() ?></td>
	</tr>
	<?php endforeach ?>