
<?php if (isset($error)): ?>
<p class="error"><?php echo $error ?></p>
<?php endif ?>

<form method="post" action="<?php echo $top_uri ?>/save">
	
	<input type='hidden' name='category[id]' value='<?php echo $category->getId() ?>'/>
	
	<div class="formRow">
		<label for="NameInput">Name:</label>
		<input id="NameInput" type="text" name="category[name]" maxlength="64" placeholder="Category Name" pattern="^(\w|\s){3}(\w|\s)*$" value="<?php echo $category->getName() ?>"/>			
	</div>
	
	<div class="formRow">
		<label for="DepartmentSelect">Department:</label>
		<select id="DepartmentSelect" name="category[department_id]" class="margin block">
			<option value="">- Select Department -</option>
			<?php foreach ($departments AS $department): ?>
			<option value="<?php echo $department->getId() ?>" <?php echo ($department->getId() == $category->getDepartmentId()) ? 'SELECTED' : '' ?>><?php echo $department->getName() ?></option>
			<?php endforeach ?>
		</select>
		<div class="hint">What department this category will be available to</div>
	</div>
	
	<div class="formRow">
		<label for="ClarityReferenceInput">Report Override:</label>
		<input id="ClarityReferenceInput" type="text" name="category[clarity_reference]" maxlength="64" placeholder="" value="<?php echo $category->getClarityReference() ?>"/>			
		<div class="hint">
			Naming override to display in reports instead of "Name" property. Projects  & Categories with the same value for the override will be grouped together in reports.
		</div>
	</div>

	<div class="formRow formRowActions">	
			<button class="button" id="Cancel" onclick="document.location='<?php echo $top_uri ?>'; return false;">Cancel</button>
			<input class="button" type="Submit" name="save_and_done" value="Save">
	</div>

</form>