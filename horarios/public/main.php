<?php
if (!function_exists('check_language_in_url')) {
	function check_language_in_url($idiomas)
	{
		// Convertir la URL actual a mayúsculas
		$current_url = strtoupper($_SERVER['REQUEST_URI']);

		// Dividir la URL en segmentos por las barras "/"
		$url_segments = explode('/', $current_url);

		// Recorrer cada idioma proporcionado
		foreach ($idiomas as $lang) {

			// Recorrer cada segmento de la URL
			foreach ($url_segments as $segment) {
				// Comparar si el segmento contiene el idioma
				if (strpos($segment, $lang["idioma"]) !== false) {
					return $lang["idioma"];
				}
			}
		}

		return false;
	}
}

global $wpdb;
$queryIdiomas = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_horarios_idiomas");
$idiomas = $wpdb->get_results($queryIdiomas, ARRAY_A);

$current_language = check_language_in_url($idiomas);
$idioma = "ES";

if ($current_language != false) {
	$idioma = $current_language;
}

$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_horarios_textos WHERE idioma = '$idioma'");
$textosTraducidos = $wpdb->get_results($queryTextos, ARRAY_A)[0];

$queryConstantes = $wpdb->prepare("SELECT url_horarios FROM {$wpdb->prefix}insotel_horarios_constantes");
$url_horarios = $wpdb->get_results($queryConstantes);
$wsdl = $url_horarios[0]->url_horarios;

$client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors

$fecha = isset($_POST['fecha-horario']) ? $_POST['fecha-horario'] : date("d/m/Y");
$date = DateTime::createFromFormat('d/m/Y', $fecha);

$date_errors = DateTime::getLastErrors();
if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
	$errors[] = 'Some useful error message goes here.';
}

// web service input param
$request_param = array(
	'fecha' => $date->format('Y-m-d')
);

$horarios_ida = "";
$horarios_vuelta = "";

try {
	$responce_param = $client->ObtenerHorarios($request_param);
	$cuantos = count($responce_param->ObtenerHorariosResult->HorarioPantalla);
	for ($a = 0; $a < $cuantos; $a++) {
		$horario = $responce_param->ObtenerHorariosResult->HorarioPantalla[$a];
		if ($horario->Origen == "Ibiza") {
			$horarios_ida .= "<div class=\"fila\">";
			$horarios_ida .= "		<div class=\"horario\">";
			$horarios_ida .= "			<div class=\"hora\">";
			$horarios_ida .= "				<i style=\"display: block;\" class=\"fas fa-map-marker-alt icon-location-cards-horarios color1\"></i>";
			$horarios_ida .= "				<span class=\"color1 fs-numbers-cards-horarios montserrat\" style=\"display: block;\">" . substr($horario->HoraEmbarque, 0, 5) . "</span>";
			$horarios_ida .= "				<span style=\"display: block;\" class=\"fw-600 fs-15px\">" . $horario->Origen . "</span>";
			$horarios_ida .= "			</div>";
			$horarios_ida .= "			<div class=\"duracion\">";
			$horarios_ida .= "				<div class=\"tiempo\">";
			$horarios_ida .= "					<i style=\"display: block;\" class=\"fas fa-arrow-circle-right iconrow-cards-horarios\"></i>";
			$horarios_ida .= "				</div>";
			$horarios_ida .= "			</div>";
			$horarios_ida .= "			<div class=\"llegada\">";
			$horarios_ida .= "				<span class=\"fs-15px fw-600 color1 mt-10px\">" . $textosTraducidos["label_minutos"] . "</span>";
			$horarios_ida .= "				<span class=\"fs-numbers-cards-horarios color1 montserrat\" style=\"display: block;\">30 min.</span>";
			$horarios_ida .= "				<span style=\"display: block;\" class=\"fw-600 fs-15px\">" . $horario->Destino . "</span>";
			$horarios_ida .= "			</div>";
			$horarios_ida .= "		</div>";
			$horarios_ida .= "		<div class=\"datos\">";
			// if (!$horario->IsTrasmapi) {
			// 	$horarios_ida .= "			<div class=\"barco\">";
			// 	$horarios_ida .= "				  Formentera Lines";
			// 	$horarios_ida .= "			</div>";
			// } else {
			// 	$horarios_ida .= "			<div class=\"barco\">";
			// 	$horarios_ida .= "				  Trasmapi";
			// 	$horarios_ida .= "			</div>";
			// }
			$horarios_ida .= "		</div>";
			$horarios_ida .= "	</div>";
		} else {
			$horarios_vuelta .= "<div class=\"fila\">";
			$horarios_vuelta .= "		<div class=\"horario\">";
			$horarios_vuelta .= "			<div class=\"hora\">";
			$horarios_vuelta .= "				<i style=\"display: block;\" class=\"fas fa-map-marker-alt icon-location-cards-horarios color1 \"></i>";
			$horarios_vuelta .= "				<span class=\"color1 fs-numbers-cards-horarios montserrat\" style=\"display: block;\">" . substr($horario->HoraEmbarque, 0, 5) . "</span>";
			$horarios_vuelta .= "				<span style=\"display: block;\" class=\"fw-600 fs-15px\">" . $horario->Origen . "</span>";
			$horarios_vuelta .= "			</div>";
			$horarios_vuelta .= "			<div class=\"duracion\">";
			$horarios_vuelta .= "				<div class=\"tiempo\">";
			$horarios_vuelta .= "					<i style=\"display: block;\" class=\"fas fa-arrow-circle-right iconrow-cards-horarios\"></i>";
			$horarios_vuelta .= "				</div>";
			$horarios_vuelta .= "			</div>";
			$horarios_vuelta .= "			<div class=\"llegada\">";
			$horarios_vuelta .= "				<span class=\"fw-600 fs-15px color1 mt-10px\">" . $textosTraducidos["label_minutos"] . "</span>";
			$horarios_vuelta .= "				<span class=\"color1 fs-numbers-cards-horarios montserrat\" style=\"display: block;\">30 min.</span>";
			$horarios_vuelta .= "				<span style=\"display: block;\" class=\"fw-600 fs-15px\">" . $horario->Destino . "</span>";
			$horarios_vuelta .= "			</div>";
			$horarios_vuelta .= "		</div>";
			$horarios_vuelta .= "		<div class=\"datos\">";
			// if (!$horario->IsTrasmapi) {
			// 	$horarios_vuelta .= "			<div class=\"barco\">";
			// 	$horarios_vuelta .= "				 Formentera Lines";
			// 	$horarios_vuelta .= "			</div>";
			// } else {
			// 	$horarios_vuelta .= "			<div class=\"barco\">";
			// 	$horarios_vuelta .= "				 Trasmapi";
			// 	$horarios_vuelta .= "			</div>";
			// }
			$horarios_vuelta .= "		</div>";
			$horarios_vuelta .= "	</div>";
		}
	}
} catch (Exception $e) {
	echo "<h2>Exception Error</h2>";
	echo $e->getMessage();
}

?>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		var r = document.querySelector(':root');
		r.style.setProperty('--altoSticky', '160px');

		function checkLanguageInUrl() {

			// Obtener la URL actual
			const currentUrl = window.location.pathname;

			// Lista de códigos de idioma a verificar
			const languages = ['ES', 'EN', 'IT'];

			// Verificar si la URL contiene alguno de los códigos de idioma
			for (let i = 0; i < languages.length; i++) {
				let lang = languages[i];
				if (currentUrl.includes('/' + lang + '/')) {
					return lang;
				}
			}

			return false; // No se encontró ningún código de idioma
		}
		const actuallyLanguage = checkLanguageInUrl();
		let options;
		switch (actuallyLanguage) {
			case "ES":
				options = {
					locale: {
						direction: "ltr",
						format: "DD/MM/YYYY",
						separator: " - ",
						applyLabel: "Aplicar",
						cancelLabel: "Cancelar",
						fromLabel: "De",
						toLabel: "A",
						customRangeLabel: "Custom",
						daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
						monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
						firstDay: 1
					}
				}
				break;

			case "EN":
				options = {
					locale: {
						direction: "ltr",
						format: "DD/MM/YYYY",
						separator: " - ",
						applyLabel: "Apply",
						cancelLabel: "Cancel",
						fromLabel: "From",
						toLabel: "To",
						customRangeLabel: "Custom",
						daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
						monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
						firstDay: 0
					}
				}
				break;

			case "IT":
				options = {
					locale: {
						direction: "ltr",
						format: "DD/MM/YYYY",
						separator: " - ",
						applyLabel: "Aplicar",
						cancelLabel: "Cancelar",
						fromLabel: "De",
						toLabel: "A",
						customRangeLabel: "Custom",
						daysOfWeek: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
						monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
						firstDay: 1
					}
				}
				break;


			default:
				options = {
					locale: {
						direction: "ltr",
						format: "DD/MM/YYYY",
						separator: " - ",
						applyLabel: "Aplicar",
						cancelLabel: "Cancelar",
						fromLabel: "De",
						toLabel: "A",
						customRangeLabel: "Custom",
						daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
						monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
						firstDay: 1
					}
				}
				break;
		}

		const date = moment('<?php echo $date->format('d-m-Y') ?>', "DD-MM-YYYY");
		$('#fecha-horario').daterangepicker({
			"autoApply": true,
			"singleDatePicker": true,
			"startDate": moment(date).format('DD-MM-YYYY'),
			"endDate": moment(date).format('DD-MM-YYYY'),
			"minDate": moment(new Date()).format('DD-MM-YYYY'),
			locale: options.locale
		}, function(start, end, label) {
			console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
		});

		$("#btn-actualiza-horarios").bind("click", function(event) {
			event.preventDefault();
			$("#horarios-form").submit();
		});
	})
</script>

<form id="horarios-form" action="" method="post">
	<div class="containerShortcode">
		<div class="containerSeleccionarFechaViaje">
			<span class="mr-5px mt-movil-5px labelSeleccioneFecha montserrat">
				<?php echo $textosTraducidos["label_seleccione_fecha"] ?>
			</span>
			<input id="fecha-horario" name="fecha-horario" class="mt-movil-5px mr-5px form-control form-control-lg form-control-solid fecha-viaje-horario" type="text">
			<button type="button" id="btn-actualiza-horarios" class="btn-horarios bgColor1 mr-5px mt-movil-5px"><?php echo $textosTraducidos["label_boton_consultar"] ?> </button>
		</div>
		<div class="text-center w-100">
			<label class="horariosDisponibles montserrat" style="text-align: center !important;"><?php echo $textosTraducidos["label_horarios_disponibles"] . $date->format('d/m/Y') ?></label>
		</div>
		<div class="containerHorarios">
			<div class="containerHorario">
				<h5 class="sticky text-center color1 caveat fs-labels-sticky bgWhite mt-20px">Ibiza - Formentera</h5>
				<div id="horarioida">
					<?php echo $horarios_ida; ?>
				</div>
			</div>

			<div class="containerHorario">
				<h5 class="sticky text-center color1 caveat fs-labels-sticky bgWhite mt-20px">Formentera - Ibiza</h5>
				<div id="horariovuelta">
					<?php echo $horarios_vuelta; ?>
				</div>
			</div>
		</div>
	</div>
</form>

