
<?php if (isset($error)): ?>
<p class="error"><?php echo $error ?></p>
<?php endif ?>

<form method="post" action="<?php echo $top_uri ?>/save" style="width: 300px">
	
	<input type='hidden' name='category[id]' value='<?php echo $category->getId() ?>'/>
	
	<div class="inlineLabel margin">
		<label for="NameInput">Name:</label>
		<input id="NameInput" type="text" name="category[name]" maxlength="64" placeholder="Category Name" pattern="^(\w|\s){3}(\w|\s)*$" value="<?php echo $category->getName() ?>"/>			
	</div>
	
	<select name="category[department_id]" class="margin block">
		<option value="">- Select Department -</option>
		<?php foreach ($departments AS $department): ?>
		<option value="<?php echo $department->getId() ?>" <?php echo ($department->getId() == $category->getDepartmentId()) ? 'SELECTED' : '' ?>><?php echo $department->getName() ?></option>
		<?php endforeach ?>
	</select>
	
	<div class="inlineLabel margin">
		<label for="ClarityReferenceInput">Clarity Ref:</label>
		<input id="ClarityReferenceInput" type="text" name="category[clarity_reference]" maxlength="64" placeholder="" value="<?php echo $category->getClarityReference() ?>"/>			
	</div>

	<div>	
			<input type="Submit" name="save_and_done" value="Save"> 
			<button id="Cancel" onclick="document.location='<?php echo $top_uri ?>'; return false;">Cancel</button>
	</div>

</form>