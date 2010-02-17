<?php

if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == FALSE && strpos($_SERVER['HTTP_USER_AGENT'], 'iPod') == FALSE)
{
	//header("Location: ../");
}
//session_start(); 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<html>
	<head>
		<title>Beer.</title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

		<link rel="stylesheet" href="WebApp/Design/Render.css" />
		<script type="text/javascript" src="WebApp/Action/Logic.js"></script>
<script> 
			var timerID;
			var username='<%= Session("username").ToString() %>'
			WA.AddEventListener("beginslide", function() {
				//WA.ProgressHUD("Rechargement des donnees");
			//	clearTimeout(timerID);
			});
 
			WA.AddEventListener("endasync", function(evt) {
				if (!evt.target.responseXML) {
					WA.Loader("oki");
					clearTimeout(timerID);
				}
			});
			
			function searchLoader() {
				WA.Loader("oki", 1);
				timerID = setTimeout(WA.Loader, 5000, 'oki');
			}
			function loginLoader() {
				WA.Loader("loginsub", 1);
				timerID = setTimeout(WA.Loader, 5000, 'loginsub');
			}
			function getUsername()
			{
				return '<%= Session("username").ToString() %>';
			}
			
			
		</script> 
	</head>
	<body>
		<div id="WebApp">
			<div id="iHeader"> 
				<a href="#" id="waBackButton">Back</a> 
				<a href="#" id="waHomeButton">Home</a> 
				<a href="#" onclick="return WA.HideBar()"><span id="waHeadTitle">Beer.</span></a>  
	<!-- Add an hidden form in the header. Will be shown pressing search button --> 
				<form class="iForm" id="headForm" action="isearch.php" onsubmit="searchLoader()"> 
					<a href="#" rel="action" id="gogo" class="iButton iBAction">Search</a> 
					<a href="#" rel="back" class="iButton iBClassic">Cancel</a> 
					<fieldset> 
						<legend>Search</legend> 
						<input type="search" name="search" placeholder="Search term here" /> 
					</fieldset> 
				</form> 
				<div class="iItem" id="tab1"> 
					<div class="iTab" style="float:right"> 
						<ul id="list"> 
							<li><a href="#" -rel="action" -onclick="alert('coucou');return false"><img src="WebApp/Img/up.png" style="position:relative;top:2px;margin:0 2px 0 4px" /></a></li> 
							<li><a href="#" -rel="action" -onclick="alert('coucou');return false"><img src="WebApp/Img/down.png" style="position:relative;top:2px;margin:0 4px 0 2px" /></a></li> 
						</ul> 
					</div> 
				</div> 
			</div>
			<div id="iGroup">
				<div class="iLayer" id="waHome" title="Home">
					 <a href="#" rel="action" onclick="return WA.Form('headForm')" id="oki" class="iButton iBClassic"><span>Search</span></a>
					<div class="iMenu"> 
						<h3>Common Tasks</h3> 
						<ul class="iArrow"> 
							<li><a rev="async" href="iadd.php">Add a Beer</a></li>
							<li><a rev="async" href="itopten.php">See the Top Ten Beers</a></li>
							<li><a href="#_BeerSearch" onClick="WA.Form('headForm')">Search for a Beer</a></li>
						</ul>
						<h3>User Tasks</h3> 
						<ul class="iArrow"> 
							<?php 
								if(isset($_SESSION['logged_in']))
								{?>
									<li><a rev="async" href="ilogout.php">Logout</a></li>
								<?php
								}
								else
								{?>
									<li><a href="#_Register">Register</a></li>
									<li><a href="#_Login">Login</a></li>
								<?php
								}
							?>
						</ul> 
					</div>
					<div class="iFooter">
						Beer. &copy; 2010
					</div>
				</div>
				<div class="iLayer" id="waLogin" title="Please Login">
				<form rev="async" action="ilogin.php" id="loginForm" onSubmit="return WA.Submit('loginForm');">
					<div class="iPanel">
					<fieldset>
						<ul> 
							<li><input type="text" name="buname" placeholder="username" /></li>
							<li><input type="password" name="bpass" placeholder="password" /></li>
							<li><input style="width:100%" type="submit" id="loginsub" value="Login" class="iPush iBClassic"></li>
						</ul>
						</fieldset>
						
					</div>
					</form>
					<div class="iFooter">
						Beer. &copy; 2010
					</div>
				</div> 
				<div class="iLayer" id="waRegister" title="Register.">
				<form rev="async" action="iregister.php" id="registerForm" onSubmit="return WA.Submit('registerForm');">
					<div class="iPanel">
					<fieldset>
						<ul> 
							<li><input type="text" name="bname" placeholder="Your Name" /></li>
							<li><input type="text" name="bemail" placeholder="Your Email Address" /></li>
							<li><input type="text" name="buname" placeholder="A Username" /></li>
							<li><input type="password" name="bpass" placeholder="A Password" /></li>
							<li><input type="password" name="bpassc" placeholder="Confirm That Password" /></li>
							<li><select>
							<?php
							echo'<select name="bregion">';
							$res = mssql_query("SELECT * FROM regions");
							for($i=0;$i<mssql_num_rows($res);$i++) {
								$row = mssql_fetch_assoc($res);
								echo "<option>".$row["city"]."</option>";
							}
							echo'</select>';
							?>
							</select></li>
							<li><input style="width:100%" type="submit" id="registersub" value="Register" class="iPush iBClassic"></li>
						</ul>
						</fieldset>
						
					</div>
					</form>
					<div class="iFooter">
						Beer. &copy; 2010
					</div>
				</div> 
			</div>
		</div>
	</body>
</html>