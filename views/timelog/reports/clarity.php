<table class="report list">
	<thead>
		<tr>
			<?php foreach ($columns AS $col): ?><th><?php echo $col ?></th><?php endforeach ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows AS $row): ?>
		<tr>
			<?php foreach ($columns AS $col): ?>
			<td><?php echo (isset($row[$col])) ? $row[$col] : ' ' ?></td>
			<?php endforeach ?>
		</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr class="total">
			<?php foreach ($columns AS $col): ?>
			<td><?php echo (isset($totals[$col])) ? $totals[$col] : ' ' ?></td>
			<?php endforeach ?>
		</tr>
	</tfoot>
</table>