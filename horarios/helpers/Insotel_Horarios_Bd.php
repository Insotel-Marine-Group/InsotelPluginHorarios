<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class Insotel_Horarios_Bd
{
    public function create_table_insotel_horarios_idiomas($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_horarios_idiomas';

        $sql = "CREATE TABLE $nameTable (id mediumint(9) NOT NULL AUTO_INCREMENT, idioma varchar(50) NOT NULL, PRIMARY KEY  (id)) $charset_collate;";

        dbDelta($sql);

        // Verificar si el idioma ya existe
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable",
        ));

        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'ES',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'EN',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'CA',
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'IT',
                )
            );
        }
    }


    public function create_table_insotel_horarios_textos($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_horarios_textos';

        $sql = "CREATE TABLE $nameTable (id mediumint(9) NOT NULL AUTO_INCREMENT,
        idioma varchar(100) NOT NULL,
        label_minutos varchar(100) NOT NULL,
        label_seleccione_fecha varchar(200) NOT NULL,
        label_boton_consultar varchar(100) NOT NULL,
        label_horarios_disponibles varchar(200) NOT NULL,
        label_operado_por varchar(200) NOT NULL,
        PRIMARY KEY  (id)) $charset_collate;";
        
        dbDelta($sql);



        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));


        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'ES',
                    'label_minutos' => 'Directo',
                    'label_seleccione_fecha' => 'Seleccione la fecha de viaje ',
                    'label_boton_consultar' => 'Consultar',
                    'label_horarios_disponibles' => 'Horarios disponibles para el día ',
                    'label_operado_por' => 'Operado por '
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'EN',
                    'label_minutos' => 'Direct',
                    'label_seleccione_fecha' => 'Select the travel date ',
                    'label_boton_consultar' => 'Search',
                    'label_horarios_disponibles' => 'Schedules available for the day ',
                    'label_operado_por' => 'Operated by '
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'CA',
                    'label_minutos' => 'Directe',
                    'label_seleccione_fecha' => 'Seleccioneu la data de viatge ',
                    'label_boton_consultar' => 'Consultar',
                    'label_horarios_disponibles' => 'Horaris disponibles per al dia ',
                    'label_operado_por' => 'Operat per '
                )
            );

            $wpdb->insert(
                $nameTable,
                array(
                    'idioma' => 'IT',
                    'label_minutos' => 'Senza scale',
                    'label_seleccione_fecha' => 'Seleziona la data del viaggio ',
                    'label_boton_consultar' => 'Consultare',
                    'label_horarios_disponibles' => 'Orari disponibili per la giornata ',
                    'label_operado_por' => 'Operato da '
                )
            );
        }
    }

    public function create_table_insotel_horarios_constantes($wpdb)
    {
        $charset_collate = $wpdb->get_charset_collate();
        $nameTable = $wpdb->prefix . 'insotel_horarios_constantes';

        $sql = "CREATE TABLE $nameTable (id mediumint(9) NOT NULL AUTO_INCREMENT, url_horarios varchar(400) NOT NULL, PRIMARY KEY  (id)) $charset_collate;";
        dbDelta($sql);

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $nameTable"
        ));


        if ($count === 0 || $count === "0") {
            $wpdb->insert(
                $nameTable,
                array(
                    'url_horarios' => 'http://sobordos.mediterraneapitiusa.com/Horario.asmx?WSDL',
                )
            );
        }
    }
}
