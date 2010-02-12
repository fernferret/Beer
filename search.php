<?php 
	include "includes/config.php";
	include "includes/db.php";
	include $_TEMPLATE."header.php";
	include $_UTIL."user.php";
?>
	<div class="container">
		<div class="column span-24">			
			<div class="shadow">
				<div class="page" id="search">
					<h2>Search for a <strong>beer!</strong></h2>
					<form name="search" method="post" action="search.php">
					<?php 
						if (isset($_POST['Form_Submit'])) {	
							$search = $_POST["Form_Search"];
							$user = new User();
							$user->search($search);
						} 
						?>
						<ul>
							<li><label for="Form_Search">Search</label> <input type="text" id="Form_Search" name="Form_Search" value="" class="inputbox"></li>
  	                        <br />
	                 		<li><input type="submit" id="Form_Submit" name="Form_Submit" value="Search" class="button"></li>
                 		</ul>
					</form>
				</div>
			</div>
		</div>
		<div class="clearfooter"></div>
	</div>

<?php
	include $_TEMPLATE."footer.php"; 
?>