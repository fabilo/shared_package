<?php if(isset($startDate) && isset($endDate)): ?>
<input type="hidden" id="startDate" value="<?php echo $startDate ?>"/>
<input type="hidden" id="endDate" value="<?php echo $endDate ?>"/>
<?php endif ?>
<table id="TimelogList" class="table list">
	<thead>
	<tr class="headings">
		<th class="date">Date</th>
		<th class="start-time time">Start</th>
		<th class="end-time time">End</th>
		<th class="hours">Hours</th>
		<th class="project">Project</th>
		<th class="category">Category</th>
		<th class="notes">Notes</th>
	</tr>
	</thead>
	<tbody>
		<?php echo $html ?>
	</tbody>
</table>