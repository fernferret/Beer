<?php 
	include "includes/config.php";
	include "includes/db.php";
	include $_TEMPLATE."header.php";
?>
	<div class="container">
		<div class="column span-24">			
			<div class="shadow full">
				<div class="page" id="error">
					<h2>Error!</h2>
					<p>You have thrown an error, most likely because you are trying to reach a script directly without identifying an ID or username.
					<br /><br/>
					<INPUT TYPE="button" class="button" VALUE="Back" OnClick="history.go( -2 );return true;">
				</div>
			</div>
		</div>
		<div class="clearfooter"></div>
	</div>

<?php
	include $_TEMPLATE."footer.php"; 
?>