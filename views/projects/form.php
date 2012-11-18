
<?php if (isset($error)): ?>
<p class="error"><?php echo $error ?></p>
<?php endif ?>

<form method="post" action="<?php echo $top_uri ?>/save">
	
	<input type='hidden' name='project[id]' value='<?php echo $project->getId() ?>'/>
	
	<div class="formRow">
		<label for="NameInput">Name:</label>
		<input id="NameInput" type="text" name="project[name]" maxlength="64" placeholder="Project Name" pattern="^(\w|\s){3}(\w|\s)*$" value="<?php echo $project->getName() ?>"/>			
		<div class="hint">
			The name of this project
		</div>
	</div>
	
	<div class="formRow">
		<label for="DepartmentInput">Department:</label>
		<select id="DepartmentInput" name="project[department_id]" class="block">
			<option value="">- Select Department -</option>
			<?php foreach ($departments AS $department): ?>
			<option value="<?php echo $department->getId() ?>" <?php echo ($department->getId() == $project->getDepartmentId()) ? 'SELECTED' : '' ?>><?php echo $department->getName() ?></option>
			<?php endforeach ?>
		</select>
		<div class="hint">
			Department the project is visible to
		</div>
	</div>
	
	<div class="formRow">
		<label for="TeamSelect">Team:</label>
		<select name="project[team_id]" class="block">
			<option value="">All Teams</option>
			<?php foreach ($teams AS $team): ?>
			<option value="<?php echo $team->getId() ?>" <?php echo ($team->getId() == $project->getTeamId()) ? 'SELECTED' : '' ?>><?php echo $team->getName() ?></option>
			<?php endforeach ?>
		</select>
			<div class="hint">
				Team the project is visible to
			</div>
	</div>
	
	<div class="formRow">
		<label for="ClarityReferenceInput">Report Override:</label>
		<input id="ClarityReferenceInput" type="text" name="project[clarity_reference]" maxlength="64" placeholder="" value="<?php echo $project->getClarityReference() ?>"/>			
		<div class="hint">
			Naming override to display in reports instead of "Name" property. Projects  & Categories with the same value for the override will be grouped together in reports.
		</div>
	</div>
	
	<div class="formRow checkboxRow">
		<input id="ArchivedCheckbox" type="checkbox" name="project[archived]" value="1" <?php echo ($project->archived) ? 'CHECKED' : '' ?>/>
		<label for="ArchivedCheckbox">Archived?</label>
		<div class="hint">
			Archiving the project will stop it being displayed in the timelog form select list
		</div>
	</div>
	
	<div class="formRow">
		<label for="DescriptionTextarea">Description:</label>
		<textarea id="DescriptionTextarea" name="project[description]" placeholder="Description" class="margin block"><?php echo $project->getDescription() ?></textarea>
	</div>
		
	<div class="formRow formRowActions">	
			<button class="button" id="Cancel" onclick="document.location='<?php echo $top_uri ?>'; return false;">Cancel</button>
			<input class="button" type="Submit" name="save_and_done" value="Save">
	</div>

</form>