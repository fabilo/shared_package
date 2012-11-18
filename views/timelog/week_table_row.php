	<tr id="week<?php echo $year ?>-<?php echo $week ?>">
		<td class="date">Week <?php echo $week ?></td>
		<td><?php echo date('d/m', strtotime($start_date)) ?></td>
		<td><?php echo date('d/m', strtotime($end_date)) ?></td>
		<td class="hours align-right"><?php echo $week_hours ?></td>
		<td colspan="3"></td>
	</tr>