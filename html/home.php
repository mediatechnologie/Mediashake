<?php include('header.php'); ?>

	<div id="column1">
		<div id="join-banner">
			<p>Join the online platform for European media students!</p>
			<form id="register-form">
				<fieldset>
					<dl>
						<dt><label for="name">Username</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Email Address</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Choose a Password</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Confirm Password</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Firstname</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Lastname</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Gender</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">Location</label></dt>
						<dd><input type="text" name="name" /></dd>
					</dl>
					<dl>
						<dt><label for="name">School</label></dt>
						<dd>
							<select name="school">
								<option>Mediacollege Amsterdam</option>
							</select>
						</dd>
					</dl>
				</fieldset>
			</form>
			<input type="button" value="Create account" name="createaccount" />
		</div>
		<!--
		<ul id="showcase">
			<li>
				<img src="work/1-320x220.jpg"/>
				<p class="title">Mediacollege Amsterdam group photo</p>
			</li>
			<li>
				<img src="work/2-320x220.jpg"/>
			</li>
			<li>
				<img src="work/3-320x220.jpg"/>
			</li>
			<li>
				<img src="work/3-320x220.jpg"/>
			</li>
		</ul>
	-->
	</div>
	<?php include('sidebar.php'); ?>
	
	<?php echo $content; ?>
</div>

<?php include('footer.php'); ?>