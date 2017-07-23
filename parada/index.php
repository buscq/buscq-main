<?php
include('../var.php');

if($_GET['parada'] == "") {
	header('HTTP/1.0 404 Not Found');
	header('Location: ..');
}
$paradaJson = json_decode(file_get_contents($endpoint . 'paradas/' . $_GET['parada']), true);
$lineas = $paradaJson['lineas'];

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title><?php printf('%s | %s', $paradaJson['nombre'], $websiteName); ?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="/css/stop.css">
		<link rel="icon" href="/favicon.ico?v=2" type="image/x-icon">
		<meta http-equiv="refresh" content="30">
	</head>
	<body>
		<div id="map"></div>
		<script>
			function initMap() {
				var stop = <?php printf("{lat: %s, lng: %s};\n", $paradaJson['coordenadas']['latitud'], $paradaJson['coordenadas']['longitud']); ?>
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 17,
					center: stop,
					disableDefaultUI: true
				});

				var marker = new google.maps.Marker({
					position: stop,
					map: map,
					title: '<?php echo $paradaJson['nombre']; ?>',
					icon: '/images/bus.png'
 				});
			}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsKey; ?>&amp;callback=initMap"></script>
		<h1>Próximos autobuses en <?php echo $paradaJson['nombre']; ?></h1>
		<h3><?php printf('Zona de %s', $paradaJson['zona']); ?></h3>
		<table>
			<thead>
				<tr>
					<th>Minutos aproximados</th>
					<th>Hora aproximada</th>
					<th>Línea</th>
					<th>Trayecto</th>
				</tr>
			</thead>
			<tbody>
				<?php if(count($lineas) == 0) { echo '<tr><td colspan="4">No hay buses disponibles para mostrar ahora mismo en esta parada. Por favor, inténtalo de nuevo en unos minutos <a href="/sobre">(¿Por qué aparece esto?)</a></td></tr>'; } else { ?>
					<?php foreach($lineas as $key => $value): ?>
						<tr>
							<td><?php echo round((strtotime(substr($value['proximoPaso'], - 5)) - strtotime('now')) / 60); ?></td>
							<td><?php echo substr($value['proximoPaso'], -5); ?></td>
							<?php printf('<td style="background: %s"><a href="/lineas/%s">%s</a></td>', $value['estilo'], $value['id'], $value['sinoptico']); ?>
							<td><?php echo $value['nombre']; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>
