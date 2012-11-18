<?php if (!isset($sidebar_form)) $sidebar_form = false ?>
<?php if (!isset($ajax)) $ajax = false ?>
<form id="Timelog<?php echo $sidebar_form  ? 'Sidebar' : ($ajax ? 'Ajax' : '') ?>Form" class="timelogForm" method="post" action="<?php echo dirname($top_uri) ?>/timelogs/save">
	
	<?php if ($sidebar_form): ?>
	<div class="formRow">
		<?php if ($timelog->isNew()): ?>
		<input class="startStop button" type="submit" name="submit" value="Start" />
		<?php else: ?>
		<input class="startStop button" name="submit" type="Submit" value="Stop" class="clearfix"/>
		<?php endif ?>
	</div>
	<?php endif ?>
	
	<?php if ($sidebar_form): ?>
	<input type="hidden" name="sidebar_form" value="1"/>
	<?php endif ?>
	<?php if (!$timelog->isNew()): ?>
	<input id="TimelogIdInput" type='hidden' name='timelog[id]' value='<?php echo $timelog->getId() ?>'/>
	<?php endif ?>
	
	<div class="formRow">
		<label for="DateInput">Date:</label>
		<input id="DateInput" class="dateInput border" type="text" name="timelog[date]" maxlength="10" placeholder="2012-08-21" pattern="^20[0-9]{2}-(0[1-9]|1[0-2])-(([0-2][0-9])|30|31)$" value="<?php echo $timelog->getDate() ?>"/>			
		<div class="hint">The day this log is for</div>
	</div>
	
		<div class="formRow" id="StartTime">
			<label for="StartInput">Start:</label>
			<input id="StartInput" class="border" type="text" name="timelog[start_time]" maxlength="5" placeholder="9:30" patterm="^([0-2]?[0-9]):([0-5][1-9])$" value="<?php echo $timelog->getStartTimeNice() ?>"/>
			<div class="hint">Time the log started</div>
		</div>
		<div class="formRow" id="EndTime">
			<label for="EndInput">End:</label>
			<input id="EndInput" class="border" type="text" name="timelog[end_time]" maxlength="5" placeholder="13:45" patterm="^([0-2]?[0-9]):([0-5][1-9])$" value="<?php echo $timelog->getEndTimeNice() ?>"/>
			<div class="hint">Time the log ended</div>
		</div>
	
	<div class="formRow selectFormRow">
		<label for="ProjectSelect">Project: </label>
		<select name="timelog[project_id]" class="block border" id="ProjectSelect">
			<option value="">- Select Project -</option>
			<?php foreach ($projects AS $project): ?>
			<option value="<?php echo $project->getId() ?>" <?php echo ($project->getId() == $timelog->getProjectId()) ? 'SELECTED' : '' ?>><?php echo $project->getName() ?></option>
			<?php endforeach ?>
		</select>
		<div class="hint">Project the log is for</div>
	</div>
	
	<div class="formRow selectFormRow">
		<label for="CategorySelect">Category:</label>
		<select name="timelog[category_id]" class="block border" id="CategorySelect">>
			<option value="">- Select Category -</option>
			<?php foreach ($categories AS $category): ?>
			<option value="<?php echo $category->getId() ?>" <?php echo ($category->getId() == $timelog->getCategoryId()) ? 'SELECTED' : '' ?>><?php echo $category->getName() ?></option>
			<?php endforeach ?>
		</select>
		<div class="hint">Category log is for</div>
	</div>
	
	<div class="formRow textareaFormRow">
		<label for="NotesTextarea">Notes:</label>
		<textarea id="NotesTextarea" name="timelog[notes]" placeholder="Notes/Comments" class="block border"><?php echo $timelog->getNotes() ?></textarea>
		<div class="hint">Notes & description for this log</div>
	</div>

	<div class="formRow formRowActions">
			<input class="col2 button" name="submit" type="Submit" value="Cancel"/>
			<input class="col2 button floatRight" name="submit" type="Submit" value="Save"/>
			<?php if (!$ajax && !$sidebar_form): ?>
			<input class="block red button" name="submit" onclick="return confirm('Are you sure you want to delete timelog?')" type="Submit" value="Delete"/>
			<?php endif ?>
	</div>
	
	<p class="loading hidden">Loading.<blink>.</blink></p>
</form>