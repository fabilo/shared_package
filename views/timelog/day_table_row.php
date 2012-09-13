<tr id="<?php echo $day->getDate() ?>" class="day-total collapsed">
	<td class="date"><?php echo $day->getDate() ?></td>
	<td class="start-time time"><?php echo $day->getStartTimeNice() ?></td>
	<td class="end-time time"><?php echo $day->getEndTimeNice() ?></td>
	<td class="hours align-right"><?php echo $day->getHours() ?></td>
	<td class="project"></td>
	<td class="category"></td>
	<td class="notes"></td>
</tr>