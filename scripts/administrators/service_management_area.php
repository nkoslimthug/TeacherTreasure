<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<?php
			$root_path="../../";
			include($root_path."config/paths.php");
		?>
		<title><?php echo $sitetitle;?></title>
		<link rel="stylesheet" href="<?php echo $root_path;?>styles/normalize.css">
		<link rel="stylesheet" href="<?php echo $root_path;?>styles/main.css">
		<link rel="stylesheet" href="<?php echo $root_path;?>styles/myStyles.css">
		<style type="text/css">
			div#first{
				background-image: url('<?php echo $watermark;?>');
			}
		</style>	
	</head> 
	<body>
		<div class="container">
			<?php
				include($root_path."config/page_header.php");
			?>
			<div class='sidebar1' style='background-color: #004080;'>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<ul class='nav'>
					<li><a href='services/view-users.php'>Subscribers</a></li>
					<li><a href='services/view-packages.php'>Packages</a></li>
					<li><a href='administrator_home.php'>Back</a></li>
					<li><a href='../general/logout.php'>Logout</a></li>
				</ul>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p><!-- end .sidebar1 --></p>
			</div>
			<div class='content'>
				<div id='main'>
					<div id='first'>
					</div>
					<div id='second'>
						<p id='container'>
							<?php
							/*
								$right=true;
								if(isset($right)){
									unset($left);
									include($root_path."scripts/general/placeAdverts.php");
								}	
								*/
							?>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
						</p>
					</div>
				</div>	
			</div>			
			<div class='footer'>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</div>
		</div>
	</body>
</html>