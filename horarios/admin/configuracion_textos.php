<?php
global $wpdb;

$queryIdiomas = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_horarios_idiomas");
$arrayIdiomas = $wpdb->get_results($queryIdiomas, ARRAY_A);

$queryTextos = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_horarios_textos");
$textos = $wpdb->get_results($queryTextos);

// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    // Obtener el nuevo valor enviado desde el formulario
    $idioma = sanitize_text_field($_POST['idioma']);
    $label_minutos = sanitize_text_field($_POST['label_minutos']);
    $label_seleccione_fecha = sanitize_text_field($_POST['label_seleccione_fecha']);
    $label_boton_consultar = sanitize_text_field($_POST['label_boton_consultar']);
    $label_horarios_disponibles = sanitize_text_field($_POST['label_horarios_disponibles']);
    $label_operado_por = sanitize_text_field($_POST['label_operado_por']);

    // Actualizar la tabla
    $table_insotel_horarios = $wpdb->prefix . 'insotel_horarios_textos';

    // Verificar si el idioma ya existe
    $existing_idioma_texto = $wpdb->get_var($wpdb->prepare(
        "SELECT idioma FROM $table_insotel_horarios WHERE idioma = %s",
        $idioma
    ));

    if ($existing_idioma_texto === null) {
        $result = $wpdb->insert(
            $table_insotel_horarios,
            array(
                'idioma' => $idioma,
                'label_minutos' => $label_minutos,
                'label_seleccione_fecha' => $label_seleccione_fecha,
                'label_boton_consultar' => $label_boton_consultar,
                'label_horarios_disponibles' => $label_horarios_disponibles,
                'label_operado_por' => $label_operado_por
            )
        );
        if ($result !== false) {
            $textos = $wpdb->get_results($queryTextos);
            $message = 'Se ha insertado en la tabla correctamente.';
        } else {
            $message = 'Hubo un error al insertar la tabla.';
        }
    } else {
        $result = $wpdb->update(
            $table_insotel_horarios,
            array(
                'idioma' => $idioma,
                'label_minutos' => $label_minutos,
                'label_seleccione_fecha' => $label_seleccione_fecha,
                'label_boton_consultar' => $label_boton_consultar,
                'label_horarios_disponibles' => $label_horarios_disponibles,
                'label_operado_por' => $label_operado_por
            ),
            array('idioma' => $idioma)
        );
        if ($result !== false) {
            $textos = $wpdb->get_results($queryTextos);
            $message = 'La tabla se ha actualizado correctamente.';

        } else {
            $message = 'Hubo un error al actualizar la tabla.';
        }
    }
    
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<script>
    let textosBd = <?php echo json_encode($textos); ?>;

    document.addEventListener("DOMContentLoaded", () => {
        printValues(textosBd[0]);

        document.querySelector("#idioma").addEventListener("change", () => {
            changeValues(textosBd);
        })
    });

    function changeValues(textos) {
        let interruptor = false;
        textos.forEach(texto => {
            if (texto.idioma == document.querySelector("#idioma").value) {
                interruptor = true;
                printValues(texto);
            }
        });

        if (!interruptor) {
            let textoVacio = {
                label_minutos: "",
                label_seleccione_fecha: "",
                label_boton_consultar: "",
                label_horarios_disponibles: "",
                label_operado_por: "",
            }

            printValues(textoVacio);
        }
    }

    function printValues(values) {
        document.querySelector("#label_minutos").value = values.label_minutos;
        document.querySelector("#label_seleccione_fecha").value = values.label_seleccione_fecha;
        document.querySelector("#label_boton_consultar").value = values.label_boton_consultar;
        document.querySelector("#label_horarios_disponibles").value = values.label_horarios_disponibles;
        document.querySelector("#label_operado_por").value = values.label_operado_por;
    }
</script>


<body>
    <?php if (!empty($message)) : ?>
        <div id="update-result"><?php echo $message; ?></div>
    <?php endif; ?>

    <h1>CONFIGURACIÓN DE TEXTOS PARA EL PLUGIN</h1>
    <div class="container text-bg-light p-5 shadow">
        <form method="post" action="" class="pb-4" id="formulario_configuracion" name="formulario_configuracion">
            <div class="row pt-1">
                <div class="col-sm-12">
                    <label for="idioma">Idioma:</label><br>
                    <select id="idioma" name="idioma">
                        <?php
                        foreach ($arrayIdiomas as $key => $value) {
                            $idioma = $value["idioma"];
                            echo "<option value='$idioma'>$idioma</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="label_operado_por">Label operado por:</label><br>
                    <input type="text" class="form-control" id="label_operado_por" name="label_operado_por">
                </div>
                <div class="col-sm-6">
                    <label for="label_minutos">Label minutos:</label><br>
                    <input type="text" class="form-control" id="label_minutos" name="label_minutos">
                </div>
                <div class="col-sm-6">
                    <label for="label_seleccione_fecha">Label seleccione fecha:</label><br>
                    <input type="text" class="form-control" id="label_seleccione_fecha" name="label_seleccione_fecha">
                </div>
                <div class="col-sm-6">
                    <label for="label_boton_consultar">Label boton consultar:</label><br>
                    <input type="text" class="form-control" id="label_boton_consultar" name="label_boton_consultar">
                </div>
                <div class="col-sm-6">
                    <label for="label_horarios_disponibles">Label horarios disponibles:</label><br>
                    <input type="text" class="form-control" id="label_horarios_disponibles" name="label_horarios_disponibles">
                </div>
            </div>
            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success float-end mt-2">Guardar Cambios</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>