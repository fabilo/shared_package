<table id="TimelogCategoriesList" class="table list">
	<thead>
	<tr class="headings">
		<td class="name">Name</th>
		<td class="department">Department</th>
		<td class="clairtyReference">Clarity Ref</th>
		<td class="edit"></td>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($categories AS $category): ?>
		<tr id="category-<?php echo $category->getId() ?>" class="clickable">
			<td><?php echo $category->getName() ?></td>
			<td><?php echo $category->getDepartment() ?></td>
			<td><?php echo $category->getClarityReference() ?></td>
			<td class="edit">
				<a href="<?php echo $top_uri ?>/edit/<?php echo $category->getId() ?>">Edit</a>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<script>
$(document).ready(function(){	
	// add click on edit binding to table row	
	$('.list tbody tr').each(function(){
		// add click binding
		$(this).bind('click', function(){
			document.location = '<?php echo $top_uri ?>/edit/'+$(this).attr('id').substring(9);
		});
	});
	
	// remove edit tds
	$('.list .edit').remove();
});
</script>