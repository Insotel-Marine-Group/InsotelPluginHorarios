<?php
// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table'])) {
    global $wpdb;

    // Obtener el nuevo valor enviado desde el formulario
    $idioma = sanitize_text_field($_POST['idioma']);

    // Actualizar la tabla
    $table_sfm_reservas = $wpdb->prefix . 'insotel_horarios_idiomas';

    // Verificar si el idioma ya existe
    $existing_idioma = $wpdb->get_var($wpdb->prepare(
        "SELECT idioma FROM $table_sfm_reservas WHERE idioma = %s",
        $idioma
    ));

    if ($existing_idioma === null) 
    {
        $result = $wpdb->insert(
            $table_sfm_reservas,
            array(
                'idioma' => $idioma,
            ),
        );
    
        if ($result !== false) {
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

    <h1>CONFIGURACIÓN DE IDIOMAS PLUGIN</h1>
    <div class="container text-bg-light p-5 shadow">
        <form method="post" action="" class="pb-4" id="formulario_configuracion" name="formulario_configuracion">
            <div class="row pt-1">
                <div class="col-sm-6">
                    <label for="idioma">Idioma:</label><br>
                    <input type="text" class="form-control" id="idioma" name="idioma">
                </div>
            </div>
            <button id="boton_guardar" name="update_table" type="submit" class="btn btn-success float-end mt-2">Guardar Cambios</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>