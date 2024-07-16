<?php
global $wpdb;
$queryConstantes = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}insotel_horarios_constantes");
$constantes = $wpdb->get_results($queryConstantes)[0];


// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    // Obtener el nuevo valor enviado desde el formulario
    $url_horarios = sanitize_text_field($_POST['url_horarios']);

    // Actualizar la tabla
    $table_insotel_horarios_constantes = $wpdb->prefix . 'insotel_horarios_constantes';

    // Verificar si el idioma ya existe
    $existing_constantes = $wpdb->get_var($wpdb->prepare(
        "SELECT url_horarios FROM $table_insotel_horarios_constantes"
    ));

    if ($existing_constantes === null) 
    {
        $result = $wpdb->insert(
            $table_insotel_horarios_constantes,
            array(
                'url_horarios' => $url_horarios
            )
        );
    
        if ($result !== false) {
            $constantes = $wpdb->get_results($queryConstantes)[0];
            $message = 'La tabla se ha actualizado correctamente.';
        } else {
            $message = 'Hubo un error al actualizar la tabla.';
        }
    }
    else
    {
        $result = $wpdb->update(
            $table_insotel_horarios_constantes,
            array(
                'url_horarios' => $url_horarios,
            ),
            array('id' => "1")
        );

        if ($result !== false) {
            $constantes = $wpdb->get_results($queryConstantes)[0];
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

<body>

    <?php if (!empty($message)) : ?>
        <div id="update-result"><?php echo $message; ?></div>
    <?php endif; ?>

    <h1>CONFIGURACIÓN CONSTANTES DEL PLUGIN</h1>
    <div class="container text-bg-light p-5 shadow">
        <form method="post" action="" class="pb-4" id="formulario_configuracion" name="formulario_configuracion">
            <div class="row pt-1">
                <div class="col-sm-6">
                    <label for="url_horarios">Url horarios:</label><br>
                    <input type="text" class="form-control" id="url_horarios" name="url_horarios" value="<?php echo $constantes->url_horarios ?>">
                </div>
            </div>
            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success float-end mt-2">Guardar Cambios</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>