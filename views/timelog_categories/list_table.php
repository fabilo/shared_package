<table id="TimelogCategoriesList" class="table list">
	<thead>
	<tr class="headings">
		<th class="name">Name</th>
		<th class="department">Department</th>
		<th class="clairtyReference">Clarity Ref</th>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($categories AS $category): ?>
		<tr>
			<td><?php echo $category->getName() ?></td>
			<td><?php echo $category->getDepartment() ?></td>
			<td><?php echo $category->getClarityReference() ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>