<?php
include('../var.php');

if($_GET['linea'] == '') {
	header('HTTP/1.0 404 Not Found');
	header('Location: /');
}

$lineasJson = json_decode(file_get_contents($endpoint . 'lineas/' . $_GET['linea']), true);
$trayectos = $lineasJson['trayectos'];

// Utter and complete mess ////////////////////////////////////////////////////////////////////////////////////////////////////////////
$jsonMapaIda = '[';

$paradasIdaJson = $trayectos[0]['paradas'];
$paradasVueltaJson = $trayectos[1]['paradas'];
for($i = 0; $i < sizeof($trayectos[0]['paradas']); $i++) {
	$jsonMapaIda .= '{ "nombre": "' . $paradasIdaJson[$i]['nombre'] . '",';
	$jsonMapaIda .= '"lat": "' . $paradasIdaJson[$i]['coordenadas']['latitud'] . '", ';
	$jsonMapaIda .= '"lon": "' . $paradasIdaJson[$i]['coordenadas']['longitud'] . '",';
	$jsonMapaIda .= '"route": "Ida", "extra": "' . ($paradasIdaJson[$i]['extraordinaria'] == "1" ? 1 : 0) . '"},';
}

for($i = 0; $i < sizeof($trayectos[1]['paradas']); $i++) {
	$jsonMapaVuelta .= '{ "nombre": "' . $paradasVueltaJson[$i]['nombre'] . '",';
	$jsonMapaVuelta .= '"lat": "' . $paradasVueltaJson[$i]['coordenadas']['latitud'] . '", ';
	$jsonMapaVuelta .= '"lon": "' . $paradasVueltaJson[$i]['coordenadas']['longitud'] . '",';
	$jsonMapaVuelta .= '"route": "Vuelta" , "extra": "' . ($paradasVueltaJson[$i]['extraordinaria'] == "1" ? 1 : 0) . '"},';
}

$jsonMapa = $jsonMapaIda . rtrim($jsonMapaVuelta, ',') . ']';

$isCircular = sizeof($paradasVueltaJson) == 0 ? true: false;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title><?php printf('%s , %s | %s', 'Línea ' . $lineasJson['sinoptico'], $lineasJson['nombre'], $websiteName); ?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="/css/lines.css">
		<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="icon" href="/favicon.ico?v=2" type="image/x-icon">
		<script>
			var markerGroups = {
				'Ida': [],
				'Vuelta': []
			};

			function initMap() {
				var json = <?php echo $jsonMapa ?>;
				var customIcons = {
					0: {
						icon: '/images/bus.png'
					},

					1: {
						icon: '/images/busExtra.png'
					}
				};

				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 13,
					disableDefaultUI: true
				});

				var bounds = new google.maps.LatLngBounds();

				for (var i = 0, length = json.length; i < length; i++) {
					var data = json[i], 
					latLng = new google.maps.LatLng(data.lat, data.lon);
					var icon = customIcons[data.extra] || {};
					var marker = new google.maps.Marker({
    						position: latLng,
    						map: map,
    						title: data.nombre,
						icon: icon.icon
					});
					if (!markerGroups[data.route]) markerGroups[data.route] = [];
					markerGroups[data.route].push(marker);

					bounds.extend(marker.position);
					
				}
				map.fitBounds(bounds);
				map.panToBounds(bounds);
				toggleGroup('Vuelta');
			}

			function toggleGroup(type) {
				for (var i = 0; i < markerGroups[type].length; i++) {
					var marker = markerGroups[type][i];
					if (!marker.getVisible()) {
						marker.setVisible(true);
					} else {
						marker.setVisible(false);
					}
				}
			}
		</script>
	</head>
	<body>
		<div id="map"></div>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsKey; ?>&amp;callback=initMap"></script>
		<table>
			<thead>
				<tr>	
					<th colspan="2" style="background: <?php echo $lineasJson['estilo'] ?>">
						<h1>
							<?php printf('%s , %s', 'Línea ' . $lineasJson['sinoptico'], $lineasJson['nombre']); ?>
						</h1>
					</th>
				</tr>
				<tr>
					<th><?php echo $isCircular ? 'Circular' : $trayectos[0]['nombre'] ?> <input type="checkbox" onclick="toggleGroup(this.value);" value="Ida" checked></th>
					<th><?php echo $trayectos[1]['nombre']; ?> <input type="checkbox" onclick="toggleGroup(this.value);" value="Vuelta"></th>
				</tr>
			</thead>
			<tbody>
				<?php for($key = 0; $key < max(sizeof($trayectos[0]['paradas']), sizeof($trayectos[1]['paradas'])); $key++): ?>
					<tr>	
						<?php $extraIda = ($trayectos[0]['paradas'][$key]['extraordinaria'] == '1') ? '#d4d4d4' : '#000000';
						$extraVuelta = ($trayectos[1]['paradas'][$key]['extraordinaria'] == '1') ? '#d4d4d4' : '#000000'; ?>

						<?php echo '<td style="color: ' . $extraIda . ';"><a href="/parada/' . $trayectos[0]['paradas'][$key]['id'] . '">' . $trayectos[0]['paradas'][$key]['nombre'] . '</a></td>';
						echo $isCircular ? '' : '<td style="color: ' . $extraVuelta . ';"><a href="/parada/' . $trayectos[1]['paradas'][$key]['id'] . '">' . $trayectos[1]['paradas'][$key]['nombre'] . '</a></td>'; ?>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</body>
</html>
