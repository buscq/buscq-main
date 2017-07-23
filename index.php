<?php 
include('var.php');
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $websiteName; ?></title>
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="icon" href="/favicon.ico?v=2" type="image/x-icon">
		<script>let lineas = <?php echo file_get_contents($endpoint . "lineas"); ?></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="/js/data-service.js"></script>
		<script src="/js/bus-lines-component.js"></script>
		<script src="/main.js"></script>
	</head>
	<body>
		<div id="content">
			<h1><?php echo $websiteName; ?></h1>
			<div id="header">
				<img src="/images/header.png" alt="<?php echo $websiteName; ?>">
			</div>
			<h2>Líneas de autobús urbano de Santiago de Compostela</h2>
			<div id="bus-lines"></div>
		</div>
	</body>
</html>
