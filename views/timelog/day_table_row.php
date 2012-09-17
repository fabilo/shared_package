<tr id="<?php echo $day->date ?>" class="day-total <?php echo ($expand_timelogs) ? 'expanded': 'collapsed' ?>" rel="week<?php echo $day->year_week ?>">
	<td class="date align-right"><?php echo $day->getDateNice() ?></td>
	<td class="start-time time"><?php echo $day->getStartTimeNice() ?></td>
	<td class="end-time time"><?php echo $day->getEndTimeNice() ?></td>
	<td class="hours align-right"><?php echo $day->getHours() ?></td>
	<td class="project"></td>
	<td class="category"></td>
	<td class="notes"></td>
</tr>