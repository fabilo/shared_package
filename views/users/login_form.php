<div id="LoginFormContainer">
		<?php if(isset($message) && $message != ''): ?>
		<p class="message"><?php echo $message ?></p>
		<?php endif ?>
		<form method="POST">
			<div class="formRow">
				<label for="UsernameInput">Username:</label>
				<input type="text" required="required" id="UsernameInput" name="username" value="<?php echo $username ?>"/>
			</div>
			<div class="formRow">
				<label for="UsernameInput">Password:</label>
				<input type="password" required="required" id="PasswordInput" name="password"/>
			</div>
			<div class="formRown formRowActions">
				<input type="submit" value="Login" class="button" name="submit"/>
			</div>
		</form>
</div>